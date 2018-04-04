<?php

declare(strict_types=1);

namespace CCT\Kong\Model;

class Api
{
    /**
     * Identification of API
     *
     * @var string
     */
    protected $id;

    /**
     * The API name
     *
     * @var string
     */
    protected $name;

    /**
     * The number of retries to execute upon failure to proxy. The default is 5.
     *
     * @var int
     */
    protected $retries = 5;

    /**
     * A comma-separated list of domain names that point to your API. For example: example.com.
     * At least one of hosts, uris, or methods should be specified.
     *
     * @var array
     */
    protected $hosts = [];

    /**
     * A comma-separated list of URIs prefixes that point to your API. For example: /my-path.
     * At least one of hosts, uris, or methods should be specified.
     *
     * @var array
     */
    protected $uris = [];

    /**
     * A comma-separated list of HTTP methods that point to your API.
     * For example: GET,POST. At least one of hosts, uris, or methods should be specified.
     *
     * @var array
     */
    protected $methods = [];

    /**
     * The base target URL that points to your API server. This URL will be used for proxying requests. For example: https://example.com.
     *
     * @var string
     */
    protected $upstreamUrl;

    /**
     * When matching an API via one of the uris prefixes, strip that
     * matching prefix from the upstream URI to be requested. Default: true.
     *
     * @var bool
     */
    protected $stripUri = true;

    /**
     * When matching an API via one of the hosts domain names, make sure the request Host header is
     * forwarded to the upstream service. By default, this is false, and the upstream
     * Host header will be extracted from the configured upstream_url.
     *
     * @var bool
     */
    protected $preserveHost = false;

    /**
     * To be enabled if you wish to only serve an API through HTTPS,
     * on the appropriate port (8443 by default). Default: false.
     *
     * @var bool
     */
    protected $httpsOnly = false;

    /**
     * The timeout in milliseconds for establishing a connection to your upstream service. Defaults to 60000.
     *
     * @var int
     */
    protected $upstreamConnectTimeout = 60000;

    /**
     * The timeout in milliseconds between two successive write operations
     * for transmitting a request to your upstream service Defaults to 60000.
     *
     * @var int
     */
    protected $upstreamSendTimeout = 60000;

    /**
     * The timeout in milliseconds between two successive read operations for transmitting
     * a request to your upstream service Defaults to 60000.
     *
     * @var int
     */
    protected $upstreamReadTimeout = 60000;

    /**
     * Consider the X-Forwarded-Proto header when enforcing HTTPS only traffic. Default: false.
     *
     * @var bool
     */
    protected $httpIfTerminated = false;

    /**
     * The date when the API was created.
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Gets the API`s identifier.
     *
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Sets the API name.
     *
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the API name.
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the number of attempts before the upon failure to proxy.
     *
     * @param int $attempts
     *
     * @return static
     */
    public function setRetries(int $attempts): self
    {
        $this->retries = $attempts;

        return $this;
    }

    /**
     * Gets the number of attempts before the upon failure to proxy.
     *
     * @return int
     */
    public function getRetries(): int
    {
        return $this->retries;
    }

    /**
     * Sets the domain names that point to your API.
     *
     * @param array $hosts
     *
     * @return static
     */
    public function setHosts(array $hosts = []): self
    {
        $this->hosts = $hosts;

        return $this;
    }

    /**
     * Gets the domain names that point to your API.
     *
     * @return array
     */
    public function getHosts(): array
    {
        return $this->hosts;
    }

    /**
     * Sets the list of URIs prefixes that point to your API.
     *
     * @param array $uris
     *
     * @return static
     */
    public function setUris(array $uris = []): self
    {
        $this->uris = $uris;

        return $this;
    }

    /**
     * Sets the list of URIs prefixes that point to your API.
     *
     * @return array
     */
    public function getUris(): array
    {
        return $this->hosts;
    }

    /**
     * Sets the list of HTTP methods that point to your API.
     *
     * @param array $methods
     *
     * @return static
     */
    public function setMethods(array $methods = []): self
    {
        $this->methods = $methods;

        return $this;
    }

