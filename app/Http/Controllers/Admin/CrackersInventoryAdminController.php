<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CrackersProduct;
use App\Models\CrackersCategory;
use App\Models\CrackersInventoryLog;
use Illuminate\Support\Facades\Auth;

class CrackersInventoryAdminController extends Controller
{
    public function lowStockAlerts(Request $request)
    {
        $lowStockProducts = CrackersProduct::where('status', true)
            ->whereColumn('stock', '<=', 'low_stock_threshold')
            ->orderBy('stock', 'asc')
            ->get(['id', 'name', 'category', 'stock', 'low_stock_threshold', 'unit']);

        return response()->json([
            'success' => true,
            'count' => $lowStockProducts->count(),
            'products' => $lowStockProducts
        ]);
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $stockStatus = $request->query('stock_status');

        $query = CrackersProduct::query();

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if (!empty($category)) {
            $query->where('category', $category);
        }

        if ($stockStatus === 'in_stock') {
            $query->where('stock', '>', 10);
        } elseif ($stockStatus === 'low_stock') {
            $query->where('stock', '>', 0)->where('stock', '<=', 10);
        } elseif ($stockStatus === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        }

        $products = $query->latest()->paginate(20)->withQueryString();

        // Calculate summary metrics
        $totalProducts = CrackersProduct::count();
        $totalUnits = CrackersProduct::sum('stock');
        $lowStockCount = CrackersProduct::where('stock', '>', 0)->where('stock', '<=', 10)->count();
        $outOfStockCount = CrackersProduct::where('stock', '<=', 0)->count();

        $categories = CrackersCategory::where('status', true)->pluck('name')->toArray();

        return view('admin.inventory.index', compact(
            'products',
            'categories',
            'search',
            'category',
            'stockStatus',
            'totalProducts',
            'totalUnits',
            'lowStockCount',
            'outOfStockCount'
        ));
    }

    public function adjustStock(Request $request, $id)
    {
        $product = CrackersProduct::findOrFail($id);

        $validated = $request->validate([
            'adjustment_type' => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        $oldStock = $product->stock;
        $qty = (int)$validated['quantity'];
        $type = $validated['adjustment_type'];

        if ($type === 'add') {
            $newStock = $oldStock + $qty;
            $logType = 'addition';
        } elseif ($type === 'subtract') {
            $newStock = max(0, $oldStock - $qty);
            $logType = 'subtraction';
        } else { // set
            $newStock = max(0, $qty);
            $logType = 'manual_adjustment';
        }

        $product->stock = $newStock;
        $product->save();

        CrackersInventoryLog::create([
            'product_id' => $product->id,
            'type' => $logType,
            'quantity' => $qty,
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'notes' => $validated['notes'] ?? 'Manual stock adjustment by admin',
            'created_by' => Auth::user() ? Auth::user()->name : 'Admin',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully!',
                'new_stock' => $newStock,
            ]);
        }

        return redirect()->route('admin.inventory.index')->with('success', "Stock for {$product->name} updated successfully!");
    }

    public function quickUpdateStock(Request $request, $id)
    {
        $product = CrackersProduct::findOrFail($id);

        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $oldStock = $product->stock;
        $newStock = (int)$validated['stock'];

        $product->stock = $newStock;
        $product->save();

        CrackersInventoryLog::create([
            'product_id' => $product->id,
            'type' => 'manual_adjustment',
            'quantity' => abs($newStock - $oldStock),
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'notes' => 'Quick inline stock update by admin',
            'created_by' => Auth::user() ? Auth::user()->name : 'Admin',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully!',
            'new_stock' => $newStock,
        ]);
    }

    public function logs($id)
    {
        $product = CrackersProduct::findOrFail($id);
        $logs = CrackersInventoryLog::where('product_id', $product->id)->latest()->take(20)->get();

        return response()->json([
            'success' => true,
            'product' => $product->name,
            'logs' => $logs,
        ]);
    }
}
