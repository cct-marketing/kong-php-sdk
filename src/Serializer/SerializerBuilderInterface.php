<?php

declare(strict_types=1);

namespace CCT\Kong\Serializer;

use JMS\Serializer\SerializerInterface;

interface SerializerBuilderInterface
{
    /**
     * Builds the JMS Serializer object.
     *
     * @return SerializerInterface
     */
    public function build(): SerializerInterface;
}
