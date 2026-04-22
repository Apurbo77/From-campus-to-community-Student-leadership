<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\FDonation;
use App\Models\TrainingSession;
use App\Models\BloodDonationCamp;
use App\Models\BloodDonation;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function home(Request $request)
    {
        $area = $request->input('area', '');
        $group = $request->input('blood_group', '');
        
        $students = collect();
        if ($request->has('search')) {
            $query = Student::query();
            if ($area) {
                $query->where('area', 'like', "%{$area}%");
            }
            if ($group) {
                $query->where('blood_group', $group);
            }
            $students = $query->get();
        }

        $totalDonations = FDonation::sum('amount') ?? 0;
        
        $latestDonationRec = FDonation::select('student.full_name', 'f_donation.date')
            ->leftJoin('student', 'student.student_id', '=', 'f_donation.student_id')
            ->orderBy('f_donation.date', 'desc')
            ->first();
        $latestDonation = $latestDonationRec ? $latestDonationRec->full_name : null;

        $recentSessions = TrainingSession::orderBy('date', 'desc')->limit(5)->get();

        $topVolunteers = Student::select('student.full_name', DB::raw('AVG(feedback.rating) as avg_rating'))
            ->leftJoin('feedback', 'student.student_id', '=', 'feedback.student_id')
            ->groupBy('student.student_id', 'student.full_name') // Include full_name for strictly mode compatibility
            ->orderBy('avg_rating', 'desc')
            ->limit(5)->get();

        $camps = BloodDonationCamp::orderBy('date', 'asc')->limit(5)->get();
        $totalBags = BloodDonation::sum('bag_count') ?? 0;
        
        $latestBlood = BloodDonation::select('student.full_name as student_name', 'blood_donation.bag_count', 'blood_donation_camp.title as camp_name', 'blood_donation.donation_date')
            ->join('student', 'blood_donation.student_id', '=', 'student.student_id')
            ->join('blood_donation_camp', 'blood_donation.camp_id', '=', 'blood_donation_camp.camp_id')
            ->orderBy('blood_donation.donation_date', 'desc')
            ->orderBy('blood_donation.camp_id', 'desc')
            ->first();

        return view('home', compact('students', 'area', 'group', 'totalDonations', 'latestDonation', 'recentSessions', 'topVolunteers', 'camps', 'totalBags', 'latestBlood'));
    }

    public function leaderboard()
    {
        $leaders = \App\Models\DFinancial::select('d_financial.id', 'd_financial.name', 'd_financial.finance_score', 'd_financial.flag', 'student.full_name')
            ->leftJoin('student', 'student.student_id', '=', 'd_financial.student_id')
            ->orderBy('finance_score', 'desc')
            ->limit(20)->get();

        $blood = \App\Models\DBlood::select('student.student_id', 'd_blood.name', 'd_blood.blood_score', 'd_blood.blood_group', 'd_blood.flag', 'student.full_name')
            ->rightJoin('student', 'student.student_id', '=', 'd_blood.student_id')
            ->whereNotNull('d_blood.blood_score')
            ->orderBy('blood_score', 'desc')
            ->limit(20)->get();

        return view('leaderboard', compact('leaders', 'blood'));
    }

    public function training()
    {
        $studentId = session('student_id');
        if (!$studentId) return redirect()->route('login')->with('error', 'Please login to access the training portal.');
        $student = Student::find($studentId);

        $sessions = TrainingSession::orderBy('date', 'asc')->orderBy('time', 'asc')->get();
        foreach($sessions as $s) {
            $s->count = \App\Models\RegisterSession::where('session_id', $s->session_id)->count();
            $s->is_full = $s->count >= $s->capacity;
        }

        $registrations = \App\Models\RegisterSession::select('register_session.status', 'training_session.title', 'training_session.location', 'training_session.date', 'training_session.time')
            ->join('training_session', 'training_session.session_id', '=', 'register_session.session_id')
            ->where('register_session.student_id', $studentId)
            ->orderBy('training_session.date', 'desc')
            ->get();

        $donHistory = BloodDonation::select('blood_donation.donation_date', 'blood_donation.bag_count', 'blood_donation_camp.title', 'blood_donation_camp.location')
            ->leftJoin('blood_donation_camp', 'blood_donation_camp.camp_id', '=', 'blood_donation.camp_id')
            ->where('blood_donation.student_id', $studentId)
            ->orderBy('blood_donation.donation_date', 'desc')
            ->get();

        return view('training', compact('student', 'sessions', 'registrations', 'donHistory'));
    }

    public function trainingUpdateStatus(Request $request)
    {
        $studentId = session('student_id');
        if (!$studentId) return redirect()->route('login');
        
        if ($request->has('available')) {
            Student::where('student_id', $studentId)->update(['available' => $request->available]);
            return back()->with('success', 'Availability updated.');
        }

        if ($request->has('last_donation_date')) {
            Student::where('student_id', $studentId)->update(['last_donation_date' => $request->last_donation_date]);
            return back()->with('success', 'Last donation date updated.');
        }
        
        return back();
    }

    public function trainingRegister(Request $request)
    {
        $studentId = session('student_id');
        if (!$studentId) return redirect()->route('login');
        $sessionId = $request->session_id;

        $session = TrainingSession::find($sessionId);
        if (!$session) return back()->with('error', 'Session not found.');

        $existing = \App\Models\RegisterSession::where('student_id', $studentId)->where('session_id', $sessionId)->first();
        if ($existing) {
            return back()->with('error', 'You are already registered for this session.');
        }

        $count = \App\Models\RegisterSession::where('session_id', $sessionId)->count();
        if ($count >= $session->capacity) {
            return back()->with('error', 'This session is full.');
        }

        \App\Models\RegisterSession::create([
            'student_id' => $studentId,
            'session_id' => $sessionId,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Successfully registered for training (pending).');
    }

    public function bloodMatching(Request $request)
    {
        $area = $request->input('area', '');
        $group = $request->input('blood_group', '');
        
        $students = collect();
        if ($request->has('search')) {
            $query = Student::query();
            if ($area) {
                $query->where('area', 'like', "%{$area}%");
            }
            if ($group) {
                $query->where('blood_group', $group);
            }
            $students = $query->get();
        }

        return view('blood_matching', compact('students', 'area', 'group'));
    }

    public function bloodMatchingRequest(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'location' => 'required',
            'blood_group' => 'required',
            'urgency_level' => 'required'
        ]);

        \App\Models\BloodRequest::create([
            'name' => $request->name,
            'phone_no' => $request->phone,
            'location' => $request->location,
            'blood_group' => $request->blood_group,
            'urgency_level' => $request->urgency_level
        ]);

        return back()->with('success', 'Your blood request has been received. We will contact matching donors soon.');
    }

    public function donate()
    {
        $students = Student::orderBy('full_name')->get();
        $camps = BloodDonationCamp::orderBy('date', 'asc')->get();
        return view('donate', compact('students', 'camps'));
    }

    public function donateBlood(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'camp_id' => 'required',
            'bag_count' => 'required|numeric|min:1',
            'flag' => 'required'
        ]);

        $date = now()->toDateString();
        
        BloodDonation::create([
            'student_id' => $request->student_id,
            'camp_id' => $request->camp_id,
            'bag_count' => $request->bag_count,
            'donation_date' => $date,
            'flag' => $request->flag
        ]);

        Student::where('student_id', $request->student_id)->update(['last_donation_date' => $date]);

        $score = $request->bag_count * 10;
        $dblood = \App\Models\DBlood::where('student_id', $request->student_id)->first();
        if ($dblood) {
            $dblood->increment('blood_score', $score);
        } else {
            $student = Student::find($request->student_id);
            \App\Models\DBlood::create([
                'student_id' => $request->student_id,
                'name' => $student->full_name,
                'blood_score' => $score,
                'flag' => $request->flag,
                'blood_group' => $student->blood_group ?? 'Unknown',
                'camp_id' => $request->camp_id
            ]);
        }

        return back()->with('success', 'Blood donation recorded successfully.');
    }

    public function donateFund(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'amount' => 'required|numeric|min:1',
            'flag' => 'required'
        ]);

        $date = now()->toDateString();
        
        FDonation::create([
            'student_id' => $request->student_id,
            'amount' => $request->amount,
            'date' => $date,
            'flag' => $request->flag
        ]);

        $score = $request->amount / 100;
        $dfinancial = \App\Models\DFinancial::where('student_id', $request->student_id)->first();
        if ($dfinancial) {
            $dfinancial->increment('finance_score', $score);
        } else {
            $student = Student::find($request->student_id);
            \App\Models\DFinancial::create([
                'student_id' => $request->student_id,
                'name' => $student->full_name,
                'finance_score' => $score,
                'flag' => $request->flag
            ]);
        }

        return back()->with('success', 'Thank you! Your financial donation has been recorded.');
    }

    public function certification()
    {
        $students = Student::orderBy('full_name')->get();
        $trainings = TrainingSession::select('title')->distinct()->orderBy('title')->get();
        return view('certification', compact('students', 'trainings'));
    }

    public function certificationRequest(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'training_type' => 'required'
        ]);

        \DB::table('certification')->insert([
            'badge_id' => rand(10000, 99999), // Laravel strict mode requires this field
            'student_id' => $request->student_id,
            'badge_name' => $request->training_type,
            'training_type' => $request->training_type
        ]);

        return back()->with('success', 'Certification request has been received. You will be notified once approved.');
    }

    public function profileView()
    {
        $studentId = session('student_id');
        if (!$studentId) return redirect()->route('login');
        
        $student = Student::find($studentId);
        return view('profile', compact('student'));
    }

    public function profileEdit()
    {
        $studentId = session('student_id');
        if (!$studentId) return redirect()->route('login');
        
        $student = Student::find($studentId);
        return view('profile_edit', compact('student'));
    }

    public function profileUpdate(Request $request)
    {
        $studentId = session('student_id');
        if (!$studentId) return redirect()->route('login');

        $request->validate([
            'full_name' => 'required',
            'phone' => 'required',
            'area' => 'required',
            'city' => 'required',
            'profile_url' => 'nullable|url'
        ]);

        $student = Student::find($studentId);
        
        $updateData = [
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'area' => $request->area,
            'city' => $request->city,
            'profile_url' => $request->profile_url,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = password_hash($request->password, PASSWORD_DEFAULT);
        }

        $student->update($updateData);

        session(['student_name' => $request->full_name]);

        return back()->with('success', 'Profile updated successfully.');
    }
}
