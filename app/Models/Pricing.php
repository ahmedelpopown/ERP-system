<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pricing extends Model
{
    use HasFactory;

    protected $table = 'pricing';

    protected $fillable = [
        'name',
        'budget',
        'payments',
        'duration',
    ];

    /**
     * Get all projects with this pricing.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
