<?php

namespace App\DTO;

class OrderDTO
{
	/**
	 * @var array<int, array{product_id:int, quantity:int}>
	 */
	public array $items;

	public function __construct(array $data)
	{
		$this->items = $data['items'];
	}
}
