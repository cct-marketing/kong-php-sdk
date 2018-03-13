<?php

declare(strict_types=1);

namespace CCT\Kong\Serializer;

use CCT\Kong\Config;
use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\EventDispatcher\EventDispatcher;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializerBuilder as JMSSerializerBuilder;

class SerializerBuilder implements SerializerBuilderInterface
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var JMSSerializerBuilder
     */
    protected $jmsSerializerBuilder;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->jmsSerializerBuilder = JMSSerializerBuilder::create();
    }

    public static function createByConfig(Config $config)
    {
        return new static($config);
    }

    public function addMetadataDir($dir, $namespacePrefix)
    {
        $metadataDirs = $this->config->get(Config::METADATA_DIRS, []);
        $metadataDirs[] = [
            'dir' => $dir,
            'namespacePrefix' => $namespacePrefix
        ];

        $this->config->set(Config::METADATA_DIRS, $metadataDirs);

        return $this;
    }

    public function removeMetadataDir($dir)
    {
        $metadataDirs = $this->config->get(Config::METADATA_DIRS, []);

        foreach ($metadataDirs as $key => $metadataDir) {
            if ($metadataDir['dir'] == $dir) {
                unset($metadataDirs[$key]);
            }
        }

        $this->config->set(Config::METADATA_DIRS, $metadataDirs);

        return $this;
    }

    public function addEventSubscribers(EventSubscriberInterface $eventSubscriber)
    {
        $this->config->merge([
            Config::EVENT_SUBSCRIBERS => [$eventSubscriber]
        ]);

        return $this;
    }

    public function removeEventSubscribers(EventSubscriberInterface $eventSubscriber)
    {
        $eventSubscribers = $this->config->get(Config::EVENT_SUBSCRIBERS, []);

        foreach ($eventSubscribers as $key => $storedEventSubscribers) {
            if (get_class($storedEventSubscribers) === get_class($eventSubscriber)) {
                unset($eventSubscribers[$key]);
            }
        }

        $this->config->set(Config::EVENT_SUBSCRIBERS, $eventSubscribers);

        return $this;
    }

    public function addSerializationHandler(SubscribingHandlerInterface $subscribingHandler)
    {
        $this->config->merge([
            Config::SERIALIZATION_HANDLERS => [$subscribingHandler]
        ]);

        return $this;
    }

    public function removeSerializationHandler(SubscribingHandlerInterface $subscribingHandler)
    {
        $subscribingHandlers = $this->config->get(Config::SERIALIZATION_HANDLERS, []);

        foreach ($subscribingHandlers as $key => $storedSubscribingHandlers) {
            if (get_class($storedSubscribingHandlers) === get_class($subscribingHandler)) {
                unset($subscribingHandlers[$key]);
            }
        }

        $this->config->set(Config::SERIALIZATION_HANDLERS, $subscribingHandlers);

        return $this;
    }

    public function setObjectConstructor(ObjectConstructorInterface $objectConstructor)
    {
        $this->config->set(Config::OBJECT_CONSTRUCTOR, $objectConstructor);

        return $this;
    }

    public function configureDefaults()
    {
        $this->jmsSerializerBuilder->addDefaultHandlers();
        $this->jmsSerializerBuilder->addDefaultListeners();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build() : SerializerInterface
    {
        $this->jmsSerializerBuilder->setDebug($this->config->get('debug', false));

        foreach ($this->config->get(Config::METADATA_DIRS, []) as $metadataDir) {
            $this->jmsSerializerBuilder->addMetadataDir($metadataDir['dir'], $metadataDir['namespacePrefix']);
        }

        $eventSubscribers = $this->config->get(Config::EVENT_SUBSCRIBERS, []);
        $this->jmsSerializerBuilder->configureListeners(function (EventDispatcher $dispatcher) use ($eventSubscribers) {
            foreach ($eventSubscribers as $eventSubscriber) {
                $dispatcher->addSubscriber($eventSubscriber);
            }
        });

        $serializationHandlers = $this->config->get(Config::SERIALIZATION_HANDLERS, []);
        $this->jmsSerializerBuilder->configureHandlers(function (HandlerRegistry $handlerRegistry) use ($serializationHandlers) {
            foreach ($serializationHandlers as $handler) {
                $handlerRegistry->registerSubscribingHandler($handler);
            }
        });

        $objectConstructor = $this->config->get(Config::OBJECT_CONSTRUCTOR, null);
        if (null !== $objectConstructor) {
            $this->jmsSerializerBuilder->setObjectConstructor($objectConstructor);
        }

        return $this->jmsSerializerBuilder->build();
    }
}
