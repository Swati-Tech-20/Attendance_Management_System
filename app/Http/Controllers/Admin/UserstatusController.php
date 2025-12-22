<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\User;
use App\Models\Attendance; // Add the Attendance model
use Carbon\Carbon;

class UserstatusController extends Controller
{
    public function index(Request $request)
    {
        // Get filters from request (if any)
        $selectedUser = $request->input('user_id');
        $selectedMonth = $request->input('month');
    
        // If no month is selected, default to the current month
        if (!$selectedMonth) {
            $selectedMonth = Carbon::now()->format('Y-m');
        }
    
        try {
            // Get the start and end date of the selected month
            $startOfMonth = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
            $endOfMonth = Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Invalid month format. Please select a valid month.');
        }
    
        // Fetch users
        $users = User::where('is_admin', 0)->get();

        // Fetch total leave data
        $leaveData = Leave::query()
            ->when($selectedUser, function ($query) use ($selectedUser) {
                $query->where('user_id', $selectedUser);
            })
            ->whereBetween('start_date', [$startOfMonth, $endOfMonth])
            ->where('status', 'approved')
            ->selectRaw('user_id, COUNT(*) as total_leaves')
            ->groupBy('user_id')
            ->get();

        // Calculate total working days for each user
        $attendanceData = Attendance::query()
            ->when($selectedUser, function ($query) use ($selectedUser) {
                $query->where('user_id', $selectedUser);
            })
            ->whereBetween('punch_in', [$startOfMonth, $endOfMonth])
            ->selectRaw('user_id, COUNT(DISTINCT punch_in) as total_working_days')
            ->groupBy('user_id')
            ->get();
    
        return view('admin.userstatus', compact('users', 'leaveData', 'attendanceData', 'selectedUser', 'selectedMonth'));
    }
}
