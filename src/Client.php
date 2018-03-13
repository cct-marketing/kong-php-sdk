<?php

declare(strict_types=1);

namespace CCT\Kong;

use Assert\Assert;
use CCT\Kong\EventListener\DeserializeExtraFieldsSubscriber;
use CCT\Kong\Exception\InvalidParameterException;
use CCT\Kong\Helper\ObjectConstructor;
use CCT\Kong\Http\Request\ApiRequest;
use CCT\Kong\Http\Request\ConsumerRequest;
use CCT\Kong\Http\Request\CertificateRequest;
use CCT\Kong\Http\Request\KongRequest;
use CCT\Kong\Http\Request\PluginRequest;
use CCT\Kong\Http\Request\SNIRequest;
use CCT\Kong\Http\Request\StatusRequest;
use CCT\Kong\Http\RequestInterface;
use CCT\Kong\Model\Api;
use CCT\Kong\Model\Consumer;
use CCT\Kong\Model\Info;
use CCT\Kong\Model\Kong;
use CCT\Kong\Model\Plugin;
use CCT\Kong\Model\SNI;
use CCT\Kong\Model\Status;
use CCT\Kong\Serializer\Handler\BooleanToStringHandler;
use CCT\Kong\Serializer\Handler\TimestampDatetimeHandler;
use CCT\Kong\Serializer\SerializerBuilder;
use CCT\Kong\Transformer\Response\CollectionObjectTransformer;
use CCT\Kong\Transformer\Response\ObjectTransformer;
use CCT\Kong\Model\Certificate;
use GuzzleHttp\Client as GuzzleClient;
use JMS\Serializer\SerializerInterface;

class Client
{
    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var bool
     */
    protected $defaultConfig = true;

    /**
     * @var SerializerInterface
     */
    protected static $serializer;

    public function __construct(Config $config, bool $defaultConfig = true)
    {
        Assert::that($config->toArray())->keyExists(Config::ENDPOINT);

        $this->defaultConfig = $defaultConfig;
        $this->config = $config;
        $this->client = new GuzzleClient([
            'base_uri' => $config->get(Config::ENDPOINT)
        ]);

        if ($defaultConfig) {
            $this->applyDefaults();
        }
    }

    /**
     * @return KongRequest
     */
    public function kong() : KongRequest
    {
        $config = clone $this->config;
        $modelClass = $config->get('kong.model.class', Kong::class);

        if ($this->isDefaultConfig()) {
            $config->set(Config::OBJECT_CONSTRUCTOR, new ObjectConstructor());
            $config->set(Config::EVENT_SUBSCRIBERS, [
                new DeserializeExtraFieldsSubscriber([
                    $modelClass => [
                        'tagline',
                        'lua_version',
                        'hostname',
                        'version',
                    ]
                ]),
            ]);
        }

        $serializer = $this->buildSerializer($config);
        if ($this->shouldUseDefaultResponseTransformers()) {
            $this->applyDefaultResponseTransformers($config, $serializer, $modelClass);
        }

        return $this->createRequestInstance(KongRequest::class, $serializer, $config);
    }

    /**
     * @return StatusRequest
     */
    public function status() : StatusRequest
    {
        $config = clone $this->config;
        $modelClass = $config->get('status.model.class', Status::class);

        $serializer = $this->getBuiltSerializer($config);
        if ($this->shouldUseDefaultResponseTransformers()) {
            $this->applyDefaultResponseTransformers($config, $serializer, $modelClass);
        }

        return $this->createRequestInstance(StatusRequest::class, $serializer, $config);
    }

    /**
     * @return ApiRequest
     */
    public function apis() : ApiRequest
    {
        $config = clone $this->config;
        $modelClass = $config->get('api.model.class', Api::class);

        $serializer = $this->getBuiltSerializer($config);
        if ($this->shouldUseDefaultResponseTransformers()) {
            $this->applyDefaultResponseTransformers($config, $serializer, $modelClass);
        }

        return $this->createRequestInstance(ApiRequest::class, $serializer, $config);
    }

    /**
     * @return ConsumerRequest
     */
    public function consumers() : ConsumerRequest
    {
        $config = clone $this->config;
        $modelClass = $config->get('consumer.model.class', Consumer::class);

        $serializer = $this->getBuiltSerializer($config);
        if ($this->shouldUseDefaultResponseTransformers()) {
            $this->applyDefaultResponseTransformers($config, $serializer, $modelClass);
        }

        return $this->createRequestInstance(ConsumerRequest::class, $serializer, $config);
    }

