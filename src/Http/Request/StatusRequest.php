<?php

namespace CCT\Kong\Http\Request;

use CCT\Kong\Config;
use CCT\Kong\Http\Request;
use CCT\Kong\Http\ResponseInterface;
use JMS\Serializer\SerializationContext;

class StatusRequest extends Request
{
    protected function setUp()
    {
        $this->config->set(Config::URI_PREFIX, '/status');
    }

    public function info(): ResponseInterface
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('read'));

        return parent::requestGet($this->getUri(), null);
    }
}
