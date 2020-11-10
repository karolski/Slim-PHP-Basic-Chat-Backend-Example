<?php


namespace App\Serializers;


use Doctrine\ORM\Mapping\Entity;

interface SerializerInterface
{
    /**
     * @param Entity $entity
     * @return array
     */
    public static function serialize($entity);


    /**
     * @param array $entities
     * @return array
     */
    public static function serializeArray(array $entities);
}