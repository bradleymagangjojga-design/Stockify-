<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories with counts and totals.
     */
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();

        $totalCategories = $categories->count();
        $totalProducts   = Product::count();
        $averageProduct  = $totalCategories > 0
            ? round($totalProducts / $totalCategories, 1)
            : 0;

        return view('categories.index', compact(
            'categories',
            'totalCategories',
            'totalProducts',
            'averageProduct'
        ));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100', Rule::unique('categories', 'name')],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Category::create($data);

        return redirect()
            ->route('categories.index')
            ->with('success', __('Category created successfully.'));
    }

    public function show(Category $category)
    {
        $products = Product::with('supplier')
            ->where('category_id', $category->id)
            ->orderBy('name')
            ->get();

        $stats = [
            'total_products' => $products->count(),
            'total_stock'    => $products->sum('stock'),
            'total_value'    => $products->sum(fn ($p) => $p->stock * $p->purchase_price),
            'low_stock'      => $products->filter(fn ($p) => $p->stock <= $p->min_stock)->count(),
        ];

        return view('categories.show', compact('category', 'products', 'stats'));
    }

    public function print(Category $category)
    {
        $products = Product::with('supplier')
            ->where('category_id', $category->id)
            ->orderBy('name')
            ->get();

        $stats = [
            'total_products' => $products->count(),
            'total_stock'    => $products->sum('stock'),
            'total_value'    => $products->sum(fn ($p) => $p->stock * $p->purchase_price),
        ];

        return view('categories.print', compact('category', 'products', 'stats'));
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100',
                              Rule::unique('categories', 'name')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $category->update($data);

        return redirect()
            ->route('categories.index')
            ->with('success', __('Category updated successfully.'));
    }

    /**
     * Delete the category.
     *
     * ── Option A: Reassign & Delete ─────────────────────────────
     * If the category has products, the request must include a
     * `reassign_to` category ID. Products are moved there, then
     * the (now empty) category is deleted — all inside a transaction.
     * If the category is already empty, it deletes directly.
     */
    public function destroy(Request $request, Category $category)
    {
        $productCount = $category->products()->count();

        // ── Case 1: Category has products → reassign required ───
        if ($productCount > 0) {
            $data = $request->validate([
                'reassign_to' => [
                    'required',
                    'integer',
                    Rule::exists('categories', 'id'),
                    Rule::notIn([$category->id]),
                ],
            ], [
                'reassign_to.required' => __('Choose a destination category to move the :count product(s) into.', [
                    'count' => $productCount,
                ]),
                'reassign_to.not_in'   => __('Products cannot be moved to the same category being deleted.'),
                'reassign_to.exists'   => __('The selected destination category does not exist.'),
            ]);

            $destination = Category::findOrFail($data['reassign_to']);

            DB::transaction(function () use ($category, $destination) {
                // Move all products from the doomed category → destination
                $category->products()->update(['category_id' => $destination->id]);

                // Now safe to delete — no products reference it
                $category->delete();
            });

            return redirect()
                ->route('categories.index')
                ->with('success', __('":name" deleted. :count product(s) moved to ":dest".', [
                    'name'  => $category->name,
                    'count' => $productCount,
                    'dest'  => $destination->name,
                ]));
        }

        // ── Case 2: Category is empty → delete directly ─────────
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', __('Category deleted successfully.'));
    }
}