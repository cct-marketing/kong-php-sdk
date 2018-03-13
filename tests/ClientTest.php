<?php

namespace CCT\Kong\Tests;

use CCT\Kong\Client;
use CCT\Kong\Config;
use CCT\Kong\Http\Request\ApiRequest;
use CCT\Kong\Http\Request\ConsumerRequest;
use CCT\Kong\Http\Request\KongRequest;
use CCT\Kong\Http\Request\PluginRequest;
use CCT\Kong\Http\Request\SNIRequest;
use JMS\Serializer\Serializer;
use PHPUnit\Framework\TestCase;
use Assert\InvalidArgumentException;
use CCT\Kong\Http\Request\CertificateRequest;

class ClientTest extends TestCase
{
    protected $client;

    protected $config;

    /**
     * @var Serializer
     */
    protected $serializer;

    protected function setUp()
    {
        $this->config = new Config([
            Config::ENDPOINT => 'http://example.com'
        ]);

        $this->client = new Client($this->config, true);
    }

    public function testClientCreationWithoutEndpoint()
    {
        $this->expectException(InvalidArgumentException::class);

        $config = new Config();
        new Client($config);
    }

    public function testConsumersRequestInstance()
    {
        $this->assertInstanceOf(ConsumerRequest::class, $this->client->consumers());
    }

    public function testKongRequestInstance()
    {
        $this->assertInstanceOf(KongRequest::class, $this->client->kong());
    }

    public function testPluginRequestInstance()
    {
        $this->assertInstanceOf(PluginRequest::class, $this->client->plugins());
    }

    public function testApisRequestInstance()
    {
        $this->assertInstanceOf(ApiRequest::class, $this->client->apis());
    }

    public function testSnisRequestInstance()
    {
        $this->assertInstanceOf(SNIRequest::class, $this->client->snis());
    }

    public function testCertificateTestInstance()
    {
        $this->assertInstanceOf(CertificateRequest::class, $this->client->certificates());
    }
}