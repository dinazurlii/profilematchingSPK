<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\ScoreController;

// Login Routes
Route::view('/', 'login')->name('login');

Route::post('/', function (Request $request) {
    $user = DB::table('aigenperformancemonitor.users')
        ->where('username', $request->username)
        ->where('password', $request->password) // plain text untuk testing
        ->first();

    if ($user) {
        session(['user' => $user]);
        return redirect('/dashboard');
    }

    return back()->with('error', 'Username atau password salah.');
})->name('login.process');

// Dashboard dan halaman lainnya
Route::get('/dashboard', [ScoreController::class, 'dashboard'])->name('dashboard');

Route::get('/performance', function () {
    return view('performance');
});

Route::get('/performance/reviews', [PerformanceController::class, 'reviews'])->name('performance.reviews');

Route::get('/criteria', function () {
    return view('criteria');
});

// Employee routes
Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');
Route::get('/employees/add', [EmployeeController::class, 'add']);
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');

Route::delete('/employees/delete/{id}', [EmployeeController::class, 'destroy']);
Route::get('/employees/edit', function () {
    return view('employeesAdEditd');
});

// Logout
Route::get('/logout', function () {
    session()->forget('user');
    return redirect()->route('login');
});

// Route untuk menampilkan form edit
Route::get('/employees/edit/{id}', [EmployeeController::class, 'edit'])->name('employees.edit');

// Route untuk menyimpan update
Route::put('/employees/update/{id}', [EmployeeController::class, 'update'])->name('employees.update');

// Route untuk menampilkan kriteria
Route::get('/criteria', [EmployeeController::class, 'criteria'])->name('criteria');

Route::get('/criteria/{role_id}', [EmployeeController::class, 'showByRole'])->name('criteria.detail');

Route::post('/subcriteria', [EmployeeController::class, 'storeSubCriteria'])->name('subcriteria.store');

// ⭐ SCORE ROUTES - TAMBAH 3 BARIS INI
Route::get('/scores/create/{employeeNip}', [ScoreController::class, 'create'])->name('scores.create');
Route::post('/scores/store', [ScoreController::class, 'store'])->name('scores.store');
Route::get('/scores/detail/{employeeNip}', [ScoreController::class, 'detail'])->name('scores.detail');

// Debug route - untuk test apakah route berfungsi
Route::get('/test-route', function() {
    return 'Route berhasil!';
});

// =====================================================
// ROUTES untuk Profile Matching - Tambahkan ke web.php
// =====================================================

// Existing Score routes (sudah ada sebelumnya)
Route::get('/scores/{employeeNip}/create', [ScoreController::class, 'create'])->name('scores.create');
Route::post('/scores/store', [ScoreController::class, 'store'])->name('scores.store');
Route::get('/scores/{employeeNip}/detail', [ScoreController::class, 'detail'])->name('scores.detail');

// =====================================================
// NEW: Profile Matching Routes
// =====================================================

// Profile Matching Form & CRUD
Route::get('/scores/{employeeNip}/profile-matching/create', [ScoreController::class, 'createProfileMatching'])->name('scores.createProfileMatching');
Route::post('/scores/profile-matching/store', [ScoreController::class, 'storeProfileMatching'])->name('scores.storeProfileMatching');
Route::get('/scores/{employeeNip}/profile-matching', [ScoreController::class, 'showProfileMatching'])->name('scores.showProfileMatching');

// Profile Matching Analytics & Reports
Route::get('/scores/ranking', [ScoreController::class, 'ranking'])->name('scores.ranking');
Route::get('/scores/dashboard', [ScoreController::class, 'dashboard'])->name('scores.dashboard');

// Profile Matching List untuk semua employees
Route::get('/profile-matching', function() {
    $employees = DB::table('aigenperformancemonitor.employees')
        ->leftJoin('aigenperformancemonitor.divisions', 'employees.division_id', '=', 'divisions.id')
        ->leftJoin('aigenperformancemonitor.roles', 'employees.role_id', '=', 'roles.id')
        ->select(
            'employees.nip',
            'employees.name', 
            'employees.total_profile_score',
            'employees.status',
            'employees.submission',
            'divisions.name as division_name',
            'roles.name as role_name'
        )
        ->orderBy('employees.total_profile_score', 'desc')
        ->orderBy('employees.name', 'asc')
        ->get();
        
    return view('profileMatchingList', compact('employees'));
})->name('profile.matching.list');

// =====================================================
// EXISTING Employee Routes (jika belum ada)
// =====================================================

Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');
Route::get('/employees/add', [EmployeeController::class, 'add'])->name('employees.add');
Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

// =====================================================
// NAVIGATION HELPERS
// =====================================================

// Route untuk redirect ke Profile Matching berdasarkan employee
Route::get('/employee/{employeeNip}/profile-matching', function($employeeNip) {
    return redirect()->route('scores.showProfileMatching', ['employeeNip' => $employeeNip]);
})->name('employee.profile.matching');

// Route untuk quick access ke form Profile Matching
Route::get('/employee/{employeeNip}/evaluate', function($employeeNip) {
    return redirect()->route('scores.createProfileMatching', ['employeeNip' => $employeeNip]);
})->name('employee.evaluate');

// Route untuk Performance Review dengan Profile Matching
Route::get('/performance/reviews/{division_id?}', function($division_id = null) {
    $query = DB::table('aigenperformancemonitor.employees')
        ->leftJoin('aigenperformancemonitor.divisions', 'employees.division_id', '=', 'divisions.id')
        ->leftJoin('aigenperformancemonitor.roles', 'employees.role_id', '=', 'roles.id')
        ->select(
            'employees.nip',
            'employees.name',
            'employees.status',
            'employees.total_profile_score',
            'employees.submission',
            'divisions.name as division_name',
            'roles.name as role_name'
        );
    
    if ($division_id) {
        $query->where('employees.division_id', $division_id);
    }
    
    $employees = $query->orderBy('employees.name')->get();
    $divisions = DB::table('aigenperformancemonitor.divisions')->get();
    
    return view('performanceReviews', compact('employees', 'divisions', 'division_id'));
})->name('performance.reviews');