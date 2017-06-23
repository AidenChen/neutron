<?php

namespace App\Repositories;

use App\Facades\DB;

class LiveRepository
{
    public function queryUrl($request)
    {
        $config = require(ROOTPATH . '/config/config.php');
        $number = $request['data']['number'];

//        $deviceId = DB::single('select id from devices where number = :number', [
//            'number' => $number
//        ]);
//        $liveIds = DB::column('select live_id from device_live where device_id = :device_id', [
//            'device_id' => $deviceId
//        ]);

//        $urls = array_map(function($liveId) use ($config) {
//            return [
//                'push_url' => $config['host']['live'] . '/' . ($liveId['live_id'] + 100000),
//                'play_url' => $config['host']['live'] . '/' . ($liveId['live_id'] + 100000)
//            ];
//        }, $liveIds);

        return [
            'code' => 0,
            'msg' => '请求成功！',
            'data' => [
                'urls' => 111
            ]
        ];
    }
}
