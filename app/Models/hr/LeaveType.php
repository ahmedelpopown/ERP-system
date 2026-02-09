<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use App\Models\Hr\LeaveRequest;
class LeaveType extends Model
{
        protected $fillable = ['name', 'days_per_year', 'is_paid'];

    protected $casts = [
        'is_paid' => 'boolean',
    ];

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
