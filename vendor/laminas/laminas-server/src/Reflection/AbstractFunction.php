<?php

/**
 * @see       https://github.com/laminas/laminas-server for the canonical source repository
 */

namespace Laminas\Server\Reflection;

use Laminas\Code\Reflection\DocBlock\Tag\ParamTag;
use Laminas\Code\Reflection\DocBlock\Tag\ReturnTag;
use Laminas\Code\Reflection\DocBlockReflection;
use Laminas\Server\Reflection\Node;
use ReflectionClass as PhpReflectionClass;
use ReflectionFunction as PhpReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod as PhpReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter as PhpReflectionParameter;

use function array_merge;
use function array_shift;
use function array_unshift;
use function call_user_func_array;
use function count;
use function is_array;
use function is_string;
use function method_exists;
use function preg_match;

use const PHP_VERSION_ID;

/**
 * Function/Method Reflection
 *
 * Decorates a ReflectionFunction. Allows setting and retrieving an alternate
 * 'service' name (i.e., the name to be used when calling via a service),
 * setting and retrieving the description (originally set using the docblock
 * contents), retrieving the callback and callback type, retrieving additional
 * method invocation arguments, and retrieving the
 * method {@link \Laminas\Server\Reflection\Prototype prototypes}.
 */
abstract class AbstractFunction
{
    /** @var ReflectionFunctionAbstract */
    protected $reflection;

    /**
     * Additional arguments to pass to method on invocation
     *
     * @var array
     */
    protected $argv = [];

    /**
     * Used to store extra configuration for the method (typically done by the
     * server class, e.g., to indicate whether or not to instantiate a class).
     * Associative array; access is as properties via {@link __get()} and
     * {@link __set()}
     *
     * @var array
     */
    protected $config = [];

    /**
     * Declaring class (needed for when serialization occurs)
     *
     * @var string
     */
    protected $class;

    /**
     * Function name (needed for serialization)
     *
     * @var string
     */
    protected $name;

    /**
     * Function/method description
     *
     * @var string
     */
    protected $description = '';

    /**
     * Namespace with which to prefix function/method name
     *
     * @var string
     */
    protected $namespace;

    /**
     * Prototypes
     *
     * @var array
     */
    protected $prototypes = [];

    /**
     * Phpdoc comment
     *
     * @var string
     */
    protected $docComment = '';

    /** @var array */
    protected $return = [];

    /** @var string */
    protected $returnDesc;

    /** @var array<int, string> */
    protected $paramDesc;

    /** @var array */
    protected $sigParams;

    /** @var int */
    protected $sigParamsDepth;

    /**
     * Constructor
     *
     * @param null|string $namespace
     * @param null|array $argv
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public function __construct(ReflectionFunctionAbstract $r, $namespace = null, $argv = [])
    {
        $this->reflection = $r;

        // Determine namespace
        if (null !== $namespace) {
            $this->setNamespace($namespace);
        }

        // Determine arguments
        if (is_array($argv)) {
            $this->argv = $argv;
        }

        // If method call, need to store some info on the class
        if ($r instanceof PhpReflectionMethod) {
            $this->class = $r->getDeclaringClass()->getName();
        }

        $this->name = $r->getName();

        // Perform some introspection
        $this->reflect();
    }

    /**
     * Create signature node tree
     *
     * Recursive method to build the signature node tree. Increments through
     * each array in {@link $sigParams}, adding every value of the next level
     * to the current value (unless the current value is null).
     *
     * @param int $level
     * @return void
     */
    protected function addTree(Node $parent, $level = 0)
    {
        if ($level >= $this->sigParamsDepth) {
            return;
        }

        foreach ($this->sigParams[$level] as $value) {
            $node = new Node($value, $parent);
            if ((null !== $value) && ($this->sigParamsDepth > $level + 1)) {
                $this->addTree($node, $level + 1);
            }
        }
    }

