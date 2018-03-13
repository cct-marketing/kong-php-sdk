<?php

declare(strict_types=1);

namespace CCT\Kong\Tests\Model;

use CCT\Kong\Exception\InvalidParameterException;
use CCT\Kong\Tests\Helper\ProtectedMethodSetter;
use PHPUnit\Framework\TestCase;
use CCT\Kong\Model\Plugin;

class PluginTest extends TestCase
{
    use ProtectedMethodSetter;

    public function testGetterId()
    {
        $plugin = $this->createInstance();
        $this->assertNull($plugin->getId());

        $this->setProtectedProperty($plugin, 'id', 'some-string');
        $this->assertEquals('some-string', $plugin->getId());
    }

    public function testGetterSetterApiId()
    {
        $plugin = $this->createInstance();
        $plugin->setApiId('some-string');

        $this->assertEquals('some-string', $plugin->getApiId());
    }

    public function testGetterSetterConsumerId()
    {
        $plugin = $this->createInstance();
        $plugin->setConsumerId('some-string');

        $this->assertEquals('some-string', $plugin->getConsumerId());
    }

    public function testGetterSetterName()
    {
        $plugin = $this->createInstance();
        $plugin->setName('some-name');

        $this->assertEquals('some-name', $plugin->getName());
    }

    public function testGetterSetterConfig()
    {
        $plugin = $this->createInstance();
        $plugin->setConfig([
            'config.something' => 'value'
        ]);

        $this->assertInternalType('array', $plugin->getConfig());
    }

    public function testSetterGetterEnabled()
    {
        $plugin = $this->createInstance();
        $this->assertTrue($plugin->isEnabled());

        $plugin->setEnabled(false);
        $this->assertFalse($plugin->isEnabled());
    }

    public function testCreatedAt()
    {
        $plugin = $this->createInstance();
        $this->assertNull($plugin->getCreatedAt());

        $this->setProtectedProperty($plugin, 'createdAt', new \DateTime());
        $this->assertInstanceOf(\DateTime::class, $plugin->getCreatedAt());
    }

    protected function createInstance()
    {
        return new Plugin();
    }
}