    /**
     * @return PluginRequest
     */
    public function plugins() : PluginRequest
    {
        $config = clone $this->config;
        $modelClass = $config->get('plugin.model.class', Plugin::class);

        $serializer = $this->getBuiltSerializer($config);
        if ($this->shouldUseDefaultResponseTransformers()) {
            $this->applyDefaultResponseTransformers($config, $serializer, $modelClass);
        }

        return $this->createRequestInstance(PluginRequest::class, $serializer, $config);
    }

    /**
     * @return CertificateRequest
     */
    public function certificates() : CertificateRequest
    {
        $config = clone $this->config;
        $modelClass = $config->get('certificate.model.class', Certificate::class);

        $serializer = $this->getBuiltSerializer($config);
        if ($this->shouldUseDefaultResponseTransformers()) {
            $this->applyDefaultResponseTransformers($config, $serializer, $modelClass);
        }

        return $this->createRequestInstance(CertificateRequest::class, $serializer, $config);
    }

    /**
     * @return SNIRequest
     */
    public function snis() : SNIRequest
    {
        $config = clone $this->config;
        $modelClass = $config->get('sni.model.class', SNI::class);

        $serializer = $this->getBuiltSerializer($config);
        if ($this->shouldUseDefaultResponseTransformers()) {
            $this->applyDefaultResponseTransformers($config, $serializer, $modelClass);
        }

        return $this->createRequestInstance(SNIRequest::class, $serializer, $config);
    }

    public function enableDefaultConfig()
    {
        $this->defaultConfig = true;
    }

    public function disableDefaultConfig()
    {
        $this->defaultConfig = false;
    }

    public function isDefaultConfig() : bool
    {
        return $this->defaultConfig;
    }

    protected function applyDefaults()
    {
        $this->config->set(Config::METADATA_DIRS, [
            [
                'dir' => __DIR__ . '/Resources/metadata',
                'namespacePrefix' => 'CCT\\Kong\\Model',
            ]
        ]);

        $this->config->set(Config::USE_DEFAULT_RESPONSE_TRANSFORMERS, true);
        $this->config->set(Config::SERIALIZATION_HANDLERS, [
            new BooleanToStringHandler(),
            new TimestampDatetimeHandler()
        ]);
    }

    public function clearDefaults()
    {
        $this->config->remove(Config::METADATA_DIRS);
        $this->config->remove(Config::DEBUG);
        $this->config->remove(Config::EVENT_SUBSCRIBERS);
        $this->config->remove(Config::SERIALIZATION_HANDLERS);
        $this->config->remove(Config::OBJECT_CONSTRUCTOR);
        $this->config->remove(Config::USE_DEFAULT_RESPONSE_TRANSFORMERS);
    }

    protected function buildSerializer(Config $config)
    {
        return SerializerBuilder::createByConfig($config)
            ->configureDefaults()
            ->build()
        ;
    }

    protected function getBuiltSerializer(Config $config)
    {
        if (null === static::$serializer) {
            static::$serializer = $this->buildSerializer($config);
        }

        return static::$serializer;
    }

    protected function createRequestInstance($class, SerializerInterface $serializer, Config $config)
    {
        $reflectionClass = new \ReflectionClass($class);

        if (!$reflectionClass->implementsInterface(RequestInterface::class)) {
            throw new InvalidParameterException(sprintf(
                'The class must be an instance of %s',
                RequestInterface::class
            ));
        }

        return $reflectionClass->newInstance(
            $this->client,
            $serializer,
            $config
        );
    }

    /**
     * Should use the default response transformers?
     *
     * @return bool
     */
    protected function shouldUseDefaultResponseTransformers() : bool
    {
        return (bool) $this->config->get(Config::USE_DEFAULT_RESPONSE_TRANSFORMERS, true);
    }

    protected function applyDefaultResponseTransformers(Config $config, SerializerInterface $serializer, $modelClass)
    {
        $config->set(Config::RESPONSE_TRANSFORMERS, [
            new ObjectTransformer($serializer, $modelClass),
            new CollectionObjectTransformer($serializer, $modelClass)
        ]);
    }
}
