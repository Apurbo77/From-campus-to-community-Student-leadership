<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function students()
    {
        return response()->json(\App\Models\Student::select('student_id', 'full_name', 'area', 'city', 'blood_group', 'available')->get());
    }

    public function camps()
    {
        return response()->json(\App\Models\BloodDonationCamp::all());
    }

    public function requests()
    {
        return response()->json(\App\Models\BloodRequest::orderBy('created_at', 'desc')->get());
    }

    public function training()
    {
        return response()->json(\App\Models\TrainingSession::all());
    }
}
