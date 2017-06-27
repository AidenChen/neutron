<?php

namespace App\Controllers;

use App\Repositories\AuthRepository;

class AuthController
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register($request)
    {
        return $this->authRepository->register($request);
    }

    public function login($request)
    {
        return $this->authRepository->login($request);
    }
}
