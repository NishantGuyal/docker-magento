<?php declare (strict_types = 1);

namespace Nishant\CommandProxyTest\Console\Command;

use Nishant\CommandProxyTest\Model\Fast;
use Nishant\CommandProxyTest\Model\Slow;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExecuteModels extends Command
{
    const TYPE = 'type';

    public function __construct(
        protected Fast $fast,
        protected Slow $slow,
        string $type = null,
    ) {
        parent::__construct($type);
    }

    protected function configure(): void
    {
        $this->setName('nishant:execute-models');
        $this->setDescription('Execute models to test the usage of a proxy.');
        $this->addOption(
            self::TYPE,
            null,
            InputOption::VALUE_REQUIRED,
            'Type',
        );

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $type = $input->getOption(self::TYPE) ?? 'none';
        $data = 'empty';

        if ($type === 'fast') {
            $data = $this->fast->getSomeData();
        } else if ($type === 'slow') {
            $data = $this->slow->getSomeData();
        }

        $output->writeln("<info>Execution completed. Type: $type, Data: $data</info>");

        return 0;
    }
}
