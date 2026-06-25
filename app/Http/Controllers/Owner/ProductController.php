<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('subCategory.category')->latest()->paginate(15);
        return view('owner.products.index', compact('products'));
    }

    public function create()
    {
        $subCategories = SubCategory::with('category')->get();
        return view('owner.products.create', compact('subCategories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data['slug'] = Str::slug($data['name']) . '-' . uniqid();
        $data['is_active'] = $request->boolean('is_active', true);
        
        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('products', 'public');
        }
        
        Product::create($data);
        return redirect()->route('owner.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $subCategories = SubCategory::with('category')->get();
        return view('owner.products.edit', compact('product', 'subCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request);
        $data['is_active'] = $request->boolean('is_active');
        
        if ($request->hasFile('image')) {
            if ($product->image_url) {
                Storage::disk('public')->delete($product->image_url);
            }
            $data['image_url'] = $request->file('image')->store('products', 'public');
        }
        
        $product->update($data);
        return redirect()->route('owner.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->hasOrders()) {
            $product->update(['is_active' => false]);
            return back()->with('error', 'Produk memiliki riwayat pesanan, sehingga dinonaktifkan (tidak dihapus permanen).');
        }
        if ($product->image_url) {
            Storage::disk('public')->delete($product->image_url);
        }
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'weight_label'    => 'required|string|max:50',
            'stock'           => 'required|integer|min:0',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    }
}
