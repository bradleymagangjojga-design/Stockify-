<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku', 'name', 'description', 'category_id', 'supplier_id',
        'purchase_price', 'selling_price', 'stock', 'min_stock', 'image',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price'  => 'decimal:2',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function stockOpnames()
    {
        return $this->hasMany(StockOpname::class);
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Products with stock strictly BELOW minimum threshold.
     * Note: stock == min_stock is considered SAFE, not low.
     */
    public function scopeLowStock($query)
    {
        return $query->where('stock', '>', 0)
                     ->whereColumn('stock', '<', 'min_stock');
    }

    /**
     * Products completely out of stock.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    /**
     * Products with sufficient stock (safe).
     */
    public function scopeSafeStock($query)
    {
        return $query->whereColumn('stock', '>=', 'min_stock');
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock > 0 && $this->stock < $this->min_stock;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->stock <= 0;
    }

    public function getIsSafeStockAttribute(): bool
    {
        return $this->stock >= $this->min_stock;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'out';
        }
        if ($this->stock < $this->min_stock) {
            return 'low';
        }
        return 'safe';
    }

    public function getStockStatusLabelAttribute(): string
    {
        return match ($this->stock_status) {
            'out'  => __('Out of Stock'),
            'low'  => __('Low Stock'),
            default => __('Safe Stock'),
        };
    }

    public function getStockStatusColorAttribute(): string
    {
        return match ($this->stock_status) {
            'out'  => 'red',
            'low'  => 'orange',
            default => 'green',
        };
    }
}