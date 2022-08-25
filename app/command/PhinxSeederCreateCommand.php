<?php

namespace app\command;

use nyuwa\generator\PhinxSeederGenerator;
use support\Db;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;


class PhinxSeederCreateCommand extends Command
{
    protected static $defaultName = 'phinx:seeder:create';
    protected static $defaultDescription = 'phinx seeder create';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $output->writeln('Hello phinx:seeder:create   ---'.$name);
        //生成文件
        $class = nyuwa_app(PhinxSeederGenerator::class);
//        $class->setGenInfo("system_user")->generator();
        $info = Db::select("SELECT TABLE_NAME,TABLE_COMMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = :database ;",['database'=>env('DB_DATABASE', 'stone')]);

        foreach ($info as $item){
            $class->setGenInfo($item->TABLE_NAME)->generator();
        }

        return self::SUCCESS;
    }

}
