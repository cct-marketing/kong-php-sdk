<?php

declare(strict_types=1);

namespace CCT\Kong\Tests\Model;

use CCT\Kong\Collection\CollectionInterface;
use CCT\Kong\Model\Kong;
use CCT\Kong\Tests\Helper\ProtectedMethodSetter;
use PHPUnit\Framework\TestCase;

class KongTest extends TestCase
{
    use ProtectedMethodSetter;

    public function testGetHostname()
    {
        $object = $this->createKongInstance();
        $this->setProtectedProperty($object, 'hostname', 'foo.bar');

        $this->assertEquals('foo.bar', $object->getHostname());
    }

    public function testGetLuaVersion()
    {
        $object = $this->createKongInstance();
        $this->setProtectedProperty($object, 'luaVersion', 'LuaJIT 2.1.0-beta3');

        $this->assertEquals('LuaJIT 2.1.0-beta3', $object->getLuaVersion());
    }

    public function testGetTagLine()
    {
        $object = $this->createKongInstance();
        $this->setProtectedProperty($object, 'tagLine', 'Welcome to kong');

        $this->assertEquals('Welcome to kong', $object->getTagLine());
    }

    public function testGetVersion()
    {
        $object = $this->createKongInstance();
        $this->setProtectedProperty($object, 'version', '0.12.1');

        $this->assertEquals('0.12.1', $object->getVersion());
    }

    public function testGetExtraFields()
    {
        $object = $this->createKongInstance();

        $this->assertInstanceOf(CollectionInterface::class, $object->getExtraFields());
    }

    /**
     * @return Kong
     */
    protected function createKongInstance() : Kong
    {
        return new Kong();
    }
}
