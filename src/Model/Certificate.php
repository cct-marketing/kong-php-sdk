<?php

declare(strict_types=1);

namespace CCT\Kong\Model;

class Certificate
{
    /**
     * PEM-encoded public certificate of the SSL key pair.
     *
     * @var string
     */
    protected $id;

    /**
     * PEM-encoded private key of the SSL key pair.
     *
     * @var string
     */
    protected $cert;

    /**
     * One or more hostnames to associate with this certificate as an SNI. This is a sugar parameter that will,
     * under the hood, create an SNI object and associate it with this certificate for your convenience.
     *
     * SNI: Server name indication
     *
     * @var array
     */
    protected $snis = [];

    /**
     * PEM-encoded private key of the SSL key pair.
     *
     * @var string
     */
    protected $key;

    /**
     * Gets the date of creation of Certificate.
     *
     * @var \DateTime
     */
    protected $createdAt;

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCert(): ?string
    {
        return $this->cert;
    }

    /**
     * @param string $cert
     *
     * @return static
     */
    public function setCert(string $cert): self
    {
        $this->cert = $cert;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return static
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return array
     */
    public function getSNIs(): array
    {
        return $this->snis;
    }

    /**
     * @param array $snis
     *
     * @return static
     */
    public function setSNIs(array $snis): self
    {
        $this->snis = $snis;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
}
