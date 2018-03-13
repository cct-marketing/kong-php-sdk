<?php

namespace CCT\Kong\Http\Request;

use CCT\Kong\Config;
use CCT\Kong\Http\Definition\QueryParams;
use CCT\Kong\Http\Request;
use CCT\Kong\Model\Api;
use JMS\Serializer\SerializationContext;

class ApiRequest extends Request
{
    protected function setUp()
    {
        $this->config->set(Config::URI_PREFIX, '/apis/');
    }

    public function list(QueryParams $queryParams = null)
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('read'));

        return parent::requestGet($this->getUri(), $queryParams);
    }

    public function create(Api $api)
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('create'));

        return parent::requestPost($this->getUri(), $api, null);
    }

    public function update(Api $api)
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('update'));

        return parent::requestPatch($this->appendToUri($api->getId()), $api, null);
    }

    public function updateOrCreate(Api $api)
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups([ 'update', 'create' ]));

        return parent::requestPut($this->appendToUri($api->getId()), $api, null);
    }

    public function retrieve(string $nameOrId)
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('read'));

        return parent::requestGet($this->appendToUri($nameOrId), null);
    }

    public function delete(string $nameOrId)
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('delete'));

        return parent::requestDelete($this->appendToUri($nameOrId), null);
    }
}
