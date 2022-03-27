<?php



namespace app\adminUi\controller\dataCenter;


use nyuwa\NyuwaController;

/**
 * 消息通知ui
 * Class NoticeController
 */
class NoticeController extends NyuwaController
{

    public function index(){
        return view("system/dataCenter/notice/index");
    }

    public function save(){
        return view("system/dataCenter/notice/save");
    }
}
