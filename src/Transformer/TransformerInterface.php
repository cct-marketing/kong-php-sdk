<?php

namespace CCT\Kong\Transformer;

use CCT\Kong\Http\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

interface TransformerInterface
{
    /**
     * Executes the transformation of the response.
     *
     * @param ResponseInterface|Response $response
     *
     * @return void
     */
    public function transform(ResponseInterface $response);

    /**
     * Checks if the response sent is supported to executes the transformation.
     *
     * @param ResponseInterface|Response $response
     *
     * @return bool
     */
    public function supports(ResponseInterface $response): bool;
}
