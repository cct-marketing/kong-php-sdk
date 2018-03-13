<?php

declare(strict_types=1);

namespace CCT\Kong\Tests\Http\Request;

use CCT\Kong\Config;
use CCT\Kong\Http\Definition\QueryParams;
use CCT\Kong\Http\Request\PluginRequest;
use CCT\Kong\Http\Response;
use CCT\Kong\Model\Plugin;
use CCT\Kong\Model\Response\ContentCollection;
use CCT\Kong\Transformer\Response\CollectionObjectTransformer;
use CCT\Kong\Transformer\Response\ObjectTransformer;

class PluginRequestTest extends AbstractRequest
{
    public function testListRequestWithValidResponse()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'plugins_list.json');

        $request = $this->createPluginsRequest($client);
        $response = $request->list();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(ContentCollection::class, $response->getData());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testListEnabled()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'plugins_enabled.json');

        $request = $this->createPluginsRequest($client);
        $response = $request->listEnabled();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInternalType('array', $response->getData());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testRetrieveSchema()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'plugins_schema.json');

        $request = $this->createPluginsRequest($client);
        $response = $request->retrieveSchema('rate-limiting');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInternalType('array', $response->getData());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testListByAPIRequestWithValidResponse()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'plugins_list.json');

        $request = $this->createPluginsRequest($client);
        $response = $request->listByApi('some-api-id');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(ContentCollection::class, $response->getData());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testListRequestWithInvalidDataResponse()
    {
        $client = $this->createClientMocked(Response::HTTP_NOT_FOUND, 'plugins_404.json');

        $request = $this->createPluginsRequest($client);
        $queryParams = new QueryParams([
            'id' => 'invalid_id'
        ]);

        $response = $request->list($queryParams);

        $this->assertTrue($response->isNotFound());
    }

    public function testCreateRequestWithValidResponse()
    {
        $client = $this->createClientMocked(Response::HTTP_CREATED, 'plugins_create.json');

        $plugin = $this->createPluginInstance();
        $plugin->setApiId('some-api-id');

        $request = $this->createPluginsRequest($client);
        $response = $request->create($plugin);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(Plugin::class, $response->getData());
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testCreateRequestWithInvalidDataResponse()
    {
        $client = $this->createClientMocked(Response::HTTP_BAD_REQUEST, 'plugins_404.json');

        $plugin = $this->createPluginInstance();
        $plugin->setApiId('some-api-id');
        $plugin->setConfig([
            'config.some_wrong_parameter' => 'value'
        ]);

        $request = $this->createPluginsRequest($client);
        $response = $request->create($plugin);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotEmpty($response->getData());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testRetrieveRequest()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'plugins_retrieve.json');

        $request = $this->createPluginsRequest($client);
        $response = $request->retrieve('54ce5893-0985-46e7-b415-7e3fdde67dfb', 'dffa0564-2d40-4137-9ab1-002d5d01546d');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(Plugin::class, $response->getData());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testRetrieveRequestWithInvalidIds()
    {
        $client = $this->createClientMocked(Response::HTTP_NOT_FOUND, 'plugins_404.json');

        $request = $this->createPluginsRequest($client);
        $response = $request->retrieve('invalid-id', 'invalid-plugin');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testUpdateData()
    {
        $plugin = $this->findOnePlugin();
        $plugin->setEnabled(true);

        $client = $this->createClientMocked(Response::HTTP_OK, 'plugins_retrieve.json');
        $request = $this->createPluginsRequest($client);
        $response = $request->update($plugin);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(Plugin::class, $response->getData());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateOrCreateWithValidData()
    {
        $plugin = $this->findOnePlugin();
        $plugin->setEnabled(true);

        $client = $this->createClientMocked(Response::HTTP_OK, 'plugins_retrieve.json');
        $request = $this->createPluginsRequest($client);
        $response = $request->updateOrCreate($plugin);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(Plugin::class, $response->getData());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteWithValidData()
    {
        $plugin = $this->findOnePlugin();

        $client = $this->createClientMocked(Response::HTTP_NO_CONTENT, 'plugins_delete_204.json');
        $request = $this->createPluginsRequest($client);
        $response = $request->delete($plugin);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEmpty($response->getData());
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @return Plugin
     */
    protected function findOnePlugin()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'plugins_retrieve.json');
        $request = $this->createPluginsRequest($client);
        $response = $request->retrieve(
            '54ce5893-0985-46e7-b415-7e3fdde67dfb',
            'dffa0564-2d40-4137-9ab1-002d5d01546d'
        );

        return $response->getData();
    }

    protected function createPluginsRequest($client) : PluginRequest
    {
        $modelClass = Plugin::class;
        $request = $this->createRequest(
            $client,
            PluginRequest::class,
            new Config([
                Config::RESPONSE_TRANSFORMERS => [
                    new ObjectTransformer($this->getSerializer(), $modelClass),
                    new CollectionObjectTransformer($this->getSerializer(), $modelClass)
                ]
            ])
        );

        return $request;
    }

    protected function createPluginInstance()
    {
        return new Plugin();
    }
}
