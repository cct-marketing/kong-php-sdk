<?php

declare(strict_types=1);

namespace CCT\Kong\Tests\Http\Request;

use CCT\Kong\Config;
use CCT\Kong\Exception\InvalidParameterException;
use CCT\Kong\Http\Request\CertificateRequest;
use CCT\Kong\Http\Response;
use CCT\Kong\Model\Certificate;
use CCT\Kong\Model\Response\ContentCollection;
use CCT\Kong\Transformer\Response\CollectionObjectTransformer;
use CCT\Kong\Transformer\Response\ObjectTransformer;

class CertificateRequestTest extends AbstractRequest
{
    public function testList()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'certificates_list.json');

        $request = $this->createCertificateRequest($client);
        $response = $request->list();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(ContentCollection::class, $response->getData());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'certificates_create.json');
        $request = $this->createCertificateRequest($client);

        $certificate = $this->createObjectInstance();
        $certificate->setCert('foo');
        $certificate->setKey('bar');

        $response = $request->create($certificate);
        $data = $response->getData();

        $this->assertInstanceOf(Certificate::class, $data);
        $this->assertEquals('foo', $data->getCert());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateWithWrongData()
    {
        $client = $this->createClientMocked(Response::HTTP_BAD_REQUEST, 'certificates_create_400.json');
        $request = $this->createCertificateRequest($client);

        $certificate = $this->createObjectInstance();
        $certificate->setKey('bar');

        $response = $request->create($certificate);
        $data = $response->getData();

        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('cert', $data);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testRetrieve()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'certificates_retrieve.json');

        $request = $this->createCertificateRequest($client);
        $response = $request->retrieve('5df6712c-024f-4d26-9875-6ccc9cd73eb9');
        $data = $response->getData();

        $this->assertInstanceOf(Certificate::class, $data);
        $this->assertEquals('5df6712c-024f-4d26-9875-6ccc9cd73eb9', $data->getId());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testRetrieveInvalidId()
    {
        $client = $this->createClientMocked(Response::HTTP_NOT_FOUND, 'certificates_404.json');

        $request = $this->createCertificateRequest($client);
        $response = $request->retrieve('invalid-id');
        $data = $response->getData();

        $this->assertInternalType('array', $data);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'certificates_update.json');
        $request = $this->createCertificateRequest($client);

        $certificate = $this->retrieveCertificate();
        $certificate->setKey('fooBar');

        $response = $request->update($certificate);
        $data = $response->getData();

        $this->assertInstanceOf(Certificate::class, $data);
        $this->assertEquals('fooBar', $certificate->getKey());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateOnUpdateOrCreate()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'certificates_update.json');
        $request = $this->createCertificateRequest($client);

        $certificate = $this->retrieveCertificate();
        $certificate->setKey('fooBar');

        $response = $request->updateOrCreate($certificate);
        $data = $response->getData();

        $this->assertInstanceOf(Certificate::class, $data);
        $this->assertEquals('fooBar', $certificate->getKey());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateOnUpdateOrCreate()
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'certificates_update.json');
        $request = $this->createCertificateRequest($client);

        $certificate = $this->createObjectInstance();
        $certificate->setCert('foo');
        $certificate->setKey('bar');

        $response = $request->updateOrCreate($certificate);
        $data = $response->getData();

        $this->assertInstanceOf(Certificate::class, $data);
        $this->assertEquals('foo', $certificate->getCert());
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $client = $this->createClientMocked(Response::HTTP_NO_CONTENT, 'certificates_delete_204.json');
        $request = $this->createCertificateRequest($client);

        $certificate = $this->retrieveCertificate();
        $deleteResponse = $request->delete($certificate);

        $this->assertInstanceOf(Response::class, $deleteResponse);
        $this->assertEmpty($deleteResponse->getData());
        $this->assertEquals(Response::HTTP_NO_CONTENT, $deleteResponse->getStatusCode());
    }

    public function testDeleteWithIncomplete()
    {
        $this->expectException(InvalidParameterException::class);

        $client = $this->createClientMocked(Response::HTTP_METHOD_NOT_ALLOWED, 'certificates_405.json');
        $request = $this->createCertificateRequest($client);

        $certificate = $this->createObjectInstance();
        $request->delete($certificate);
    }

    protected function retrieveCertificate() : Certificate
    {
        $client = $this->createClientMocked(Response::HTTP_OK, 'certificates_retrieve.json');

        $request = $this->createCertificateRequest($client);
        $response = $request->retrieve('5df6712c-024f-4d26-9875-6ccc9cd73eb9');

        return $response->getData();
    }

    protected function createCertificateRequest($client) : CertificateRequest
    {
        $modelClass = Certificate::class;
        $request = $this->createRequest(
            $client,
            CertificateRequest::class,
            new Config([
                Config::RESPONSE_TRANSFORMERS => [
                    new ObjectTransformer($this->getSerializer(), $modelClass),
                    new CollectionObjectTransformer($this->getSerializer(), $modelClass)
                ]
            ])
        );

        return $request;
    }

    protected function createObjectInstance()
    {
        return new Certificate();
    }
}