    /**
     * Build the signature tree
     *
     * Builds a signature tree starting at the return values and descending
     * through each method argument. Returns an array of
     * {@link \Laminas\Server\Reflection\Node}s.
     *
     * @return array
     */
    protected function buildTree()
    {
        $returnTree = [];
        foreach ($this->return as $value) {
            $node = new Node($value);
            $this->addTree($node);
            $returnTree[] = $node;
        }

        return $returnTree;
    }

    /**
     * Build method signatures
     *
     * Builds method signatures using the array of return types and the array of
     * parameters types
     *
     * @param array $return Array of return types
     * @param string $returnDesc Return value description
     * @param array $paramTypes Array of arguments (each an array of types)
     * @param array<int, string> $paramDesc Array of parameter descriptions
     */
    protected function buildSignatures($return, $returnDesc, $paramTypes, $paramDesc): void
    {
        $this->return         = $return;
        $this->returnDesc     = $returnDesc;
        $this->paramDesc      = $paramDesc;
        $this->sigParams      = $paramTypes;
        $this->sigParamsDepth = count($paramTypes);
        $signatureTrees       = $this->buildTree();
        $signatures           = [];

        $endPoints = [];
        foreach ($signatureTrees as $root) {
            $tmp = $root->getEndPoints();
            if (empty($tmp)) {
                $endPoints = array_merge($endPoints, [$root]);
            } else {
                $endPoints = array_merge($endPoints, $tmp);
            }
        }

        foreach ($endPoints as $node) {
            if (! $node instanceof Node) {
                continue;
            }

            $signature = [];
            do {
                array_unshift($signature, $node->getValue());
                $node = $node->getParent();
            } while ($node instanceof Node);

            $signatures[] = $signature;
        }

        // Build prototypes
        $params = $this->reflection->getParameters();
        foreach ($signatures as $signature) {
            $return = new ReflectionReturnValue(array_shift($signature), $this->returnDesc);
            $tmp    = [];
            foreach ($signature as $key => $type) {
                $param = new ReflectionParameter(
                    $params[$key],
                    $type,
                    $this->paramDesc[$key] ?? null
                );
                $param->setPosition($key);
                $tmp[] = $param;
            }

            $this->prototypes[] = new Prototype($return, $tmp);
        }
    }

    /**
     * Use code reflection to create method signatures
     *
     * Determines the method help/description text from the function DocBlock
     * comment. Determines method signatures using a combination of
     * ReflectionFunction and parsing of DocBlock @param and @return values.
     *
     * @throws Exception\RuntimeException
     * @return void
     */
    protected function reflect()
    {
        $function   = $this->reflection;
        $paramCount = $function->getNumberOfParameters();
        $parameters = $function->getParameters();

        if (! $this->docComment) {
            $this->docComment = $function->getDocComment();
        }

        $scanner  = new DocBlockReflection($this->docComment ? : '/***/');
        $helpText = $scanner->getLongDescription();
        /** @var ParamTag[] $paramTags */
        $paramTags = $scanner->getTags('param');
        /** @var ReturnTag $returnTag */
        $returnTag = $scanner->getTag('return');

        if (empty($helpText)) {
            $helpText = $scanner->getShortDescription();
            if (empty($helpText)) {
                $helpText = $function->getName();
            }
        }
        $this->setDescription($helpText);

        if ($returnTag) {
            $return     = [];
            $returnDesc = $returnTag->getDescription();
            foreach ($returnTag->getTypes() as $type) {
                $return[] = $type;
            }
        } else {
            $return     = ['void'];
            $returnDesc = '';
        }

        $paramTypesTmp = [];
        $paramDesc     = [];
        if (empty($paramTags)) {
            foreach ($parameters as $param) {
                // Suppressing, because false positive
                $paramTypesTmp[] = [$this->paramIsArray($param) ? 'array' : 'mixed'];
                $paramDesc[]     = '';
            }
        } else {
            $paramDesc = [];
            foreach ($paramTags as $paramTag) {
                $paramTypesTmp[] = $paramTag->getTypes();
                $paramDesc[]     = $paramTag->getDescription() ? : '';
            }
        }

        // Get all param types as arrays
        $nParamTypesTmp = count($paramTypesTmp);
        if ($nParamTypesTmp < $paramCount) {
            $start = $paramCount - $nParamTypesTmp;
            for ($i = $start; $i < $paramCount; ++$i) {
                $paramTypesTmp[$i] = ['mixed'];
                $paramDesc[$i]     = '';
            }
        } elseif ($nParamTypesTmp !== $paramCount) {
            throw new Exception\RuntimeException(
                'Variable number of arguments is not supported for services (except optional parameters). '
                . 'Number of function arguments must correspond to actual number of arguments described in a docblock.'
            );
        }

        $paramTypes = [];
        foreach ($paramTypesTmp as $i => $param) {
            if ($parameters[$i]->isOptional()) {
                array_unshift($param, null);
            }
            $paramTypes[] = $param;
        }

        $this->buildSignatures($return, $returnDesc, $paramTypes, $paramDesc);
    }

