<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{
    public function create($employeeNip)
    {
        try {
            // Get employee data using existing Employee model
            $employee = Employee::with(['role', 'division'])
                                ->where('nip', $employeeNip)
                                ->first();

            if (!$employee) {
                return redirect()->back()->with('error', 'Employee not found');
            }

            // Get criteria and sub-criteria using Query Builder with explicit schema
            $criteriaData = DB::table('aigenperformancemonitor.role_criteria')
                ->join('aigenperformancemonitor.criteria', 'aigenperformancemonitor.role_criteria.criteria_id', '=', 'aigenperformancemonitor.criteria.id')
                ->join('aigenperformancemonitor.sub_criteria', 'aigenperformancemonitor.role_criteria.id', '=', 'aigenperformancemonitor.sub_criteria.role_criteria_id')
                ->select(
                    'aigenperformancemonitor.criteria.id as criteria_id',
                    'aigenperformancemonitor.criteria.name as criteria_name',
                    'aigenperformancemonitor.role_criteria.code',
                    'aigenperformancemonitor.sub_criteria.id as sub_criteria_id',
                    'aigenperformancemonitor.sub_criteria.description as sub_criteria_description'
                )
                ->where('aigenperformancemonitor.role_criteria.role_id', $employee->role_id)
                ->orderBy('aigenperformancemonitor.criteria.id')
                ->get();

            // Group sub-criteria by criteria
            $groupedCriteria = [];
            foreach ($criteriaData as $item) {
                if (!isset($groupedCriteria[$item->criteria_id])) {
                    $groupedCriteria[$item->criteria_id] = [
                        'name' => $item->criteria_name,
                        'code' => $item->code,
                        'sub_criteria' => []
                    ];
                }
                $groupedCriteria[$item->criteria_id]['sub_criteria'][] = [
                    'id' => $item->sub_criteria_id,
                    'description' => $item->sub_criteria_description
                ];
            }

            // Get existing scores if any
            $existingScores = DB::table('aigenperformancemonitor.scores')
                ->where('employee_id', $employeeNip)
                ->pluck('score', 'sub_criteria_id')
                ->toArray();

            return view('scoreAdd', compact('employee', 'groupedCriteria', 'existingScores'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading page: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:1|max:5'
        ]);

        $employeeId = $request->employee_id;
        $scores = $request->scores;
        $evaluatorId = session('user')['id'] ?? 1;

        DB::beginTransaction();
        
        try {
            // Delete existing scores
            DB::table('aigenperformancemonitor.scores')->where('employee_id', $employeeId)->delete();

            // Insert new scores
            $scoreData = [];
            foreach ($scores as $subCriteriaId => $score) {
                $scoreData[] = [
                    'employee_id' => $employeeId,
                    'sub_criteria_id' => $subCriteriaId,
                    'score' => $score,
                    'evaluator_id' => $evaluatorId,
                    'evaluated_at' => now()
                ];
            }

            DB::table('aigenperformancemonitor.scores')->insert($scoreData);

            // Update employee status using Eloquent
            $employee = Employee::where('nip', $employeeId)->first();
            if ($employee) {
                $employee->status = 'completed';
                $employee->submission = now()->format('Y-m-d');
                $employee->save();
            }

            DB::commit();

            // Redirect back to reviews with division_id parameter
            return redirect()->route('performance.reviews', ['division_id' => $employee->division_id])
                ->with('success', 'Performance review has been saved successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to save performance review: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function detail($employeeNip)
    {
        try {
            // Get employee data
            $employee = Employee::with(['role', 'division'])
                                ->where('nip', $employeeNip)
                                ->first();

            if (!$employee) {
                return redirect()->back()->with('error', 'Employee not found');
            }

            // Get scores with related data using Query Builder
            $scoresData = DB::table('aigenperformancemonitor.scores')
                ->join('aigenperformancemonitor.sub_criteria', 'aigenperformancemonitor.scores.sub_criteria_id', '=', 'aigenperformancemonitor.sub_criteria.id')
                ->join('aigenperformancemonitor.role_criteria', 'aigenperformancemonitor.sub_criteria.role_criteria_id', '=', 'aigenperformancemonitor.role_criteria.id')
                ->join('aigenperformancemonitor.criteria', 'aigenperformancemonitor.role_criteria.criteria_id', '=', 'aigenperformancemonitor.criteria.id')
                ->leftJoin('aigenperformancemonitor.users', 'aigenperformancemonitor.scores.evaluator_id', '=', 'aigenperformancemonitor.users.id')
                ->select(
                    'aigenperformancemonitor.criteria.id as criteria_id',
                    'aigenperformancemonitor.criteria.name as criteria_name',
                    'aigenperformancemonitor.role_criteria.code',
                    'aigenperformancemonitor.sub_criteria.id as sub_criteria_id',
                    'aigenperformancemonitor.sub_criteria.description as sub_criteria_description',
                    'aigenperformancemonitor.scores.score',
                    'aigenperformancemonitor.scores.evaluated_at',
                    'aigenperformancemonitor.users.full_name as evaluator_name'
                )
                ->where('aigenperformancemonitor.scores.employee_id', $employeeNip)
                ->orderBy('aigenperformancemonitor.criteria.id')
                ->get();

            // Group scores by criteria
            $groupedScores = [];
            foreach ($scoresData as $item) {
                if (!isset($groupedScores[$item->criteria_id])) {
                    $groupedScores[$item->criteria_id] = [
                        'name' => $item->criteria_name,
                        'code' => $item->code,
                        'sub_criteria' => []
                    ];
                }
                $groupedScores[$item->criteria_id]['sub_criteria'][] = [
                    'id' => $item->sub_criteria_id,
                    'description' => $item->sub_criteria_description,
                    'score' => $item->score,
                    'evaluated_at' => $item->evaluated_at,
                    'evaluator_name' => $item->evaluator_name ?? 'Unknown'
                ];
            }

            // Calculate final score
            $finalScore = $this->calculateFinalScore($employeeNip);

            return view('scoreDetail', compact('employee', 'groupedScores', 'finalScore'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading detail: ' . $e->getMessage());
        }
    }

    public function calculateFinalScore($employeeNip)
    {
        try {
            // Calculate average score using Query Builder
            $avgScore = DB::table('aigenperformancemonitor.scores')
                ->where('employee_id', $employeeNip)
                ->avg('score');

            return $avgScore ? round($avgScore, 1) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    // =====================================================
    // PROFILE MATCHING METHODS
    // =====================================================

    /**
     * Show Profile Matching form for employee
     */
    public function createProfileMatching($employeeNip)
    {
        try {
            // Get employee data
            $employee = DB::table('aigenperformancemonitor.employees')
                ->leftJoin('aigenperformancemonitor.groups', 'employees.group_id', '=', 'groups.group_id')
                ->leftJoin('aigenperformancemonitor.roles', 'employees.role_id', '=', 'roles.id')
                ->leftJoin('aigenperformancemonitor.divisions', 'employees.division_id', '=', 'divisions.id')
                ->select(
                    'employees.*',
                    'groups.groupname as group_name',
                    'roles.name as role_name',
                    'divisions.name as division_name'
                )
                ->where('employees.nip', $employeeNip)
                ->first();

            if (!$employee) {
                return redirect()->back()->with('error', 'Employee tidak ditemukan.');
            }

            // Parse existing Profile Matching data jika ada
            $existingProfileData = null;
            if (!empty($employee->profile_matching_scores)) {
                try {
                    $existingProfileData = json_decode($employee->profile_matching_scores, true);
                } catch (\Exception $e) {
                    $existingProfileData = null;
                }
            }

            return view('profileMatchingAdd', compact('employee', 'existingProfileData'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading Profile Matching form: ' . $e->getMessage());
        }
    }

    /**
     * Store Profile Matching scores
     */
    /**
 * Store Profile Matching scores
 */
public function storeProfileMatching(Request $request)
{
    // Basic validation
    $validated = $request->validate([
        'employee_nip' => 'required|string',
        'profile_matching_scores' => 'required|string', // JSON string
    ]);

    // Additional validation for sub_criteria inputs
    $subCriteriaInputs = [];
    foreach ($request->all() as $key => $value) {
        if (preg_match('/^sub_criteria_\d+$/', $key)) {
            $request->validate([
                $key => 'required|integer|min:1|max:5'
            ]);
            $subCriteriaInputs[$key] = (int)$value;
        }
    }

    $employeeNip = $validated['employee_nip'];
    
    // Handle session user - could be array or object
    $sessionUser = session('user');
    $evaluatorId = 1; // default value
    
    if ($sessionUser) {
        if (is_array($sessionUser)) {
            $evaluatorId = $sessionUser['id'] ?? 1;
        } elseif (is_object($sessionUser)) {
            $evaluatorId = $sessionUser->id ?? 1;
        }
    }

    DB::beginTransaction();
    
    try {
        // Parse Profile Matching data dengan error handling yang lebih baik
        $jsonString = $validated['profile_matching_scores'];
        
        // Pastikan JSON string valid
        if (empty($jsonString) || !is_string($jsonString)) {
            throw new \Exception('Profile matching scores data is empty or invalid.');
        }
        
        $profileMatchingData = json_decode($jsonString, true);
        $jsonError = json_last_error();
        
        if ($jsonError !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON format: ' . json_last_error_msg());
        }
        
        if (!is_array($profileMatchingData) || !isset($profileMatchingData['total'])) {
            throw new \Exception('Profile matching data missing required fields.');
        }

        $totalScore = (float)$profileMatchingData['total'];

        // 1. DELETE existing scores untuk employee ini
        DB::table('aigenperformancemonitor.scores')
            ->where('employee_id', $employeeNip)
            ->delete();

        // 2. INSERT new scores ke tabel scores menggunakan sub_criteria inputs langsung
        if (!empty($subCriteriaInputs)) {
            $scoreData = [];
            foreach ($subCriteriaInputs as $inputName => $score) {
                // Extract sub_criteria_id from input name (format: sub_criteria_123)
                if (preg_match('/^sub_criteria_(\d+)$/', $inputName, $matches)) {
                    $subCriteriaId = (int)$matches[1];
                    
                    $scoreData[] = [
                        'employee_id' => $employeeNip,
                        'sub_criteria_id' => $subCriteriaId,
                        'score' => $score,
                        'evaluator_id' => $evaluatorId,
                        'evaluated_at' => now()
                    ];
                }
            }

            // Insert scores if any
            if (!empty($scoreData)) {
                DB::table('aigenperformancemonitor.scores')->insert($scoreData);
            }
        }

        // 3. UPDATE employee dengan Profile Matching scores dan total score
        DB::table('aigenperformancemonitor.employees')
            ->where('nip', $employeeNip)
            ->update([
                'profile_matching_scores' => $validated['profile_matching_scores'],
                'total_profile_score' => $totalScore, // Simpan total score di sini
                'status' => 'completed',
                'submission' => now()->format('Y-m-d')
            ]);

        DB::commit();

        return redirect()->route('scores.showProfileMatching', ['employeeNip' => $employeeNip])
            ->with('success', 'Profile Matching evaluation berhasil disimpan dengan total skor: ' . number_format($totalScore, 4));

    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()
            ->with('error', 'Gagal menyimpan Profile Matching: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Show Profile Matching detail for employee
     */
    public function showProfileMatching($employeeNip)
    {
        try {
            $employee = DB::table('aigenperformancemonitor.employees')
                ->leftJoin('aigenperformancemonitor.groups', 'employees.group_id', '=', 'groups.group_id')
                ->leftJoin('aigenperformancemonitor.roles', 'employees.role_id', '=', 'roles.id')
                ->leftJoin('aigenperformancemonitor.divisions', 'employees.division_id', '=', 'divisions.id')
                ->select(
                    'employees.*',
                    'groups.groupname as group_name',
                    'roles.name as role_name',
                    'divisions.name as division_name'
                )
                ->where('employees.nip', $employeeNip)
                ->first();

            if (!$employee) {
                return redirect()->back()->with('error', 'Employee tidak ditemukan.');
            }

            // Parse Profile Matching scores
            $profileData = null;
            if (!empty($employee->profile_matching_scores)) {
                try {
                    $profileData = json_decode($employee->profile_matching_scores, true);
                } catch (\Exception $e) {
                    $profileData = null;
                }
            }

            return view('profileMatchingDetail', compact('employee', 'profileData'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading Profile Matching detail: ' . $e->getMessage());
        }
    }

    /**
     * Show Profile Matching ranking
     */
    public function ranking()
    {
        try {
            $employees = DB::table('aigenperformancemonitor.employees')
                ->leftJoin('aigenperformancemonitor.groups', 'employees.group_id', '=', 'groups.group_id')
                ->leftJoin('aigenperformancemonitor.roles', 'employees.role_id', '=', 'roles.id')
                ->leftJoin('aigenperformancemonitor.divisions', 'employees.division_id', '=', 'divisions.id')
                ->select(
                    'employees.nip',
                    'employees.name',
                    'employees.email',
                    'employees.total_profile_score',
                    'employees.status',
                    'employees.submission',
                    'groups.groupname as group_name',
                    'roles.name as role_name',
                    'divisions.name as division_name'
                )
                ->whereNotNull('employees.total_profile_score')
                ->orderBy('employees.total_profile_score', 'desc')
                ->orderBy('employees.name', 'asc')
                ->get();

            // Add ranking number and performance category
            $rankedEmployees = $employees->map(function ($employee, $index) {
                $employee->rank = $index + 1;
                
                // Determine performance category
                $score = $employee->total_profile_score;
                if ($score >= 4.5) {
                    $employee->performance_category = 'Excellent';
                    $employee->category_class = 'success';
                } elseif ($score >= 4.0) {
                    $employee->performance_category = 'Very Good';
                    $employee->category_class = 'primary';
                } elseif ($score >= 3.5) {
                    $employee->performance_category = 'Good';
                    $employee->category_class = 'info';
                } elseif ($score >= 3.0) {
                    $employee->performance_category = 'Fair';
                    $employee->category_class = 'warning';
                } else {
                    $employee->performance_category = 'Needs Improvement';
                    $employee->category_class = 'danger';
                }
                
                return $employee;
            });

            // Calculate statistics
            $stats = [
                'total_evaluated' => $rankedEmployees->count(),
                'average_score' => $rankedEmployees->avg('total_profile_score'),
                'highest_score' => $rankedEmployees->max('total_profile_score'),
                'lowest_score' => $rankedEmployees->min('total_profile_score'),
                'excellent_count' => $rankedEmployees->where('total_profile_score', '>=', 4.5)->count(),
                'very_good_count' => $rankedEmployees->whereBetween('total_profile_score', [4.0, 4.49])->count(),
                'good_count' => $rankedEmployees->whereBetween('total_profile_score', [3.5, 3.99])->count(),
                'fair_count' => $rankedEmployees->whereBetween('total_profile_score', [3.0, 3.49])->count(),
                'needs_improvement_count' => $rankedEmployees->where('total_profile_score', '<', 3.0)->count(),
            ];

            return view('profileMatchingRanking', compact('rankedEmployees', 'stats'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading ranking: ' . $e->getMessage());
        }
    }

    /**
     * Profile Matching Dashboard
     */
    public function dashboard()
{
    try {
        // Overall statistics dari tabel employees
        $totalEmployees = DB::table('aigenperformancemonitor.employees')->count();
        $evaluatedEmployees = DB::table('aigenperformancemonitor.employees')
            ->whereNotNull('total_profile_score')->count();
        $pendingEvaluations = DB::table('aigenperformancemonitor.employees')
            ->where('status', 'pending')->count();
        
        $avgScore = DB::table('aigenperformancemonitor.employees')
            ->whereNotNull('total_profile_score')
            ->avg('total_profile_score');

        // Statistics by Division - ambil dari employees table dengan join
        $divisionStats = DB::table('aigenperformancemonitor.employees')
            ->leftJoin('aigenperformancemonitor.divisions', 'employees.division_id', '=', 'divisions.id')
            ->select(
                'divisions.name as division_name',
                DB::raw('COUNT(*) as total_employees'),
                DB::raw('COUNT(employees.total_profile_score) as evaluated_employees'),
                DB::raw('ROUND(AVG(employees.total_profile_score), 4) as avg_score'),
                DB::raw('MAX(employees.total_profile_score) as max_score'),
                DB::raw('MIN(employees.total_profile_score) as min_score')
            )
            ->groupBy('divisions.id', 'divisions.name')
            ->orderBy('avg_score', 'desc')
            ->get();

        // Top 10 Performers - ambil dari employees dengan total_profile_score tertinggi
        $topPerformers = DB::table('aigenperformancemonitor.employees')
            ->leftJoin('aigenperformancemonitor.roles', 'employees.role_id', '=', 'roles.id')
            ->leftJoin('aigenperformancemonitor.divisions', 'employees.division_id', '=', 'divisions.id')
            ->select(
                'employees.id',
                'employees.nip',
                'employees.name',
                'employees.total_profile_score',
                'roles.name as role_name',
                'divisions.name as division_name'
            )
            ->whereNotNull('employees.total_profile_score')
            ->orderBy('employees.total_profile_score', 'desc')
            ->limit(10)
            ->get();

        // Recent evaluations - ambil dari employees yang baru submit
        $recentEvaluations = DB::table('aigenperformancemonitor.employees')
            ->leftJoin('aigenperformancemonitor.divisions', 'employees.division_id', '=', 'divisions.id')
            ->select(
                'employees.nip',
                'employees.name',
                'employees.total_profile_score',
                'employees.submission',
                'divisions.name as division_name'
            )
            ->whereNotNull('employees.submission')
            ->whereNotNull('employees.total_profile_score')
            ->orderBy('employees.submission', 'desc')
            ->limit(10)
            ->get();

        // Performance distribution berdasarkan total_profile_score
        $performanceDistribution = [
            'excellent' => DB::table('aigenperformancemonitor.employees')
                ->where('total_profile_score', '>=', 4.5)->count(),
            'very_good' => DB::table('aigenperformancemonitor.employees')
                ->whereBetween('total_profile_score', [4.0, 4.49])->count(),
            'good' => DB::table('aigenperformancemonitor.employees')
                ->whereBetween('total_profile_score', [3.5, 3.99])->count(),
            'fair' => DB::table('aigenperformancemonitor.employees')
                ->whereBetween('total_profile_score', [3.0, 3.49])->count(),
            'needs_improvement' => DB::table('aigenperformancemonitor.employees')
                ->where('total_profile_score', '<', 3.0)->count(),
        ];

        // Untuk ranking table - ambil semua employees yang sudah ada total_profile_score
        $rankedEmployees = DB::table('aigenperformancemonitor.employees')
            ->leftJoin('aigenperformancemonitor.groups', 'employees.group_id', '=', 'groups.group_id')
            ->leftJoin('aigenperformancemonitor.roles', 'employees.role_id', '=', 'roles.id')
            ->leftJoin('aigenperformancemonitor.divisions', 'employees.division_id', '=', 'divisions.id')
            ->select(
                'employees.nip',
                'employees.name',
                'employees.email',
                'employees.total_profile_score',
                'employees.status',
                'employees.submission',
                'groups.groupname as group_name',
                'roles.name as role_name',
                'divisions.name as division_name'
            )
            ->whereNotNull('employees.total_profile_score')
            ->orderBy('employees.total_profile_score', 'desc')
            ->orderBy('employees.name', 'asc')
            ->get();

        // Add ranking number and performance category untuk ranked employees
        $rankedEmployees = $rankedEmployees->map(function ($employee, $index) {
            $employee->rank = $index + 1;
            
            // Determine performance category
            $score = $employee->total_profile_score;
            if ($score >= 4.5) {
                $employee->performance_category = 'Excellent';
                $employee->category_class = 'excellent';
            } elseif ($score >= 4.0) {
                $employee->performance_category = 'Very Good';
                $employee->category_class = 'very-good';
            } elseif ($score >= 3.5) {
                $employee->performance_category = 'Good';
                $employee->category_class = 'good';
            } elseif ($score >= 3.0) {
                $employee->performance_category = 'Fair';
                $employee->category_class = 'fair';
            } else {
                $employee->performance_category = 'Needs Improvement';
                $employee->category_class = 'needs-improvement';
            }
            
            return $employee;
        });

        $dashboardData = [
            'overview' => [
                'total_employees' => $totalEmployees,
                'evaluated_employees' => $evaluatedEmployees,
                'pending_evaluations' => $pendingEvaluations,
                'completion_rate' => $totalEmployees > 0 ? round(($evaluatedEmployees / $totalEmployees) * 100, 1) : 0,
                'average_score' => round($avgScore ?? 0, 4)
            ],
            'division_stats' => $divisionStats,
            'top_performers' => $topPerformers,
            'recent_evaluations' => $recentEvaluations,
            'performance_distribution' => $performanceDistribution
        ];

        return view('dashboard', compact('dashboardData', 'rankedEmployees'));

    } catch (\Exception $e) {
        dd('Error: ' . $e->getMessage()); // Debug error
    }
}

    /**
     * Calculate Profile Matching score (utility method)
     */
    private function calculateProfileMatchingScore($ratings)
    {
        // GAP to Weight conversion table
        $gapWeightTable = [
            0 => 5,
            1 => 4.5, -1 => 4.5,
            2 => 4, -2 => 4,
            3 => 3.5, -3 => 3.5,
            4 => 3, -4 => 3
        ];

        // Target value (ideal profile)
        $target = 5;

        // Criteria weights
        $criteriaWeights = [
            'teamwork' => 0.10,      // 10%
            'integrity' => 0.10,     // 10%
            'initiative' => 0.10,    // 10%
            'professional' => 0.30,  // 30%
            'contribution' => 0.40   // 40%
        ];

        $scores = [];

        // Calculate for each criteria
        foreach (['teamwork', 'integrity', 'initiative', 'professional', 'contribution'] as $criteria) {
            $subCriteriaSum = 0;
            $subCriteriaCount = 4; // Each criteria has 4 sub-criteria

            for ($i = 1; $i <= 4; $i++) {
                $actualValue = $ratings["{$criteria}_{$i}"] ?? 5;
                
                // Calculate GAP
                $gap = $actualValue - $target;
                
                // Convert GAP to weight
                $weight = $gapWeightTable[$gap] ?? 3; // Default to 3 if gap > 4
                
                $subCriteriaSum += $weight;
            }

            // Calculate average and multiply by criteria weight
            $averageWeight = $subCriteriaSum / $subCriteriaCount;
            $criteriaScore = $averageWeight * $criteriaWeights[$criteria];
            $scores[$criteria] = $criteriaScore;
        }

        // Calculate total score
        $totalScore = array_sum($scores);

        return [
            'scores' => $scores,
            'total' => $totalScore,
            'timestamp' => now()->toISOString()
        ];
    }
}