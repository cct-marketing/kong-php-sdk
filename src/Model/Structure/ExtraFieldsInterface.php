<?php

namespace CCT\Kong\Model\Structure;

use CCT\Component\Collections\CollectionInterface;

interface ExtraFieldsInterface
{
    /**
     * Gets the collection of extra fields.
     *
     * @return CollectionInterface
     */
    public function getExtraFields(): CollectionInterface;
}