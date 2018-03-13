<?php

declare(strict_types=1);

namespace CCT\Kong\Tests\Model;

use CCT\Kong\Model\Certificate;
use CCT\Kong\Tests\Helper\ProtectedMethodSetter;
use PHPUnit\Framework\TestCase;

class CertificateTest extends TestCase
{
    use ProtectedMethodSetter;
    
    public function testGetId()
    {
        $object = $this->createObjectInstance();
        $this->assertNull($object->getId());

        $this->setProtectedProperty($object, 'id', 'foo');
        $this->assertEquals('foo', $object->getId());
    }

    public function testSetGetCert()
    {
        $object = $this->createObjectInstance();
        $this->assertNull($object->getCert());

        $object->setCert('foo');
        $this->assertEquals('foo', $object->getCert());
    }

    public function testSetGetKey()
    {
        $object = $this->createObjectInstance();
        $this->assertNull($object->getKey());

        $object->setKey('bar');
        $this->assertEquals('bar', $object->getKey());
    }

    public function testSetGetSnis()
    {
        $object = $this->createObjectInstance();
        $this->assertEmpty($object->getSNIs());

        $object->setSNIs(['foo', 'bar']);
        $this->assertInternalType('array', $object->getSNIs());
        $this->assertContains('foo', $object->getSNIs());
    }

    public function testGetCreatedAt()
    {
        $object = $this->createObjectInstance();
        $this->assertNull($object->getCreatedAt());

        $dateTime = new \DateTime();
        $this->setProtectedProperty($object, 'createdAt', $dateTime);

        $this->assertInstanceOf(\DateTime::class, $object->getCreatedAt());
    }

    protected function createObjectInstance()
    {
        return new Certificate();
    }
}
