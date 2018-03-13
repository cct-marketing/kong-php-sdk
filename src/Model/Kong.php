<?php

declare(strict_types=1);

namespace CCT\Kong\Model;

use CCT\Kong\Model\Structure\ExtraFieldsInterface;
use CCT\Kong\Model\Structure\ExtraFieldsTrait;

class Kong implements ExtraFieldsInterface
{
    use ExtraFieldsTrait;

    /**
     * @var string
     */
    protected $hostname;

    /**
     * @var string
     */
    protected $luaVersion;

    /**
     * @var string
     */
    protected $tagLine;

    /**
     * @var string
     */
    protected $version;

    /**
     * @return string
     */
    public function getHostname() : string
    {
        return $this->hostname;
    }

    /**
     * @return string
     */
    public function getLuaVersion() : string
    {
        return $this->luaVersion;
    }

    /**
     * @return string
     */
    public function getTagLine() : string
    {
        return $this->tagLine;
    }

    /**
     * @return string
     */
    public function getVersion() : string
    {
        return $this->version;
    }
}
