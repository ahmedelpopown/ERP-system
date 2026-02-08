<?php

namespace App\Models\hr;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
      protected $fillable = ['user_id', 'date', 'check_in', 'check_out', 'status', 'notes'];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);// attendances Well Be = M , and user :1 relations is (M : 1)
    }
}
