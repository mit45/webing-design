<?php

namespace App\Domain\Entities;

class Product
{
    private ?int $id;
    private string $title;
    private string $slug;
    private ?string $description;
    private float $price;
    private string $currency;
    private ?int $categoryId;
    private string $status;

    public function __construct(?int $id, string $title, string $slug, ?string $description, float $price, string $currency = 'USD', ?int $categoryId = null, string $status = 'draft')
    {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->price = $price;
        $this->currency = $currency;
        $this->categoryId = $categoryId;
        $this->status = $status;
    }

    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getSlug(): string { return $this->slug; }
    public function getDescription(): ?string { return $this->description; }
    public function getPrice(): float { return $this->price; }
    public function getCurrency(): string { return $this->currency; }
    public function getCategoryId(): ?int { return $this->categoryId; }
    public function getStatus(): string { return $this->status; }
}
