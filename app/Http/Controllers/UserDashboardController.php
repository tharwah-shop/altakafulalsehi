<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user dashboard.
     */
    public function index()
    {
        return view('user.dashboard');
    }

    /**
     * Show the user profile.
     */
    public function profile()
    {
        return view('user.profile');
    }

    /**
     * Update the user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Update basic info
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
            }
            
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * Show user subscriptions.
     */
    public function subscriptions()
    {
        // TODO: Get user subscriptions when Subscriber model is connected to User
        return view('user.subscriptions');
    }
}
