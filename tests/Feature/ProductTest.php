<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;


    public function test_get_all_products_successfully(): void
    {
        Product::factory(10)->create();
        $response = $this->getJson('/api/products');

        $response->assertJson(['success' => true, 'data' => [['id' => 1 ]], 'message' => 'Products retrived successfully'])
        ->assertStatus(200);
    }

    public function test_get_one_product_successfully(): void
    {
        Product::factory()->create(['id' => 1]);
        $response = $this->getJson('/api/products/1');

        $response->assertJson(['success' => true, 'data' => ['id' => 1 ], 'message' => 'Product retrived successfully'])
        ->assertStatus(200);
    }

    public function test_not_found_product_error_at_try_to_get_product(): void
    {
        Product::factory()->create(['id' => 1]);
        $response = $this->getJson('/api/products/2');

        $response->assertJson(['success' => false,  'error' => 'Product not found'])
        ->assertStatus(404);
    }



    public function test_store_product_successfully():void {
        $response = $this->postJson('/api/products', [
            'name' => 'Creando producto',
        ]);

        $response->assertJson(['success' => true, 'data' => ['name' => 'Creando producto' ],
         'message' => 'Product created successfully'])->assertStatus(201);
    }

    public function test_bad_request_error_at_store_product():void {
        $response = $this->postJson('/api/products', [
            'name' => 1,
        ]);

        $response->assertJson(['success' => false, 'error' => 'Bad Request'])
        ->assertStatus(400);
    }


    public function test_update_product_successfully(): void
    {
        Product::factory()->create(['id' => 1]);
        $response = $this->putJson('/api/products/1', [
            'name' => 'actualizado',
        ]);

        $response->assertJson(['success' => true, 'data' => ['name' => 'actualizado' ], 'message' => 'Product updated successfully'])->assertStatus(200);
    }

    public function test_not_found_product_error_at_try_to_update_product(): void
    {
        Product::factory()->create(['id' => 1]);
        $response = $this->putJson('/api/products/2');

        $response->assertJson(['success' => false,  'error' => 'Product not found'])
        ->assertStatus(404);
    }


    public function test_delete_product_successfully(): void
    {
        Product::factory()->create(['id' => 1]);
        $response = $this->deleteJson('/api/products/1');

        $response->assertJson(['success' => true, 'data' => ['id' => 1 ], 'message' => 'Product deleted successfully'])->assertStatus(200);
    }

    public function test_not_found_product_error_at_try_to_deleted_product(): void
    {
        Product::factory()->create(['id' => 1]);
        $response = $this->deleteJson('/api/products/2');

        $response->assertJson(['success' => false,  'error' => 'Product not found'])
        ->assertStatus(404);
    }
}
