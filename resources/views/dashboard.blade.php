@extends('layouts.mainApp')

@if (!session()->has('user'))
    <script>window.location.href = '/login';</script>
@endif

@section('content')
<style>
    /* CRITICAL: Remove all Bootstrap default styling */
    * {
        box-sizing: border-box;
    }

    /* Remove Bootstrap form defaults */
    .form-control,
    .form-select,
    .btn {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }

    /* Existing Welcome Card */
    .welcome-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 25px;
        padding: 2.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        margin-bottom: 2rem;
        color: rgba(255, 255, 255, 0.95);
        position: relative;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        padding: 1.5rem;
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #4facfe, #00f2fe);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        color: rgba(255, 255, 255, 0.95);
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-subtitle {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.8rem;
    }

    /* Section Cards */
    .section-card {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
    }

    .section-title {
        color: rgba(255, 255, 255, 0.95);
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        padding-bottom: 1rem;
    }

    /* Top Performers Grid */
    .top-performers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
    }

    .performer-card {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
    }

    .performer-card:hover {
        background: rgba(255, 255, 255, 0.12);
        transform: translateY(-2px);
    }

    .performer-rank {
        position: absolute;
        top: -10px;
        left: 20px;
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .performer-rank.top-3 {
        background: linear-gradient(135deg, #ffd700, #ffed4a);
        color: #333;
    }

    .performer-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .performer-details h4 {
        color: rgba(255, 255, 255, 0.95);
        margin: 0 0 0.3rem 0;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .performer-details p {
        color: rgba(255, 255, 255, 0.7);
        margin: 0;
        font-size: 0.9rem;
    }

    .performer-score {
        text-align: right;
    }

    .score-value {
        color: rgba(255, 255, 255, 0.95);
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
    }

    .score-category {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.8rem;
        margin: 0;
    }

    /* Performance Distribution */
    .distribution-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .distribution-item {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 15px;
        padding: 1rem;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .distribution-count {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .distribution-label {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .excellent { color: #28a745; }
    .very-good { color: #007bff; }
    .good { color: #17a2b8; }
    .fair { color: #ffc107; }
    .needs-improvement { color: #dc3545; }

    /* Division Stats Table */
    .division-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .division-table th,
    .division-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .division-table th {
        color: rgba(255, 255, 255, 0.8);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .division-table td {
        color: rgba(255, 255, 255, 0.9);
    }

    .division-table tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    /* Ranking Table */
    .ranking-table-container {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 15px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .table-filters {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-label {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        font-weight: 500;
    }

    .filter-input,
    .filter-select {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 10px !important;
        padding: 0.5rem 1rem !important;
        color: rgba(255, 255, 255, 0.9) !important;
        font-size: 0.9rem;
    }

    .filter-input:focus,
    .filter-select:focus {
        background: rgba(255, 255, 255, 0.15) !important;
        border-color: rgba(79, 172, 254, 0.5) !important;
        outline: none !important;
    }

    .ranking-table {
        width: 100%;
        border-collapse: collapse;
    }

    .ranking-table th,
    .ranking-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .ranking-table th {
        background: rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.8);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 10;
        cursor: pointer;
    }

    .ranking-table th:hover {
        background: rgba(255, 255, 255, 0.08);
    }

    .ranking-table td {
        color: rgba(255, 255, 255, 0.9);
    }

    .ranking-table tr:hover {
        background: rgba(255, 255, 255, 0.03);
    }

    .rank-badge {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .rank-badge.top-3 {
        background: linear-gradient(135deg, #ffd700, #ffed4a);
        color: #333;
    }

    .performance-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-excellent {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }

    .badge-very-good {
        background: rgba(0, 123, 255, 0.2);
        color: #007bff;
        border: 1px solid rgba(0, 123, 255, 0.3);
    }

    .badge-good {
        background: rgba(23, 162, 184, 0.2);
        color: #17a2b8;
        border: 1px solid rgba(23, 162, 184, 0.3);
    }

    .badge-fair {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .badge-needs-improvement {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.3);
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

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, #4facfe, #00f2fe) !important;
        border: none !important;
        color: rgba(255, 255, 255, 0.9) !important;
        padding: 0.6rem 1.5rem !important;
        border-radius: 12px !important;
        font-weight: 600 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3) !important;
        text-decoration: none !important;
        font-size: 0.9rem !important;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #00f2fe, #4facfe) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4) !important;
        color: rgba(255, 255, 255, 1) !important;
    }

    .btn-outline {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: rgba(255, 255, 255, 0.9) !important;
        padding: 0.6rem 1.5rem !important;
        border-radius: 12px !important;
        font-weight: 500 !important;
        transition: all 0.3s ease !important;
        text-decoration: none !important;
        font-size: 0.9rem !important;
        cursor: pointer;
    }

    .btn-outline:hover {
        background: rgba(255, 255, 255, 0.2) !important;
        color: rgba(255, 255, 255, 1) !important;
    }

    /* Chart Container */
    .chart-container {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 1rem;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .chart-placeholder {
        color: rgba(255, 255, 255, 0.6);
        text-align: center;
    }

    /* Export Actions */
    .export-actions {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    /* Quick Actions Grid */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .quick-action-card {
        background: rgba(255, 255, 255, 0.08);
        padding: 1.5rem;
        border-radius: 15px;
        text-decoration: none;
        color: white;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .quick-action-card:hover {
        background: rgba(255, 255, 255, 0.12);
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }

    .quick-action-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
        display: block;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .welcome-card {
            padding: 2rem;
            border-radius: 20px;
        }

        .section-card {
            padding: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .top-performers-grid {
            grid-template-columns: 1fr;
        }

        .distribution-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .table-filters {
            grid-template-columns: 1fr;
        }
        
        .nav-links {
            flex-direction: column;
            align-items: stretch;
        }

        .ranking-table,
        .division-table {
            font-size: 0.8rem;
        }

        .ranking-table th,
        .ranking-table td,
        .division-table th,
        .division-table td {
            padding: 0.5rem;
        }

        .export-actions {
            justify-content: center;
        }

        .quick-actions-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .welcome-card {
            padding: 1.5rem;
            margin: 0.5rem;
        }

        .section-card {
            padding: 1rem;
            margin: 0.5rem;
        }

        .distribution-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Animation */
    .welcome-card, .section-card {
        opacity: 0;
        transform: translateY(20px);
        animation: slideInUp 0.6s ease forwards;
    }

    .section-card:nth-child(2) { animation-delay: 0.1s; }
    .section-card:nth-child(3) { animation-delay: 0.2s; }
    .section-card:nth-child(4) { animation-delay: 0.3s; }

    @keyframes slideInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Enhanced Welcome Card -->
<div class="welcome-card">
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
        <div style="background: linear-gradient(135deg, #4facfe, #00f2fe); padding: 1rem; border-radius: 20px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-tachometer-alt" style="font-size: 2rem; color: white;"></i>
        </div>
        <div>
            <h2 style="margin: 0; font-size: 2.2rem; font-weight: 700; color: #4facfe;">
                Selamat Datang, {{ session('user')->full_name }}
            </h2>
            <p style="margin: 0.5rem 0 0 0; color: rgba(255, 255, 255, 0.8); font-size: 1rem;">
                Sistem Monitoring Performa
            </p>
        </div>
    </div>
    
    <div style="background: rgba(255, 255, 255, 0.08); border-radius: 15px; padding: 1.5rem; border: 1px solid rgba(255, 255, 255, 0.1);">
        <p style="margin: 0; color: rgba(255, 255, 255, 0.9); font-size: 1.1rem; line-height: 1.6;">
            <i class="fas fa-chart-line" style="color: #4facfe; margin-right: 0.5rem;"></i>
            Kelola dan pantau performa karyawan dengan mudah melalui dashboard yang modern dan intuitif. 
            Monitor evaluasi, analisis ranking, dan dapatkan insights mendalam tentang performa tim Anda.
        </p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.8rem;">
                <div style="background: rgba(40, 167, 69, 0.2); padding: 0.5rem; border-radius: 10px;">
                    <i class="fas fa-users" style="color: #28a745; font-size: 1.2rem;"></i>
                </div>
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Employee Management</div>
                    <div style="color: rgba(255, 255, 255, 0.9); font-weight: 600;">Comprehensive</div>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; gap: 0.8rem;">
                <div style="background: rgba(79, 172, 254, 0.2); padding: 0.5rem; border-radius: 10px;">
                    <i class="fas fa-trophy" style="color: #4facfe; font-size: 1.2rem;"></i>
                </div>
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Performance Ranking</div>
                    <div style="color: rgba(255, 255, 255, 0.9); font-weight: 600;">Real-time</div>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; gap: 0.8rem;">
                <div style="background: rgba(255, 193, 7, 0.2); padding: 0.5rem; border-radius: 10px;">
                    <i class="fas fa-chart-pie" style="color: #ffc107; font-size: 1.2rem;"></i>
                </div>
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Analytics & Reports</div>
                    <div style="color: rgba(255, 255, 255, 0.9); font-weight: 600;">Advanced</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($dashboardData))
    <!-- Navigation Links -->
    <div class="nav-links">
        <a href="/dashboard" class="nav-link active">
            <i class="fas fa-tachometer-alt"></i> Dashboard Overview
        </a>
        <a href="{{ route('scores.ranking') }}" class="nav-link">
            <i class="fas fa-trophy"></i> Employee Ranking
        </a>
        <a href="/employees" class="nav-link">
            <i class="fas fa-users"></i> Employee Management
        </a>
        <a href="/performance" class="nav-link">
            <i class="fas fa-chart-bar"></i> Performance
        </a>
    </div>

    <!-- Stats Overview (Keep Existing) -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-label">Total Employees</div>
            <div class="stat-value">{{ $dashboardData['overview']['total_employees'] ?? 0 }}</div>
            <div class="stat-subtitle">Registered in system</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-label">Evaluated</div>
            <div class="stat-value">{{ $dashboardData['overview']['evaluated_employees'] ?? 0 }}</div>
            <div class="stat-subtitle">{{ $dashboardData['overview']['completion_rate'] ?? 0 }}% completion rate</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-label">Average Score</div>
            <div class="stat-value">{{ number_format($dashboardData['overview']['average_score'] ?? 0, 2) }}</div>
            <div class="stat-subtitle">Out of 5.0000</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $dashboardData['overview']['pending_evaluations'] ?? 0 }}</div>
            <div class="stat-subtitle">Awaiting evaluation</div>
        </div>
    </div>

    <!-- Performance Distribution (New) -->
    <div class="section-card">
        <h2 class="section-title">
            <i class="fas fa-chart-pie"></i>
            Performance Distribution
        </h2>
        
        <div class="distribution-grid">
            <div class="distribution-item">
                <div class="distribution-count excellent">
                    {{ $dashboardData['performance_distribution']['excellent'] ?? 0 }}
                </div>
                <div class="distribution-label">Excellent</div>
                <div style="font-size: 0.7rem; color: rgba(255,255,255,0.5);">4.5 - 5.0</div>
            </div>

            <div class="distribution-item">
                <div class="distribution-count very-good">
                    {{ $dashboardData['performance_distribution']['very_good'] ?? 0 }}
                </div>
                <div class="distribution-label">Very Good</div>
                <div style="font-size: 0.7rem; color: rgba(255,255,255,0.5);">4.0 - 4.49</div>
            </div>

            <div class="distribution-item">
                <div class="distribution-count good">
                    {{ $dashboardData['performance_distribution']['good'] ?? 0 }}
                </div>
                <div class="distribution-label">Good</div>
                <div style="font-size: 0.7rem; color: rgba(255,255,255,0.5);">3.5 - 3.99</div>
            </div>

            <div class="distribution-item">
                <div class="distribution-count fair">
                    {{ $dashboardData['performance_distribution']['fair'] ?? 0 }}
                </div>
                <div class="distribution-label">Fair</div>
                <div style="font-size: 0.7rem; color: rgba(255,255,255,0.5);">3.0 - 3.49</div>
            </div>

            <div class="distribution-item">
                <div class="distribution-count needs-improvement">
                    {{ $dashboardData['performance_distribution']['needs_improvement'] ?? 0 }}
                </div>
                <div class="distribution-label">Needs Improvement</div>
                <div style="font-size: 0.7rem; color: rgba(255,255,255,0.5);">< 3.0</div>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-placeholder">
                <i class="fas fa-chart-pie" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <p>Performance Distribution Chart</p>
                <small>Visual chart will be implemented here</small>
            </div>
        </div>
    </div>

    <!-- Top Performers (Keep Existing) -->
    <div class="section-card">
        <h2 class="section-title">
            <i class="fas fa-medal"></i>
            Top 10 Performers
        </h2>
        
        <div class="top-performers-grid">
            @forelse($dashboardData['top_performers'] ?? [] as $index => $performer)
                <div class="performer-card">
                    <div class="performer-rank {{ $index < 3 ? 'top-3' : '' }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="performer-info">
                        <div class="performer-details">
                            <h4>{{ $performer->name }}</h4>
                            <p>{{ $performer->role_name ?? 'N/A' }} • {{ $performer->division_name ?? 'N/A' }}</p>
                        </div>
                        <div class="performer-score">
                            <div class="score-value">{{ number_format($performer->total_profile_score, 2) }}</div>
                            <div class="score-category">
                                @php
                                    $score = $performer->total_profile_score;
                                    if ($score >= 4.5) echo 'Excellent';
                                    elseif ($score >= 4.0) echo 'Very Good';
                                    elseif ($score >= 3.5) echo 'Good';
                                    elseif ($score >= 3.0) echo 'Fair';
                                    else echo 'Needs Improvement';
                                @endphp
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="performer-card">
                    <p style="text-align: center; color: rgba(255,255,255,0.6);">
                        <i class="fas fa-info-circle"></i><br>
                        No performance data available yet
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Division Performance Analysis (New) -->
    <div class="section-card">
        <h2 class="section-title">
            <i class="fas fa-building"></i>
            Division Performance Analysis
        </h2>
        
        <div class="ranking-table-container">
            <table class="division-table">
                <thead>
                    <tr>
                        <th>Division</th>
                        <th>Total Employees</th>
                        <th>Evaluated</th>
                        <th>Completion Rate</th>
                        <th>Average Score</th>
                        <th>Highest Score</th>
                        <th>Lowest Score</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dashboardData['division_stats'] ?? [] as $division)
                        <tr>
                            <td style="font-weight: 600;">{{ $division->division_name ?? 'Unknown' }}</td>
                            <td>{{ $division->total_employees }}</td>
                            <td>{{ $division->evaluated_employees }}</td>
                            <td>
                                @php
                                    $completionRate = $division->total_employees > 0 
                                        ? round(($division->evaluated_employees / $division->total_employees) * 100, 1) 
                                        : 0;
                                @endphp
                                <span class="performance-badge badge-{{ $completionRate >= 80 ? 'excellent' : ($completionRate >= 60 ? 'good' : 'fair') }}">
                                    {{ $completionRate }}%
                                </span>
                            </td>
                            <td>
                                <strong>{{ $division->avg_score ? number_format($division->avg_score, 2) : 'N/A' }}</strong>
                            </td>
                            <td>{{ $division->max_score ? number_format($division->max_score, 2) : 'N/A' }}</td>
                            <td>{{ $division->min_score ? number_format($division->min_score, 2) : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: rgba(255,255,255,0.6);">
                                No division data available
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Complete Employee Ranking (New) -->
    <div class="section-card">
        <h2 class="section-title">
            <i class="fas fa-list-ol"></i>
            Complete Employee Ranking
        </h2>
        
        <!-- Export Actions -->
        <div class="export-actions">
            <button class="btn-outline" onclick="exportToCSV()">
                <i class="fas fa-download"></i> Export CSV
            </button>
            <button class="btn-outline" onclick="window.print()">
                <i class="fas fa-print"></i> Print Report
            </button>
            <a href="{{ route('scores.ranking') }}" class="btn-primary">
                <i class="fas fa-external-link-alt"></i> View Full Ranking
            </a>
        </div>
        
        <div class="ranking-table-container">
            <!-- Table Filters -->
            <div class="table-filters">
                <div class="filter-group">
                    <label class="filter-label">Search Employee</label>
                    <input type="text" class="filter-input" id="searchEmployee" placeholder="Search by name or NIP...">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Filter by Division</label>
                    <select class="filter-select" id="filterDivision">
                        <option value="">All Divisions</option>
                        @foreach($dashboardData['division_stats'] ?? [] as $division)
                            <option value="{{ $division->division_name }}">{{ $division->division_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Filter by Performance</label>
                    <select class="filter-select" id="filterPerformance">
                        <option value="">All Performance Levels</option>
                        <option value="excellent">Excellent (4.5+)</option>
                        <option value="very-good">Very Good (4.0-4.49)</option>
                        <option value="good">Good (3.5-3.99)</option>
                        <option value="fair">Fair (3.0-3.49)</option>
                        <option value="needs-improvement">Needs Improvement (<3.0)</option>
                    </select>
                </div>
            </div>

            <!-- Ranking Table -->
            <table class="ranking-table" id="rankingTable">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">
                            Rank <i class="fas fa-sort"></i>
                        </th>
                        <th onclick="sortTable(1)">
                            Employee <i class="fas fa-sort"></i>
                        </th>
                        <th onclick="sortTable(2)">
                            Division <i class="fas fa-sort"></i>
                        </th>
                        <th onclick="sortTable(3)">
                            Role <i class="fas fa-sort"></i>
                        </th>
                        <th onclick="sortTable(4)">
                            Score <i class="fas fa-sort"></i>
                        </th>
                        <th onclick="sortTable(5)">
                            Performance <i class="fas fa-sort"></i>
                        </th>
                        <th onclick="sortTable(6)">
                            Evaluation Date <i class="fas fa-sort"></i>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rankedEmployees ?? [] as $employee)
                        <tr>
                            <td>
                                <span class="rank-badge {{ $employee->rank <= 3 ? 'top-3' : '' }}">
                                    #{{ $employee->rank }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $employee->name }}</strong><br>
                                <small style="color: rgba(255,255,255,0.6);">{{ $employee->nip }}</small>
                            </td>
                            <td>{{ $employee->division_name ?? 'N/A' }}</td>
                            <td>{{ $employee->role_name ?? 'N/A' }}</td>
                            <td>
                                <strong style="font-size: 1.1rem;">{{ number_format($employee->total_profile_score, 2) }}</strong>
                            </td>
                            <td>
                                <span class="performance-badge badge-{{ $employee->category_class ?? 'secondary' }}">
                                    {{ $employee->performance_category ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                {{ $employee->submission ? \Carbon\Carbon::parse($employee->submission)->format('M d, Y') : 'N/A' }}
                            </td>
                            <td>
                                <a href="{{ route('scores.showProfileMatching', ['employeeNip' => $employee->nip]) }}" 
                                   class="btn-outline" style="padding: 0.3rem 0.8rem; font-size: 0.8rem;">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: rgba(255,255,255,0.6); padding: 2rem;">
                                <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.3;"></i><br>
                                No ranking data available yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions (Keep Existing) -->
    <div class="section-card">
        <h2 class="section-title">
            <i class="fas fa-rocket"></i>
            Quick Actions
        </h2>
        
        <div class="quick-actions-grid">
            <a href="/employees" class="quick-action-card">
                <i class="fas fa-users quick-action-icon" style="color: #4facfe;"></i>
                <strong>Manage Employees</strong><br>
                <small style="color: rgba(255,255,255,0.7);">Add, edit, or view employee data</small>
            </a>
            
            <a href="{{ route('scores.ranking') }}" class="quick-action-card">
                <i class="fas fa-trophy quick-action-icon" style="color: #ffd700;"></i>
                <strong>View Full Ranking</strong><br>
                <small style="color: rgba(255,255,255,0.7);">Complete employee performance ranking</small>
            </a>
            
            <a href="/performance" class="quick-action-card">
                <i class="fas fa-chart-line quick-action-icon" style="color: #28a745;"></i>
                <strong>Performance Reviews</strong><br>
                <small style="color: rgba(255,255,255,0.7);">Conduct employee evaluations</small>
            </a>
            
            <a href="/criteria" class="quick-action-card">
                <i class="fas fa-list-check quick-action-icon" style="color: #17a2b8;"></i>
                <strong>Manage Criteria</strong><br>
                <small style="color: rgba(255,255,255,0.7);">Configure evaluation criteria</small>
            </a>
        </div>
    </div>
@endif

<script>
    // Search and Filter Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchEmployee');
        const divisionFilter = document.getElementById('filterDivision');
        const performanceFilter = document.getElementById('filterPerformance');
        const table = document.getElementById('rankingTable');
        
        if (searchInput && divisionFilter && performanceFilter && table) {
            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const divisionTerm = divisionFilter.value.toLowerCase();
                const performanceTerm = performanceFilter.value.toLowerCase();
                const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    if (row.cells.length > 1) {
                        const employee = row.cells[1].textContent.toLowerCase();
                        const division = row.cells[2].textContent.toLowerCase();
                        const performanceBadge = row.cells[5].querySelector('.performance-badge');
                        const performance = performanceBadge ? performanceBadge.className.toLowerCase() : '';
                        
                        const matchesSearch = employee.includes(searchTerm);
                        const matchesDivision = !divisionTerm || division.includes(divisionTerm);
                        const matchesPerformance = !performanceTerm || performance.includes(performanceTerm);
                        
                        if (matchesSearch && matchesDivision && matchesPerformance) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                }
            }
            
            searchInput.addEventListener('input', filterTable);
            divisionFilter.addEventListener('change', filterTable);
            performanceFilter.addEventListener('change', filterTable);
        }
    });

    // Table Sorting
    function sortTable(n) {
        const table = document.getElementById('rankingTable');
        if (!table) return;
        
        let switching = true;
        let dir = 'asc';
        let switchcount = 0;
        
        while (switching) {
            switching = false;
            const rows = table.rows;
            
            for (let i = 1; i < (rows.length - 1); i++) {
                let shouldSwitch = false;
                let x = rows[i].getElementsByTagName('TD')[n];
                let y = rows[i + 1].getElementsByTagName('TD')[n];
                
                let xValue = x.textContent || x.innerText;
                let yValue = y.textContent || y.innerText;
                
                // Handle numeric values
                if (n === 0 || n === 4) { // Rank or Score columns
                    xValue = parseFloat(xValue.replace(/[^0-9.-]/g, ''));
                    yValue = parseFloat(yValue.replace(/[^0-9.-]/g, ''));
                }
                
                if (dir === 'asc') {
                    if (xValue > yValue) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir === 'desc') {
                    if (xValue < yValue) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                if (switchcount === 0 && dir === 'asc') {
                    dir = 'desc';
                    switching = true;
                }
            }
        }
    }

    // Export to CSV
    function exportToCSV() {
        const table = document.getElementById('rankingTable');
        if (!table) return;
        
        const rows = table.querySelectorAll('tr');
        const csv = [];
        
        for (let i = 0; i < rows.length; i++) {
            const row = [];
            const cols = rows[i].querySelectorAll('td, th');
            
            for (let j = 0; j < cols.length - 1; j++) { // Exclude actions column
                let cellText = cols[j].innerText.replace(/"/g, '""');
                row.push('"' + cellText + '"');
            }
            csv.push(row.join(','));
        }
        
        const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
        const downloadLink = document.createElement('a');
        downloadLink.download = 'employee_ranking_' + new Date().toISOString().split('T')[0] + '.csv';
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = 'none';
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
</script>

@endsection