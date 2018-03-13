<?php

namespace CCT\Kong\Transformer\Response;

use CCT\Kong\Http\Response;
use CCT\Kong\Transformer\SerializerTransformer;
use CCT\Kong\Http\ResponseInterface;

class ObjectTransformer extends SerializerTransformer
{
    /**
     * @param ResponseInterface|Response $response
     *
     * {@inheritdoc}
     */
    public function transform(ResponseInterface $response)
    {
        $data = $this->serializer->deserialize(
            $response->getContent(),
            $this->class,
            'json',
            $this->context
        );

        $response->setData($data);
    }

    /**
     * @param ResponseInterface|Response $response
     *
     * {@inheritdoc}
     */
    public function supports(ResponseInterface $response) : bool
    {
        $data = $response->getData();

        return
            false === isset($data['data'])
            && $response->isSuccessful()
            && !empty($data)
        ;
    }
}
