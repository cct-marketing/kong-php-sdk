<?php

declare(strict_types=1);

namespace CCT\Kong\Tests\Http\Request;

use CCT\Kong\Config;
use CCT\Kong\Http\Request;
use CCT\Kong\Serializer\Handler\BooleanToStringHandler;
use CCT\Kong\Serializer\Handler\TimestampDatetimeHandler;
use CCT\Kong\Tests\Helper\ProtectedMethodSetter;
use GuzzleHttp\Client;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Response;

abstract class AbstractRequest extends TestCase
{
    use ProtectedMethodSetter;

    /**
     * @var string
     */
    protected $metadataPath;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->metadataPath = realpath(__DIR__ . '/../../../src/Resources/metadata');
        $this->serializer = $this->createSerializer();

        parent::setUp();
    }

    /**
     * @param int           $statusCode
     * @param string|null   $contentFile
     * @param array         $headers
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|Client
     */
    protected function createClientMocked(
        int $statusCode,
        string $contentFile = null,
        array $headers = ['Content-type' => 'application/json']
    ) {
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $contentPath = realpath(__DIR__ . '/../../Resources/');
        if (!file_exists($contentPath . '/' . $contentFile)) {
            throw new \InvalidArgumentException('The file was not found.');
        }

        $body = file_get_contents($contentPath . '/' . $contentFile);
        $client->expects($this->any())
            ->method('request')
            ->willReturn(new Response(
                $statusCode,
                $headers,
                $body
            ))
        ;

        return $client;
    }

    /**
     * @return Serializer
     */
    protected function createSerializer() : Serializer
    {
        return SerializerBuilder::create()
            ->addMetadataDir($this->metadataPath, 'CCT\\Kong\\Model')
            ->setDebug(true)
            ->addDefaultHandlers()
            ->configureHandlers(function(HandlerRegistry $registry) {
                $registry->registerSubscribingHandler(new BooleanToStringHandler());
                $registry->registerSubscribingHandler(new TimestampDatetimeHandler());
            })
            ->build()
        ;
    }

    /**
     * @param Client $client
     * @param string $class
     * @param Config $config
     *
     * @return Request
     */
    protected function createRequest($client, $class, Config $config) : Request
    {
        $request = new $class($client, $this->getSerializer(), $config);

        return $request;
    }

    /**
     * @return Serializer
     */
    protected function getSerializer() : Serializer
    {
        return $this->serializer;
    }
}
