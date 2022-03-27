<?php

namespace app\command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Vtiful\Kernel\Excel;


class TestCommand extends Command
{
    protected static $defaultName = 'Test';
    protected static $defaultDescription = 'Test';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addOption('table', "table",InputOption::VALUE_REQUIRED, 'Name description');
        $this->addOption('module', "module",InputOption::VALUE_REQUIRED, 'Name description');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = $input->getOption('table');
        $module = $input->getOption('module');
        var_dump($table);
        var_dump($module);
        $output->writeln('Hello Test');
        $config = [
            'path' => public_path().'/excel/test' // xlsx文件保存路径
        ];
        $excel  = new Excel($config);

// fileName 会自动创建一个工作表，你可以自定义该工作表名称，工作表名称为可选参数
        $filePath = $excel->fileName('tutorial01.xlsx', 'sheet1')
            ->header(['Item', 'Cost'])
            ->data([
                ['Rent', 1000],
                ['Gas',  100],
                ['Food', 300],
                ['Gym',  50],
            ])
            ->output();
        return self::SUCCESS;
    }

}
