<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/leaderboard', [PageController::class, 'leaderboard'])->name('leaderboard');
Route::get('/training', [PageController::class, 'training'])->name('training');
Route::post('/training/status', [PageController::class, 'trainingUpdateStatus'])->name('training.status');
Route::post('/training/register', [PageController::class, 'trainingRegister'])->name('training.register');
Route::get('/blood-matching', [PageController::class, 'bloodMatching'])->name('blood_matching');
Route::post('/blood-matching/request', [PageController::class, 'bloodMatchingRequest'])->name('blood_matching.request');

Route::get('/donate', [PageController::class, 'donate'])->name('donate');
Route::post('/donate/blood', [PageController::class, 'donateBlood'])->name('donate.blood');
Route::post('/donate/fund', [PageController::class, 'donateFund'])->name('donate.fund');

Route::get('/certification', [PageController::class, 'certification'])->name('certification');
Route::post('/certification', [PageController::class, 'certificationRequest'])->name('certification.request');

use App\Http\Controllers\DashboardController;

Route::get('/login', [AuthController::class, 'studentLogin'])->name('login');
Route::post('/login', [AuthController::class, 'studentLoginPost']);
Route::get('/logout', [AuthController::class, 'studentLogout'])->name('logout');

Route::get('/profile', [PageController::class, 'profileView'])->name('profile');
Route::get('/profile/edit', [PageController::class, 'profileEdit'])->name('profile.edit');
Route::post('/profile/edit', [PageController::class, 'profileUpdate'])->name('profile.update');

Route::get('/admin/login', [AuthController::class, 'adminLogin'])->name('admin_login');
Route::post('/admin/login', [AuthController::class, 'adminLoginPost']);

Route::get('/admin-dashboard', [DashboardController::class, 'adminDashboard'])->name('admin_dashboard');
Route::post('/admin/sessions/create', [DashboardController::class, 'createSession'])->name('admin.sessions.create');
Route::post('/admin/camps/create', [DashboardController::class, 'createCamp'])->name('admin.camps.create');
Route::post('/admin/students/delete', [DashboardController::class, 'deleteStudent'])->name('admin.students.delete');
Route::post('/admin/registrations/update', [DashboardController::class, 'updateRegStatus'])->name('admin.registrations.update');
Route::post('/admin/certifications/approve', [DashboardController::class, 'approveCertification'])->name('admin.certifications.approve');
