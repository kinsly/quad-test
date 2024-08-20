<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderAPITest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Run the seeder to create default user roles
        $this->artisan('db:seed');
    }

    /**
     * Test creating a new order by client.
     */
    public function test_order_create()
    {
        $user = User::factory()->create();
        $user->assignRole('client');

        $product = Product::factory()->create();
        $productData = [
            'product_id' => $product->id
        ];
        $response = $this->actingAs($user)->postJson('api/orders',$productData);

        // Assert the response status and structure
        $response->assertStatus(201)
                 ->assertJson([
                     'user_id' => $user->id,
                     'product_id' => $product->id,
                 ]);

        // Assert the product was created in the database
        $this->assertDatabaseHas('orders', [
            'product_id' => $product->id,
            'user_id' => $user->id
        ]);

    }

    /**
     * Test - Creating a order on unavailable product
     */
    public function test_order_placed_on_unavailable_product()
    {
        $user = User::factory()->create();
        $user->assignRole('client');

        $productData = [
            'product_id' => 999
        ];
        $response = $this->actingAs($user)->postJson('api/orders',$productData);

        // Assert the response status and structure
        $response->assertStatus(404)
                 ->assertJson([
                    'error' => 'Product not found',
                 ]);
    }

    /**
     * Test - Removing placed order
     */
    public function test_remove_placed_order()
    {
        $user = User::factory()->create();
        $user->assignRole('client');

        //Placing a order
        $product = Product::factory()->create();
        $productData = [
            'product_id' => $product->id
        ];
        $response = $this->actingAs($user)->postJson('api/orders',$productData);

        //Get order id from respone json
        $orderId = $response->json('id');

        //Check order is placed correctly
        $response->assertStatus(201)
            ->assertJson([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);

        //Remove placed order
        $response = $this->deleteJson('api/orders/'.$orderId, $productData);
        // Assert the response status
        $response->assertStatus(204);

        $this->assertDatabaseMissing('orders', ['id' => $orderId]);
    }

    /**
     * Test - Removing unplaced order
     */
    public function test_remove_unplaced_order()
    {
        $user = User::factory()->create();
        $user->assignRole('client');

        $productData = [
            'product_id' => 999
        ];

        //Remove unplaced order
        $response = $this->actingAs($user)->deleteJson('api/orders/999', $productData);
        // Assert the response status
        $response->assertStatus(404);
    }
    
}
