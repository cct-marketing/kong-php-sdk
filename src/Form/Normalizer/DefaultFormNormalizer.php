<?php

declare(strict_types=1);

namespace CCT\Kong\Form\Normalizer;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;

class DefaultFormNormalizer implements FormNormalizerInterface
{
    /**
     * @var Serializer|SerializerInterface
     */
    protected $serializer;

    /**
     * @var SerializationContext|null
     */
    protected $serializationContext;

    public function __construct(SerializerInterface $serializer, SerializationContext $context = null)
    {
        $this->serializer = $serializer;
        $this->serializationContext = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($formData = []) : array
    {
        if (empty($formData)) {
            return [];
        }

        if (is_object($formData)) {
            $formData = $this->serializer->toArray(
                $formData,
                $this->serializationContext
            );
        }

        $formParams = $this->normalizeParams($formData);

        return $formParams;
    }

    protected function normalizeParams(array $formData) : array
    {
        $formParams = [];
        foreach ($formData as $key => $value) {
            if (empty($value)) {
                continue;
            }

            $formParams[$key] = (is_array($value))
                ? join(',', $value)
                : $value
            ;
        }

        return $formParams;
    }
}