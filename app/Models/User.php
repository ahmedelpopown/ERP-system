<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Hr\Attendance;
use App\Models\Hr\Department;
use App\Models\Hr\LeaveRequest;
use App\Models\Hr\Payroll;
use App\Models\Hr\PerformanceReview;
use App\Models\Hr\Position;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable ,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'position_id',
        'employee_id',
        'phone',
        'date_of_birth',
        'hire_date',
        'employment_type',
        'status',
        'salary',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'salary' => 'decimal:2'
        ];
    }


    // Relations ships
    public function department()
    {
        return $this->belongsTo(Department::class); // Department Well Be = 1 , and user :M relations is (1 : M)
    }

    public function position()
    {
        return $this->belongsTo(Position::class); // position Well Be = 1 , and user :M relations is (1 : M)
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);  // attendances Well Be = M , and user :1 relations is (M : 1)
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class); // leaveRequests Well Be = M , and user :1 relations is (M : 1)
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class); // payrolls Well Be = M , and user :1 relations is (M : 1)
    }

    public function performanceReviews()
    {
        return $this->hasMany(PerformanceReview::class);   // performanceReviews Well Be = M , and user :1 relations is (M : 1)
    }
    public function purchase(): HasMany
    {
        return $this->hasMany(Purchase::class);   // performanceReviews Well Be = M , and user :1 relations is (M : 1)
    }

    // Events

    protected static function boot()
    {
        parent::boot();
        //EMP-0001

        static::creating(function ($employee) {

                 if (empty($employee->employee_id)) {
                $lastEmployee = static::orderBy('id','desc')->first(); 
                $nextNumber = 1;
                if ($lastEmployee && $lastEmployee->employee_id) {
                     if (preg_match('/^EMP-(\d+)$/', $lastEmployee->employee_id,$matches)) {
                        $nextNumber = ((int) $matches[1]) + 1;
                    }
                }
                  $employee->employee_id = 'EMP-' . str_pad($nextNumber, 6,'0', STR_PAD_LEFT);
            }

        });
    }
}


/* 
If the employee_id is empty, get the last employee by sorting id desc and take the first one.
Set the default number to 1.
If lastEmployee and employee_id exist, use:
preg_match('/^EMP-(\d+)$/', $lastEmployee->employee_id, $matches)
Make sure it's going like this
preg_match('/^EMP-(\d+)$/',$lastEmployee->employee_id,$matches)
This PHP function makes sure the value follows the pattern:
^ → beginning of the text, must start with EMP-

\d → numbers from 0 to 9, + means one or more

$ → end of the text

If the pattern is true, get the number from matches[1], convert it to int, and add 1.
After that, store the new value in:
$employee->employee_id = 'EMP-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
str_pad takes:

1:The number (nextNumber)

2:Length = 6

3:Padding string = 0

4:Pad from left

So the final ID will be like: EMP-000124.

*/
