<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_items';

    protected $fillable = [
        'purchase_id',
        'material_id',
        'quantity',
        'price',
    ];

    /**
     * Get the purchase this item belongs to.
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get the material for this purchase item.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
