<?php

namespace CCT\Kong\Http;

use CCT\Kong\Exception\InvalidParameterException;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class Response extends BaseResponse implements ResponseInterface
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(string $content = '', int $status = 200, array $headers = array())
    {
        parent::__construct($content, $status, $headers);

        $this->data = $this->jsonToArray($content);
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    protected function jsonToArray(string $content = null) : ?array
    {
        if (empty($content)) {
            return null;
        }

        if (false === strpos($this->headers->get('Content-Type'), 'json')) {
            throw new InvalidParameterException('The content returned must be in a JSON format.');
        }

        $data = @json_decode($content, true);
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('It was not possible to convert the current content to JSON.');
        }

        return $data;
    }
}
