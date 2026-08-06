<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CrackersCategory;
use Illuminate\Support\Str;

class CrackersCategoryAdminController extends Controller
{
    public function index()
    {
        $categories = CrackersCategory::latest()->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:crackers_categories,name',
            'icon' => 'nullable|string|max:255',
        ]);

        CrackersCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'icon' => !empty($validated['icon']) ? trim($validated['icon']) : null,
            'status' => true,
        ]);

        return redirect()->back()->with('success', 'Category added successfully!');
    }

    public function update(Request $request, $id)
    {
        $category = CrackersCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:crackers_categories,name,' . $id,
            'icon' => 'nullable|string|max:255',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'icon' => !empty($validated['icon']) ? trim($validated['icon']) : null,
        ]);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    public function toggleStatus($id)
    {
        $category = CrackersCategory::findOrFail($id);
        $category->status = !$category->status;
        $category->save();

        return redirect()->back()->with('success', 'Category status updated!');
    }

    public function destroy($id)
    {
        $category = CrackersCategory::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully!');
    }
}
