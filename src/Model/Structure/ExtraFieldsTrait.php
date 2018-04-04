<?php

declare(strict_types=1);

namespace CCT\Kong\Model\Structure;

use CCT\Component\Collections\ParameterCollection;
use CCT\Component\Collections\CollectionInterface;

trait ExtraFieldsTrait
{
    protected $extraFields;

    public function getExtraFields(): CollectionInterface
    {
        if (null === $this->extraFields) {
            $this->extraFields = new ParameterCollection();
        }

        return $this->extraFields;
    }
}
