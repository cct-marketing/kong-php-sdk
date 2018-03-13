<?php

declare(strict_types=1);

namespace CCT\Kong\Tests\Http\Request;

use CCT\Kong\Config;
use CCT\Kong\Exception\InvalidParameterException;
use CCT\Kong\Exception\InvalidResourceException;
use CCT\Kong\Http\Definition\QueryParams;
use CCT\Kong\Http\Request;
use CCT\Kong\Http\Request\ConsumerRequest;
use CCT\Kong\Http\Response;
use CCT\Kong\Model\Consumer;
use CCT\Kong\Model\Response\ContentCollection;
use CCT\Kong\Transformer\Response\CollectionObjectTransformer;
use CCT\Kong\Transformer\Response\ObjectTransformer;

class ConsumerRequestTest extends AbstractRequest
{
    public function testListRequestWithValidResponse()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'consumers_list.json');

        $request = $this->createConsumersRequest($client);
        $response = $request->list();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(ContentCollection::class, $response->getData());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testListRequestWithInvalidDataResponse()
    {
        $client = $this->createClientMocked(Response::HTTP_NOT_FOUND, 'consumers_list_404.json');

        $request = $this->createConsumersRequest($client);
        $queryParams = new QueryParams([
            'id' => 'invalid_id'
        ]);

        $response = $request->list($queryParams);

        $this->assertTrue($response->isNotFound());
    }

    public function testCreateRequestWithValidResponse()
    {
        $client = $this->createClientMocked(Response::HTTP_CREATED, 'consumers_create.json');

        $consumer = new Consumer();
        $consumer->setCustomId('custom_consumer_id');

        $request = $this->createConsumersRequest($client);
        $response = $request->create($consumer);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(Consumer::class, $response->getData());
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testCreateRequestWithInvalidDataResponse()
    {
        $client = $this->createClientMocked(Response::HTTP_BAD_REQUEST, 'consumers_create_400.json');

        $consumer = new Consumer();

        $request = $this->createConsumersRequest($client);
        $response = $request->create($consumer);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotEmpty($response->getData());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testRetrieveRequestWithValidResponse()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'consumers_retrieve.json');

        $request = $this->createConsumersRequest($client);
        $response = $request->retrieve('fb4b3060-fc4d-4e9f-9a81-fc54da8b8e44');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(Consumer::class, $response->getData());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }


    public function testRetrieveRequestWithInvalidUsernameOrId()
    {
        $client = $this->createClientMocked(Response::HTTP_NOT_FOUND, 'consumers_retrieve_404.json');

        $request = $this->createConsumersRequest($client);
        $response = $request->retrieve('invalid-id');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testUpdateWithValidData()
    {
        $consumer = $this->findOneConsumer();
        $consumer->setUsername('new username');

        $client = $this->createClientMocked(Response::HTTP_OK, 'consumers_retrieve.json');
        $request = $this->createConsumersRequest($client);
        $response = $request->update($consumer);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(Consumer::class, $response->getData());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateOrCreateWithValidData()
    {
        $consumer = $this->findOneConsumer();
        $consumer->setUsername('new username');

        $client = $this->createClientMocked(Response::HTTP_OK, 'consumers_retrieve.json');
        $request = $this->createConsumersRequest($client);
        $response = $request->updateOrCreate($consumer);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(Consumer::class, $response->getData());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteWithValidData()
    {
        $consumer = $this->findOneConsumer();

        $client = $this->createClientMocked(Response::HTTP_NO_CONTENT, 'consumers_delete_204.json');
        $request = $this->createConsumersRequest($client);
        $response = $request->delete($consumer);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEmpty($response->getData());
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteWithNoData()
    {
        $this->expectException(InvalidParameterException::class);
        $consumer = new Consumer();

        $client = $this->createClientMocked(Response::HTTP_METHOD_NOT_ALLOWED, 'consumers_delete_404.json');
        $request = $this->createConsumersRequest($client);
        $request->delete($consumer);
    }

    public function testDeleteWithInvalidIdData()
    {
        $consumer = new Consumer();
        $this->setProtectedProperty($consumer, 'id', 'random-id');

        $client = $this->createClientMocked(Response::HTTP_NOT_FOUND, 'consumers_delete_404.json');
        $request = $this->createConsumersRequest($client);
        $response = $request->delete($consumer);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @return Consumer
     */
    protected function findOneConsumer()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'consumers_retrieve.json');
        $request = $this->createConsumersRequest($client);
        $response = $request->retrieve('fb4b3060-fc4d-4e9f-9a81-fc54da8b8e44');

        return $response->getData();
    }

    protected function createConsumersRequest($client) : ConsumerRequest
    {
        $modelClass = Consumer::class;
        $request = $this->createRequest(
            $client,
            ConsumerRequest::class,
            new Config([
                Config::RESPONSE_TRANSFORMERS => [
                    new ObjectTransformer($this->getSerializer(), $modelClass),
                    new CollectionObjectTransformer($this->getSerializer(), $modelClass)
                ]
            ])
        );

        return $request;
    }
}
