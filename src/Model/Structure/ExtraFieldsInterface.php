<?php

namespace CCT\Kong\Model\Structure;

use CCT\Kong\Collection\CollectionInterface;

interface ExtraFieldsInterface
{
    /**
     * Gets the collection of extra fields.
     *
     * @return CollectionInterface
     */
    public function getExtraFields() : CollectionInterface;
}