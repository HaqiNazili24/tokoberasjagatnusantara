<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function home(Request $request)
    {
        $q = Product::where('is_active', true)->with('subCategory.category');
        if ($request->filled('sub_category')) {
            $q->whereHas('subCategory', fn($x) => $x->where('slug', $request->sub_category));
        } elseif ($request->filled('category')) {
            $q->whereHas('subCategory.category', fn($x) => $x->where('slug', $request->category));
        }
        $q->orderBy(match ($request->sort) {
            'price_asc' => 'price', 'price_desc' => 'price', default => 'created_at',
        }, $request->sort === 'price_desc' ? 'desc' : ($request->sort === 'price_asc' ? 'asc' : 'desc'));
        $products = $q->paginate(12)->withQueryString();
        $categories = Category::with('subCategories')->get();
        return view('customer.products.home', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)
            ->with(['subCategory.category', 'reviews.user'])->firstOrFail();
        return view('customer.products.show', compact('product'));
    }

    public function search(Request $request)
    {
        $keyword = trim((string) $request->q);
        $products = Product::where('is_active', true)
            ->where(fn($x) => $x->where('name', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%"))
            ->paginate(12)->withQueryString();
        return view('customer.products.search', compact('products', 'keyword'));
    }
}