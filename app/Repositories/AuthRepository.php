<?php

namespace App\Repositories;

use App\Exceptions\ApplicationException;
use App\Facades\DB;
use App\Facades\Token;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class AuthRepository
{
    public function login($request)
    {
        $token = Token::generate(26);

        dd($token);
        $id = Token::validate($token);
    }
}
