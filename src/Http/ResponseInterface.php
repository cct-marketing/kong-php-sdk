<?php

declare(strict_types=1);

namespace CCT\Kong\Http;

interface ResponseInterface
{
    /**
     * Gets the Response data.
     *
     * The data of Kong Response is not the same of the content,
     * as the content is the original body response from the GuzzleResponse.
     *
     * @return mixed
     */
    public function getData();

    /**
     * Sets the response data.
     *
     * The data of Kong Response is not the same of the content,
     * as the content is the original body response from the GuzzleResponse.
     *
     * @param mixed $data
     *
     * @return void
     */
    public function setData($data);
}
