<?php


namespace App\Serializers;

use App\Entities\Message;

class MessageSerializer implements SerializerInterface
{
    /**
     * @param Message $message
     * @return array
     */
    public static function serialize($message): array
    {
        $from = UserSerializer::serialize($message->getFrom());
        $to = UserSerializer::serialize($message->getto());

        return [
            "content" => $message->getContent(),
            "id" => $message->getId(),
            "from" => $from,
            "to" => $to,
            "created_at" => $message->getCreatedAt()->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @param Message[] $messages
     * @return array
     */
    public static function serializeArray(array $messages): array
    {
        return array_map(function ($message) {
            return self::serialize($message);
        }, $messages);
    }
}