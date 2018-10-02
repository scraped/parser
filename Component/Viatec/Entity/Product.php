<?php

namespace Onixcat\Component\Viatec\Entity;

/**
 * Class Product
 */
class Product implements ProductInterface
{
    /**
     * @var $code string
     */
    private $code;

    /**
     * @var $name string
     */
    private $name;

    /**
     * @var $inStock string
     */
    private $inStock;

    /**
     * @var $price string
     */
    private $retailPrice;

    /**
     * @var $category CategoryInterface
     */
    private $category;

    /**
     * @inheritdoc
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @inheritdoc
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @inheritdoc
     */
    public function setInStock(?string $inStock): void
    {
        $this->inStock = $inStock;
    }

    /**
     * @inheritdoc
     */
    public function getInStock(): ?string
    {
        return $this->inStock;
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
    public function setRetailPrice(?string $price): void
    {
        $this->retailPrice = $price;
    }

    /**
     * @inheritdoc
     */
    public function getRetailPrice(): ?string
    {
        return $this->retailPrice;
    }

    /**
     * @inheritdoc
     */
    public function setCategory(?CategoryInterface $category): void
    {
        $this->category = $category;
    }

    /**
     * @inheritdoc
     */
    public function getCategory(): ?CategoryInterface
    {
        return $this->category;
    }
}
