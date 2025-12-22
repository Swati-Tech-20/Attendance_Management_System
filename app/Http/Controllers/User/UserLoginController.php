<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class UserLoginController extends Controller
{
    public function index()
    {
       
        return view('user.auth.login'); // Path to the login view
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended(route('user.index'));
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials.']);
    }


    public function showPasswordForm()
    {
        $user = Auth::user();
    
        return view('user.auth.passwordreset', compact('user'));
    }
    public function updatePassword(Request $request,$id)
    {

        $user = User::findOrFail($id);

        // Validate input
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);
    
        // Check if email is verified
        // if (!$user->hasVerifiedEmail()) {
        //     return redirect()->back()->withErrors(['email' => 'You need to verify your email before changing the password.']);
        // }
    
        // Check if the current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'The current password is incorrect.',
            ]);
        }
    
        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

    
        // Redirect with success message
        flash()->success('Success','Schedule has been created successfully !');
        return redirect()->route('user.index')->with('status', 'Password has been updated successfully!');
    }
}