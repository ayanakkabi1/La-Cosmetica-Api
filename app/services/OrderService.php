<?php

namespace App\Services;

use App\DAO\OrderDAO;
use App\DTO\OrderDTO;

class OrderService
{
	private OrderDAO $orderDAO;

	public function __construct(OrderDAO $orderDAO)
	{
		$this->orderDAO = $orderDAO;
	}

	public function getAllForUser(int $userId)
	{
		return $this->orderDAO->getAllForUser($userId);
	}

	public function findByIdForUser(int $id, int $userId)
	{
		return $this->orderDAO->findByIdForUser($id, $userId);
	}

	public function createForUser(int $userId, OrderDTO $dto)
	{
		return $this->orderDAO->createForUser($userId, $dto->items);
	}

	public function deleteForUser(int $id, int $userId)
	{
		return $this->orderDAO->deleteForUser($id, $userId);
	}
}
