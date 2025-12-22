<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class UserlistController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', 0)->get(); 
        return view('admin.userlist', compact('users')); 
    }

      
    public function update(Request $request, $id)
    {
        // $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:100',
        ]);
    
         $user = User::findOrFail($id);
         $user->name = $request->name;
         $user->email = $request->email;
       
         $user->save();
    
        flash()->success('Success', 'Employee record has been updated successfully!');
        return redirect()->route('admin.userlist');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        flash()->success('Success','Employee Record has been Deleted successfully !');
        return redirect()->route('admin.userlist')->with('success');
    }
}