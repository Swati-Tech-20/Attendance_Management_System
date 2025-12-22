<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Attendance;
use App\Models\Breaks;
use App\Models\User;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
{
    // Initialize query
    $query = Attendance::query(); 
    
    // Apply filters if provided
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $query->whereBetween('punch_in', [$startDate, $endDate]);
    }
    
    if ($request->filled('user_id')) {
        $userId = $request->input('user_id');
        $query->where('user_id', $userId) ;
    }

    if ($request->filled('punch_in')) {
        $punchIn = $request->input('punch_in');
        $query->where('punch_in', 'like', "%{$punchIn}%");
    }
    
    if ($request->filled('punch_out')) {
        $punchOut = $request->input('punch_out');
        $query->where('punch_out', 'like', "%{$punchOut}%");
    }

    // Execute query and get results
    $attendances = $query->orderBy(\DB::raw('DATE(punch_in)'), 'DESC')
                         ->orderBy('user_id', 'DESC')
                         ->orderBy('punch_in', 'DESC')
                         ->get();  
    $month = $request->input('month', date('Y-m')); 

    
    //change this query in 
    $employees = User::where('is_admin', 0)
                 ->orderBy('name', 'ASC')
                     ->get();  
    $breaks = [];

    // Check if the attendance records are empty
    if ($attendances->isEmpty()) {
        return view('admin.check', [
            'attendances' => $attendances,
            'employees' => $employees,
            'month' => $month,
            'breaks' => $breaks,
        ]);
    }

    foreach ($attendances as $attendance) {
        $totalBreakSeconds = 0;

        $breaks = Breaks::where('attendance_id', $attendance->id)->get();
        
        if ($breaks->isEmpty()) {
            $attendance->total_break_hours = '- hours - minutes';
        } else {
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
    }

    return view('admin.check', [
        'attendances' => $attendances,
        'employees' => $employees,
        'month' => $month,
        'breaks' => $breaks 
    ]);
}


    public function getBreakDetails($attendanceId)
    {
        $breaks = Breaks::where('attendance_id', $attendanceId)->get();
        return response()->json(['breaks' => $breaks]);
    }

    public function punchOut($id)
{
    // Find the attendance record by ID
    $attendance = Attendance::find($id);

    // Check if the attendance record exists
    if (!$attendance) {
        flash()->success('error', 'Attendance record not found !');
        return redirect()->route('admin.check');
    }

    // Check if punch out time is already set
    if ($attendance->punch_out) {
        flash()->success('error', 'Already punched out !');
        return redirect()->route('admin.check');
    }

    // Manually convert punch_in to a Carbon instance to get the date
    $punchInDate = \Carbon\Carbon::parse($attendance->punch_in)->format('Y-m-d');

    // Set punch out time to 6 PM on the punch in date
    $punchOutTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $punchInDate . ' 18:00:00');

    // Check if the user has punched out of break in the breaks table
    $break = Breaks::where('attendance_id', $id)->first(); // Assuming you have an attendance_id in breaks table

    if (!$break) {
        // If there's no break record, create one
        $break = new Breaks();
        $break->attendance_id = $id; // Link to the attendance record
        $break->break_out = $punchOutTime; // Set break_out to the same time
        $break->save(); // Save the break record
    } else {
        // If break already exists, update break_out
        $break->break_out = $punchOutTime;
        $break->save();
    }

    // Update the punch_out field in the attendance record
    $updated = Attendance::where('id', $id)->update(['punch_out' => $punchOutTime]);

    // Check if the update was successful
    if ($updated) {
        flash()->success('success', 'Punch out successful.');
        return redirect()->route('admin.check');
    }
    flash()->success('error', 'Failed to update attendance record.');
    return redirect()->route('admin.check');
}

    

}
