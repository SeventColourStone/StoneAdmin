<?php


namespace process;


use support\Request;
use Workerman\Connection\AsyncTcpConnection;

class Api
{
    public function onMessage($connection, Request $request)
    {
        $device_id = $request->get('device_id');
        $ws = new AsyncTcpConnection('ws://127.0.0.1:2022');
        $ws->onMessage = function ($ws, $data) use ($connection) {
            $connection->send($data);
            $ws->close();
        };
        $ws->onConnect = function ($ws) use ($device_id) {
            $ws->send($device_id);
        };
        $ws->connect();
    }
}
