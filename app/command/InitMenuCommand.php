<?php

namespace app\command;

use app\admin\model\system\SystemMenu;
use app\admin\service\system\SystemMenuService;
use nyuwa\traits\GencodeTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;


class InitMenuCommand extends Command
{
    use GencodeTrait;
    protected static $defaultName = 'stone:init-menu';
    protected static $defaultDescription = 'InitMenu';

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
        $output->writeln('Hello InitMenu');

        $topMenuArr = [
            ["name"=>"工作空间","code"=>"work","icon"=>".pear-icon-electronics","type"=>SystemMenu::DIRECTORY_LIST],
            ["name"=>"系统中心","code"=>"system","icon"=>".pear-icon-home","type"=>SystemMenu::DIRECTORY_LIST],
            ["name"=>"数据中心","code"=>"data","icon"=>".pear-icon-data-view","type"=>SystemMenu::DIRECTORY_LIST],
            ["name"=>"开发中心","code"=>"dev","icon"=>".pear-icon-work","type"=>SystemMenu::DIRECTORY_LIST]
        ];

        $menuList = [];
        foreach ($topMenuArr as $k =>$item){
            $menuItem = [
                "parent_id"=> 0,
                "level"=> 0,
                "name"=> $item['name'],
                "route"=> "",
                "component"=> "",
                "redirect"=> "",
                "code"=> $item['code'],
                "icon"=> $item['icon'],
                "sort"=> $k,
                "type"=> $item['type'],
                "open_type"=> "",
            ];
            $menuList []= $menuItem;

        }
//        $ser = new SystemMenuService();
//        $ser->batchSave($menuList);
        $bool = nyuwa_app(SystemMenuService::class)->batchSave($menuList);
        var_dump($bool);


        return self::SUCCESS;
    }

}
