<?php

declare(strict_types=1);

namespace CCT\Kong\Model;

class SNI
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $certificateId;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Sets the SNI name to associate with the given certificate.
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
     * Gets the SNI name given to the certificate.
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the id (a UUID) of the certificate with which to associate the SNI hostname.
     *
     * @param string $certificateId
     *
     * @return static
     */
    public function setCertificateId(string $certificateId): self
    {
        $this->certificateId = $certificateId;

        return $this;
    }

    /**
     * Gets the id (a UUID) of the certificate with which to associate the SNI hostname.
     *
     * @return null|string
     */
    public function getCertificateId(): ?string
    {
        return $this->certificateId;
    }

    /**
     * Gets the date which the object was created.
     *
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
}