    /**
     * Proxy reflection calls
     *
     * @param string $method
     * @param array $args
     * @throws Exception\BadMethodCallException
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (method_exists($this->reflection, $method)) {
            return call_user_func_array([$this->reflection, $method], $args);
        }

        throw new Exception\BadMethodCallException('Invalid reflection method ("' . $method . '")');
    }

    /**
     * Retrieve configuration parameters
     *
     * Values are retrieved by key from {@link $config}. Returns null if no
     * value found.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->config[$key] ?? null;
    }

    /**
     * Set configuration parameters
     *
     * Values are stored by $key in {@link $config}.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * Set method's namespace
     *
     * @param string $namespace
     * @throws Exception\InvalidArgumentException
     * @return void
     */
    public function setNamespace($namespace)
    {
        if (empty($namespace)) {
            $this->namespace = '';
            return;
        }

        if (! is_string($namespace) || ! preg_match('/[a-z0-9_\.]+/i', $namespace)) {
            throw new Exception\InvalidArgumentException('Invalid namespace');
        }

        $this->namespace = $namespace;
    }

    /**
     * Return method's namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Set the description
     *
     * @param string $string
     * @throws Exception\InvalidArgumentException
     * @return void
     */
    public function setDescription($string)
    {
        if (! is_string($string)) {
            throw new Exception\InvalidArgumentException('Invalid description');
        }

        $this->description = $string;
    }

    /**
     * Retrieve the description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Retrieve all prototypes as array of
     * {@link \Laminas\Server\Reflection\Prototype}s
     *
     * @return Prototype[]
     */
    public function getPrototypes()
    {
        return $this->prototypes;
    }

    /**
     * Retrieve additional invocation arguments
     *
     * @return array
     */
    public function getInvokeArguments()
    {
        return $this->argv;
    }

    /**
     * @return string[]
     */
    public function __sleep()
    {
        $serializable = [];
        foreach ($this as $name => $value) {
            if (
                $value instanceof PhpReflectionFunction
                || $value instanceof PhpReflectionMethod
            ) {
                continue;
            }

            $serializable[] = $name;
        }

        return $serializable;
    }

    /**
     * Wakeup from serialization
     *
     * Reflection needs explicit instantiation to work correctly. Re-instantiate
     * reflection object on wakeup.
     *
     * @return void
     */
    public function __wakeup()
    {
        if ($this->reflection instanceof PhpReflectionMethod) {
            $class            = new PhpReflectionClass($this->class);
            $this->reflection = new PhpReflectionMethod($class->newInstance(), $this->name);
        } else {
            $this->reflection = new PhpReflectionFunction($this->name);
        }
    }

    private function paramIsArray(PhpReflectionParameter $param): bool
    {
        if (PHP_VERSION_ID >= 80000) {
            $type = $param->getType();
            return $type instanceof ReflectionNamedType && $type->getName() === 'array';
        }

        return $param->isArray();
    }
}
