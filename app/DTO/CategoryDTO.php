<?php

namespace App\DTO;

class CategoryDTO
{
    public string $name;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
    }
}
