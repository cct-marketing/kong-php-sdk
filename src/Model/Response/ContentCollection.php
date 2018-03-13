<?php

declare(strict_types=1);

namespace CCT\Kong\Model\Response;

class ContentCollection
{
    /**
     * @var int
     */
    protected $total;

    /**
     * @var object[]
     */
    protected $data;

    /**
     * @var string|null
     */
    protected $next;

    /**
     * @var string|null
     */
    protected $offset;

    public function __construct(array $data)
    {
        $this->normalize($data);
    }

    public function getTotal() : int
    {
        return $this->total;
    }

    public function getNext() : ?string
    {
        return $this->next;
    }

    public function getOffset() : ?string
    {
        return $this->offset;
    }

    public function getData()
    {
        return $this->data;
    }

    protected function normalize(array $data)
    {
        $this->data = $data['data'];
        $this->total = $data['total'];
        $this->next = $data['next'] ?? null;
        $this->offset = $data['offset'] ?? null;
    }
}
