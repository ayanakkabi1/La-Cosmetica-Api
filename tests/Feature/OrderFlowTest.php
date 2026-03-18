<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    private function authHeadersFor(User $user): array
    {
        $token = JWTAuth::fromUser($user);

        return [
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ];
    }

    public function test_user_can_create_and_view_own_order(): void
    {
        $user = User::factory()->create(['role' => 'client']);

        $category = Category::create([
            'name' => 'Makeup',
            'slug' => 'makeup',
        ]);

        $p1 = Product::create([
            'name' => 'Lipstick',
            'slug' => 'lipstick',
            'description' => 'Matte lipstick',
            'price' => 20,
            'category_id' => $category->id,
        ]);

        $p2 = Product::create([
            'name' => 'Mascara',
            'slug' => 'mascara',
            'description' => 'Volume mascara',
            'price' => 15,
            'category_id' => $category->id,
        ]);

        $create = $this->withHeaders($this->authHeadersFor($user))
            ->postJson('/api/orders', [
                'items' => [
                    ['product_id' => $p1->id, 'quantity' => 2],
                    ['product_id' => $p2->id, 'quantity' => 1],
                ],
            ]);

        $create->assertStatus(201)
            ->assertJsonPath('status', 'pending')
            ->assertJsonPath('total_price', '55.00');

        $orderId = $create->json('id');

        $this->withHeaders($this->authHeadersFor($user))
            ->getJson('/api/orders')
            ->assertStatus(200)
            ->assertJsonCount(1);

        $this->withHeaders($this->authHeadersFor($user))
            ->getJson('/api/orders/'.$orderId)
            ->assertStatus(200)
            ->assertJsonPath('id', $orderId)
            ->assertJsonCount(2, 'items');
    }

    public function test_user_cannot_view_other_users_order(): void
    {
        $owner = User::factory()->create(['role' => 'client']);
        $other = User::factory()->create(['role' => 'client']);

        $category = Category::create([
            'name' => 'Skincare',
            'slug' => 'skincare',
        ]);

        $product = Product::create([
            'name' => 'Cleanser',
            'slug' => 'cleanser',
            'description' => 'Gentle cleanser',
            'price' => 12,
            'category_id' => $category->id,
        ]);

        $order = Order::create([
            'user_id' => $owner->id,
            'status' => 'pending',
            'total_price' => 12,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 12,
        ]);

        $this->withHeaders($this->authHeadersFor($other))
            ->getJson('/api/orders/'.$order->id)
            ->assertStatus(404);
    }
}
