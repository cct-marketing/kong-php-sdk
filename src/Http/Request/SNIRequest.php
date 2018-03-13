<?php

namespace CCT\Kong\Http\Request;

use CCT\Kong\Config;
use CCT\Kong\Exception\InvalidParameterException;
use CCT\Kong\Exception\MethodNotImplementedException;
use CCT\Kong\Http\Definition\QueryParams;
use CCT\Kong\Http\Request;
use CCT\Kong\Http\Response;
use CCT\Kong\Http\ResponseInterface;
use CCT\Kong\Model\SNI;

class SNIRequest extends Request
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        if (!$this->config->containsKey(Config::URI_PREFIX)) {
            $this->config->set(Config::URI_PREFIX, '/snis/');
        }
    }

    /**
     * Gets the list of SNIs.
     *
     * @param QueryParams|null $queryParams
     *
     * @return ResponseInterface|Response
     */
    public function list(QueryParams $queryParams = null) : ResponseInterface
    {
        $this->setSerializationContextFor(['read']);

        return parent::requestGet($this->getUri(), $queryParams);
    }

    /**
     * Creates a SNI.
     *
     * @param SNI $sni
     *
     * @return ResponseInterface|Response
     */
    public function create(SNI $sni) : ResponseInterface
    {
        $this->setSerializationContextFor(['create']);

        return parent::requestPost($this->getUri(), $sni);
    }

    /**
     * Retrieves a SNI by its name.
     *
     * @param string $name
     *
     * @return ResponseInterface|Response
     */
    public function retrieve(string $name) : ResponseInterface
    {
        $this->setSerializationContextFor(['read']);

        return parent::requestGet($this->appendToUri($name));
    }

    /**
     * Updates a SNI.
     *
     * @param SNI $sni
     *
     * @return ResponseInterface|Response
     */
    public function update(SNI $sni) : ResponseInterface
    {
        $this->setSerializationContextFor(['update']);

        return parent::requestPatch($this->appendToUri($sni->getName()), $sni);
    }

    /**
     * Method is not implemented.
     *
     * @param SNI $sni
     *
     * @throws MethodNotImplementedException
     */
    public function updateOrCreate(SNI $sni)
    {
        throw new MethodNotImplementedException('Please check the issue at: https://github.com/Kong/kong/issues/3168');
    }

    /**
     * Deletes a SNI.
     *
     * @param SNI $sni
     *
     * @return ResponseInterface
     */
    public function delete(SNI $sni) : ResponseInterface
    {
        if (null === $sni->getName()) {
            throw new InvalidParameterException('The name must be defined to delete the SNI.');
        }

        $this->setSerializationContextFor(['delete']);

        return parent::requestDelete($this->appendToUri($sni->getName()));
    }
}
