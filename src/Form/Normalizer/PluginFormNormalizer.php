<?php

declare(strict_types=1);

namespace CCT\Kong\Form\Normalizer;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class PluginFormNormalizer implements FormNormalizerInterface
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
    public function normalize($formData = []): array
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

    /**
     * @param array $formData
     *
     * @return array
     */
    protected function normalizeParams(array $formData): array
    {
        $formParams = [];
        foreach ($formData as $key => $value) {
            if (empty($value)) {
                continue;
            }

            if ($key === 'config') {
                $this->setConfigParams($formParams, $value);
                continue;
            }

            $formParams[$key] = (is_array($value))
                ? join(',', $value)
                : $value
            ;
        }

        return $formParams;
    }

    protected function setConfigParams(&$formParams, array $configData): void
    {
        foreach ($configData as $key => $data) {
            if (false === strpos($key, 'config.')) {
                $key = sprintf('config.%s', $key);
            }

            if (is_bool($data)) {
                $data = $data ? 'true' : 'false';
            }

            $formParams[$key] = $data;
        }
    }
}
