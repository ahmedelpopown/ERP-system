<?php

namespace App\Models\hr;

use Illuminate\Database\Eloquent\Model;
use App\Models\hr\LeaveRequest;
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
