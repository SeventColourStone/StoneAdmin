<?php


namespace plugin\stone\nyuwa;


use Webman\Http\Response;

class NyuwaResponse extends Response
{
    /**
     * @param string|null $message
     * @param array | object $data
     * @param int $code
     * @return NyuwaResponse
     */
    public function success(string $message = null, $data = [], int $code = 200): NyuwaResponse
    {
        $format = [
            'success' => true,
            'message' => $message ?: nyuwa_trans('nyuwa_messages.response_success'),
            'code'    => $code,
            'data'    => &$data,
        ];
        $format = json_encode($format,JSON_UNESCAPED_UNICODE);
        return $this->getResponse()
            ->withHeader('Server', 'WebManAdmin')
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withBody($format);
    }

    /**
     * @param string $message
     * @param array $data
     * @param int $code
     * @return NyuwaResponse
     */
    public function error(string $message = '', int $code = 500, array $data = []): NyuwaResponse
    {
        $format = [
            'success' => false,
            'code'    => $code,
            'message' => $message ?: nyuwa_trans('nyuwa_messages.response_error',[],"nyuwa_messages"),
        ];

        if (!empty($data)) {
            $format['data'] = &$data;
        }

        $format = json_encode($format,JSON_UNESCAPED_UNICODE);
        return $this->getResponse()
            ->withHeader('Server', 'WebManAdmin')
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withBody($format);
    }

    /**
     * 向浏览器输出图片
     * @param string $image
     * @param string $type
     * @return NyuwaResponse
     */
    public function responseImage(string $image, string $type = 'image/png'): NyuwaResponse
    {
        return $this->getResponse()->withHeader('Server', 'WebManAdmin')
            ->withHeader('Content-Type', $type)
            ->withBody($image);
    }

    public function getResponse(): NyuwaResponse
    {
        return new NyuwaResponse();
    }

}
