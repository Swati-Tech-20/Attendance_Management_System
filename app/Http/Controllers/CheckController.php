<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;    
use App\Models\Breaks;

class CheckController extends Controller
{
    public function index()
    {
        return view('user.check')->with([
            'attendances' => Attendance::all(),
            'breaks' => Breaks::all()
        ]);
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $currentDate = now()->toDateString();

        $attendance = Attendance::where('emp_id', $user->id) // Adjust based on the field name
                                ->whereDate('created_at', $currentDate)
                                ->first();

        if ($request->type == 'punch_in' && !$attendance) {
            Attendance::create([
                'emp_id' => $user->id, // Adjust based on the field name
                'punch_in' => now(),
            ]);
        } elseif ($request->type == 'punch_out' && $attendance && !$attendance->punch_out) {
            $attendance->update([
                'punch_out' => now(),
            ]);
        }

        return response()->json(['success' => ucfirst($request->type) . ' recorded successfully']);
    }

    public function status()
    {
        $user = Auth::user();
        $currentDate = now()->toDateString();

        $attendance = Attendance::where('emp_id', $user->id) // Adjust based on the field name
                                ->whereDate('created_at', $currentDate)
                                ->first();

        $status = [
            'punched_in' => $attendance ? (bool) $attendance->punch_in : false,
            'punched_out' => $attendance ? (bool) $attendance->punch_out : false,
        ];

        return response()->json($status);
    }
}
