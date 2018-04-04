<?php

declare(strict_types=1);

namespace CCT\Kong\Exception;

use Psr\Http\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Response;

class ServiceUnavailableException extends \RuntimeException
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * ServiceUnavailableException constructor.
     *
     * @param RequestInterface $request
     * @param string           $message
     * @param int              $statusCode
     */
    public function __construct(RequestInterface $request, string $message, int $statusCode = Response::HTTP_SERVICE_UNAVAILABLE)
    {
        $this->request = $request;

        parent::__construct($message);
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}