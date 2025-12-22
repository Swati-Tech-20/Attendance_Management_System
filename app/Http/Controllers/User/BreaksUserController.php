<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Employee;
use App\Models\Breaks;
use App\Models\Attendance;

class BreaksUserController extends Controller
{
    public function index()
    {
        $breaks = breaks::with('user')->get();
        return view('breaks.create', compact('breaks'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $currentDate = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('created_at', $currentDate)
                                ->first();

        if (!$attendance) {
            return response()->json(['error' => 'No attendance record found for today'], 404);
        }

        if ($request->type == 'break_in') {
            Breaks::create([
                'user_id' => $user->id,
                'attendance_id' => $attendance->id,
                'break_in' => now(),
            ]);
        } elseif ($request->type == 'break_out') {
            $break = Breaks::where('user_id', $user->id)
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
        $currentDate = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('created_at', $currentDate)
                                ->first();

        $break = $attendance ? Breaks::where('user_id', $user->id)
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
