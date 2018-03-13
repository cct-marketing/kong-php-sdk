<?php

declare(strict_types=1);

namespace CCT\Kong\Http\Request;

use CCT\Kong\Config;
use CCT\Kong\Http\Request;
use CCT\Kong\Http\Response;
use CCT\Kong\Http\ResponseInterface;
use CCT\Kong\Model\Certificate;

class CertificateRequest extends Request
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        if (!$this->config->containsKey(Config::URI_PREFIX)) {
            $this->config->set(Config::URI_PREFIX, '/certificates/');
        }
    }

    public function list() : ResponseInterface
    {
        $this->setSerializationContextFor(['read']);

        return parent::requestGet($this->getUri(), null);
    }

    public function create(Certificate $certificate) : ResponseInterface
    {
        $this->setSerializationContextFor(['create']);

        return parent::requestPost($this->getUri(), $certificate);
    }

    public function retrieve(string $sniOrCertificateId) : ResponseInterface
    {
        $this->setSerializationContextFor(['read']);

        return parent::requestGet($this->appendToUri($sniOrCertificateId));
    }

    public function update(Certificate $certificate) : Response
    {
        $this->validateObjectId($certificate);
        $this->setSerializationContextFor(['update']);

        return parent::requestPatch($this->appendToUri($certificate->getId()), $certificate);
    }

    public function updateOrCreate(Certificate $certificate) : Response
    {
        $this->setSerializationContextFor(['update', 'create']);

        return parent::requestPut($this->getUri(), $certificate);
    }

    public function delete(Certificate $certificate)
    {
        $this->validateObjectId($certificate);
        $this->setSerializationContextFor(['delete']);

        return parent::requestDelete($this->appendToUri($certificate->getId()));
    }
}
