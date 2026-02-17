<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMaterial extends Model
{
    protected $fillable=['inventory_id','material_id','quantity'];
    public function inventory(){
        return $this->belongsTo(Inventory::class);
    }
    public function material(){
        return $this->belongsTo(Material::class);
    }
}
