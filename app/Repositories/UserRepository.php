<?php

namespace App\Repositories;

use App\Facades\DB;

class UserRepository
{
    public function index($request)
    {
        $size = isset($request['data']['page_size']) ? $request['data']['page_size'] : 10;
        $index = isset($request['data']['page_index']) ? $request['data']['page_index'] : 1;
        $size = $size > 200 ? 200 : $size;
        $index = ($index - 1) * $size;

        $count = DB::single('select count(id) from users');
        $users = DB::query('select * from users limit :index, :size', [
            'index' => $index,
            'size' => $size
        ]);

        return [
            'total' => $count,
            'items' => $users
        ];
    }

    public function indexLesson($request)
    {
        $name = $request['data']['name'];

        $userId = DB::single('select id from users where name = :name', [
            'name' => $name
        ]);
        $lessonIds = DB::column('select lesson_id from lesson_user where user_id = :user_id', [
            'user_id' => $userId
        ]);
        $lessons = DB::query('select * from lessons where id in (?)', $lessonIds);

        if (0) {
            return [
                'code' => 40002,
                'params' => [
                    'time' => 3,
                    'min' => 5
                ]
            ];
        } else {
            return [
                'total' => count($lessons),
                'items' => $lessons
            ];
        }
    }
}