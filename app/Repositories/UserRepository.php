<?php

namespace App\Repositories;

use App\Exceptions\ApplicationException;
use App\Facades\DB;
use App\Transformers\LessonTransformer;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class UserRepository
{
    protected $lessonTransformer;

    /**
     * UserRepository constructor.
     * @param $lessonTransformer
     */
    public function __construct(LessonTransformer $lessonTransformer)
    {
        $this->lessonTransformer = $lessonTransformer;
    }

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
        $validator = Validator::attribute('name', Validator::stringType()->noWhitespace()->length(1,5)->setName('name'));
        try {
            $validator->assert($request);
        } catch(NestedValidationException $exception) {
            $errors = $exception->findMessages([
                'length' => '{{name}}',
                'noWhitespace' => '{{name}}'
            ]);
            print_r($errors);
            exit();
            throw new ApplicationException(42000, $exception->getMessages()[count($exception->getMessages()) - 1]);
        }

//        $userId = DB::single('select id from users where name = :name', [
//            'name' => $name
//        ]);
//        $lessonIds = DB::column('select lesson_id from lesson_user where user_id = :user_id', [
//            'user_id' => $userId
//        ]);
//        $lessons = DB::query('select * from lessons where id in (?)', $lessonIds);

        if (0) {
            throw new ApplicationException(40001);
//            return [
//                'code' => 40002,
//                'params' => [
//                    'time' => 3,
//                    'min' => 5
//                ]
//            ];
        } else {
            return [
//                'total' => count($lessons),
//                'items' => $this->lessonTransformer->collection($lessons)
            ];
        }
    }
}
