<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\TrainingSession;
use App\Models\BloodDonationCamp;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        if (!session()->has('admin_id')) {
            return redirect('/admin/login');
        }

        $students = Student::orderBy('full_name')->get();
        $sessions = TrainingSession::orderBy('date', 'asc')->orderBy('time', 'asc')->get();
        $camps = BloodDonationCamp::orderBy('date', 'asc')->orderBy('time', 'asc')->get();

        $recentRegistrations = DB::table('register_session as r')
            ->join('student as s', 's.student_id', '=', 'r.student_id')
            ->join('training_session as t', 't.session_id', '=', 'r.session_id')
            ->select('r.id', 'r.status', 's.full_name', 't.title', 't.date')
            ->orderBy('r.registered_at', 'desc')
            ->limit(8)
            ->get();

        $pendingCertifications = DB::table('certification as c')
            ->join('student as s', 's.student_id', '=', 'c.student_id')
            ->whereNull('c.date_awarded')
            ->select('c.certification_id', 'c.badge_name', 's.full_name', 'c.training_type')
            ->orderBy('c.certification_id', 'desc')
            ->get();

        return view('admin_dashboard', compact('students', 'sessions', 'camps', 'recentRegistrations', 'pendingCertifications'));
    }

    public function createSession(Request $request)
    {
        $request->validate(['title' => 'required', 'location' => 'required', 'date' => 'required', 'time' => 'required', 'capacity' => 'required|numeric']);
        TrainingSession::create([
            'title' => $request->title,
            'location' => $request->location,
            'date' => $request->date,
            'time' => $request->time,
            'capacity' => $request->capacity,
            'created_by' => 1
        ]);
        return back()->with('success', 'Training session created.');
    }

    public function createCamp(Request $request)
    {
        $request->validate(['c_title' => 'required', 'c_location' => 'required', 'c_date' => 'required', 'c_time' => 'required']);
        BloodDonationCamp::create([
            'title' => $request->c_title,
            'location' => $request->c_location,
            'date' => $request->c_date,
            'time' => $request->c_time
        ]);
        return back()->with('success', 'Blood donation camp created.');
    }

    public function deleteStudent(Request $request)
    {
        $studentId = $request->student_id;
        DB::transaction(function() use ($studentId) {
            DB::table('blood_donation')->where('student_id', $studentId)->delete();
            DB::table('f_donation')->where('student_id', $studentId)->delete();
            DB::table('register_session')->where('student_id', $studentId)->delete();
            DB::table('feedback')->where('student_id', $studentId)->delete();
            DB::table('d_blood')->where('student_id', $studentId)->delete();
            DB::table('d_financial')->where('student_id', $studentId)->delete();
            DB::table('certification')->where('student_id', $studentId)->delete();
            Student::where('student_id', $studentId)->delete();
        });
        return back()->with('success', 'Student and related records deleted successfully.');
    }

    public function updateRegStatus(Request $request)
    {
        $request->validate(['reg_id' => 'required', 'new_status' => 'required']);
        DB::table('register_session')->where('id', $request->reg_id)->update(['status' => $request->new_status]);
        return back()->with('success', 'Registration status updated to: ' . $request->new_status);
    }

    public function approveCertification(Request $request)
    {
        $request->validate(['cert_id' => 'required', 'badge_icon' => 'required']);
        $validIcons = [
            'CPR' => 'img/CPR.png',
            'ECG' => 'img/ECG.png',
            'INJECTION' => 'img/INJECTION.png',
            'FIRST AID' => 'img/FIRST AID.png'
        ];
        
        $icon = $validIcons[$request->badge_icon] ?? null;
        if ($icon) {
            DB::table('certification')->where('certification_id', $request->cert_id)->update([
                'date_awarded' => now()->toDateString(),
                'badge_icon' => $icon
            ]);
            return back()->with('success', 'Certification approved with ' . $request->badge_icon . ' badge.');
        }
        return back()->with('error', 'Invalid certification approval request.');
    }
}
