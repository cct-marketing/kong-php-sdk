<?php

declare(strict_types=1);

namespace CCT\Kong\Tests\Model;

use CCT\Kong\Model\SNI;
use CCT\Kong\Tests\Helper\ProtectedMethodSetter;
use PHPUnit\Framework\TestCase;

class SNITest extends TestCase
{
    use ProtectedMethodSetter;

    public function testSetGetName()
    {
        $object = $this->createInstance();
        $this->assertNull($object->getName());

        $object->setName('foo');
        $this->assertEquals('foo', $object->getName());
    }

    public function testSetGetSSLCertificateId()
    {
        $object = $this->createInstance();
        $this->assertNull($object->getCertificateId());

        $object->setCertificateId('foo');
        $this->assertEquals('foo', $object->getCertificateId());
    }

    public function testGetCreatedAt()
    {
        $object = $this->createInstance();
        $this->assertNull($object->getCreatedAt());

        $this->setProtectedProperty($object, 'createdAt', new \DateTime());
        $this->assertInstanceOf(\DateTime::class, $object->getCreatedAt());
    }

    protected function createInstance()
    {
        return new SNI();
    }
}
