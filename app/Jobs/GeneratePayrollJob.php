<?php

namespace App\Jobs;

use App\Models\Hr\Payroll;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GeneratePayrollJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public $timeout = 300;
    public $trait = 3;
    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $month,
        public int $year,
        public ?int $userId = null
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       $query=User::where('status','active')
       ->whereNotNull('salary');
       if($this->userId){
        $query->where("user_id",$this->userId);
       }
       $employees=$query->get();
       foreach ($employees as $employee) {
         try {
            $existPayroll=Payroll::where('user_id',$employee->id)
         ->where("month",$this->month)
         ->where("year",$this->year);
         if($existPayroll){
            Log::info("this user has taken payroll ");
            continue;
         }
         $bonus=500;
         $allowances=200;
         $deductions=150;
         $basicSalary=$employee->salary;
         $netSalary=$bonus+$allowances+$basicSalary-$deductions;
     Payroll::create([
                    'user_id' => $employee->id,
                    'month' => $this->month,
                    'year'=> $this->year,
                    'basic_salary'=> $basicSalary,
                    'allowances' => $allowances,
                    'bonus' => $bonus,
                    'deductions' => $deductions,
                    'net_salary' => $netSalary,
                    'status' => 'draft',
                ]);
             Log::info("Payroll generated for {$employee->name} - {$this->month} - {$this->year}");
            } catch (\Exception $e) {
                Log::error("Error generating payroll for {$employee->name} - {$this->month} - {$this->year}: {$e->getMessage()}");
            }

       }
    }
}
