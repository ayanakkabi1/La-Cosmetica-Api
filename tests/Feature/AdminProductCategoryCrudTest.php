<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminProductCategoryCrudTest extends TestCase
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

    public function test_non_admin_cannot_create_category(): void
    {
        $user = User::factory()->create(['role' => 'client']);

        $response = $this->withHeaders($this->authHeadersFor($user))
            ->postJson('/api/categories', [
                'name' => 'Skincare',
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_create_update_and_delete_category_and_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $categoryResponse = $this->withHeaders($this->authHeadersFor($admin))
            ->postJson('/api/categories', [
                'name' => 'Skincare',
            ]);

        $categoryResponse->assertStatus(201)
            ->assertJsonPath('name', 'Skincare');

        $categoryId = $categoryResponse->json('id');

        $productCreateResponse = $this->withHeaders($this->authHeadersFor($admin))
            ->postJson('/api/products', [
                'name' => 'Hydrating Serum',
                'description' => 'Daily hydration serum',
                'price' => 29.99,
                'category_id' => $categoryId,
            ]);

        $productCreateResponse->assertStatus(201)
            ->assertJsonPath('category_id', $categoryId);

        $productId = $productCreateResponse->json('id');

        $productUpdateResponse = $this->withHeaders($this->authHeadersFor($admin))
            ->putJson('/api/products/'.$productId, [
                'name' => 'Hydrating Serum Pro',
                'description' => 'Daily hydration serum',
                'price' => 39.99,
                'category_id' => $categoryId,
            ]);

        $productUpdateResponse->assertStatus(200)
            ->assertJsonPath('name', 'Hydrating Serum Pro');

        $this->withHeaders($this->authHeadersFor($admin))
            ->deleteJson('/api/products/'.$productId)
            ->assertStatus(200);

        $this->withHeaders($this->authHeadersFor($admin))
            ->deleteJson('/api/categories/'.$categoryId)
            ->assertStatus(200);
    }
}
