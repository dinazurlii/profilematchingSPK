<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Division;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function reviews(Request $request)
    {
        $divisionId = $request->query('division_id');

        // Ambil nama divisi
        $division = Division::find($divisionId);

        // Ambil karyawan + role-nya
        $employees = Employee::with('role')
                  ->where('division_id', $divisionId)
                  ->get();


        return view('performanceReviews', compact('division', 'employees'));
    }
}
