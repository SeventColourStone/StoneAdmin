<?php


namespace app\adminUi\controller\dataCenter;


use nyuwa\NyuwaController;

/**
 * 附件ui
 * Class AttachmentController
 * @package app\admin\controller\ui\system\dataCenter
 */
class AttachmentController extends NyuwaController
{
    public function index(){
        return view("system/dataCenter/attachment/index");
    }

    public function save(){
        return view("system/dataCenter/attachment/save");
    }
}
