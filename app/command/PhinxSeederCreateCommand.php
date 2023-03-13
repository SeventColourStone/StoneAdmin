<?php

namespace app\command;

use plugin\stone\nyuwa\generator\PhinxSeederGenerator;
use support\Db;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class PhinxSeederCreateCommand extends Command
{
    protected static $defaultName = 'phinx:seeder:create';
    protected static $defaultDescription = 'phinx seeder create 数据备份';

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setDescription('phinx seeder create 一键数据库备份')
            ->setHelp('phinx seeder create 数据备份')
            ->setDefinition([
                new InputOption('table','t' ,InputOption::VALUE_OPTIONAL, '数据库表 名称'),
                new InputOption('path','p' ,InputOption::VALUE_OPTIONAL, '生成的文件路径，默认为插件下plugin/stone/database/seeds_[path] 不填默认为YmdHi'),
            ]);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $table = $input->getOption('table');
        $path = $input->getOption('path');

        $output->writeln('Hello phinx:seeder:create   ---'.$table);
        //生成文件
        $class = nyuwa_app(PhinxSeederGenerator::class);
        if (!empty($table)){
            $info = Db::select("SELECT TABLE_NAME,TABLE_COMMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = :database and TABLE_NAME = :table;",['database'=>env('DB_DATABASE', 'stone'),'table'=>$table]);
            if (!empty($info)){
                $class->setGenInfo("$table")->generator($path);
                $output->writeln("Hello phinx:seeder:create   {$table} --- 数据表生成完");
            }
        }else{
            $info = Db::select("SELECT TABLE_NAME,TABLE_COMMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = :database ;",['database'=>env('DB_DATABASE', 'stone')]);

            foreach ($info as $item){
                $class->setGenInfo($item->TABLE_NAME)->generator($path);
                $output->writeln("Hello phinx:seeder:create 全量生成： 当前 {$item->TABLE_NAME} --- 数据表生成完");
            }
        }



        return self::SUCCESS;
    }

}
