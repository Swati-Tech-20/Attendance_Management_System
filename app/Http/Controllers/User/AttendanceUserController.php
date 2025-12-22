<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AttendanceHelper;
use App\Models\Attendance;
use App\Models\Breaks;
use App\Models\User;
use App\Models\Leave;
use Carbon\Carbon;

class AttendanceUserController extends Controller
{
     public function index()    
    {
        $userId = Auth::id();
        $data = AttendanceHelper::getButtons();
        $buttons = $data['buttons'];
        $onLeave = $data['onLeave'];

        $today = Carbon::today()->toDateString();

        // Get attendances for today
        $attendances = Attendance::where('user_id', $userId)
                                 ->whereDate('punch_in', $today)
                                 ->orWhere(function ($query) use ($userId, $today) {
                                     $query->where('user_id', $userId)
                                           ->whereDate('punch_out', $today);
                                 })
                                 ->get();

        // Get breaks for today
        $breaks = Breaks::where('user_id', $userId)
                       ->whereDate('break_in', $today)
                       ->orWhere(function ($query) use ($userId, $today) {
                           $query->where('user_id', $userId)
                                 ->whereDate('break_out', $today);
                       })
                       ->get();

        return view('user.check', compact('buttons', 'onLeave','attendances', 'breaks'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'type' => 'required|in:punch_in,punch_out',
        ]);
    
        $user = Auth::user();
        $currentDate = now()->toDateString();
    
        $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('created_at', $currentDate)
                                ->first();
    
        if ($request->type == 'punch_in') {
            if (!$attendance) {
                Attendance::create([
                    'user_id' => $user->id,
                    'punch_in' => now(),
                ]);
                return response()->json(['success' => 'Punch In recorded successfully']);
            }
            return response()->json(['error' => 'Already punched in today'], 400);
        } 
    
        if ($request->type == 'punch_out') {
            if ($attendance && !$attendance->punch_out) {
                $attendance->update([
                    'punch_out' => now(),
                ]);
                return response()->json(['success' => 'Punch Out recorded successfully']);
            }
            return response()->json(['error' => 'No punch in record or already punched out'], 400);
        }
    
        return response()->json(['error' => 'Invalid request'], 400);
    }
    

    public function status()
    {
        $user = Auth::user();
        $currentDate = now()->toDateString();
        $currentTime = now()->toTimeString();
    
        $leave = Leave::where('user_id', $user->id)
                      ->where('status', 'approved')
                      ->whereDate('start_date', '<=', $currentDate)
                      ->whereDate('end_date', '>=', $currentDate)
                      ->first();
    
        $onLeave = false;
        $leaveType = null;
    
        if ($leave) {
            $onLeave = true;
            $leaveType = $leave->type;  
            if ($leave->option_for_leave == 'HD') {
                $halfDayStart = ($currentTime < '12:00:00');  
               if ($halfDayStart) {
                  return response()->json([
                        'on_leave' => true,
                        'punch_in_allowed' => true,
                        'punch_out_allowed' => false
                    ]);
                } else {
                     return response()->json([
                        'on_leave' => true,
                        'punch_in_allowed' => false,
                        'punch_out_allowed' => true
                    ]);
                }
            }
        }
    
         $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('created_at', $currentDate)
                                ->first();
    
        $status = [
            'punched_in' => $attendance ? (bool) $attendance->punch_in : false,
            'punched_out' => $attendance ? (bool) $attendance->punch_out : false,
            'on_leave' => $onLeave,
            'punch_in_allowed' => $onLeave ? ($leaveType == 'HD' ? ($currentTime < '12:00:00') : false) : true,
            'punch_out_allowed' => $onLeave ? ($leaveType == 'HD' ? ($currentTime >= '12:00:00') : false) : true
        ];
    
        return response()->json($status);
    }
    
}
