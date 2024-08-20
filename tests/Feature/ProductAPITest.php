<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductAPITest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Run the seeder to create default user roles
        $this->artisan('db:seed');
    }


     /**
     * Test creating a new product.
     *
     * @return void
     */
    public function test_create_product()
    {
        // Create a user to authenticate the request
        $user = User::factory()->create();
        $user->assignRole('admin');
        
        // Define the product data
        $productData = [
            'name' => 'Test Product',
            'price' => 99.99,
            'description' => "Test Product details"
        ];
        
        // Make the POST request to create a new product
        $response = $this->actingAs($user)->postJson('/api/products', $productData);
        
        // Assert the response status and structure
        $response->assertStatus(201)
                 ->assertJson([
                     'name' => 'Test Product',
                     'price' => 99.99,
                     'description' => "Test Product details"
                 ]);

        // Assert the product was created in the database
        $this->assertDatabaseHas('products', $productData);
    }

    /**
     * Test - Create product by client user - unauthorized
     */
    public function test_create_product_by_client()
    {
        // Create a user to authenticate the request
        $user = User::factory()->create();
        $user->assignRole('client');
        
        // Define the product data
        $productData = [
            'name' => 'Test Product',
            'price' => 99.99,
            'description' => "Test Product details"
        ];
        
        // Make the POST request to create a new product
        $response = $this->actingAs($user)->postJson('/api/products', $productData);
        
        // Assert the response status and structure
        $response->assertStatus(403);
    }


    /**
     *  Test - listing all available products - Admins
     */
    public function test_showing_all_products_by_admins()
    {
        // Create a user and assign a role
        $user = User::factory()->create();
        $user->assignRole('admin');

        // Create some products
        $products = Product::factory()->count(3)->create();
        $this->actingAs($user);
        // Loop through each product and make a POST request to create it
        foreach ($products as $product) {
            $productData = [
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
            ];
            $this->postJson('/api/products', $productData);
        }

        // Make a GET request to the index method
        $response = $this->getJson('/api/products');

        // Assert the response status and structure
        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'description', 'price', 'created_at', 'updated_at']
        ]);

        // Assert the products are returned in the response
        $response->assertJsonFragment([
            'id' => $products[0]->id,
            'name' => $products[0]->name,
        ]);
        $response->assertJsonFragment([
            'id' => $products[1]->id,
            'name' => $products[1]->name,
        ]);
        $response->assertJsonFragment([
            'id' => $products[2]->id,
            'name' => $products[2]->name,
        ]);
    }


    /**
     *  Test - listing all available products - Clients
     */
    public function test_showing_all_products_by_clients()
    {
        // Create a user and assign a role
        $user = User::factory()->create();
        $user->assignRole('client');

        // Create some products
        $products = Product::factory()->count(3)->create();
        $this->actingAs($user);
        // Loop through each product and make a POST request to create it
        foreach ($products as $product) {
            $productData = [
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
            ];
            $this->postJson('/api/products', $productData);
        }

        // Make a GET request to the index method
        $response = $this->getJson('/api/products');

        // Assert the response status and structure
        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'description', 'price', 'created_at', 'updated_at']
        ]);

        // Assert the products are returned in the response
        $response->assertJsonFragment([
            'id' => $products[0]->id,
            'name' => $products[0]->name,
        ]);
        $response->assertJsonFragment([
            'id' => $products[1]->id,
            'name' => $products[1]->name,
        ]);
        $response->assertJsonFragment([
            'id' => $products[2]->id,
            'name' => $products[2]->name,
        ]);
    }

    /**
     * Test showing available product item. 
     */
    public function test_show_product_success()
    {
        // Create a user and a product
        $user = User::factory()->create();
        $user->assignRole('admin');
        $product = Product::factory()->create();

        // Make the GET request to retrieve the product
        $response = $this->actingAs($user)->getJson("/api/products/{$product->id}");

        // Assert the response status and structure
        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $product->id,
                     'name' => $product->name,
                     'price' => $product->price,
                     'description' => $product->description,
                 ]);
    }

    /**
     * Test unable to show incorrect product
     */
    public function test_show_product_not_found()
    {
        // Create a user
        $user = User::factory()->create();
        $user->assignRole('admin');

        // Make the GET request to retrieve a non-existent product
        $response = $this->actingAs($user)->getJson('/api/products/999');

        // Assert the response status and structure
        $response->assertStatus(404)
                 ->assertJson([
                     'error' => 'Product not found',
                 ]);
    }

    /**
     * Test -  Updating existing product
     */
    public function test_update_product_success()
    {
        // Create a user and a product
        $user = User::factory()->create();
        $user->assignRole('admin');
        $product = Product::factory()->create();

        // Define the updated product data
        $updatedData = [
            'id' => $product->id,
            'name' => 'Updated Product',
            'price' => 199.99,
            'description' => "Updated Product details"
        ];

        // Make the PUT request to update the product
        $response = $this->actingAs($user)->putJson("/api/products/{$product->id}", $updatedData);

        // Assert the response status and structure
        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $product->id,
                     'name' => 'Updated Product',
                     'price' => 199.99,
                     'description' => "Updated Product details"
                 ]);

        // Assert the product was updated in the database
        $this->assertDatabaseHas('products', $updatedData);
    }

    /**
     * Test - Try to update unavailable product
     */
    public function test_update_product_not_found()
    {
        // Create a user
        $user = User::factory()->create();
        $user->assignRole('admin');

        // Define the updated product data
        $updatedData = [
            'id' => '999',
            'name' => 'Updated Product',
            'price' => 199.99,
            'description' => "Updated Product details"
        ];

        // Make the PUT request to update a non-existent product
        $response = $this->actingAs($user)->putJson('/api/products/999', $updatedData);

        // Assert the response status and structure
        $response->assertStatus(404)
                 ->assertJson([
                     'error' => 'Product not found',
                 ]);
    }

    /**
     * Test - Deleting available product
     */
    public function test_destroy_product_success()
    {
        // Create a user and a product
        $user = User::factory()->create();
        $user->assignRole('admin');
        $product = Product::factory()->create();

        // Make the DELETE request to delete the product
        $response = $this->actingAs($user)->deleteJson("/api/products/{$product->id}");

        // Assert the response status
        $response->assertStatus(204);

        // Assert the product was deleted from the database
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /**
     * Test - Trying to delete unavailabe product
     */
    public function test_destroy_product_not_found()
    {
        // Create a user
        $user = User::factory()->create();
        $user->assignRole('admin');

        // Make the DELETE request to delete a non-existent product
        $response = $this->actingAs($user)->deleteJson('/api/products/999');

        // Assert the response status and structure
        $response->assertStatus(404)
                ->assertJson([
                    'error' => 'Product not found',
                ]);
    }
}
