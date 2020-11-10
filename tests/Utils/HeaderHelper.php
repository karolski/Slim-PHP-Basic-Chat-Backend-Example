<?php


namespace Tests\Utils;


use App\Entities\User;
use http\Header;

class HeaderHelper
{
    /**
     * @param User $user
     * @return Header
     */
    public static function getAuthHeader(User $user): Header
    {
        $token = JwtGenerator::getJwt($user);
        return new Header("Authorization", "Bearer " . $token);
    }
}