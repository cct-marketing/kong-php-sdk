<?php

namespace CCT\Kong\Http\Request;

use CCT\Kong\Config;
use CCT\Kong\Exception\InvalidParameterException;
use CCT\Kong\Exception\InvalidResourceException;
use CCT\Kong\Http\Definition\QueryParams;
use CCT\Kong\Http\Request;
use CCT\Kong\Http\ResponseInterface;
use CCT\Kong\Model\Consumer;
use JMS\Serializer\SerializationContext;

class ConsumerRequest extends Request
{
    protected function setUp()
    {
        $this->config->set(Config::URI_PREFIX, '/consumers/');
    }

    public function list(QueryParams $queryParams = null) : ResponseInterface
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('read'));

        return parent::requestGet($this->getUri(), $queryParams);
    }

    public function create(Consumer $consumer) : ResponseInterface
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('create'));

        return parent::requestPost($this->getUri(), $consumer, null);
    }

    public function update(Consumer $consumer) : ResponseInterface
    {
        $this->validateObjectId($consumer);
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('update'));

        return parent::requestPatch($this->appendToUri($consumer->getId()), $consumer, null);
    }

    public function updateOrCreate(Consumer $consumer) : ResponseInterface
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('update'));

        try {
            $this->validateObjectId($consumer);
            $uri = $this->appendToUri($consumer->getId());
        } catch (InvalidParameterException $e) {
            $uri = $this->getUri();
        }

        return parent::requestPut($uri, $consumer, null);
    }

    public function retrieve(string $usernameOrId) : ResponseInterface
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('read'));

        return parent::requestGet($this->appendToUri($usernameOrId), null);
    }

    public function delete(Consumer $consumer) : ResponseInterface
    {
        $this->validateObjectId($consumer);
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('delete'));

        return parent::requestDelete($this->appendToUri($consumer->getId()), null);
    }
}
