<?php

declare(strict_types=1);

namespace CCT\Kong\Model;

class Consumer
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $customId;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Gets the consumer's identification;
     *
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Sets the unique username of the consumer.
     *
     * @param string $username
     *
     * @return static
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Gets the unique username of the consumer.
     *
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Sets the field for storing an existing unique ID for the consumer.
     *
     * @param string $customId
     *
     * @return static
     */
    public function setCustomId(string $customId): self
    {
        $this->customId = $customId;

        return $this;
    }

    /**
     * Gets the field for storing an existing unique ID for the consumer.
     *
     * @return null|string
     */
    public function getCustomId(): ?string
    {
        return $this->customId;
    }

    /**
     * Gets the date that the consumer was created.
     *
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
}