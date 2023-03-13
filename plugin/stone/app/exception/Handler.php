<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace plugin\stone\app\exception;

use plugin\stone\nyuwa\traits\ControllerTrait;
use support\Log;
use Throwable;
use Webman\Http\Request;
use Webman\Http\Response;

/**
 * Class Handler
 * @package support\exception
 */
class Handler extends \support\exception\Handler
{
    use ControllerTrait;
    public function render(Request $request, Throwable $exception): Response
    {
        $code = $exception->getCode();
        $debug = $this->_debug ?? $this->debug;
        if ($request->expectsJson()) {
            $json = ['exception_code' => $code ?: 500, 'msg' => $debug ? $exception->getMessage() : 'Server internal error', 'type' => 'failed'];
            $debug && $json['traces'] = (string)$exception;
            $msg = $debug ? $exception->getMessage() : 'Server internal error';
            Log::error($exception->getFile()."||".$exception->getLine()."||".$exception->getMessage()."||".json_encode($json,JSON_UNESCAPED_UNICODE));
            return $this->error($msg);
        }
        $error = $debug ? \nl2br((string)$exception) : 'Server internal error';
        return new Response(500, [], $error);
    }
}
