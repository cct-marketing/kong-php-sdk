<?php

declare(strict_types=1);

namespace CCT\Kong\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;

class TimestampDatetimeHandler implements SubscribingHandlerInterface
{
    protected $timezone;

    public function __construct(\DateTimeZone $timezone = null)
    {
        if (null === $timezone) {
            $timezone = new \DateTimeZone('UTC');
        }

        $this->timezone = $timezone;
    }

    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'DateTime',
                'method' => 'deserializeTimestampToJson',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'DateTime',
                'method' => 'serializeTimestampToJson',
            ],
        ];
    }

    public function deserializeTimestampToJson(JsonDeserializationVisitor $visitor, $data, $type) :? \DateTime
    {
        if (empty($data) || 13 !== strlen((string) $data)) {
            return null;
        }

        return $this->convertToDateTime((string) $data);
    }

    public function serializeTimestampToJson(
        JsonSerializationVisitor $visitor,
        \DateTime $date,
        array $type,
        Context $context
    ) : int {
        $time = (int) substr($date->format('Uu'), 0, 13);

        return $visitor->visitInteger($time, $type, $context);
    }

    protected function convertToDateTime(string $data)
    {
        $datetime = \DateTime::createFromFormat(
            'U.u',
            $this->normalizeKongDateFormat($data),
            $this->timezone
        );

        if (false === $datetime) {
            throw new \RuntimeException("It was not possible to convert the Kong's date to PHP \DateTime");
        }

        return $datetime;
    }

    protected function normalizeKongDateFormat(string $data)
    {
        return sprintf(
            '%s.%s',
            substr($data, 0, 10),
            round((int) substr($data, -3, 3))
        );
    }
}
