<?php

declare(strict_types=1);

namespace CCT\Kong\Http\Definition;

use CCT\Component\Collections\ParameterCollection;

class QueryParams extends ParameterCollection
{
    public function toString()
    {
        if ($this->isEmpty()) {
            return '';
        }

        return '?' . http_build_query($this->all());
    }

    public static function create($params = [])
    {
        return new static($params);
    }
}
