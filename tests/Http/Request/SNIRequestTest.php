<?php

declare(strict_types=1);

namespace CCT\Kong\Tests\Http\Request;

use CCT\Kong\Config;
use CCT\Kong\Exception\MethodNotImplementedException;
use CCT\Kong\Http\Request\SNIRequest;
use CCT\Kong\Http\Response;
use CCT\Kong\Http\ResponseInterface;
use CCT\Kong\Model\SNI;
use CCT\Kong\Model\Response\ContentCollection;
use CCT\Kong\Transformer\Response\CollectionObjectTransformer;
use CCT\Kong\Transformer\Response\ObjectTransformer;

class SNIRequestTest extends AbstractRequest
{

    public function testListSNIs()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'snis_list.json');
        $request = $this->createSNIRequest($client);

        $response = $request->list();
        $data = $response->getData();

        $this->assertInstanceOf(ContentCollection::class, $data);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateSNI()
    {
        $client = $this->createClientMocked(Response::HTTP_CREATED, 'snis_create_201.json');
        $request = $this->createSNIRequest($client);

        $object = $this->createSNIInstance();
        $object->setCertificateId('92a7af83-2071-4515-9550-9f3a010d241f');
        $object->setName('example.com');

        $response = $request->create($object);
        $data = $response->getData();

        $this->assertInstanceOf(SNI::class, $data);
        $this->assertEquals('example.com', $data->getName());
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testCreateSNIWithInvalidCertificateId()
    {
        $client = $this->createClientMocked(Response::HTTP_NOT_FOUND, 'snis_create_404.json');
        $request = $this->createSNIRequest($client);

        $object = $this->createSNIInstance();
        $object->setName('example.com');
        $object->setCertificateId('1bd05a78-01d4-4f19-8cf0-2ce65638b033');

        $response = $request->create($object);
        $data = $response->getData();

        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('ssl_certificate_id', $data);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testRetrieveSNI()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'snis_retrieve.json');
        $request = $this->createSNIRequest($client);

        $response = $request->retrieve('foo');
        $data = $response->getData();

        $this->assertInstanceOf(SNI::class, $data);
        $this->assertEquals('foo', $data->getName());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testRetrieveNonExistentSNI()
    {
        $client = $this->createClientMocked(Response::HTTP_NOT_FOUND, 'snis_retrieve_404.json');
        $request = $this->createSNIRequest($client);

        $response = $request->retrieve('foo');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testUpdateSNI()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'snis_update.json');
        $request = $this->createSNIRequest($client);

        $object = $this->findOneSNI();
        $object->setCertificateId('92a7af83-2071-xxxx-9550-9f3a010d241f');

        $response = $request->update($object);
        $data = $response->getData();

        $this->assertInstanceOf(SNI::class, $data);
        $this->assertEquals('92a7af83-2071-xxxx-9550-9f3a010d241f', $data->getCertificateId());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateOrCreateForCreation()
    {
        $this->expectException(MethodNotImplementedException::class);

        // There is an issue when I try to create the SNI by a PUT request
        // Please check the issue at: https://github.com/Kong/kong/issues/3168

        $client = $this->createClientMocked(Response::HTTP_OK, 'snis_create_201.json');
        $request = $this->createSNIRequest($client);

        $sni = $this->createSNIInstance();
        $sni->setName('foo');
        $sni->setCertificateId('92a7af83-2071-4515-9550-9f3a010d241f');

        $response = $request->updateOrCreate($sni);
        $object = $response->getData();

        $this->assertInstanceOf(SNI::class, $object);
        $this->assertEquals('92a7af83-2071-4515-9550-9f3a010d241f', $object->getCertificateId());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @return SNI
     */
    protected function findOneSNI()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'snis_retrieve.json');
        $request = $this->createSNIRequest($client);
        $response = $request->retrieve('foo');

        return $response->getData();
    }

    protected function createSNIRequest($client) : SNIRequest
    {
        $modelClass = SNI::class;
        $request = $this->createRequest(
            $client,
            SNIRequest::class,
            new Config([
                Config::RESPONSE_TRANSFORMERS => [
                    new ObjectTransformer($this->getSerializer(), $modelClass),
                    new CollectionObjectTransformer($this->getSerializer(), $modelClass)
                ]
            ])
        );

        return $request;
    }

    protected function createSNIInstance() : SNI
    {
        return new SNI();
    }
}
