<?php


namespace plugin\stone\app\controller;


use DI\Attribute\Inject;
use plugin\stone\app\service\system\SystemUploadfileService;
use plugin\stone\app\validate\system\SystemUploadfileValidate;
use plugin\stone\nyuwa\NyuwaController;
use plugin\stone\nyuwa\NyuwaResponse;
use support\Request;

/**
 * 上传接口待定
 * Class UploadController
 * @package plugin\stone\app\controller
 */
class UploadController extends NyuwaController
{
    /**
     * SystemUploadfileService 服务
     */
    #[Inject]
    protected SystemUploadfileService $service;


    /**
     * @var SystemUploadfileValidate 验证器
     */
    #[Inject]
    protected SystemUploadfileValidate $validate;



    /**
     * 上传文件
     */
    #[PostMapping("uploadFile"), Auth]
    public function uploadFile(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("upload_file")->check($request->all());
        if ($request->file('file')->isValid()) {
            $data = $this->service->upload(
                $request->file('file'), ['path' => $request->input('path', null)]
            );
            return empty($data) ? $this->error() : $this->success($data);
        } else {
            return $this->error(nyuwa_trans('system.upload_file_verification_fail'));
        }
    }

    /**
     * 上传图片
     */
    #[PostMapping("uploadImage"), Auth]
    public function uploadImage(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("upload_image")->check($request->all());
        if ( $request->file('image')->isValid()) {
            $data = $this->service->upload(
                $request->file('image'), ['path' => $request->input('path', null)]
            );
            return empty($data) ? $this->error() : $this->success($data);
        } else {
            return $this->error(nyuwa_trans('system.upload_image_verification_fail'));
        }
    }

    /**
     * 保存网络图片
     */
    #[PostMapping("saveNetworkImage"), Auth]
    public function saveNetworkImage(Request $request): NyuwaResponse
    {
        $data = $this->validate->scene("network_image")->check($request->all());
        return $this->success($this->service->saveNetworkImage($data));
    }

    /**
     * 获取当前目录所有文件和目录
     */
    #[GetMapping("getAllFiles"), Auth]
    public function getAllFiles(Request $request): NyuwaResponse
    {
        return $this->success(
            $this->service->getAllFile($request->all())
        );
    }

    /**
     * 获取文件信息
     */
    #[GetMapping("getFileInfo")]
    public function getFileInfo(Request $request): NyuwaResponse
    {
        return $this->success($this->service->read($request->input('id', null)));
    }

    /**
     * 下载文件
     */
    #[GetMapping("download")]
    public function download(Request $request): NyuwaResponse
    {
        $id = $request->input('id');
        if (empty($id)) {
            return $this->error("附件ID必填");
        }
        $model = $this->service->read($id);
        return $this->_download(BASE_PATH . '/public' . $model->url, $model->origin_name);
    }


}