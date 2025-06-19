@extends('layouts.mainApp')
@if (!session()->has('user'))
    <script>
        window.location.href = '/login';
    </script>
@endif

@section('content')
    <style>
        /* CRITICAL: Remove all Bootstrap default styling */
        * {
            box-sizing: border-box;
        }

        // DEBUG HELPER: Function untuk troubleshooting
        function debugProfileMatching() {
            console.log('\n=== DEBUG PROFILE MATCHING ===');
            
            if (!criteriaStructure) {
                console.log('❌ No criteria structure found');
                return;
            }
            
            console.log('✅ Criteria structure loaded:', Object.keys(criteriaStructure).length, 'criteria');
            
            Object.values(criteriaStructure).forEach((criteria, index) => {
                console.log(`\nCriteria ${index + 1}:`);
                console.log('  Name:', criteria.name);
                console.log('  ID:', criteria.id);
                console.log('  Sub-criteria count:', criteria.sub_criteria ? criteria.sub_criteria.length : 0);
                
                if (criteria.sub_criteria) {
                    criteria.sub_criteria.forEach((sub, subIndex) => {
                        const input = document.querySelector(`input[name="sub_criteria_${sub.id}"]`);
                        console.log(`    Sub ${subIndex + 1}: ID=${sub.id}, Value=${input ? input.value : 'NOT_FOUND'}`);
                    });

        /*
        ========================================
        🔍 DEBUGGING INSTRUCTIONS
        ========================================
        
        Jika total score masih 3.5 saat semua input = 5:
        
        1. Buka Developer Console (F12)
        2. Uncomment baris debugProfileMatching() di atas  
        3. Refresh halaman
        4. Lihat output console untuk:
           - Nama kriteria yang terbaca
           - Mapping bobot yang digunakan
           - Nilai input yang terbaca
        
        Expected hasil jika benar:
        - Teamwork: 5 × 0.10 = 0.5000
        - Integritas Kerja: 5 × 0.10 = 0.5000  
        - Inisiatif: 5 × 0.10 = 0.5000
        - Professional Responsible: 5 × 0.30 = 1.5000
        - Kontribusi: 5 × 0.40 = 2.0000
        - TOTAL: 5.0000
        
        Jika hasilnya beda, cek:
        - Nama kriteria di database vs nama di JavaScript
        - Apakah semua input fields terbaca dengan benar
        - Apakah ada kriteria yang di-skip karena tidak ada mapping
        ========================================
        */
                }
            });
            
            // Test perhitungan manual
            console.log('\n=== MANUAL CALCULATION TEST ===');
            const testWeights = {
                'Teamwork': 0.10,
                'Integritas Kerja': 0.10,  
                'Inisiatif': 0.10,
                'Professional Responsible': 0.30,
                'Kontribusi': 0.40
            };
            
            console.log('Expected weights:', testWeights);
            console.log('Total expected weight:', Object.values(testWeights).reduce((a,b) => a+b, 0));
            
            // Hitung manual jika semua input = 5
            let manualTotal = 0;
            Object.entries(testWeights).forEach(([name, weight]) => {
                const score = 5 * weight; // GAP=0, weight=5
                manualTotal += score;
                console.log(`${name}: 5 × ${weight} = ${score.toFixed(4)}`);
            });
            console.log('Manual total (all inputs = 5):', manualTotal.toFixed(4));
        }

        // Jalankan debug saat halaman dimuat (hanya di development)
        document.addEventListener('DOMContentLoaded', function() {
            // Uncomment baris ini untuk debugging
            // debugProfileMatching();
        });

        /* Remove Bootstrap form defaults */
        .form-control,
        .form-select,
        .btn {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* Page Container */
        .container {
            max-width: 1000px !important;
            margin: 0 auto !important;
            padding: 0 !important;
        }

        /* Main Form Container - Glassmorphism */
        .evaluation-form-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            margin-bottom: 2rem;
        }

        .evaluation-form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        }

        /* Page Title */
        .page-title {
            color: rgba(255, 255, 255, 0.95);
            font-size: 2rem;
            font-weight: 600;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            margin-bottom: 2rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Breadcrumb */
        .breadcrumb {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            padding: 0;
        }

        .breadcrumb a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .breadcrumb a:hover {
            color: rgba(255, 255, 255, 1);
        }

        /* Employee Info Card */
        .employee-info {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
        }

        .employee-info h3 {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .employee-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .employee-detail-item {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        .employee-detail-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.3rem;
        }

        .employee-detail-value {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }

        /* Content Wrapper */
        .content-wrapper {
            position: relative;
            z-index: 1;
            color: white;
        }

        /* Profile Matching Section */
        .profile-matching-section {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 2rem;
            margin-top: 2rem;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
        }

        .section-title {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .criteria-container {
            margin-bottom: 1.5rem;
        }

        .criteria-header {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 15px;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .criteria-header:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .criteria-title {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .criteria-weight {
            background: rgba(79, 172, 254, 0.3);
            color: rgba(255, 255, 255, 0.9);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .sub-criteria-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 1rem;
        }

        .sub-criteria-item {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1rem;
            align-items: center;
            padding: 0.8rem;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sub-criteria-text {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        /* Radio Button Styling untuk Rating */
        .rating-container {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            justify-content: center;
        }

        .rating-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.2rem;
        }

        .rating-radio {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .rating-radio:checked {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border-color: rgba(79, 172, 254, 0.8);
            box-shadow: 0 0 10px rgba(79, 172, 254, 0.5);
        }

        .rating-radio:checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }

        .rating-radio:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(79, 172, 254, 0.6);
        }

        .rating-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.75rem;
            font-weight: 500;
            user-select: none;
            cursor: pointer;
        }

        .rating-option:hover .rating-label {
            color: rgba(255, 255, 255, 1);
        }

        /* Profile Matching Results */
        .profile-results {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .result-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 15px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
        }

        .result-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .result-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .result-value {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.2rem;
            font-weight: 600;
        }

        .total-score {
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.3), rgba(0, 242, 254, 0.3));
            border: 1px solid rgba(79, 172, 254, 0.5);
            text-align: center;
            padding: 1.5rem;
            border-radius: 20px;
            margin-top: 1rem;
        }

        .total-score .result-label {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .total-score .result-value {
            font-size: 2rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 1);
        }

        /* Sticky Current Criteria */
        .sticky-criteria {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem;
            z-index: 1000;
            transform: translateY(-100%);
            transition: all 0.3s ease;
        }

        .sticky-criteria.show {
            transform: translateY(0);
        }

        .sticky-criteria-content {
            max-width: 1000px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: rgba(255, 255, 255, 0.9);
        }

        .sticky-criteria-title {
            font-size: 1rem;
            font-weight: 600;
        }

        .sticky-criteria-progress {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Alert Styling */
        .alert {
            background: rgba(220, 53, 69, 0.15) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(220, 53, 69, 0.3) !important;
            border-radius: 15px !important;
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 1rem 1.5rem !important;
            margin-bottom: 2rem;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.15) !important;
            border: 1px solid rgba(40, 167, 69, 0.3) !important;
        }

        /* Button Container */
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Primary Button */
        .btn-primary {
            background: linear-gradient(135deg, #4facfe, #00f2fe) !important;
            border: none !important;
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 0.75rem 2rem !important;
            border-radius: 15px !important;
            font-weight: 600 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3) !important;
            text-decoration: none !important;
            font-size: 0.95rem !important;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #00f2fe, #4facfe) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4) !important;
            color: rgba(255, 255, 255, 1) !important;
        }

        /* Secondary Button */
        .btn-secondary {
            background: rgba(108, 117, 125, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 0.75rem 2rem !important;
            border-radius: 15px !important;
            font-weight: 500 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            transition: all 0.3s ease !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
            text-decoration: none !important;
            font-size: 0.95rem !important;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: rgba(108, 117, 125, 1) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3) !important;
            color: rgba(255, 255, 255, 1) !important;
        }

        /* Navigation Links */
        .nav-links {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .nav-link {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            padding: 0.5rem 1rem;
            border-radius: 12px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 1);
            transform: translateY(-1px);
        }

        .nav-link.active {
            background: rgba(79, 172, 254, 0.3);
            color: rgba(255, 255, 255, 1);
            border-color: rgba(79, 172, 254, 0.5);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .evaluation-form-container {
                padding: 1.5rem;
                border-radius: 20px;
                margin: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .employee-details {
                grid-template-columns: 1fr;
            }

            .sub-criteria-item {
                grid-template-columns: 1fr;
                gap: 0.8rem;
                text-align: center;
            }

            .results-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
                gap: 0.8rem;
            }

            .btn-primary,
            .btn-secondary {
                justify-content: center;
                width: 100%;
            }

            .nav-links {
                flex-direction: column;
                align-items: stretch;
            }

            .sticky-criteria-content {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }

            .sticky-criteria {
                padding: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .evaluation-form-container {
                padding: 1rem;
                margin: 0.5rem;
            }

            .page-title {
                font-size: 1.3rem;
            }

            .profile-matching-section {
                padding: 1.5rem;
            }

            .sticky-criteria-title {
                font-size: 0.9rem;
            }

            .sticky-criteria-progress {
                font-size: 0.8rem;
            }
        }

        /* Animation */
        .evaluation-form-container {
            opacity: 0;
            transform: translateY(20px);
            animation: slideInUp 0.6s ease forwards;
        }

        @keyframes slideInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="content-wrapper">
        <!-- Sticky Current Criteria -->
        <div class="sticky-criteria" id="sticky-criteria">
            <div class="sticky-criteria-content">
                <div class="sticky-criteria-title" id="sticky-title">Current Criteria</div>
                <div class="sticky-criteria-progress" id="sticky-progress">1 of 5</div>
            </div>
        </div>

        <div class="breadcrumb">
            <a href="/employees">Employees</a> / 
            <a href="{{ route('scores.createProfileMatching', ['employeeNip' => $employee->nip]) }}">Perfomance Review</a>
        </div>

        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="{{ route('scores.createProfileMatching', ['employeeNip' => $employee->nip]) }}" class="nav-link active">
                <i class="fas fa-chart-line"></i> Profile Matching
            </a>
            @if($employee->total_profile_score)
                <a href="{{ route('scores.showProfileMatching', ['employeeNip' => $employee->nip]) }}" class="nav-link">
                    <i class="fas fa-eye"></i> View Results
                </a>
            @endif
            <a href="{{ route('scores.ranking') }}" class="nav-link">
                <i class="fas fa-trophy"></i> Ranking
            </a>
            <a href="{{ route('scores.dashboard') }}" class="nav-link">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </div>

        <div class="container">
            <div class="evaluation-form-container">
                <h2 class="page-title">
                    <i class="fas fa-chart-line"></i>
                    Perfomance Review
                </h2>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Employee Information -->
                <div class="employee-info">
                    <h3>
                        <i class="fas fa-user"></i>
                        Employee Information
                    </h3>
                    <div class="employee-details">
                        <div class="employee-detail-item">
                            <div class="employee-detail-label">NIP</div>
                            <div class="employee-detail-value">{{ $employee->nip }}</div>
                        </div>
                        <div class="employee-detail-item">
                            <div class="employee-detail-label">Name</div>
                            <div class="employee-detail-value">{{ $employee->name }}</div>
                        </div>
                        <div class="employee-detail-item">
                            <div class="employee-detail-label">Role</div>
                            <div class="employee-detail-value">{{ $employee->role_name ?? 'N/A' }}</div>
                        </div>
                        <div class="employee-detail-item">
                            <div class="employee-detail-label">Division</div>
                            <div class="employee-detail-value">{{ $employee->division_name ?? 'N/A' }}</div>
                        </div>
                        <div class="employee-detail-item">
                            <div class="employee-detail-label">Group</div>
                            <div class="employee-detail-value">{{ $employee->group_name ?? 'N/A' }}</div>
                        </div>
                        @if($employee->total_profile_score)
                            <div class="employee-detail-item">
                                <div class="employee-detail-label">Current Score</div>
                                <div class="employee-detail-value">{{ number_format($employee->total_profile_score, 4) }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('scores.storeProfileMatching') }}" id="profileMatchingForm">
                    @csrf
                    <input type="hidden" name="employee_nip" value="{{ $employee->nip }}">

                    <!-- Profile Matching Results - DIPINDAH KE ATAS -->
                    <div class="profile-results">
                        <h3 class="section-title">
                            <i class="fas fa-calculator"></i> Score Calculation
                        </h3>
                        
                        <div class="results-grid" id="results-grid">
                            @if(!empty($groupedCriteria))
                                @foreach($groupedCriteria as $criteriaId => $criteria)
                                    @if(isset($criteria['name']) && isset($criteria['id']))
                                        @php
                                            // PERBAIKAN: Define specific weights untuk semua role (konsisten)
                                            $criteriaWeights = [
                                                'Teamwork' => 10,
                                                'Integritas Kerja' => 10,
                                                'Inisiatif' => 10,  // DIPERBAIKI: dari 20 ke 10
                                                'Professional Responsible' => 30,
                                                'Kontribusi' => 40
                                            ];
                                            // Trim whitespace untuk mapping yang tepat
                                            $criteriaName = trim($criteria['name'] ?? '');
                                            $weight = $criteriaWeights[$criteriaName] ?? 10; // Default 10% if not found
                                            
                                            // Debug: Log jika tidak ditemukan mapping
                                            if (!isset($criteriaWeights[$criteriaName])) {
                                                error_log("Warning: No weight mapping found for criteria: '$criteriaName'");
                                                error_log("Available weights: " . implode(', ', array_keys($criteriaWeights)));
                                            }
                                        @endphp
                                        <div class="result-item">
                                            <div class="result-label">{{ $criteria['name'] }} ({{ $weight }}%)</div>
                                            <div class="result-value" id="criteria-score-{{ $criteria['id'] }}">0.0000</div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="result-item">
                                    <div class="result-label">No Criteria</div>
                                    <div class="result-value">0.0000</div>
                                </div>
                            @endif
                        </div>

                        <div class="total-score">
                            <div class="result-label">Total Skor Akhir</div>
                            <div class="result-value" id="total-score">0.0000</div>
                        </div>

                        <!-- Hidden field to store profile matching scores -->
                        <input type="hidden" name="profile_matching_scores" id="profile-matching-data">
                    </div>

                    <!-- Profile Matching Section -->
                    <div class="profile-matching-section">
                        <h3 class="section-title">
                            <i class="fas fa-chart-line"></i> Criteria 
                        </h3>

                        @if(empty($groupedCriteria))
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Tidak ada kriteria yang ditemukan untuk role ini. Silakan hubungi administrator untuk mengatur kriteria evaluasi.
                            </div>
                        @else
                            @foreach($groupedCriteria as $criteriaId => $criteria)
                                @php
                                    // PERBAIKAN: Define specific weights untuk semua role - KONSISTEN DENGAN JAVASCRIPT
                                    $criteriaWeights = [
                                        'Teamwork' => 10,
                                        'Integritas Kerja' => 10,
                                        'Inisiatif' => 10,  // DIPERBAIKI: dari 20 ke 10
                                        'Professional Responsible' => 30,
                                        'Kontribusi' => 40
                                    ];
                                    // Trim whitespace untuk mapping yang tepat
                                    $criteriaName = trim($criteria['name'] ?? '');
                                    $weight = $criteriaWeights[$criteriaName] ?? 10; // Default 10% if not found
                                    
                                    // Debug: Log jika tidak ditemukan mapping
                                    if (!isset($criteriaWeights[$criteriaName])) {
                                        error_log("Warning: No weight mapping found for criteria: '$criteriaName'");
                                        error_log("Available weights: " . implode(', ', array_keys($criteriaWeights)));
                                    }
                                @endphp
                                <!-- Dynamic Criteria from Database -->
                                <div class="criteria-container" data-criteria-id="{{ $criteria['id'] ?? $criteriaId }}" data-criteria-name="{{ $criteria['name'] ?? 'Unknown Criteria' }}">
                                    <div class="criteria-header">
                                        <h4 class="criteria-title">
                                            <span><i class="fas fa-star"></i> {{ $criteria['name'] ?? 'Unknown Criteria' }}</span>
                                            <span class="criteria-weight">{{ $weight }}%</span>
                                        </h4>
                                    </div>
                                    
                                    @if(empty($criteria['sub_criteria']) || !is_array($criteria['sub_criteria']))
                                        <div class="sub-criteria-grid">
                                            <div class="sub-criteria-item">
                                                <span class="sub-criteria-text">Tidak ada sub-kriteria untuk {{ $criteria['name'] ?? 'kriteria ini' }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="sub-criteria-grid">
                                            @foreach($criteria['sub_criteria'] as $subIndex => $subCriteria)
                                                @if(isset($subCriteria['id']) && isset($subCriteria['description']) && !empty($subCriteria['id']))
                                                    <div class="sub-criteria-item">
                                                        <span class="sub-criteria-text">{{ $subCriteria['description'] }}</span>
                                                        <input type="number" 
                                                               class="rating-input" 
                                                               name="sub_criteria_{{ $subCriteria['id'] }}" 
                                                               min="1" 
                                                               max="5" 
                                                               value="{{ old('sub_criteria_' . $subCriteria['id'], $existingScores['sub_criteria_' . $subCriteria['id']] ?? 5) }}" 
                                                               onchange="calculateProfileMatching()" 
                                                               data-criteria-id="{{ $criteria['id'] ?? $criteriaId }}"
                                                               data-sub-criteria-id="{{ $subCriteria['id'] }}"
                                                               required>
                                                    </div>
                                                @else
                                                    <!-- Skip invalid sub-criteria -->
                                                    @continue
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.history.back()">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </button>
                        @if(!empty($groupedCriteria))
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i>
                                Simpan Profile Matching
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Criteria structure from PHP (untuk JavaScript)
        const criteriaStructure = @json($groupedCriteria ?? []);
        
        document.addEventListener('DOMContentLoaded', function () {
            // Check if criteria structure is valid
            if (!criteriaStructure || Object.keys(criteriaStructure).length === 0) {
                console.warn('No criteria structure found');
                return;
            }
            
            // Initial calculation
            calculateProfileMatching();
            
            // Setup sticky header scroll listener
            setupStickyHeader();
        });

        function setupStickyHeader() {
            const stickyElement = document.getElementById('sticky-criteria');
            const criteriaContainers = document.querySelectorAll('.criteria-container');
            
            if (!stickyElement || criteriaContainers.length === 0) {
                return;
            }

            function updateStickyHeader() {
                const scrollPosition = window.scrollY + 100; // offset for better detection
                let currentCriteria = null;
                let currentIndex = 0;

                criteriaContainers.forEach((container, index) => {
                    const rect = container.getBoundingClientRect();
                    const containerTop = rect.top + window.scrollY;
                    const containerBottom = containerTop + rect.height;

                    if (scrollPosition >= containerTop && scrollPosition <= containerBottom) {
                        currentCriteria = {
                            name: container.dataset.criteriaName,
                            index: index + 1,
                            total: criteriaContainers.length
                        };
                        currentIndex = index;
                    }
                });

                // Show/hide sticky header
                if (currentCriteria && scrollPosition > 300) { // Show after scrolling past employee info
                    stickyElement.classList.add('show');
                    document.getElementById('sticky-title').textContent = currentCriteria.name;
                    document.getElementById('sticky-progress').textContent = `${currentCriteria.index} of ${currentCriteria.total}`;
                } else {
                    stickyElement.classList.remove('show');
                }
            }

            // Throttled scroll listener for better performance
            let ticking = false;
            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(updateStickyHeader);
                    ticking = true;
                    setTimeout(() => { ticking = false; }, 10);
                }
            }

            window.addEventListener('scroll', requestTick);
        }

        // PERBAIKAN FINAL: Function calculateProfileMatching dengan algoritma yang 100% benar
        function calculateProfileMatching() {
            // Check if criteria structure exists
            if (!criteriaStructure || Object.keys(criteriaStructure).length === 0) {
                console.warn('Cannot calculate - no criteria structure');
                return {scores: {}, total: 0};
            }

            // PERBAIKAN: Gunakan persentase bobot yang TEPAT sesuai spesifikasi
            const criteriaWeights = {
                'Teamwork': 0.10,                      // 10%
                'Integritas Kerja': 0.10,             // 10%
                'Inisiatif': 0.10,                    // 10%
                'Professional Responsible': 0.30,     // 30%
                'Kontribusi': 0.40                    // 40%
                // Total = 100%
            };

            // FALLBACK mapping untuk mengatasi variasi nama kriteria
            const criteriaNameMapping = {
                'teamwork': 'Teamwork',
                'integritas kerja': 'Integritas Kerja',
                'integritas': 'Integritas Kerja',
                'inisiatif': 'Inisiatif',
                'professional responsible': 'Professional Responsible',
                'profesional responsible': 'Professional Responsible',
                'professional': 'Professional Responsible',
                'kontribusi': 'Kontribusi',
                'contribution': 'Kontribusi'
            };

            // Function untuk mencari bobot dengan fallback
            function getCriteriaWeight(criteriaName) {
                // Coba exact match dulu
                if (criteriaWeights[criteriaName]) {
                    return criteriaWeights[criteriaName];
                }
                
                // Coba dengan lowercase mapping
                const lowerName = criteriaName.toLowerCase().trim();
                const mappedName = criteriaNameMapping[lowerName];
                if (mappedName && criteriaWeights[mappedName]) {
                    console.log(`Mapped '${criteriaName}' to '${mappedName}'`);
                    return criteriaWeights[mappedName];
                }
                
                // Coba partial match
                for (const [key, weight] of Object.entries(criteriaWeights)) {
                    if (key.toLowerCase().includes(lowerName) || lowerName.includes(key.toLowerCase())) {
                        console.log(`Partial match: '${criteriaName}' matched to '${key}'`);
                        return weight;
                    }
                }
                
                console.warn(`No weight found for criteria: '${criteriaName}'`);
                console.warn('Available criteria:', Object.keys(criteriaWeights));
                return 0; // Return 0 if no match found
            }

            // GAP to Weight conversion table (standar Profile Matching)
            const gapWeightTable = {
                0: 5,               // Perfect match
                1: 4.5, [-1]: 4.5,  // GAP ±1
                2: 4, [-2]: 4,      // GAP ±2  
                3: 3.5, [-3]: 3.5,  // GAP ±3
                4: 3, [-4]: 3       // GAP ±4 or more
            };

            // Target value (ideal profile)
            const target = 5;

            let criteriaScores = {};
            let allInputs = {};
            let totalFinalScore = 0;

            console.log('=== STARTING PROFILE MATCHING CALCULATION ===');
            console.log('Criteria Weights:', criteriaWeights);

            // Calculate for each criteria
            Object.values(criteriaStructure).forEach((criteria, index) => {
                // Safe check for criteria structure
                if (!criteria || typeof criteria !== 'object' || !criteria.name) {
                    console.warn('Invalid criteria at index:', index);
                    return;
                }

                if (!criteria.sub_criteria || !Array.isArray(criteria.sub_criteria) || criteria.sub_criteria.length === 0) {
                    console.warn('No sub_criteria for criteria:', criteria.name);
                    return;
                }

                // PERBAIKAN: Gunakan nama kriteria yang tepat untuk mapping bobot
                const criteriaName = criteria.name ? criteria.name.trim() : '';
                const criteriaWeight = getCriteriaWeight(criteriaName);
                
                if (criteriaWeight === 0) {
                    console.warn(`Skipping criteria '${criteriaName}' - no weight mapping found`);
                    return;
                }

                console.log(`\n--- Processing ${criteriaName} (Weight: ${criteriaWeight * 100}%) ---`);

                let subCriteriaWeightSum = 0;
                let subCriteriaCount = 0;

                // Process each sub-criteria
                criteria.sub_criteria.forEach((subCriteria, subIndex) => {
                    if (!subCriteria || !subCriteria.id) {
                        console.warn('Invalid sub-criteria at index:', subIndex);
                        return;
                    }

                    const inputElement = document.querySelector(`input[name="sub_criteria_${subCriteria.id}"]`);
                    if (!inputElement) {
                        console.warn('Input element not found for sub_criteria_' + subCriteria.id);
                        return;
                    }

                    const actualValue = parseFloat(inputElement.value) || 5;
                    
                    // Store individual input for JSON
                    allInputs[`sub_criteria_${subCriteria.id}`] = actualValue;
                    
                    // Calculate GAP
                    const gap = actualValue - target;
                    
                    // Convert GAP to weight using lookup table
                    const weight = gapWeightTable[gap] !== undefined ? gapWeightTable[gap] : 3;
                    
                    subCriteriaWeightSum += weight;
                    subCriteriaCount++;

                    console.log(`  Sub-criteria ${subCriteria.id}: Value=${actualValue}, GAP=${gap}, Weight=${weight}`);
                });

                if (subCriteriaCount === 0) {
                    console.warn('No valid sub-criteria processed for criteria:', criteriaName);
                    return;
                }

                // Calculate average weight for this criteria
                const averageWeight = subCriteriaWeightSum / subCriteriaCount;
                
                // Calculate final score for this criteria (average × percentage weight)
                const criteriaFinalScore = averageWeight * criteriaWeight;
                
                // Store the score
                const criteriaKey = criteriaName;
                criteriaScores[criteriaKey] = criteriaFinalScore;
                
                // Add to total final score
                totalFinalScore += criteriaFinalScore;

                console.log(`  Average Weight: ${averageWeight.toFixed(4)}`);
                console.log(`  Criteria Score: ${averageWeight.toFixed(4)} × ${criteriaWeight} = ${criteriaFinalScore.toFixed(4)}`);

                // Update display
                const displayElement = document.getElementById(`criteria-score-${criteria.id}`);
                if (displayElement) {
                    displayElement.textContent = criteriaFinalScore.toFixed(4);
                } else {
                    console.warn('Display element not found for criteria-score-' + criteria.id);
                }
            });

            console.log('\n=== FINAL RESULTS ===');
            console.log('Individual Criteria Scores:', criteriaScores);
            console.log('Total Final Score:', totalFinalScore.toFixed(4));

            // Update total score display
            const totalScoreElement = document.getElementById('total-score');
            if (totalScoreElement) {
                totalScoreElement.textContent = totalFinalScore.toFixed(4);
            }

            // Store the data in hidden field for form submission
            const profileData = {
                scores: criteriaScores,
                total: totalFinalScore,
                timestamp: new Date().toISOString(),
                individual_scores: allInputs,
                criteria_structure: criteriaStructure,
                weights_used: criteriaWeights,
                calculation_details: {
                    target: target,
                    gap_weight_table: gapWeightTable
                }
            };
            
            const hiddenField = document.getElementById('profile-matching-data');
            if (hiddenField) {
                hiddenField.value = JSON.stringify(profileData);
            }

            return {
                scores: criteriaScores,
                total: totalFinalScore
            };
        }

        // Add event listeners to all rating inputs
        document.querySelectorAll('.rating-input').forEach(input => {
            input.addEventListener('change', calculateProfileMatching);
            input.addEventListener('input', calculateProfileMatching);
        });

        // Form validation before submit
        const form = document.getElementById('profileMatchingForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                let allValid = true;
                let invalidInputs = [];

                document.querySelectorAll('.rating-input').forEach(input => {
                    const value = parseInt(input.value);
                    if (!value || value < 1 || value > 5) {
                        allValid = false;
                        invalidInputs.push(input.name);
                        input.style.borderColor = 'rgba(220, 53, 69, 0.5)';
                    } else {
                        input.style.borderColor = 'rgba(255, 255, 255, 0.3)';
                    }
                });

                if (!allValid) {
                    e.preventDefault();
                    alert('Mohon isi semua rating dengan nilai 1-5!\n\nInput yang belum valid: ' + invalidInputs.join(', '));
                    return false;
                }

                // Check if criteria structure exists
                if (!criteriaStructure || Object.keys(criteriaStructure).length === 0) {
                    e.preventDefault();
                    alert('Tidak ada kriteria yang tersedia untuk evaluasi. Silakan hubungi administrator.');
                    return false;
                }

                // Final calculation before submit
                const result = calculateProfileMatching();
                if (!result || result.total === 0) {
                    e.preventDefault();
                    alert('Gagal menghitung Profile Matching score. Silakan coba lagi.');
                    return false;
                }
                
                return true;
            });
        }
    </script>
@endsection