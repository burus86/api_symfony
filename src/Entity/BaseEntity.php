<?php

namespace App\Entity;

use \Datetime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BaseEntity
 * @package App\Entity
 */
abstract class BaseEntity
{
    const DATETIME_FORMAT_FULL = "d/m/Y - H:i:s";

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="message.assert.notBlank.name")
     * @Assert\Length(max = 100, maxMessage = "message.assert.length.name.maxMessage")
     */
    protected $name;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;


    /**
     * BaseEntity constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): BaseEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt.
     *
     * @param DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTime $createdAt): BaseEntity
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt formatted.
     *
     * @return string|null
     */
    public function getCreatedAtFormatted(): ?string
    {
        return !is_null($this->getCreatedAt()) ? $this->getCreatedAt()->format(self::DATETIME_FORMAT_FULL) : null;
    }

    abstract public function __toString(): string;
}
