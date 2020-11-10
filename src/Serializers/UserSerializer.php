<?php


namespace App\Serializers;

use App\Entities\User;

class UserSerializer implements SerializerInterface
{
    /**
     * @param User $user
     * @return array
     */
    public static function serialize($user):array
    {
        return [
            "name" => $user->getName(),
            "id" => $user->getId()
        ];
    }

    /**
     * @param User[] $users
     * @return array
     */
    public static function serializeArray(array $users): array
    {
        return array_map(function ($user) {
            return self::serialize($user);
        }, $users);
    }
}