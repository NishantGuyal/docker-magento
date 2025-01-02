<?php

declare (strict_types = 1);

namespace Nishant\ProductCompare\Controller;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;

class Router implements RouterInterface
{

    public function __construct(
        private ActionFactory $actionFactory,
    ) {}
    /**
     * Match a route to this router.
     *
     * @param RequestInterface $request
     * @return ActionInterface|null
     */
    public function match(
        RequestInterface $request,
    ): ActionInterface | null {

        // dd($request->getPathInfo());
        $path = trim($request->getPathInfo(), "/");
        $pathParts = explode("/", $path);
        // dd($pathParts);
        if ($pathParts[0] == "compare") {
            $skus = array_slice($pathParts, 1);
            $request->setModuleName('compare')
                ->setControllerName('index')
                ->setActionName('index')
                ->setParam('skus', $skus);

            return $this->actionFactory->create(Forward::class);
        }
        return null;
    }
}
