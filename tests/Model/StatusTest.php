<?php

declare(strict_types=1);

namespace CCT\Kong\Tests\Model;

use CCT\Kong\Model\Status;
use CCT\Kong\Tests\Helper\ProtectedMethodSetter;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    use ProtectedMethodSetter;

    public function testGetDatabase()
    {
        $object = $this->createStatusInstance();
        $this->setProtectedProperty($object, 'database', [
            'reachable' => true
        ]);

        $this->assertArrayHasKey('reachable', $object->getDatabase());
    }

    public function testGetServer()
    {
        $object = $this->createStatusInstance();
        $this->setProtectedProperty($object, 'server', [
            "connections_accepted" => 50000,
            "connections_active" => 1,
            "connections_handled" => 50000,
            "connections_reading" => 0,
            "connections_waiting" => 0,
            "connections_writing" => 1,
            "total_requests" => 50000
        ]);

        $this->assertArrayHasKey('connections_accepted', $object->getServer());
    }

    /**
     * @return Status
     */
    public function createStatusInstance()
    {
        return new Status();
    }
}
