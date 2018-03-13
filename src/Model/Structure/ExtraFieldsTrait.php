<?php

declare(strict_types=1);

namespace CCT\Kong\Model\Structure;

use CCT\Kong\Collection\ArrayCollection;
use CCT\Kong\Collection\CollectionInterface;

trait ExtraFieldsTrait
{
    protected $extraFields;

    public function getExtraFields() : CollectionInterface
    {
        if (null === $this->extraFields) {
            $this->extraFields = new ArrayCollection();
        }

        return $this->extraFields;
    }
}
