<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CrackersProduct;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\CrackersCategory;

class CrackersProductAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $query = CrackersProduct::query();

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        $products = $query->latest()->paginate(20)->withQueryString();
        $categories = CrackersCategory::where('status', true)->pluck('name')->toArray();

        return view('admin.products.index', compact('products', 'categories', 'search', 'category'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'wholesale_min_qty' => 'nullable|integer|min:1',
            'wholesale_max_qty' => 'nullable|integer|min:1',
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:3072',
            'image_url' => 'nullable|url',
            'images.*' => 'nullable|image|max:3072',
            'image_urls' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        $product = new CrackersProduct();
        $product->name = $validated['name'];
        $product->slug = Str::slug($validated['name']) . '-' . rand(100, 999);
        $product->category = $validated['category'];
        $product->price = $validated['price'];
        $product->discount_price = $validated['discount_price'] ?? null;
        $product->wholesale_price = $validated['wholesale_price'] ?? null;
        $product->wholesale_min_qty = !empty($validated['wholesale_min_qty']) ? intval($validated['wholesale_min_qty']) : null;
        $product->wholesale_max_qty = !empty($validated['wholesale_max_qty']) ? intval($validated['wholesale_max_qty']) : null;
        $product->stock = $validated['stock'];
        $product->low_stock_threshold = isset($validated['low_stock_threshold']) ? intval($validated['low_stock_threshold']) : 10;
        $product->unit = $validated['unit'];
        $product->description = $validated['description'] ?? null;
        $product->is_featured = $request->has('is_featured');
        $product->status = $request->has('status');

        $uploadedImages = [];
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $uploadedImages[] = Storage::url($path);
        } elseif (!empty($validated['image_url'])) {
            $uploadedImages[] = $validated['image_url'];
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && count($uploadedImages) < 4) {
                    $path = $file->store('products', 'public');
                    $uploadedImages[] = Storage::url($path);
                }
            }
        } elseif ($request->has('image_urls') && is_array($request->image_urls)) {
            foreach ($request->image_urls as $url) {
                if (!empty($url) && count($uploadedImages) < 4) {
                    $uploadedImages[] = $url;
                }
            }
        }

        if (!empty($uploadedImages)) {
            $product->image = $uploadedImages[0];
            $product->images = array_values(array_unique(array_slice($uploadedImages, 0, 4)));
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Crackers Product added successfully!');
    }

    public function update(Request $request, $id)
    {
        $product = CrackersProduct::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'wholesale_min_qty' => 'nullable|integer|min:1',
            'wholesale_max_qty' => 'nullable|integer|min:1',
            'stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:3072',
            'image_url' => 'nullable|url',
            'images.*' => 'nullable|image|max:3072',
            'image_urls' => 'nullable|array',
        ]);

        $product->name = $validated['name'];
        $product->category = $validated['category'];
        $product->price = $validated['price'];
        $product->discount_price = $validated['discount_price'] ?? null;
        $product->wholesale_price = $validated['wholesale_price'] ?? null;
        $product->wholesale_min_qty = !empty($validated['wholesale_min_qty']) ? intval($validated['wholesale_min_qty']) : null;
        $product->wholesale_max_qty = !empty($validated['wholesale_max_qty']) ? intval($validated['wholesale_max_qty']) : null;
        $product->stock = $validated['stock'];
        $product->low_stock_threshold = isset($validated['low_stock_threshold']) ? intval($validated['low_stock_threshold']) : 10;
        $product->unit = $validated['unit'];
        $product->description = $validated['description'] ?? null;
        $product->is_featured = $request->has('is_featured');
        $product->status = $request->has('status');

        $uploadedImages = $product->images ?: [];
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $mainImg = Storage::url($path);
            array_unshift($uploadedImages, $mainImg);
        } elseif (!empty($validated['image_url'])) {
            array_unshift($uploadedImages, $validated['image_url']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file && count($uploadedImages) < 4) {
                    $path = $file->store('products', 'public');
                    $uploadedImages[] = Storage::url($path);
                }
            }
        } elseif ($request->has('image_urls') && is_array($request->image_urls)) {
            foreach ($request->image_urls as $url) {
                if (!empty($url) && count($uploadedImages) < 4) {
                    $uploadedImages[] = $url;
                }
            }
        }

        if (!empty($uploadedImages)) {
            $product->image = $uploadedImages[0];
            $product->images = array_values(array_unique(array_slice($uploadedImages, 0, 4)));
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Crackers Product updated successfully!');
    }

    public function toggleStatus($id)
    {
        $product = CrackersProduct::findOrFail($id);
        $product->status = !$product->status;
        $product->save();

        $statusText = $product->status ? 'Enabled (Active)' : 'Disabled (Inactive)';
        $message = "Product '{$product->name}' is now {$statusText}.";

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $product->status
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroy($id)
    {
        $product = CrackersProduct::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Crackers Product deleted.');
    }
}
