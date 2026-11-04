<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductShowcaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_produk_catalog_page_returns_successful_response(): void
    {
        $response = $this->get('/produk');

        $response->assertStatus(200);
    }

    public function test_template_showcase_redirects_to_produk(): void
    {
        $response = $this->get('/template-showcase');

        $response->assertStatus(301);
        $response->assertRedirect('/produk');
    }

    public function test_produk_detail_page_returns_successful_response(): void
    {
        $tenant = Tenant::create([
            'name' => 'Synbarra Technologies',
            'slug' => 'synbarra-store',
            'phone_whatsapp' => '6288804958476',
            'email' => 'synbarr.techno@gmail.com',
            'address' => 'Indonesia',
            'is_active' => true,
        ]);

        $category = Category::create([
            'tenant_id' => $tenant->id,
            'name' => 'Developer Tools',
            'slug' => 'developer-tools',
            'type' => 'product',
            'is_active' => true,
        ]);

        $product = Product::create([
            'tenant_id' => $tenant->id,
            'category_id' => $category->id,
            'name' => 'Synbarra CRUD Starter Kit',
            'slug' => 'synbarra-crud-starter-kit',
            'sku' => 'SYN-CRUD-001',
            'price' => 349000,
            'status' => 'published',
            'is_featured' => true,
        ]);

        $response = $this->get("/produk/{$product->slug}");

        $response->assertStatus(200);
    }
}

