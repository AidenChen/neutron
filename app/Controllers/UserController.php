<?php

namespace App\Controllers;

use App\Repositories\UserRepository;

class UserController
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index($request)
    {
        return $this->userRepository->index($request);
    }

    public function indexLesson($request)
    {
        return $this->userRepository->indexLesson($request);
    }
}
