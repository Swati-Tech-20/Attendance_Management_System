<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ResetPasswordController extends Controller
{

    use ResetsPasswords;
    public function index(){
        return view('user.passwordreset');
    }

    public function updatePassword(Request $request)
    {
        // Validate the request
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        // Check if the current password matches the one in the database
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update the user's password
        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        // Redirect with a success message
        return back()->with('status', 'Password updated successfully!');
    }
}
