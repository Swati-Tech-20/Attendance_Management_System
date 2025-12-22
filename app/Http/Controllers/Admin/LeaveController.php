<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use App\Models\User;

class LeaveController extends Controller
{
    public function index()
    {
        // Show all leaves
        // $user = User::all();
        $leaves = Leave::with('user')->get();
        return view('admin.leave')->with([
            'leaves' => $leaves,
            'user' => User::all()
        ]);
    }

    public function updateStatus(Request $request, Leave $leave)
    {
        $request->validate([
            'status' => 'required|string',
            'rejection_reason' => 'nullable|string'
        ]);
    
        $leave->status = $request->input('status');

        // Set rejection reason if status is 'rejected', otherwise set it to null
        if ($request->input('status') === 'rejected') {
            $leave->rejection_reason = $request->input('rejection_reason');
        } else {
            $leave->rejection_reason = null;
        }

        $leave->save();
    
        return redirect()->route('admin.leave')->with('success', 'Leave status updated successfully');
    }
}
