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
            ->setExpiration(time() + config('jwt.ttl'))
            ->set('uid', $uid)
            ->sign($signer, config('jwt.secret'))
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
            throw new ApplicationException(40101);
        }

        if (! $token->verify($signer, config('jwt.secret'))) {
            throw new ApplicationException(40103);
        }

        if (! $token->validate($data)) {
            throw new ApplicationException(40102);
        }

        $uid = $token->getClaim('uid');
        return $uid;
    }
}
