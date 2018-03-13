<?php

namespace CCT\Kong\Serializer\Handler;

use CCT\Kong\Example\BooleanToString;
use CCT\Kong\Model\Api;
use CCT\Kong\Model\BooleanToStringConverter;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use JMS\Serializer\VisitorInterface;
use Metadata\PropertyMetadata;
use Symfony\Component\PropertyAccess\PropertyAccess;

class BooleanToStringHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'boolean_string',
                'method' => 'serializeBooleanToString',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'boolean_string',
                'method' => 'deserializeBooleanToString',
            ],
        ];
    }

    public function serializeBooleanToString(JsonSerializationVisitor $visitor, $value, array $type, Context $context)
    {
        return (true === $value)
            ? 'true'
            : 'false'
        ;
    }

    public function deserializeBooleanToString(JsonDeserializationVisitor $visitor, $value, array $type, Context $context)
    {
        if ($value === 'false' || $value === '0') {
            $value = false;
        }

        return $visitor->visitBoolean($value, $type, $context);
    }
}