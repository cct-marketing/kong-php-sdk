<?php

declare(strict_types=1);

namespace CCT\Kong\Model;

class Status
{
    /**
     * @var array
     */
    protected $database;

    /**
     * @var array
     */
    protected $server;

    /**
     * @return array
     */
    public function getDatabase(): array
    {
        return $this->database;
    }

    /**
     * @return array
     */
    public function getServer(): array
    {
        return $this->server;
    }
}
