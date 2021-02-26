<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\Table(name="product", uniqueConstraints={@ORM\UniqueConstraint(columns={"name", "category_id"})})
 * @UniqueEntity(fields={"name", "category"}, message="message.unique.product.combined")
 * @UniqueEntity(fields={"serialNumber"}, message="message.unique.product.serialNumber")
 */
class Product extends BaseEntity
{
    const CURRENCY_EUR = "EUR";
    const CURRENCY_USD = "USD";
    const CURRENCIES = [self::CURRENCY_EUR, self::CURRENCY_USD];

    /**
     * @var Category|null
     *
     * Many Product have One Category.
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     */
    private $category;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2, precision=10, options={"default" : 0})
     * @Assert\NotBlank(message="message.assert.notBlank.price")
     * @Assert\Type(type="numeric", message = "message.assert.type.price")
     * @Assert\Range(min = 0, minMessage = "message.assert.range.price.minMessage")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3, options={"default" : self::CURRENCY_EUR})
     * @Assert\NotBlank(message="message.assert.notBlank.currency")
     * @Assert\Choice(choices=Product::CURRENCIES, message="message.assert.choice.currency")
     */
    private $currency = self::CURRENCY_EUR;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $featured = false;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=100, unique=true, nullable=true)
     * @Assert\Length(max = 100, maxMessage = "message.assert.length.serialNumber.maxMessage")
     */
    private $serialNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(max = 100, maxMessage = "message.assert.length.brand.maxMessage")
     */
    private $brand;


    /**
     * Get category.
     *
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Set category.
     *
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): Product
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get price.
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Set price.
     *
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): Product
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get currency.
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Set currency.
     *
     * @param string $currency
     * @return $this
     */
    public function setCurrency(string $currency): Product
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Is featured.
     *
     * @return bool
     */
    public function isFeatured(): bool
    {
        return $this->featured;
    }

    /**
     * Set featured.
     *
     * @param bool $featured
     * @return $this
     */
    public function setFeatured(bool $featured)
    {
        $this->featured = $featured;
        return $this;
    }

    /**
     * Get serialNumber.
     *
     * @return string|null
     */
    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    /**
     * Set serialNumber.
     *
     * @param string|null $serialNumber
     * @return $this
     */
    public function setSerialNumber(?string $serialNumber): Product
    {
        $this->serialNumber = $serialNumber;
        return $this;
    }

    /**
     * Get brand.
     *
     * @return string|null
     */
    public function getBrand(): ?string
    {
        return $this->brand;
    }

    /**
     * Set brand.
     *
     * @param string|null $brand
     * @return $this
     */
    public function setBrand(?string $brand): Product
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'price' => floatval($this->getPrice()),
            'currency' => $this->getCurrency(),
            'featured' => $this->isFeatured(),
            'categoryName' => !is_null($this->getCategory()) ? $this->getCategory()->__toString() : null,
            'serialNumber' => $this->getSerialNumber(),
            'brand' => $this->getBrand(),
            'createdAt' => $this->getCreatedAtFormatted(),
        );
    }
}
