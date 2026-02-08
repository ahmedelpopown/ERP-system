<?php

namespace App\Models\hr;

use App\Models\User;
use App\Models\hr\LeaveType;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
      protected $fillable = [
        'user_id', 'leave_type_id', 'start_date', 'end_date', 
        'days', 'reason', 'status', 'approved_by', 'approved_at', 'rejection_reason'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
