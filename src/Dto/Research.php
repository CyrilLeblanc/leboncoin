<?php

namespace App\Dto;

use App\Entity\SubCategory;

class Research
{
    private SubCategory $category;
    private ?string $query;
    private ?int $page;
    private ?int $maxCost;
    private ?int $minCost;
    private ?int $postalCode;
    private ?string $request;

    public function getCategory(): ?SubCategory
    {
        return $this->category;
    }

    public function setCategory(?SubCategory $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(?string $query): self
    {
        $this->query = $query;
        return $this;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(?int $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function getMaxCost(): ?int
    {
        return $this->maxCost;
    }

    public function setMaxCost(?int $maxCost): self
    {
        $this->maxCost = $maxCost;
        return $this;
    }

    public function getMinCost(): ?int
    {
        return $this->minCost;
    }

    public function setMinCost(?int $minCost): self
    {
        $this->minCost = $minCost;
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getRequest(): ?string
    {
        return $this->request;
    }

    public function setRequest(?string $request): self
    {
        $this->request = $request;
        return $this;
    }
}
