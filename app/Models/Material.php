<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit',
        'min_stock',
    ];

    /**
     * Get all inventories this material belongs to.
     */
    public function inventories(): BelongsToMany
    {
        return $this->belongsToMany(
            Inventory::class,
            'inventories_materials',
            'material_id',
            'inventory_id'
        )->withPivot('quantity')->withTimestamps();
    }

    /**
     * Get all purchase items for this material.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
