<?php

declare(strict_types=1);

namespace CCT\Kong\Tests\Model;

use CCT\Kong\Model\Api;
use CCT\Kong\Tests\Helper\ProtectedMethodSetter;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    use ProtectedMethodSetter;

    public function testGetId()
    {
        $object = $this->createApiInstance();
        $this->assertNull($object->getId());

        $this->setProtectedProperty($object, 'id', '8b6d15a6-ff7b-11e7-8be5-0ed5f89f718b');
        $this->assertEquals('8b6d15a6-ff7b-11e7-8be5-0ed5f89f718b', $object->getId());
    }

    public function testGetSetName()
    {
        $object = $this->createApiInstance();
        $this->assertNull($object->getName());

        $object->setName('foo');
        $this->assertEquals('foo', $object->getName());
    }

    public function testSetGetRetries()
    {
        $object = $this->createApiInstance();
        $this->assertEquals(5, $object->getRetries());

        $object->setRetries(2);
        $this->assertEquals(2, $object->getRetries());
    }

    public function testSetGetHosts()
    {
        $object = $this->createApiInstance();
        $this->assertEmpty($object->getHosts());

        $object->setHosts(['foo.com', 'bar.com']);
        $this->assertCount(2, $object->getHosts());
    }

    public function testSetGetMethods()
    {
        $object = $this->createApiInstance();
        $this->assertEmpty($object->getMethods());

        $object->setMethods(['POST', 'GET']);
        $this->assertCount(2, $object->getMethods());
    }

    public function testSetGetUpstreamUrl()
    {
        $object = $this->createApiInstance();
        $this->assertEmpty($object->getUpstreamUrl());

        $object->setUpstreamUrl('foo.com');
        $this->assertEquals('foo.com', $object->getUpstreamUrl());
    }

    public function testSetGetStripUrl()
    {
        $object = $this->createApiInstance();
        $this->assertTrue($object->isStripUri());

        $object->setStripUri(false);
        $this->assertFalse($object->isStripUri());
    }

    public function testSetGetPreserveHost()
    {
        $object = $this->createApiInstance();
        $this->assertFalse($object->isPreserveHost());

        $object->setPreserveHost(true);
        $this->assertTrue($object->isPreserveHost());
    }

    public function testSetGetHttpsOnly()
    {
        $object = $this->createApiInstance();
        $this->assertFalse($object->getHttpsOnly());

        $object->setHttpsOnly(true);
        $this->assertTrue($object->getHttpsOnly());
    }

    public function testSetGetUpstreamConnectTimeout()
    {
        $object = $this->createApiInstance();
        $this->assertEquals(60000, $object->getUpstreamConnectTimeout());

        $object->setUpstreamConnectTimeout(5000);
        $this->assertEquals(5000, $object->getUpstreamConnectTimeout());
    }

    public function testSetGetUpstreamSendTimeout()
    {
        $object = $this->createApiInstance();
        $this->assertEquals(60000, $object->getUpstreamSendTimeout());

        $object->setUpstreamSendTimeout(4000);
        $this->assertEquals(4000, $object->getUpstreamSendTimeout());
    }

    public function testSetGetUpstreamReadTimeout()
    {
        $object = $this->createApiInstance();
        $this->assertEquals(60000, $object->getUpstreamReadTimeout());

        $object->setUpstreamReadTimeout(2000);
        $this->assertEquals(2000, $object->getUpstreamReadTimeout());
    }

    public function testSetGetHttpIfTerminated()
    {
        $object = $this->createApiInstance();
        $this->assertFalse($object->isHttpIfTerminated());

        $object->setHttpIfTerminated(true);
        $this->assertTrue($object->isHttpIfTerminated());
    }

    public function testGetCreatedAt()
    {
        $object = $this->createApiInstance();
        $this->assertNull($object->getCreatedAt());

        $this->setProtectedProperty($object, 'createdAt', new \DateTime());
        $this->assertInstanceOf(\DateTime::class, $object->getCreatedAt());
    }

    /**
     * @return Api
     */
    protected function createApiInstance()
    {
        return new Api();
    }
}
