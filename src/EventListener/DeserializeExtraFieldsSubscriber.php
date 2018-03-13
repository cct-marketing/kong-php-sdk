<?php

declare(strict_types=1);

namespace CCT\Kong\EventListener;

use CCT\Kong\Collection\CollectionInterface;
use CCT\Kong\Model\Structure\ExtraFieldsInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

class DeserializeExtraFieldsSubscriber implements EventSubscriberInterface
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected static $config;

    /**
     * It sets the default configuration for the DeserializeExtraFields.
     * The array must be sent in following structure:
     * $config = [
     *    'FQDN' => [list of fields to ignore],
     * ];
     *
     * Example:
     * $config = [
     *    'Kong\\Model\\Kong' => ["tagline"],
     * ];
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        self::$config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        $subscribers = [];

        foreach (self::$config as $class => $ignoreFields) {
            if (!class_exists($class)) {
                continue;
            }

            $subscribers[] = [
                'event' => 'serializer.pre_deserialize',
                'method' => 'onPreDeserialize',
                'class' => $class,
                'format' => 'json',
                'priority' => 0,
            ];

            $subscribers[] = [
                'event' => 'serializer.post_deserialize',
                'method' => 'onPostDeserialize',
                'class' => $class,
                'format' => 'json',
                'priority' => 0,
            ];
        }

        return $subscribers;
    }

    /**
     * @param PreDeserializeEvent $event
     */
    public function onPreDeserialize(PreDeserializeEvent $event)
    {
        $this->data = [];

        if (!is_array($event->getData())) {
            return;
        }

        $this->data = $event->getData();
    }

    /**
     * @param ObjectEvent $event
     *
     * @return void
     */
    public function onPostDeserialize(ObjectEvent $event)
    {
        $object = $event->getObject();

        if (!is_object($object)) {
            return;
        }

        $this->populateFields($object);
    }

    /**
     * @param object $object
     *
     * @return void
     */
    protected function populateFields($object)
    {
        $config = static::$config;
        $objectClass = get_class($object);

        if (
            !$object instanceof ExtraFieldsInterface
            || !$object->getExtraFields() instanceof CollectionInterface
            || !isset($config[$objectClass])
        ) {
            return;
        }

        foreach ($this->data as $property => $value) {
            $ignoreFields = $config[$objectClass];

            if (in_array($property, $ignoreFields)) {
                continue;
            }

            $object->getExtraFields()->set($property, $value);
        }
    }
}
