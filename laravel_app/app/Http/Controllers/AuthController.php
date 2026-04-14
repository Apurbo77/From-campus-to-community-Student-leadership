<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AuthController extends Controller
{
    public function adminLogin()
    {
        return view('admin_login');
    }

    public function adminLoginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && password_verify($request->password, $admin->password_hash)) {
            session(['admin_id' => $admin->admin_id, 'admin_name' => $admin->full_name]);
            return redirect('/admin-dashboard');
        }

        return back()->with('error', 'Invalid admin credentials');
    }

    public function studentLogin()
    {
        return view('student_login');
    }

    public function studentLoginPost(Request $request)
    {
        $request->validate([
            'gsuit' => 'required|email',
            'password' => 'required'
        ]);

        $student = \App\Models\Student::where('gsuit', $request->gsuit)->first();
        if ($student && password_verify($request->password, $student->password)) {
            session(['student_id' => $student->student_id, 'student_name' => $student->full_name]);
            return redirect(route('profile'))->with('success', 'Logged in successfully! Welcome to your Profile Dashboard.');
        }

        return back()->with('error', 'Invalid G-Suite email or password.');
    }

    public function studentLogout()
    {
        session()->forget(['student_id', 'student_name']);
        return redirect(route('home'))->with('success', 'Logged out successfully.');
    }
}
