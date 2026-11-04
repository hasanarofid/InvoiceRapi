<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Katalog Produk Software & Solusi Digital Siap Pakai.
     */
    public function index(Request $request): Response
    {
        $categorySlug = $request->query('kategori');
        $search = $request->query('q');

        $categories = Category::where('type', 'product')
            ->where('is_active', true)
            ->orderBy('order')
            ->get(['id', 'name', 'slug', 'description', 'image']);

        $query = Product::where('status', 'published')
            ->with('category:id,name,slug')
            ->orderBy('order')
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at');

        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('material', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(9)->withQueryString();

        $meta = [
            'title' => 'Produk Software & Starter Kit Siap Pakai | Synbarra Technologies',
            'subtitle' => 'Koleksi Starter Kit, Aplikasi Bisnis, dan Solusi Digital Siap Pakai',
            'description' => 'Jelajahi produk software, developer starter kit, dan mini apps siap pakai dari Synbarra Technologies. Dibangun dengan clean code, strict typing, dan arsitektur modern.',
            'keywords' => 'produk digital, software siap pakai, starter kit, mini pos, nextjs starter kit, crud starter kit, developer boilerplate, synbarra technologies',
            'og_image' => 'images/posts/Synbarra_Post_08.png',
            'type' => 'website',
        ];

        return Inertia::render('Products/Index', [
            'categories' => $categories,
            'products' => $products,
            'currentCategory' => $categorySlug,
            'searchQuery' => $search,
            'meta' => $meta,
        ])->withViewData(['meta' => $meta]);
    }

    /**
     * Detail Produk Software, Spesifikasi Teknis, Tier Lisensi, dan Interactive Demo.
     */
    public function show(string $slug): Response
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'published')
            ->with(['category:id,name,slug'])
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'published')
            ->take(3)
            ->get(['id', 'name', 'slug', 'sku', 'price', 'compare_at_price', 'price_range', 'short_description', 'thumbnail', 'specifications']);

        $meta = [
            'title' => "{$product->name} | Synbarra Technologies",
            'subtitle' => $product->price_range ?? 'Solusi Digital Siap Pakai',
            'description' => $product->short_description ?? 'Produk software siap pakai berkualitas tinggi dari Synbarra Technologies.',
            'keywords' => "{$product->name}, starter kit, software siap pakai, synbarra technologies",
            'og_image' => $product->thumbnail ?? 'images/posts/Synbarra_Post_08.png',
            'type' => 'website',
        ];

        return Inertia::render('Products/Show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'meta' => $meta,
        ])->withViewData(['meta' => $meta]);
    }
}
