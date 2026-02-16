<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'details',
        'files',
        'address',

        'duration',
        'start_at',
        'end_at',
        'city_id',
        'client_id',
        'pricing_id',
    ];

    protected $casts = [
        'start_at' => 'date',
        'end_at' => 'date',
        'files' => 'array',
    ];

    /**
     * Get the client that owns this project.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the pricing for this project.
     */
    public function pricing(): BelongsTo
    {
        return $this->belongsTo(Pricing::class);
    }
      public function getUrlAttribute(){
        return asset('storage/' . $this->files);
    }
}
