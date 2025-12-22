<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Breaks;
use Illuminate\Support\Facades\Auth;

class AdminBreaksController extends Controller
{
    public function index()
    {
        return view('admin.check')->with([
            'attendances' => Attendance::all(),
            'breaks' => Breaks::all()
        ]);
    }
   public function create(Request $request)
        {
            $user = Auth::user();
            $employeeId = $user->id; 
            $currentDate = now()->toDateString();
    
            $attendance = Attendance::where('emp_id', $employeeId)
                                    ->whereDate('created_at', $currentDate)
                                    ->first();
    
            if (!$attendance) {
                return response()->json(['error' => 'No attendance record found for today'], 404);
            }
    
            if ($request->type == 'break_in') {
                Breaks::create([
                    'emp_id' => $employeeId,
                    'attendance_id' => $attendance->id,
                    'break_in' => now(),
                ]);
            } elseif ($request->type == 'break_out') {
                $break = Breaks::where('emp_id', $employeeId)
                               ->where('attendance_id', $attendance->id)
                               ->whereNull('break_out')
                               ->orderBy('break_in', 'desc')
                               ->first();
    
                if ($break) {
                    $break->update([
                        'break_out' => now(),
                    ]);
                } else {
                    return response()->json(['error' => 'No ongoing break found to end'], 404);
                }
            }
    
            return response()->json(['success' => ucfirst($request->type) . ' recorded successfully']);
        }
    
        public function status()
        {
            $user = Auth::user();
            $employeeId = $user->id;
            $currentDate = now()->toDateString();
    
            $attendance = Attendance::where('emp_id', $employeeId)
                                    ->whereDate('created_at', $currentDate)
                                    ->first();
    
            $break = $attendance ? Breaks::where('emp_id', $employeeId)
                                      ->where('attendance_id', $attendance->id)
                                      ->whereNull('break_out')
                                      ->first() : null;
    
            $status = [
                'break_in' => $break ? (bool) $break->break_in : false,
                'break_out' => $break ? (bool) $break->break_out : false,
            ];
    
            return response()->json($status);
        }
}
