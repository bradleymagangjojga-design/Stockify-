<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $products = Product::with(['category', 'supplier'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('products.index', compact('products'));
    }

    /**
     * Show product detail.
     */
    public function show(Product $product)
    {
        $transactions = Transaction::with('user')
            ->where('product_id', $product->id)
            ->latest('transaction_date')
            ->latest('id')
            ->limit(10)
            ->get();

        $stats = [
            'total_in'      => (int) Transaction::where('product_id', $product->id)
                                    ->where('type', 'in')->sum('quantity'),
            'total_out'     => (int) Transaction::where('product_id', $product->id)
                                    ->where('type', 'out')->sum('quantity'),
            'stock_value'   => $product->stock * $product->purchase_price,
            'profit_margin' => $product->selling_price - $product->purchase_price,
        ];

        return view('products.show', compact('product', 'transactions', 'stats'));
    }

    /**
     * Printable product info.
     */
    public function print(Product $product)
    {
        return view('products.print', compact('product'));
    }

    /**
     * Show create form.
     */
    public function create(Request $request)
    {
        return view('products.create', [
            'categories'  => Category::orderBy('name')->get(),
            'suppliers'   => Supplier::orderBy('name')->get(),
            'redirect_to' => $request->input('redirect_to'),
        ]);
    }

    /**
     * Store new product.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'sku'            => 'required|string|max:50|unique:products,sku',
            'name'           => 'required|string|max:150',
            'description'    => 'nullable|string',
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'nullable|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'min_stock'      => 'required|integer|min:0',
        ]);

        $product = Product::create($data);

        // ✅ LOG
        $this->logActivity(
            'created',
            "Created product: {$product->name} (SKU: {$product->sku}, Stock: {$product->stock})",
            $product
        );

        return redirect($this->safeRedirect($request, 'products.index'))
            ->with('success', __('Produk berhasil ditambahkan.'));
    }

    /**
     * Show edit form.
     */
    public function edit(Request $request, Product $product)
    {
        return view('products.edit', [
            'product'     => $product,
            'categories'  => Category::orderBy('name')->get(),
            'suppliers'   => Supplier::orderBy('name')->get(),
            'redirect_to' => $request->input('redirect_to'),
        ]);
    }

    /**
     * Update product.
     */
    public function update(Request $request, Product $product)
    {
        $original = $product->getOriginal();

        $data = $request->validate([
            'sku'            => 'required|string|max:50|unique:products,sku,' . $product->id,
            'name'           => 'required|string|max:150',
            'description'    => 'nullable|string',
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'nullable|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'min_stock'      => 'required|integer|min:0',
        ]);

        $product->update($data);

        // ✅ Build a description of what changed
        $changes = [];
        foreach ($data as $key => $value) {
            if (($original[$key] ?? null) != $value) {
                $old = $original[$key] ?? 'null';
                $changes[] = "{$key}: {$old} → {$value}";
            }
        }
        $changeText = $changes ? ' [' . implode('; ', $changes) . ']' : '';

        // ✅ LOG
        $this->logActivity(
            'updated',
            "Updated product: {$product->name}{$changeText}",
            $product
        );

        return redirect($this->safeRedirect($request, 'products.index'))
            ->with('success', __('Produk berhasil diperbarui.'));
    }

    /**
     * Delete product.
     */
    public function destroy(Request $request, Product $product)
    {
        $name = $product->name;
        $sku  = $product->sku;

        // ✅ LOG BEFORE deleting
        $this->logActivity(
            'deleted',
            "Deleted product: {$name} (SKU: {$sku})",
            $product
        );

        $product->delete();

        return redirect($this->safeRedirect($request, 'products.index'))
            ->with('success', __('Produk berhasil dihapus.'));
    }

    // ============================================================
    // HELPERS
    // ============================================================

    private function safeRedirect(Request $request, string $fallbackRoute): string
    {
        $appUrl = url('/');

        $redirect = $request->input('redirect_to');
        if ($redirect && str_starts_with($redirect, $appUrl)) {
            return $redirect;
        }

        $referer = $request->headers->get('referer');
        if ($referer && str_starts_with($referer, $appUrl)) {
            return $referer;
        }

        return route($fallbackRoute);
    }
}
