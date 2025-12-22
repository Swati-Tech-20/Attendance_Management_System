<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Breaks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ManageemployeeController extends Controller
{
    public function showStatusPage(Request $request)
    {
        $user = Auth::user();
    
        // Get the selected month from the request or default to the current month
        $month = $request->input('month', now()->format('Y-m'));
    
        // Fetch attendance records for the authenticated user for the selected month
        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('punch_in', '=', \Carbon\Carbon::parse($month)->month)
            ->whereYear('punch_in', '=', \Carbon\Carbon::parse($month)->year)
            ->get();
    
        // Calculate total break hours for each attendance record
        foreach ($attendances as $attendance) {
            $totalBreakSeconds = 0;
            $breaks = Breaks::where('attendance_id', $attendance->id)->get();
            foreach ($breaks as $break) {
                $break_in = new \DateTime($break->break_in);
                $break_out = new \DateTime($break->break_out);
                $interval = $break_out->diff($break_in);
                $totalBreakSeconds += $interval->h * 3600 + $interval->i * 60 + $interval->s;
            }
            $hours = floor($totalBreakSeconds / 3600);
            $minutes = floor(($totalBreakSeconds % 3600) / 60);
            $attendance->total_break_hours = sprintf('%d hours %d minutes', $hours, $minutes);
        }
    
        return view('user.attendancereport', compact('user', 'attendances', 'month'));
    } 
    public function getBreakDetails($attendanceId)
    {
        $breaks = Breaks::where('attendance_id', $attendanceId)->get();
        return response()->json(['breaks' => $breaks]);
    }
}
