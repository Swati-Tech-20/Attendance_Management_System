<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Leave;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceMonthSheetController extends Controller
{
    // Display the attendance sheet
    public function index()
    {
        $employees = Employee::whereHas('user', function ($query) {
            $query->where('is_admin', 0);
        })->with('user')->get();

        return view('admin.attendance_month_sheet', ['employees' => $employees]);
    }

    // Store attendance and leave data
    public function CheckStore(Request $request)
    {
        // Handle attendance
        if (isset($request->attd)) {
            foreach ($request->attd as $date => $values) {
                foreach ($values as $userId => $value) {
                    $employee = Employee::where('user_id', $userId)->with('schedules')->first();
    
                    if ($employee && $employee->schedules->isNotEmpty()) {
                        if ($value == 1) { // If checked, insert attendance record
                            // Check if attendance already exists for the date and user
                            if (!Attendance::whereDate('punch_in', $date)
                                ->where('user_id', $userId)
                                ->exists()) {
    
                                $attendance = new Attendance();
                                $attendance->user_id = $userId;
    
                                // Combine date with time_in and time_out from employee schedule
                                $punchInTime = $date . ' ' . $employee->schedules->first()->time_in;
                                $punchOutTime = $date . ' ' . $employee->schedules->first()->time_out;
    
                                $attendance->punch_in = date('Y-m-d H:i:s', strtotime($punchInTime));
                                $attendance->punch_out = date('Y-m-d H:i:s', strtotime($punchOutTime));
    
                                $attendance->save();
                            }
                        } else { // If unchecked, delete the attendance record
                            Attendance::whereDate('punch_in', $date)
                                ->where('user_id', $userId)
                                ->delete();
                        }
                    }
                }
            }
        }
    
        // Handle leave
        if (isset($request->leave)) {
            foreach ($request->leave as $date => $values) {
                foreach ($values as $userId => $value) {
                    $employee = Employee::where('user_id', $userId)->with('schedules')->first();
    
                    if ($employee && $employee->schedules->isNotEmpty()) {
                        if ($value == 1) { // If checked, insert leave record
                            // Check if leave already exists for the date and user
                            if (!Leave::whereDate('leave_date', $date)
                                ->where('user_id', $userId)
                                ->exists()) {
    
                                $leave = new Leave();
                                $leave->user_id = $userId;
    
                                // Combine date with leave_time
                                $leaveTime = $date . ' ' . $employee->schedules->first()->time_out;
    
                                $leave->leave_time = date('Y-m-d H:i:s', strtotime($leaveTime));
                                $leave->leave_date = $date;
    
                                $leave->save();
                            }
                        } else { // If unchecked, delete the leave record
                            Leave::whereDate('leave_date', $date)
                                ->where('user_id', $userId)
                                ->delete();
                        }
                    }
                }
            }
        }
    
        flash()->success('Success', 'Attendance and leave records successfully submitted!');
        return back();
    }
    
    // Generate the report view
    public function sheetReport()
    {
        $employees = Employee::whereHas('user', function ($query) {
            $query->where('is_admin', 0);
        })->with('user')->get();

        return view('admin.attendance_month_sheet_report', ['employees' => $employees]);
    }
}
