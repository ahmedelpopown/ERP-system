<?php

namespace App\Models\hr;

use App\Models\User;
use App\Models\hr\Position;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
   protected $fillable = ['name', 'description','color', 'manager_id'];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function employees()
    {
        return $this->hasMany(User::class);// attendances Well Be = M , and user :1 relations is (M : 1)
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}
