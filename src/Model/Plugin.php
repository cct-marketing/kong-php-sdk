<?php

declare(strict_types=1);

namespace CCT\Kong\Model;

class Plugin
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $apiId;

    /**
     * [Optional]
     * The unique identifier of the consumer that overrides the existing settings
     * for this specific consumer on incoming requests.
     *
     * @var string
     */
    protected $consumerId;

    /**
     * The name of the Plugin that's going to be added.
     *
     * @var string
     */
    protected $name;

    /**
     * The configuration properties for the Plugin.
     * The config must be prefixed with "config."
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var bool
     */
    protected $enabled = true;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Gets the id.
     *
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getApiId(): ?string
    {
        return $this->apiId;
    }

    /**
     * @param string $apiId
     *
     * @return static
     */
    public function setApiId(string $apiId): self
    {
        $this->apiId = $apiId;

        return $this;
    }

    /**
     * @return string
     */
    public function getConsumerId(): string
    {
        return $this->consumerId;
    }

    /**
     * @param string $consumerId
     *
     * @return static
     */
    public function setConsumerId(string $consumerId): self
    {
        $this->consumerId = $consumerId;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
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
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     *
     * @return static
     */
    public function setConfig(array $config): self
    {
        $this->config = $config;

        return $this;
    }

    public function replaceConfig(array $conf): self
    {
        $this->config = array_replace_recursive($this->config, $conf);

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     *
     * @return static
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
}
