<?php

namespace Tests\Utils;

use App\Entities\User;
use Firebase\JWT\JWT;

class JwtGenerator
{
    /**
     * @param User $user
     * @param int $minutesTillExpiry
     * @return string
     */
    public static function getJwt(User $user, int $minutesTillExpiry = 10): string
    {

        /** @var int $timestamp10MinAhead */
        $timestamp10MinAhead = time() + $minutesTillExpiry * 60;

        $payload = array(
            "sub" => $user->getId(),
            "aud" => "test.com",
            "exp" => $timestamp10MinAhead,
            "id" => $user->getId()
        );
        return JWT::encode($payload, $_ENV["JWT_PRIVATE_KEY_FOR_TESTING"], 'RS256');
    }
}
