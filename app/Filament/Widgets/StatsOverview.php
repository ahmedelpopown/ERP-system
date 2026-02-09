<?php

namespace App\Filament\Widgets;

use App\Models\Hr\Attendance;
use App\Models\Hr\Department;
use App\Models\Hr\LeaveRequest;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval='60s';
    protected function getStats(): array
    {
        return [
            Stat::make('Total Employee', User::count())->description('Active employee in system')->descriptionIcon('heroicon-o-users')->color('success'),
            Stat::make('Department',Department::count())->description('Total Departments')->descriptionIcon('heroicon-o-building-office')->color('info'),
            Stat::make('Pending Leave Request',LeaveRequest::where('status','pending')->count())->description('Awaiting approval')->descriptionIcon('heroicon-o-calendar-days')->color('warning'),
            Stat::make('Today\'s Attendance',Attendance::whereDate('date',today())->count())->description('Checked in today')->descriptionIcon('heroicon-o-clock')->color('primary')
        ];
    }
}
