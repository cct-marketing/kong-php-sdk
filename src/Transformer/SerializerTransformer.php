<?php

declare(strict_types=1);

namespace CCT\Kong\Transformer;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;

abstract class SerializerTransformer implements TransformerInterface
{
    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var SerializationContext|null
     */
    protected $context;

    public function __construct(Serializer $serializer, string $class, SerializationContext $context = null)
    {
        $this->serializer = $serializer;
        $this->class = $class;
        $this->context = $context;
    }
}
