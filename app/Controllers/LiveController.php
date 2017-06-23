<?php

namespace App\Controllers;

use App\Repositories\LiveRepository;

class LiveController
{
    protected $liveRepository;

    public function __construct(LiveRepository $liveRepository)
    {
        $this->liveRepository = $liveRepository;
    }

    public function queryUrl($request)
    {
        $res = $this->liveRepository->queryUrl($request);

        return $res;
    }
}
