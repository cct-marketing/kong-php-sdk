<?php

declare(strict_types=1);

namespace CCT\Kong\Http\Request;

use CCT\Kong\Config;
use CCT\Kong\Exception\InvalidParameterException;
use CCT\Kong\Form\Normalizer\PluginFormNormalizer;
use CCT\Kong\Http\Definition\QueryParams;
use CCT\Kong\Http\Request;
use CCT\Kong\Http\ResponseInterface;
use CCT\Kong\Model\Plugin;
use JMS\Serializer\SerializationContext;

class PluginRequest extends Request
{
    protected function setUp()
    {
        if (false === $this->config->has(Config::URI_PREFIX)) {
            $this->config->set(Config::URI_PREFIX, '/plugins/');
        }
    }

    public function list(QueryParams $queryParams = null): ResponseInterface
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('read'));

        return parent::requestGet($this->getUri(), $queryParams);
    }

    public function listEnabled(): ResponseInterface
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('read'));
        $this->config->set(Config::RESPONSE_TRANSFORMERS, []);

        return parent::requestGet($this->appendToUri('/enabled/'), null);
    }

    public function listByApi(string $apiId): ResponseInterface
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('read'));

        return parent::requestGet($this->getUriByApiId($apiId), null);
    }

    public function create(Plugin $plugin, string $apiId = null): ResponseInterface
    {
        if (null === $plugin->getApiId() && null === $apiId) {
            throw new InvalidParameterException('The API ID is required to create a new Plugin.');
        }

        $this->config->set('serialization_context', SerializationContext::create()->setGroups('create'));
        $this->changeFormNormalizer();

        $apiId = $plugin->getApiId() ?: $apiId;
        $uri = $this->getUriByApiId($apiId);

        return parent::requestPost($uri, $plugin, null);
    }

    public function retrieve(string $apiId, string $pluginId): ResponseInterface
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('read'));
        $uri = $this->getUriByApiId($apiId);

        return parent::requestGet($this->appendToUri($pluginId, $uri), null);
    }

    public function retrieveSchema(string $pluginName): ResponseInterface
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('read'));
        $this->config->set(Config::RESPONSE_TRANSFORMERS, []);

        $uri = $this->appendToUri(sprintf(
            '/schema/%s/',
            $pluginName
        ));

        return parent::requestGet($uri, null);
    }

    public function update(Plugin $plugin): ResponseInterface
    {
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('update'));
        $this->changeFormNormalizer();

        $uri = $this->getUriByApiId($plugin->getApiId());

        return parent::requestPatch($this->appendToUri($plugin->getId(), $uri), $plugin, null);
    }

    public function updateOrCreate(Plugin $plugin, string $apiId = null): ResponseInterface
    {
        if (null === $plugin->getApiId() && null === $apiId) {
            throw new InvalidParameterException('The API ID is required to create a new Plugin.');
        }

        $this->config->set('serialization_context', SerializationContext::create()->setGroups([ 'update', 'create' ]));
        $this->changeFormNormalizer();

        $apiId = $plugin->getApiId() ?: $apiId;
        $uri = $this->getUriByApiId($apiId);

        return parent::requestPut($uri, $plugin, null);
    }

    public function delete(Plugin $plugin): ResponseInterface
    {
        $this->validateObjectId($plugin);
        $this->config->set('serialization_context', SerializationContext::create()->setGroups('delete'));

        $uri = $this->getUriByApiId($plugin->getApiId());

        return parent::requestDelete($this->appendToUri($plugin->getId(), $uri), null);
    }

    /**
     * @param string $apiId
     *
     * @return string
     */
    protected function getUriByApiId(string $apiId): string
    {
        return sprintf(
            '/apis/%s/%s',
            $apiId,
            ltrim($this->getUri(), '/')
        );
    }

    /**
     * Sets the FormNormalizer to use one specific for Plugin.
     *
     * @return void
     */
    protected function changeFormNormalizer(): void
    {
        $this->config->set(
            Config::FORM_NORMALIZER,
            new PluginFormNormalizer(
                $this->serializer,
                $this->config->get('serialization_context')
            )
        );
    }
}
