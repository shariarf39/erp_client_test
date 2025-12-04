<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('parent')->orderBy('category_name')->paginate(20);
        return view('inventory.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = Category::where('is_active', 1)->get();
        return view('inventory.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_code' => 'required|unique:categories',
            'category_name' => 'required|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable',
            'is_active' => 'boolean',
        ]);

        Category::create($validated);

        return redirect()->route('inventory.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'items']);
        return view('inventory.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::where('is_active', 1)
            ->where('id', '!=', $category->id)
            ->get();
        return view('inventory.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'category_code' => 'required|unique:categories,category_code,' . $category->id,
            'category_name' => 'required|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable',
            'is_active' => 'boolean',
        ]);

        try {
            // Prevent circular parent reference
            if ($validated['parent_id'] && $validated['parent_id'] == $category->id) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Category cannot be its own parent.');
            }

            $category->update($validated);

            return redirect()->route('inventory.categories.show', $category)
                ->with('success', 'Category updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating category: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            // Check if category has items
            if ($category->items()->count() > 0) {
                return redirect()->route('inventory.categories.index')
                    ->with('error', 'Cannot delete category with associated items. Please reassign items first.');
            }

            // Check if category has child categories
            if ($category->children()->count() > 0) {
                return redirect()->route('inventory.categories.index')
                    ->with('error', 'Cannot delete category with sub-categories. Please delete sub-categories first.');
            }

            $category->delete();

            return redirect()->route('inventory.categories.index')
                ->with('success', 'Category deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('inventory.categories.index')
                ->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }
}
