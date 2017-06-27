<?php

namespace App\Repositories;

use App\Exceptions\ApplicationException;
use App\Facades\DB;
use App\Facades\Token;
use App\Transformers\UserTransformer;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class AuthRepository
{
    protected $userTransformer;

    /**
     * AuthRepository constructor.
     * @param $userTransformer
     */
    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    public function register($request)
    {
        $validator = Validator::attribute('password', Validator::stringType()->length(6, 16)->setName('密码'), true)
            ->attribute('nick', Validator::stringType()->length(1, 20)->setName('昵称'), false);
        try {
            $validator->assert($request);
        } catch (NestedValidationException $exception) {
            return [
                'code' => 42000,
                'details' => $exception->findMessages(config('validation'))
            ];
        }

        switch ($request->type) {
            default:
            case 1:
                return $this->registerByName($request);
                break;
            case 2:
                return $this->registerByPhone($request);
                break;
            case 3:
                return $this->registerByEmail($request);
                break;
        }
    }

    public function registerByName($request)
    {
        $validator = Validator::attribute('name', Validator::stringType()->length(1, 20)->setName('用户名'), true);
        try {
            $validator->assert($request);
        } catch (NestedValidationException $exception) {
            return [
                'code' => 42000,
                'details' => $exception->findMessages(config('validation'))
            ];
        }

        $userId = DB::single('select id from users where name = :name', [
            'name' => $request->name
        ]);
        if ($userId) {
            throw new ApplicationException(46001);
        }

        return $this->store($request);
    }

    public function registerByPhone($request)
    {
        $validator = Validator::attribute('phone', Validator::regex('/^1[3456789][0-9]{9}$/')->setName('电话号码'), true);
        try {
            $validator->assert($request);
        } catch (NestedValidationException $exception) {
            return [
                'code' => 42000,
                'details' => $exception->findMessages(config('validation'))
            ];
        }

        $userId = DB::single('select id from users where phone = :phone', [
            'phone' => $request->phone
        ]);
        if ($userId) {
            throw new ApplicationException(46001);
        }

        return $this->store($request);
    }

    public function registerByEmail($request)
    {
        $validator = Validator::attribute('email', Validator::email()->setName('邮箱地址'), true);
        try {
            $validator->assert($request);
        } catch (NestedValidationException $exception) {
            return [
                'code' => 42000,
                'details' => $exception->findMessages(config('validation'))
            ];
        }

        $userId = DB::single('select id from users where email = :email', [
            'email' => $request->email
        ]);
        if ($userId) {
            throw new ApplicationException(46001);
        }

        return $this->store($request);
    }

    public function store($request)
    {
        $newUser = [
            'password' => password_hash($request->password, PASSWORD_DEFAULT),
            'name' => null,
            'phone' => null,
            'email' => null
        ];

        switch ($request->type) {
            default:
            case 1:
                $newUser['name'] = $request->name;
                $newUser['nick'] = $request->name;
                break;
            case 2:
                $newUser['phone'] = $request->phone;
                $newUser['nick'] = $request->phone;
                break;
            case 3:
                $newUser['email'] = $request->email;
                $newUser['nick'] = $request->email;
                break;
        }

        $resp = DB::query('insert into users (password, name, phone, email, nick) values (:password, :name, :phone, :email, :nick)', $newUser);
        if (! $resp) {
            throw new ApplicationException(50001);
        }

        $user = DB::row('select * from users where id = :id', [
            'id' => DB::lastInsertId()
        ]);
        return [
            'user' => $this->userTransformer->transform($user)
        ];
    }

    public function login($request)
    {
        $validator = Validator::attribute('password', Validator::stringType()->notEmpty()->setName('密码'), true);
        try {
            $validator->assert($request);
        } catch (NestedValidationException $exception) {
            return [
                'code' => 42000,
                'details' => $exception->findMessages(config('validation'))
            ];
        }

        switch ($request->type) {
            default:
            case 1:
                $user = DB::row('select * from users where name = :name', [
                    'name' => $request->name
                ]);
                break;
            case 2:
                $user = DB::row('select * from users where phone = :phone', [
                    'phone' => $request->phone
                ]);
                break;
            case 3:
                $user = DB::row('select * from users where email = :email', [
                    'email' => $request->email
                ]);
                break;
        }
        if (! $user) {
            throw new ApplicationException(48001);
        }
        if (! $user['is_active']) {
            throw new ApplicationException(44001);
        }
        if (! password_verify($request->password, $user['password'])) {
            throw new ApplicationException(40104);
        }

        $token = Token::generate($user['id']);
        header('Authorization: Bearer ' . $token);

        return [
            'user' => $this->userTransformer->transform($user)
        ];
    }
}
