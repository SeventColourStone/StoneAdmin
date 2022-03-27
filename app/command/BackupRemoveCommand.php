<?php

namespace app\command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;


class BackupRemoveCommand extends Command
{
    protected static $defaultName = 'BackupRemove';
    protected static $defaultDescription = 'BackupRemove';

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('stone:backup-remove')
            ->setDescription('backup remove');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $output->writeln('Hello BackupRemove');

        return self::SUCCESS;
    }

}
