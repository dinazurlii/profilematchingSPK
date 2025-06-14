<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function add()
    {
        $groups = DB::table('aigenperformancemonitor.groups')->get();
        $divisions = DB::table('aigenperformancemonitor.divisions')->get();
        $roles = DB::table('aigenperformancemonitor.roles')->get();

        return view('employeesAdd', compact('groups', 'divisions', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string',
            'name' => 'required|string',
            'email' => 'nullable|email',
            'division_id' => 'nullable|integer',
            'role_id' => 'required|integer',
            'group_id' => 'required|integer',
        ]);

        // Cek apakah NIP sudah ada
        $existing = DB::table('aigenperformancemonitor.employees')
            ->where('nip', $validated['nip'])
            ->first();

        if ($existing) {
            return redirect('/employees/add')
                ->with('error', 'NIP sudah terdaftar.')
                ->withInput();
        }

        // Simpan data kalau NIP belum ada
        DB::table('aigenperformancemonitor.employees')->insert([
            'id' => DB::raw("nextval('aigenperformancemonitor.employees_id_seq'::regclass)"),
            'nip' => $validated['nip'],
            'name' => $validated['name'],
            'group_id' => $validated['group_id'],
            'email' => $validated['email'],
            'division_id' => $validated['division_id'],
            'role_id' => $validated['role_id'],
        ]);

        return redirect()->route('employees')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function index()
    {
        $employees = DB::table('aigenperformancemonitor.employees')
            ->join('aigenperformancemonitor.groups', 'employees.group_id', '=', 'groups.group_id')
            ->join('aigenperformancemonitor.roles', 'employees.role_id', '=', 'roles.id')
            ->join('aigenperformancemonitor.divisions', 'employees.division_id', '=', 'divisions.id')
            ->select(
                'employees.id',
                'employees.nip',
                'employees.name',
                'employees.email',
                'groups.groupname as group_name',
                'roles.name as role_name',
                'divisions.name as division_name'
            )
            ->get();

        return view('employees', compact('employees'));
    }
    public function destroy($id)
{
    DB::table('aigenperformancemonitor.employees')->where('id', $id)->delete();

    return response()->json(['success' => true], 200);
}
public function edit($id)
{
    $employee = DB::table('aigenperformancemonitor.employees')->where('id', $id)->first();
    $divisions = DB::table('aigenperformancemonitor.divisions')->get();
    $groups = DB::table('groups')->select('group_id', 'groupname')->get();
    $roles = DB::table('roles')->select('id', 'name')->get();

    return view('employeesEdit', compact('employee', 'divisions', 'groups', 'roles'));
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'nip' => 'required|string',
        'name' => 'required|string',
        'email' => 'nullable|email',
        'division_id' => 'required|integer',
        'group_id' => 'required|integer',
        'role_id' => 'required|integer',
    ]);

    DB::table('aigenperformancemonitor.employees')
        ->where('id', $id)
        ->update([
            'nip' => $validated['nip'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'division_id' => $validated['division_id'],
            'group_id' => $validated['group_id'],
            'role_id' => $validated['role_id'],
        ]);

    return redirect('/employees')->with('success', 'Data karyawan berhasil diperbarui.');
}

public function criteria()
{
    $roles = DB::table('aigenperformancemonitor.roles')
        ->join('aigenperformancemonitor.divisions', 'roles.division_id', '=', 'divisions.id')
        ->select(
            'roles.id as role_id',  // ✅ Tambah ini
            'roles.name as role_name',
            'divisions.name as division_name'
        )
        ->get();

    return view('criteria', compact('roles'));
}

public function showByRole($role_id)
{
    $criteria = DB::table('aigenperformancemonitor.role_criteria as rc')
        ->join('aigenperformancemonitor.criteria as c', 'rc.criteria_id', '=', 'c.id')
        ->join('aigenperformancemonitor.roles as r', 'rc.role_id', '=', 'r.id')
        ->join('aigenperformancemonitor.divisions as d', 'r.division_id', '=', 'd.id')
        ->where('rc.role_id', $role_id)
        ->select(
            'rc.id as role_criteria_id',
            'c.name as criteria_name',
            'rc.code',
            'r.name as role_name',
            'd.name as division_name'
        )
        ->get();

    // Ambil sub-kriteria yang sudah tersimpan untuk setiap role_criteria_id
    $subKriterias = DB::table('aigenperformancemonitor.sub_criteria')
        ->whereIn('role_criteria_id', $criteria->pluck('role_criteria_id'))
        ->get()
        ->groupBy('role_criteria_id');

    return view('criteriaDetail', [
        'criteria' => $criteria,
        'role_id' => $role_id,
        'subKriterias' => $subKriterias
    ]);
}
public function storeSubCriteria(Request $request)
{
    $validated = $request->validate([
        'role_id' => 'required|integer',
        'sub_kriteria' => 'required|array',
        'sub_kriteria.*' => 'required|array|min:1',
        'sub_kriteria.*.*' => 'required|string|max:255',
    ]);

    try {
        // Loop through each criteria and its sub-criteria
        foreach ($validated['sub_kriteria'] as $role_criteria_id => $subCriteriaArray) {
            // Delete existing sub-criteria for this role_criteria_id (if any)
            DB::table('aigenperformancemonitor.sub_criteria')
                ->where('role_criteria_id', $role_criteria_id)
                ->delete();

            // Insert new sub-criteria
            foreach ($subCriteriaArray as $description) {
                if (!empty(trim($description))) {
                    DB::table('aigenperformancemonitor.sub_criteria')->insert([
                        'role_criteria_id' => $role_criteria_id,
                        'description' => trim($description),
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Sub-kriteria berhasil disimpan!');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
    }
}

}
