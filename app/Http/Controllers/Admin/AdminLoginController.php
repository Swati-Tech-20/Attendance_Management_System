<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        // Validate the login data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Check if the logged-in user is an admin
            if (Auth::user()->is_admin == 1) {
                return redirect()->intended(route('admin.index'));
            } else {
                // Log out the user if they are not an admin
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Not an admin user.']);
            }
        }

        // Redirect back with errors if login fails
        return redirect()->back()->withErrors(['email' => 'Invalid credentials.']);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('welcome'); 
    }
    public function showPasswordForm()
    {
        $user = Auth::user();
    
        return view('admin.auth.reset', compact('user'));
    }
    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        // Validate input
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);
    
        // Debugging: Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            // Log the current password and hash for debugging purposes (only for development)
            \Log::debug('Current Password Entered: ' . $request->current_password);
            \Log::debug('Stored Password Hash: ' . $user->password);
    
            throw ValidationException::withMessages([
                'current_password' => 'The current password is incorrect.',
            ]);
        }
    
        // Update password
        $user->password = Hash::make($request->password);
        $user->save();
    
        // Redirect with success message
        flash()->success('Success','Schedule has been created successfully !');
        return redirect()->route('admin.index');
    }
}
