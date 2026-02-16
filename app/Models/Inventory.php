<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'status',
    ];

    /**
     * Get all materials in this inventory.
     */
    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(
            Material::class,
            'inventories_materials',
            'inventory_id',
            'material_id'
        )->withPivot('quantity')->withTimestamps();
    }
}
