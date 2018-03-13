<?php

declare(strict_types=1);

namespace CCT\Kong\Http\Definition;

use CCT\Kong\Collection\ArrayCollection;

class QueryParams extends ArrayCollection
{
    public function toString()
    {
        if ($this->isEmpty()) {
            return '';
        }

        return '?' . http_build_query($this->toArray());
    }

    public static function create($params = [])
    {
        return new static($params);
    }
}
