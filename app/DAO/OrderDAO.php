<?php

namespace App\DAO;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderDAO
{
	public function getAllForUser(int $userId)
	{
		return Order::with(['items.product'])
			->where('user_id', $userId)
			->latest()
			->get();
	}

	public function findByIdForUser(int $id, int $userId)
	{
		return Order::with(['items.product'])
			->where('user_id', $userId)
			->where('id', $id)
			->first();
	}

	public function createForUser(int $userId, array $items)
	{
		return DB::transaction(function () use ($userId, $items) {
			$productIds = array_column($items, 'product_id');

			$products = Product::whereIn('id', $productIds)
				->get()
				->keyBy('id');

			$total = 0;

			foreach ($items as $item) {
				$product = $products->get($item['product_id']);
				$total += ((float) $product->price) * $item['quantity'];
			}

			$order = Order::create([
				'user_id' => $userId,
				'status' => 'pending',
				'total_price' => $total,
			]);

			foreach ($items as $item) {
				$product = $products->get($item['product_id']);

				OrderItem::create([
					'order_id' => $order->id,
					'product_id' => $item['product_id'],
					'quantity' => $item['quantity'],
					'price' => $product->price,
				]);
			}

			return Order::with(['items.product'])->findOrFail($order->id);
		});
	}

	public function deleteForUser(int $id, int $userId)
	{
		return Order::where('user_id', $userId)
			->where('id', $id)
			->delete();
	}
}
