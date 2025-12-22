<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Schedule;
use App\Http\Requests\EmployeeRec;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeController extends Controller
{
   
    public function index()
    {
        $employees = Employee::with('user', 'schedules')->get();
        return view('admin.employee')->with([
            'employees' => $employees,
            'schedules' => Schedule::all(),
            'users' => User::where('is_admin', 0)->get(),
        ]);
    }

    public function store(EmployeeRec $request)
    {
        $user = Auth::user();
    
        // Validate the request data
        $request->validate([
            'position' => 'required',
            'address' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|string|max:10', // Adjust as needed
            'city' => 'required|string|max:255',
        ]);
    
        $employee = new Employee;
        $employee->user_id = $request->input('user_id'); // Use the user ID from the form
        $employee->position = $request->input('position');
    
        // Store profile details as JSON
        $profile_details = [
            'address' => $request->input('address'),
            'state' => $request->input('state'),
            'pincode' => $request->input('pincode'),
            'city' => $request->input('city'),
        ];
        $employee->profile_details = json_encode($profile_details);
    
        $employee->save();
    
        // Handle the schedule attachment
        if ($request->filled('schedule')) {
            $schedule = Schedule::whereSlug($request->input('schedule'))->first();
            if ($schedule) {
                $employee->schedules()->attach($schedule);
            }
        }
    
        flash()->success('Success', 'Employee Record has been created successfully!');
        return redirect()->route('admin.employee')->with('success');
    }
    
    
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $request->validate([
            'position' => 'required|string|max:255',
            'address' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:6',
            'city' => 'required|string|max:100',
        ]);
    
        $employee = Employee::findOrFail($id);
        $employee->position = $request->position;
        $profile_details = [
            'address' => $request->address,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'city' => $request->city,
        ];
        $employee->profile_details = json_encode($profile_details);
    
        $employee->save();
    
        flash()->success('Success', 'Employee record has been updated successfully!');
        return redirect()->route('admin.employee');
    }

    public function destroy($id)
    {
        $details = Employee::findOrFail($id);
        $details->delete();
        flash()->success('Success','Employee Record has been Deleted successfully !');
        return redirect()->route('admin.employee')->with('success');
    }
}
