<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Breaks;
use App\Models\Leave;
use App\Models\User;

class AttendanceHelper
{
    public static function getButtons()
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        // Get today's attendance record
        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('punch_in', $today)
            ->first();

        // Get today's breaks or the latest break if no records for today
        $breaks = Breaks::where('user_id', $userId)
            ->whereDate('break_in', now())
            ->latest('break_in')
            ->first();

        if (!$breaks) {
            // Fetch the latest record if no breaks today
            $breaks = Breaks::where('user_id', $userId)
                ->latest('break_in')
                ->first();
        }

        $leave = Leave::where('user_id', $userId)
                      ->where('status', 'approved')
                      ->whereDate('start_date', '<=', $today)
                      ->whereDate('end_date', '>=', $today)
                      ->first();
    
        

        // Initialize button states
        $buttons = [
            'punchIn' => false,
            'punchOut' => false,
            'breakIn' => false,
            'breakOut' => false,
        ];

        if ($leave) {
            return [
                'buttons' => $buttons,
                'onLeave' => true,  // Add a flag to indicate user is on leave
            ];
        }

        // Case 1: No attendance for today, show Punch In button
        if (!$attendance) {
            $buttons['punchIn'] = true;
        } else {
            // Case 2: User has punched in but not punched out
            if ($attendance->punch_in && !$attendance->punch_out) {
                
                // Check for break_in and break_out values
                if (!$breaks || !$breaks->break_in) {
                    // After Punch In, show Punch Out and Break In
                    $buttons['punchOut'] = true;
                    $buttons['breakIn'] = true;
                } elseif ($breaks->break_in && !$breaks->break_out) {
                    // After Break In, only show Break Out button
                    $buttons['breakOut'] = true;
                } elseif ($breaks->break_in && $breaks->break_out) {
                    // After Break Out, show Punch Out and Break In again
                    $buttons['punchOut'] = true;
                    $buttons['breakIn'] = true;
                }
            }

            // Case 3: User has punched in and out, reset buttons for a new day
            if ($attendance->punch_in && $attendance->punch_out) {
                $buttons['punchIn'] = true; // Optionally, reset for the next day
            }
        }

        // Case 4: If user has punched out, hide all buttons
        if ($attendance && $attendance->punch_out) {
            $buttons = [
                'punchIn' => false,
                'punchOut' => false,
                'breakIn' => false,
                'breakOut' => false,
            ];
        }

        return [
            'buttons' => $buttons,
            'onLeave' => false,
        ];
    }
}