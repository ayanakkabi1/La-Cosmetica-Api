<?php

namespace App\DTO;

class ProductDTO
{
    public string $name;
    public string $description;
    public float $price;
    public int $category_id;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->price = $data['price'];
        $this->category_id = $data['category_id'];
    }
}