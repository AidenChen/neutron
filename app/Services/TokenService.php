<?php

namespace App\Services;

use App\Exceptions\ApplicationException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;

class TokenService
{
    private static $instance;

    public static function getInstance()
    {
        if (! (self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function generate($uid)
    {
        $signer = new Sha256();
        $token = (new Builder())
            ->setIssuedAt(time())
            ->setExpiration(time() + 3600)
            ->set('uid', $uid)
            ->sign($signer, env('JWT_SECRET'))
            ->getToken();
        return $token;
    }

    public function parse($token)
    {
        try {
            $token = (new Parser())->parse((string) $token);
        } catch (\Exception $exception) {
            return false;
        }
        return $token;
    }

    public function validate($token)
    {
        $signer = new Sha256();
        $data = new ValidationData();

        if (! $token = $this->parse($token)) {
            throw new ApplicationException(40000);
        }

        if (! $token->verify($signer, env('JWT_SECRET'))) {
            throw new ApplicationException(40000);
        }

        if (! $token->validate($data)) {
            throw new ApplicationException(40000);
        }

        $uid = $token->getClaim('uid');
        return $uid;
    }
}
