<?php


namespace Onixcat\Component\Viatec\Entity;

interface ProductInterface
{
    /**
     * @param null|string $code
     */
    public function setCode(?string $code): void;

    /**
     * @return null|string
     */
    public function getCode(): ?string;

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void;

    /**
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * @param null|string $inStock
     */
    public function setInStock(?string $inStock): void;

    /**
     * @return null|string
     */
    public function getInStock(): ?string;

    /**
     * @param string $price
     */
    public function setRetailPrice(?string $price): void;

    /**
     * @return string|null
     */
    public function getRetailPrice(): ?string;

    /**
     * @param null|CategoryInterface $category
     * @return ProductInterface
     */
    public function setCategory(?CategoryInterface $category): void;

    /**
     * @return null|CategoryInterface
     */
    public function getCategory(): ?CategoryInterface;
}
