<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Latetime;
use App\Models\Attendance;


class AdminController extends Controller
{
    public function index()
    {
        $totalEmp = User::where('is_admin', 0)->count();
        $AllAttendance = Attendance::where('punch_in', date("Y-m-d"))->count();
        $fullDayLeave = Leave::where('start_date', date("Y-m-d"))
    ->whereIn('option_for_leave', ['PL', 'SL', 'FD', 'BL', 'Comp-Off', 'RH'])
    ->where('status', 'approved')
    ->count();

    // dd($fullDayLeave);
    // \DB::enableQueryLog(); 
    $halfDayLeave =Leave::where('start_date', date("Y-m-d"))
    ->where('option_for_leave', 'HD')
    ->where('status', 'approved')
    ->count();
    // // dd(\DB::getQueryLog());
    // dd($halfDayLeave);
        $data = [$totalEmp, $fullDayLeave, $halfDayLeave];
        
        return view('admin.index')->with(['data' => $data]);
        
    }

}
