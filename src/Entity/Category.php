<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(name="category", uniqueConstraints={@ORM\UniqueConstraint(columns={"name"})})
 * @UniqueEntity(fields={"name"}, errorPath="title", message="message.unique.category")
 */
class Category extends BaseEntity
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var Product[]|ArrayCollection
     *
     * One Category have Many Product.
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="category")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $products;


    /**
     * Category constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->products = new ArrayCollection();
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): Category
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get products.
     *
     * @return Product[]|ArrayCollection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set products.
     *
     * @param Product[]|ArrayCollection $products
     * @return $this
     */
    public function setProducts($products): Category
    {
        $this->products = $products;
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
            'description' => $this->getDescription(),
            'createdAt' => $this->getCreatedAtFormatted(),
        );
    }
}
