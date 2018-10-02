<?php

namespace Onixcat\Component\Viatec\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Category
 */
class Category implements CategoryInterface
{
    /**
     * @var $name string
     */
    private $name;

    /**
     * @var $url string
     */
    private $url;

    /**
     * @var ArrayCollection
     */
    private $products;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @inheritdoc
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @inheritdoc
     */
    public function addProduct(ProductInterface $product): void
    {
        if (!$this->hasProduct($product)) {
            $this->products->add($product);
        }

        if ($this !== $product->getCategory()) {
            $product->setCategory($this);
        }
    }

    /**
     * @inheritdoc
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @inheritdoc
     */
    public function hasProduct(ProductInterface $product): bool
    {
        return $this->products->contains($product);
    }

    public function clearProducts()
    {
        $this->products->clear();
    }
}
