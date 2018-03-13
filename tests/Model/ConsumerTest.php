<?php

declare(strict_types=1);

namespace CCT\Kong\Tests\Model;

use CCT\Kong\Model\Consumer;
use CCT\Kong\Tests\Helper\ProtectedMethodSetter;
use PHPUnit\Framework\TestCase;

class ConsumerTest extends TestCase
{
    use ProtectedMethodSetter;

    public function testIfGetIdIsNullOnInitialization()
    {
        $consumer = $this->createConsumerInstance();
        $this->assertNull($consumer->getId());
    }

    public function testIfGetIdIsStringWhenObjectIsPopulated()
    {
        $consumer = $this->createConsumerInstance();
        $this->setProtectedProperty($consumer, 'id', 'string');

        $this->assertEquals('string', $consumer->getId());
    }

    public function testSetGetUsername()
    {
        $consumer = $this->createConsumerInstance();
        $this->assertNull($consumer->getUsername());

        $consumer->setUsername('username');
        $this->assertEquals('username', $consumer->getUsername());
    }

    public function testSetGetCustomId()
    {
        $consumer = $this->createConsumerInstance();
        $this->assertNull($consumer->getCustomId());

        $consumer->setCustomId('consumer_id');
        $this->assertEquals('consumer_id', $consumer->getCustomId());
    }

    public function testIfCreatedAtIsNullOnInstantiation()
    {
        $consumer = $this->createConsumerInstance();
        $this->assertNull($consumer->getCreatedAt());
    }

    public function testIfCreatedAtIsAnDateTimeWhenInstantiated()
    {
        $consumer = $this->createConsumerInstance();
        $this->setProtectedProperty($consumer, 'createdAt', new \DateTime());

        $this->assertInstanceOf(\DateTime::class, $consumer->getCreatedAt());
    }

    /**
     * @return Consumer
     */
    protected function createConsumerInstance()
    {
        return new Consumer();
    }
}