    /**
     * Gets the list of HTTP methods that point to your API.
     *
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Sets the base target URL that points to your API server.
     *
     * @param string $upstreamUrl
     *
     * @return static
     */
    public function setUpstreamUrl(string $upstreamUrl): self
    {
        $this->upstreamUrl = $upstreamUrl;

        return $this;
    }

    /**
     * Gets the base target URL that points to your API server.
     *
     * @return null|string
     */
    public function getUpstreamUrl(): ?string
    {
        return $this->upstreamUrl;
    }

    /**
     * Sets if it should strip the URI defined when it matches with an API.
     *
     * @param bool $stripUri
     *
     * @return static
     */
    public function setStripUri(bool $stripUri): self
    {
        $this->stripUri = $stripUri;

        return $this;
    }

    /**
     * Checks if it should strip the URI defined when it matches with an API.
     *
     * @return bool
     */
    public function isStripUri(): bool
    {
        return $this->stripUri;
    }

    /**
     * Sets it the request Host header is forwarded to the upstream service
     * when there is a match to an API registered in one of the hosts.
     *
     * @param bool $preserveHost
     *
     * @return static
     */
    public function setPreserveHost(bool $preserveHost): self
    {
        $this->preserveHost = $preserveHost;

        return $this;
    }

    /**
     * Checks it the request Host header is forwarded to the upstream service
     * when there is a match to an API registered in one of the hosts.
     *
     * @return bool
     */
    public function isPreserveHost(): bool
    {
        return $this->preserveHost;
    }

    /**
     * Sets if the API should be served just through HTTPS.
     *
     * @param bool $httpsOnly
     *
     * @return static
     */
    public function setHttpsOnly(bool $httpsOnly): self
    {
        $this->httpsOnly = $httpsOnly;

        return $this;
    }

    /**
     * Gets if the API should be served just through HTTPS.
     *
     * @return bool
     */
    public function getHttpsOnly(): bool
    {
        return $this->httpsOnly;
    }

    /**
     * Sets the timeout in milliseconds for establishing a connection to your upstream.
     *
     * @param int $upstreamConnectTimeout
     *
     * @return static
     */
    public function setUpstreamConnectTimeout(int $upstreamConnectTimeout): self
    {
        $this->upstreamConnectTimeout = $upstreamConnectTimeout;

        return $this;
    }

    /**
     * Gets the timeout in milliseconds for establishing a connection to your upstream.
     *
     * @return int
     */
    public function getUpstreamConnectTimeout(): int
    {
        return $this->upstreamConnectTimeout;
    }

    /**
     * Sets the timeout in milliseconds between two successive write
     * operations for transmitting a request to your upstream.
     *
     * @param int $upstreamSendTimeout
     *
     * @return static
     */
    public function setUpstreamSendTimeout(int $upstreamSendTimeout): self
    {
        $this->upstreamSendTimeout = $upstreamSendTimeout;

        return $this;
    }

    /**
     * Gets the timeout in milliseconds between two successive write
     * operations for transmitting a request to your upstream.
     *
     * @return int
     */
    public function getUpstreamSendTimeout(): int
    {
        return $this->upstreamSendTimeout;
    }

    /**
     * Gets the timeout in milliseconds between two successive read operations for
     * transmitting a request to your upstream service.
     *
     * @param int $upstreamReadTimeout
     *
     * @return static
     */
    public function setUpstreamReadTimeout(int $upstreamReadTimeout): self
    {
        $this->upstreamReadTimeout = $upstreamReadTimeout;

        return $this;
    }

    /**
     * Gets the timeout in milliseconds between two successive read operations for
     * transmitting a request to your upstream service.
     *
     * @return int
     */
    public function getUpstreamReadTimeout(): int
    {
        return $this->upstreamReadTimeout;
    }

    /**
     * @param bool $httpIfTerminated
     *
     * @return static
     */
    public function setHttpIfTerminated(bool $httpIfTerminated): self
    {
        $this->httpIfTerminated = $httpIfTerminated;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHttpIfTerminated(): bool
    {
        return $this->httpIfTerminated;
    }

    /**
     * Gets the date which the API was created.
     *
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
}
