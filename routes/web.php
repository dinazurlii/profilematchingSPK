<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\EmployeeController;

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
Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/performance', function () {
    return view('performance');
});

Route::get('/criteria', function () {
    return view('criteria');
});

// Employee routes
Route::get('/employees', [EmployeeController::class, 'index'])->name('employees'); // ✅ ini ditambahkan
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





