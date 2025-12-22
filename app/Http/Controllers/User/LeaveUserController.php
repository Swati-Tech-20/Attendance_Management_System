<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Leave;
use App\Models\Overtime;

class LeaveUserController extends Controller
{
    /**
     * Display a listing of the user's leaves.
     */
    public function index()
    {
        $leaves = Leave::where('user_id', auth()->id())->get();
        return view('user.leave', compact('leaves'));
    }

    /**
     * Display a listing of all overtimes (for admin use).
     */
    public function indexOvertime()
    {
        return view('admin.overtime')->with(['overtimes' => Overtime::all()]);
    }

    /**
     * Show the form for creating a new leave request.
     */
    public function create()
    {
        return view('user.leave.create');
    }

    /**
     * Store a newly created leave request in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'reason' => 'required|string|max:255',
            'type' => 'required|boolean',
            'option_for_leave' => 'required|string',
        ]);

        Leave::create([
            'user_id' => auth()->id(),
            'leave_time' => now()->format('H:i:s'),
            'leave_date' => now()->format('Y-m-d'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'type' => $request->type,
            'option_for_leave' => $request->option_for_leave,
            'status' => 'pending',
        ]);

        return redirect()->route('user.leave')->with('success', 'Leave request submitted successfully.');
    }

    public function update(Request $request, $id)
{
    // Validate the incoming request
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date',
        'reason' => 'required|string|max:255',
        'type' => 'required|boolean',
        'option_for_leave' => 'required|string',
    ]);

    // Find the leave record by ID, or fail if not found
    $leave = Leave::findOrFail($id);

    // Update the leave record with the validated data
    $leave->start_date = $request->start_date;
    $leave->end_date = $request->end_date;
    $leave->reason = $request->reason;
    $leave->type = $request->type;
    $leave->option_for_leave = $request->option_for_leave;
    $leave->save();

    // Flash success message and redirect
    flash()->success('Success', 'Leave record has been updated successfully!');
    return redirect()->route('user.leave');
}



    public function show($id)
    {
        $leave = Leave::findOrFail($id);
        return view('user.leave.show', compact('leave'));
    }
}
