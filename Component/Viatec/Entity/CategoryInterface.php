<?php

namespace Onixcat\Component\Viatec\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface CategoryInterface
 */
interface CategoryInterface
{
    /**
     * @param null|string $name
     */
    public function setName(?string $name): void;

    /**
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * @param null|string $url
     */
    public function setUrl(?string $url): void;

    /**
     * @return null|string
     */
    public function getUrl(): ?string;


    /**
     * @param ProductInterface $category
     */
    public function addProduct(ProductInterface $product): void;

    /**
     * @return Collection
     */
    public function getProducts(): Collection;

    /**
     * @param CategoryInterface $category
     * @return bool
     */
    public function hasProduct(ProductInterface $product): bool;
}
