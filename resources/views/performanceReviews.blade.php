@extends('layouts.mainApp')

@if (!session()->has('user'))
    <script>
        window.location.href = '/login';
    </script>
@endif

@section('content')
<style>
    /* CRITICAL: Remove all Bootstrap table backgrounds */
    * {
        box-sizing: border-box;
    }

    /* Remove Bootstrap/Framework default table styling */
    .table-striped tbody tr:nth-of-type(odd),
    .table-striped tbody tr:nth-of-type(even),
    .table tbody tr,
    .table tbody td,
    .table thead th,
    tbody tr,
    tbody td,
    thead th,
    tr,
    td,
    th {
        background: transparent !important;
        background-color: transparent !important;
        background-image: none !important;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title {
        color: rgba(255, 255, 255, 0.95);
        font-size: 2rem;
        font-weight: 600;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        margin: 0;
    }

    .division-info {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 1rem 1.5rem;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Alert Styles */
    .alert {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-left: 4px solid;
    }

    .alert-success {
        border-left-color: #28a745;
        color: rgba(255, 255, 255, 0.95);
    }

    .alert-danger {
        border-left-color: #dc3545;
        color: rgba(255, 255, 255, 0.95);
    }

    .alert i {
        margin-right: 0.5rem;
    }

    .btn-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        color: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .btn-close:hover {
        background: rgba(255, 255, 255, 0.3);
        color: rgba(255, 255, 255, 1);
    }

    /* Main Table Container - Same as employees.blade.php */
    .employee-table-container {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 0;
        box-shadow:
            0 8px 32px rgba(0, 0, 0, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        overflow: hidden;
        position: relative;
    }

    .employee-table-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    }

    .table-header {
        background: rgba(255, 255, 255, 0.1);
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        position: relative;
    }

    .table-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 1.5rem;
        right: 1.5rem;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    }

    .table-title {
        color: rgba(255, 255, 255, 0.95);
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    /* Table Styles */
    .employee-table {
        width: 100%;
        margin: 0;
        background: transparent;
        border-collapse: separate;
        border-spacing: 0;
    }

    .employee-table thead {
        background: transparent;
    }

    .employee-table tbody {
        background: transparent;
    }

    .employee-table th {
        background: rgba(255, 255, 255, 0.08);
        border: none;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
        padding: 1.2rem 1rem;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .employee-table th:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 25%;
        bottom: 25%;
        width: 1px;
        background: rgba(255, 255, 255, 0.1);
    }

    .employee-table td {
        border: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding: 1.2rem 1rem;
        color: rgba(255, 255, 255, 0.85);
        vertical-align: middle;
        font-size: 0.95rem;
        position: relative;
    }

    .employee-table td:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 20%;
        bottom: 20%;
        width: 1px;
        background: rgba(255, 255, 255, 0.05);
    }

    .employee-table tbody tr {
        transition: all 0.3s ease;
        position: relative;
        background: transparent;
    }

    .employee-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.12) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .employee-table tbody tr:hover td {
        color: rgba(255, 255, 255, 0.95);
    }

    .employee-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Employee Name with Avatar */
    .employee-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .employee-avatar {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.8rem;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    /* Status Badges */
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .bg-success {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .bg-danger {
        background: linear-gradient(135deg, #dc3545, #ff6b6b);
        color: white;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    /* Action Buttons */
    .btn-group {
        display: flex;
        gap: 0.5rem;
    }

    .btn-sm {
        padding: 0.6rem 1.2rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-info {
        background: linear-gradient(135deg, rgba(23, 162, 184, 0.8), rgba(19, 132, 150, 0.8));
        color: rgba(255, 255, 255, 0.9);
    }

    .btn-info:hover {
        background: linear-gradient(135deg, rgba(19, 132, 150, 0.9), rgba(23, 162, 184, 0.9));
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
        color: rgba(255, 255, 255, 1);
    }

    .btn-warning {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.8), rgba(255, 235, 59, 0.8));
        color: rgba(0, 0, 0, 0.8);
    }

    .btn-warning:hover {
        background: linear-gradient(135deg, rgba(255, 235, 59, 0.9), rgba(255, 193, 7, 0.9));
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
        color: rgba(0, 0, 0, 0.9);
    }

    /* Statistics Cards */
    .statistics-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--card-gradient);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
    }

    .stat-card.primary {
        --card-gradient: linear-gradient(135deg, #667eea, #764ba2);
    }

    .stat-card.success {
        --card-gradient: linear-gradient(135deg, #28a745, #20c997);
    }

    .stat-card.danger {
        --card-gradient: linear-gradient(135deg, #dc3545, #ff6b6b);
    }

    .stat-card.info {
        --card-gradient: linear-gradient(135deg, #17a2b8, #138496);
    }

    .stat-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        font-size: 2rem;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .stat-details h5 {
        font-size: 1.8rem;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.95);
        margin: 0;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .stat-details small {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: rgba(255, 255, 255, 0.5);
    }

    /* Force transparency */
    .employee-table-container .employee-table,
    .employee-table-container .employee-table *,
    .employee-table-container .employee-table thead,
    .employee-table-container .employee-table thead tr,
    .employee-table-container .employee-table thead th,
    .employee-table-container .employee-table tbody,
    .employee-table-container .employee-table tbody tr,
    .employee-table-container .employee-table tbody td,
    .employee-table-container table,
    .employee-table-container table *,
    table.employee-table,
    table.employee-table *,
    .table,
    .table *,
    .table tbody tr,
    .table tbody td,
    .table thead th {
        background: transparent !important;
        background-color: transparent !important;
        background-image: none !important;
    }

    .employee-table-container .employee-table tbody tr:hover,
    .employee-table-container .employee-table tbody tr:hover td {
        background: rgba(255, 255, 255, 0.12) !important;
        background-color: rgba(255, 255, 255, 0.12) !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .employee-table-container {
            overflow-x: auto;
            border-radius: 15px;
        }

        .employee-table th,
        .employee-table td {
            padding: 0.8rem 0.6rem;
            font-size: 0.85rem;
        }

        .btn-sm {
            padding: 0.5rem 0.8rem;
            font-size: 0.8rem;
        }

        .statistics-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .page-title {
            font-size: 1.5rem;
        }

        .statistics-cards {
            grid-template-columns: 1fr;
        }

        .employee-info {
            flex-direction: column;
            gap: 0.5rem;
        }

        .btn-group {
            flex-direction: column;
            width: 100%;
        }
    }
</style>

<div class="content-wrapper">
    <div class="page-header">
        <h1 class="page-title">Employee's Review</h1>
        <div class="division-info">
            <i class="fas fa-building me-2"></i>
            <strong>Division:</strong> {{ $division->name ?? 'Unknown' }}
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="employee-table-container">
        <div class="table-header">
            <h3 class="table-title">
                <i class="fas fa-chart-line"></i>
                Performance Review Dashboard
            </h3>
        </div>
        <div class="table-responsive">
            <table class="table employee-table">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Employee</th>
                        <th>Role</th>
                        <th>Submission Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td>{{ $employee->nip }}</td>
                            <td>
                                <div class="employee-info">
                                    <div class="employee-avatar">{{ strtoupper(substr($employee->name, 0, 2)) }}</div>
                                    <span>{{ $employee->name }}</span>
                                </div>
                            </td>
                            <td>{{ $employee->role->name ?? '-' }}</td>
                            <td>{{ $employee->submission ?? '-' }}</td>
                            <td>
                                @if ($employee->status === 'completed')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check"></i>Completed
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation"></i>Need Review
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('scores.detail', ['employeeNip' => $employee->nip]) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('scores.create', ['employeeNip' => $employee->nip]) }}" 
                                       class="btn btn-sm btn-warning"
                                       title="Review Performance">
                                        <i class="fas fa-edit"></i> Review
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fas fa-users"></i>
                                <p>Tidak ada data karyawan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if(count($employees) > 0)
    <div class="statistics-cards">
        <div class="stat-card primary">
            <div class="stat-content">
                <i class="fas fa-users stat-icon"></i>
                <div class="stat-details">
                    <h5>{{ count($employees) }}</h5>
                    <small>Total Employees</small>
                </div>
            </div>
        </div>
        <div class="stat-card success">
            <div class="stat-content">
                <i class="fas fa-check-circle stat-icon"></i>
                <div class="stat-details">
                    <h5>{{ collect($employees)->where('status', 'completed')->count() }}</h5>
                    <small>Completed</small>
                </div>
            </div>
        </div>
        <div class="stat-card danger">
            <div class="stat-content">
                <i class="fas fa-exclamation-circle stat-icon"></i>
                <div class="stat-details">
                    <h5>{{ collect($employees)->where('status', '!=', 'completed')->count() }}</h5>
                    <small>Need Review</small>
                </div>
            </div>
        </div>
        <div class="stat-card info">
            <div class="stat-content">
                <i class="fas fa-percentage stat-icon"></i>
                <div class="stat-details">
                    <h5>{{ count($employees) > 0 ? round((collect($employees)->where('status', 'completed')->count() / count($employees)) * 100) : 0 }}%</h5>
                    <small>Progress</small>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.parentElement) {
                alert.style.transition = 'all 0.3s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    if (alert.parentElement) {
                        alert.remove();
                    }
                }, 300);
            }
        }, 5000);
    });

    // Add smooth animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    document.querySelectorAll('.employee-table-container, .stat-card').forEach((el) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
});
</script>
@endsection