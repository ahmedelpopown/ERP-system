<?php

namespace App\Filament\Hr\Widgets;

use App\Models\Hr\Attendance;
use App\Models\Hr\LeaveRequest;
use App\Models\Hr\Payroll;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make("Active Employees",User::where('status','active')->count())
            ->description('Currently active')
            ->descriptionIcon('heroicon-o-users')
            ->color('success'),
            Stat::make("Pending Leave",LeaveRequest::where('status','pending')->count())
            ->description('Request action')
            ->descriptionIcon('heroicon-o-calendar-days')
            ->color('warning'),
            Stat::make("Today\'s Attendance",Attendance::whereDate('date',today())->where('status','present')->count())
            ->description('Present today')
            ->descriptionIcon('heroicon-o-clock')
            ->color('primary'),
             Stat::make('This Month Payroll', 
                Payroll::where('month', date('F'))
                    ->where('year', date('Y'))
                    ->where('status', 'paid')
                    ->count()
            )
            ->description('Processed this month')
            ->descriptionIcon('heroicon-o-banknotes')
            ->color('info'),

        ];
    }
}
