<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_get_api_token(): void
    {
        $user = User::factory()->create([
            'email' => 'user1@example.com',
            'password' => bcrypt('password1'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user1@example.com',
            'password' => 'password1',
        ]);

        $response->assertOk()
            ->assertJsonPath('message', 'Login successful')
            ->assertJsonStructure([
                'message',
                'token',
                'user' => ['id', 'name', 'email'],
            ]);

        $this->assertNotEmpty($response->json('token'));
        $this->assertDatabaseHas('users', ['email' => $user->email]);
    }

    public function test_authenticated_user_can_access_product_endpoints(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $this->getJson('/api/products')
            ->assertUnauthorized();

        $createResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/products', [
                'name' => 'Laptop Gaming',
                'price' => 14999.99,
                'description' => 'Gaming laptop with RTX graphics',
            ]);

        $createResponse->assertCreated()
            ->assertJsonPath('name', 'Laptop Gaming')
            ->assertJsonPath('price', 14999.99)
            ->assertJsonStructure(['id', 'name', 'price', 'description', 'created_at', 'updated_at']);

        $productId = $createResponse->json('id');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/products/'.$productId)
            ->assertOk()
            ->assertJsonPath('id', $productId);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/products/'.$productId, [
                'name' => 'Laptop Gaming Updated',
                'price' => 15999.99,
            ])
            ->assertOk()
            ->assertJsonPath('message', 'Product updated successfully');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/products/'.$productId)
            ->assertNoContent();

        $this->assertDatabaseMissing('products', ['id' => $productId]);
    }
}
