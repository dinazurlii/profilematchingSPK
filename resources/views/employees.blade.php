@extends('layouts.mainApp')
@if (!session()->has('user'))
    <script>
        window.location.href = '/login';
    </script>
@endif

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
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

        /* Employee Page Specific Styles */
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

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            min-width: 300px;
        }

        .search-input {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            color: rgba(255, 255, 255, 0.9);
            padding: 0.75rem 1rem 0.75rem 3rem;
            width: 100%;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            transition: all 0.3s ease;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .search-input:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
            outline: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: rgba(255, 255, 255, 0.95);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
        }

        .btn-add {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border: none;
            color: rgba(238, 236, 236, 0.8);
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
            text-decoration: none;
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #00f2fe, #4facfe);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4);
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
        }

        /* Employee Table - Enhanced Glass Effect */
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

        .employee-table tbody {
            background: transparent;
        }

        .employee-table tbody tr {
            transition: all 0.3s ease;
            position: relative;
            background: transparent;
        }

        .employee-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .employee-table tbody tr:hover td {
            color: rgba(255, 255, 255, 0.95);
        }

        .employee-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Enhanced Action Dropdown */
        .action-dropdown {
            position: absolute;
        }

        .action-btn {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.8), rgba(118, 75, 162, 0.8));
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.9);
            padding: 0.6rem 1.2rem;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .action-btn:hover {
            background: linear-gradient(135deg, rgba(118, 75, 162, 0.9), rgba(102, 126, 234, 0.9));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            color: rgba(255, 255, 255, 1);
        }

        /* Action Button Danger - Same style as Edit but red */
.action-btn-danger {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.8), rgba(255, 107, 107, 0.8));
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 0.9);
    padding: 0.6rem 1.2rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.action-btn-danger:hover {
    background: linear-gradient(135deg, rgba(255, 107, 107, 0.9), rgba(220, 53, 69, 0.9));
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    color: rgba(255, 255, 255, 1);
}

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }

        .dropdown-item {
            color: rgba(255, 255, 255, 0.9);
            padding: 0.8rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            border: none;
            background: none;
            text-decoration: none;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 1);
            backdrop-filter: blur(10px);
        }

        .dropdown-item.text-danger {
            color: rgba(255, 107, 107, 0.9);
        }

        .dropdown-item.text-danger:hover {
            background: rgba(255, 107, 107, 0.15);
            color: rgba(255, 107, 107, 1);
        }

        /* Email styling for better readability */
        .employee-table td:nth-child(4) {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Name column styling */
        .employee-table td:first-child {
            font-weight: 500;
            color: rgba(255, 255, 255, 0.95);
        }

        /* Role and Division styling */
        .employee-table td:nth-child(2),
        .employee-table td:nth-child(3) {
            color: rgba(255, 255, 255, 0.8);
        }

        /* Force transparency - override any existing styles */
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

        /* Pagination Styles */
        .pagination-container {
            background: transparent !important;
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .pagination-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .pagination-text {
            color: #ffffff;
            font-size: 14px;
            margin: 0;
        }

        .pagination-controls {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .pagination-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 40px;
        }

        .pagination-btn:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .pagination-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .pagination-btn.active {
            background: linear-gradient(45deg, #2848a7, #20c997);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .page-numbers {
            display: flex;
            gap: 5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                border-radius: 0 0 25px 25px;
                min-height: auto;
            }

            .main {
                margin: 1rem;
                margin-top: 0;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .header-actions {
                justify-content: space-between;
            }

            .search-box {
                min-width: auto;
                flex-grow: 1;
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

            .employee-table th {
                font-size: 0.75rem;
            }

            .action-btn {
                padding: 0.5rem 0.8rem;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {

            .employee-table th,
            .employee-table td {
                padding: 0.6rem 0.4rem;
                font-size: 0.8rem;
            }

            .table-title {
                font-size: 1.1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .action-buttons .btn {
            min-width: 70px;
            font-size: 0.875rem;
        }

        /* Jika menggunakan icon saja */
        .action-buttons .btn {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fas.fa-trash {
            color: #dc3545;
            /* Bootstrap danger color */
        }
        /* Delete Modal Styling */
        .delete-modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 400px;
            margin: 0 auto;
        }

        .delete-modal-header {
            background: transparent;
            border: none;
            padding: 2rem 2rem 1rem 2rem;
            text-align: center;
        }

        .delete-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            animation: pulse 2s infinite;
        }

        .delete-icon i {
            font-size: 2rem;
            color: white;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.7);
            }
            70% {
                box-shadow: 0 0 0 20px rgba(255, 107, 107, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 107, 107, 0);
            }
        }

        .delete-modal-body {
            padding: 1rem 2rem;
            text-align: center;
        }

        .delete-title {
            color: #333;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .delete-subtitle {
            color: #666;
            font-size: 0.95rem;
            margin: 0;
        }

        .delete-modal-footer {
            border: none;
            padding: 1rem 2rem 2rem 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-cancel {
            background: rgba(108, 117, 125, 0.1);
            border: 2px solid rgba(108, 117, 125, 0.3);
            color: #6c757d;
            padding: 0.75rem 2rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            cursor: pointer;
            min-width: 100px;
        }

        .btn-cancel:hover {
            background: rgba(108, 117, 125, 0.2);
            border-color: rgba(108, 117, 125, 0.5);
            color: #495057;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            cursor: pointer;
            min-width: 100px;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, #ee5a52, #ff6b6b);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
        }

        .btn-delete:active {
            transform: translateY(0);
        }

        /* Toast Notification Styles */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            padding: 1rem 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 300px;
            max-width: 400px;
            animation: slideInRight 0.3s ease-out;
            border-left: 4px solid;
        }

        .toast-success {
            border-left-color: #28a745;
        }

        .toast-error {
            border-left-color: #dc3545;
        }

        .toast-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }

        .toast-content i {
            font-size: 1.2rem;
        }

        .toast-success .toast-content i {
            color: #28a745;
        }

        .toast-error .toast-content i {
            color: #dc3545;
        }

        .toast-content span {
            color: #333;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .toast-close {
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .toast-close:hover {
            background: rgba(0, 0, 0, 0.1);
            color: #666;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Responsive Modal */
        @media (max-width: 480px) {
            .delete-modal-content {
                margin: 1rem;
                border-radius: 20px;
            }
            
            .delete-modal-header,
            .delete-modal-body,
            .delete-modal-footer {
                padding: 1.5rem;
            }
            
            .delete-modal-footer {
                flex-direction: column;
            }
            
            .btn-cancel,
            .btn-delete {
                width: 100%;
            }

            .toast-notification {
                top: 10px;
                left: 10px;
                right: 10px;
                min-width: auto;
                max-width: none;
            }
        }
    </style>

    <div class="content-wrapper">
        <div class="page-header">
            <h1 class="page-title">Employee's Data</h1>
            <div class="header-actions">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Cari nama employee..." id="searchInput">
                </div>
                <a href="/employees/add" class="btn-add">
                    <i class="fas fa-plus"></i>
                    Tambah
                </a>
            </div>
        </div>

        <div class="employee-table-container">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-users"></i>
                    Daftar Karyawan
                </h3>
            </div>
            <div class="table-responsive">
                <table class="table employee-table" id="employeeTable">
                    <thead>
                        <tr>
                        <th>NIP</th>
                            <th>Name</th>
                            <th>Group</th>
                            <th>Role</th>
                            <th>Division</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($employees as $employee)
    <tr>
        <td>{{ $employee->nip }}</td>
        <td>{{ $employee->name }}</td>
        <td>{{ $employee->group_name }}</td>
        <td>{{ $employee->role_name }}</td>
        <td>{{ $employee->division_name }}</td>
        <td>{{ $employee->email }}</td>
        <td>
            <div class="action-buttons">
                <button class="action-btn" onclick="editEmployees({{ $employee->id }})" title="Edit">
    <i class="fas fa-edit"></i> Edit
</button>
<button onclick="deleteEmployees({{ $employee->id }})" class="action-btn-danger" title="Hapus">
    <i class="fas fa-trash"></i> Hapus
</button>
            </div>
        </td>
    </tr>
@endforeach

                    </tbody>

                </table>
            </div>
        </div>

        <!-- Pagination Container -->
        <div class="pagination-container">
            <div class="pagination-info">
                <p class="pagination-text" id="paginationText">
                    Menampilkan 1-5 dari 12 karyawan
                </p>
                <div class="pagination-controls">
                    <button class="pagination-btn" id="prevBtn" onclick="changePage(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="page-numbers" id="pageNumbers">
                        <!-- Page numbers akan diisi oleh JavaScript -->
                    </div>
                    <button class="pagination-btn" id="nextBtn" onclick="changePage(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        {{-- Delete Confirmation Modal --}}
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content delete-modal-content">
                    <div class="modal-header delete-modal-header">
                        <div class="delete-icon">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                    </div>
                    <div class="modal-body delete-modal-body">
                        <h5 class="delete-title">Are you sure want to delete this data?</h5>
                        <p class="delete-subtitle">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer delete-modal-footer">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">
                            CANCEL
                        </button>
                        <button type="button" class="btn-delete" id="confirmDeleteBtn">
                            YES, DELETE
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#employeeTable tbody tr');

            tableRows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const role = row.cells[1].textContent.toLowerCase();
                const division = row.cells[2].textContent.toLowerCase();
                const email = row.cells[3].textContent.toLowerCase();

                if (name.includes(searchTerm) || role.includes(searchTerm) ||
                    division.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });


        // Pagination variables untuk hardcoded table
        let currentPage = 1;
        const itemsPerPage = 5;
        let allRows = [];
        let filteredRows = [];

        // Initialize pagination untuk hardcoded table
        function initializePagination() {
            // Ambil semua rows dari table yang sudah ada
            const tbody = document.querySelector('#employeeTable tbody');
            allRows = Array.from(tbody.querySelectorAll('tr'));
            filteredRows = [...allRows];

            showCurrentPage();
            renderPagination();
        }

        // Show rows untuk halaman saat ini
        function showCurrentPage() {
            // Sembunyikan semua rows terlebih dahulu
            allRows.forEach(row => {
                row.classList.add('hidden');
            });

            // Hitung index untuk halaman saat ini
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;

            // Tampilkan rows untuk halaman saat ini
            for (let i = startIndex; i < endIndex && i < filteredRows.length; i++) {
                filteredRows[i].classList.remove('hidden');
            }
        }

        // Render pagination controls
        function renderPagination() {
            const totalPages = Math.ceil(filteredRows.length / itemsPerPage);
            const startIndex = (currentPage - 1) * itemsPerPage + 1;
            const endIndex = Math.min(currentPage * itemsPerPage, filteredRows.length);

            // Update pagination text
            document.getElementById('paginationText').textContent =
                `Menampilkan ${startIndex}-${endIndex} dari ${filteredRows.length} karyawan`;

            // Update prev/next buttons
            document.getElementById('prevBtn').disabled = currentPage === 1;
            document.getElementById('nextBtn').disabled = currentPage === totalPages || totalPages === 0;

            // Render page numbers
            const pageNumbers = document.getElementById('pageNumbers');
            pageNumbers.innerHTML = '';

            if (totalPages > 0) {
                // Show page numbers (max 5 visible)
                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(totalPages, startPage + 4);

                if (endPage - startPage < 4) {
                    startPage = Math.max(1, endPage - 4);
                }

                for (let i = startPage; i <= endPage; i++) {
                    const pageBtn = document.createElement('button');
                    pageBtn.className = `pagination-btn ${i === currentPage ? 'active' : ''}`;
                    pageBtn.textContent = i;
                    pageBtn.onclick = () => goToPage(i);
                    pageNumbers.appendChild(pageBtn);
                }
            }
        }

        // Change page
        function changePage(direction) {
            const totalPages = Math.ceil(filteredRows.length / itemsPerPage);
            const newPage = currentPage + direction;

            if (newPage >= 1 && newPage <= totalPages) {
                currentPage = newPage;
                showCurrentPage();
                renderPagination();
            }
        }

        // Go to specific page
        function goToPage(page) {
            currentPage = page;
            showCurrentPage();
            renderPagination();
        }

        // Search functionality untuk hardcoded table
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            // Filter rows berdasarkan search term
            filteredRows = allRows.filter(row => {
                const cells = row.querySelectorAll('td');
                const name = cells[0]?.textContent.toLowerCase() || '';
                const role = cells[1]?.textContent.toLowerCase() || '';
                const division = cells[2]?.textContent.toLowerCase() || '';
                const email = cells[3]?.textContent.toLowerCase() || '';

                return name.includes(searchTerm) ||
                    role.includes(searchTerm) ||
                    division.includes(searchTerm) ||
                    email.includes(searchTerm);
            });

            currentPage = 1; // Reset ke halaman pertama
            showCurrentPage();
            renderPagination();
        });

        // Menampilkan halaman saat ini
        function showCurrentPage() {
            const startIdx = (currentPage - 1) * itemsPerPage;
            const endIdx = startIdx + itemsPerPage;

            filteredRows.forEach((row, index) => {
                if (index >= startIdx && index < endIdx) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            updatePaginationControls();
        }

        // Ubah halaman
        function changePage(direction) {
            const totalPages = Math.ceil(filteredRows.length / itemsPerPage);
            currentPage += direction;

            if (currentPage < 1) currentPage = 1;
            if (currentPage > totalPages) currentPage = totalPages;

            showCurrentPage();
        }

        // Update pagination controls
        function updatePaginationControls() {
            const totalItems = filteredRows.length;
            const startIdx = (currentPage - 1) * itemsPerPage + 1;
            const endIdx = Math.min(startIdx + itemsPerPage - 1, totalItems);
            const paginationText = `Menampilkan ${startIdx}-${endIdx} dari ${totalItems} karyawan`;
            document.getElementById('paginationText').textContent = paginationText;

            // Buat nomor halaman (opsional)
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const pageNumbersContainer = document.getElementById('pageNumbers');
            pageNumbersContainer.innerHTML = ''; // Clear existing

            for (let i = 1; i <= totalPages; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `page-number ${i === currentPage ? 'active' : ''}`;
                pageBtn.textContent = i;
                pageBtn.onclick = function() {
                    currentPage = i;
                    showCurrentPage();
                };
                pageNumbersContainer.appendChild(pageBtn);
            }
        }

        // Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    initializePagination();
    
    // Add event listener for delete confirmation
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (currentEmployeeId) {
                performDelete(currentEmployeeId);
            }
        });
    }
});

        // Employee actions
        function showAddEmployeeModal() {
            alert('Tambah Employee Modal akan muncul di sini');
            // Implementasi modal tambah employee
        }

        function viewEmployee(id) {
            alert(`View Employee dengan ID: ${id}`);
            // Redirect ke halaman view atau buka modal
        }

        function editEmployees(id) {
            window.location.href = `/employees/edit/${id}`;
        }

        // Variables for delete modal
        let currentEmployeeId = null;
        let currentEmployeeName = null;

        // Replace your existing deleteEmployees function with this:
        function deleteEmployees(id) {
            currentEmployeeId = id;
            
            // Get employee name from the table row for personalized message
            const tableRows = document.querySelectorAll('#employeeTable tbody tr');
            let employeeName = 'this data';
            
            tableRows.forEach(row => {
                const editBtn = row.querySelector('button[onclick*="editEmployees"]');
                if (editBtn && editBtn.getAttribute('onclick').includes(id)) {
                    employeeName = row.cells[1].textContent; // Get name from second column
                }
            });
            
            currentEmployeeName = employeeName;
            
            // Update modal text with employee name
            const deleteTitle = document.querySelector('.delete-title');
            deleteTitle.textContent = `Are you sure want to delete "${employeeName}"?`;
            
            // Show the modal
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'), {
                backdrop: 'static',
                keyboard: true
            });
            deleteModal.show();
        }

        // Add this new function for handling the actual deletion:
        function performDelete(id) {
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            const cancelBtn = document.querySelector('.btn-cancel');
            const originalText = confirmBtn.innerHTML;
            
            // Show loading state
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
            confirmBtn.disabled = true;
            cancelBtn.disabled = true;
            
            fetch(`/employees/delete/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to delete employee');
                }
                return response.json();
            })
            .then(data => {
                // Close modal
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                deleteModal.hide();
                
                // Show success notification
                showNotification(`"${currentEmployeeName}" has been deleted successfully!`, 'success');
                
                // Remove the row from UI and update pagination
                const rowToRemove = allRows.find(row => {
                    const editBtn = row.querySelector('button[onclick*="editEmployees"]');
                    return editBtn && editBtn.getAttribute('onclick').includes(id);
                });
                
                if (rowToRemove) {
                    // Remove from arrays
                    allRows = allRows.filter(row => row !== rowToRemove);
                    filteredRows = filteredRows.filter(row => row !== rowToRemove);
                    
                    // Remove from DOM
                    rowToRemove.remove();
                    
                    // Update pagination
                    if (filteredRows.length <= (currentPage - 1) * itemsPerPage && currentPage > 1) {
                        currentPage--;
                    }
                    showCurrentPage();
                    renderPagination();
                } else {
                    // Fallback: reload page
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to delete data. Please try again.', 'error');
            })
            .finally(() => {
                // Reset button state
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
                cancelBtn.disabled = false;
                currentEmployeeId = null;
                currentEmployeeName = null;
            });
        }

        // Add this toast notification function:
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.toast-notification');
            existingNotifications.forEach(notification => notification.remove());
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `toast-notification toast-${type}`;
            notification.innerHTML = `
                <div class="toast-content">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
        // Logout functions (same as previous)
        function showLogoutModal(event) {
            event.preventDefault();
            const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'), {
                backdrop: 'static',
                keyboard: true
            });
            logoutModal.show();
        }

        function confirmLogout() {
            const confirmBtn = document.querySelector('.btn-confirm');
            const cancelBtn = document.querySelector('.btn-cancel');

            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging out...';
            confirmBtn.disabled = true;
            cancelBtn.disabled = true;

            setTimeout(() => {
                window.location.href = '/logout';
            }, 1500);
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const logoutModal = bootstrap.Modal.getInstance(document.getElementById('logoutModal'));
                if (logoutModal) {
                    logoutModal.hide();
                }
            }
        });

        // Add smooth animations
        const observer2 = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });

        document.querySelectorAll('.employee-table-container').forEach((el) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease';
            observer2.observe(el);
        });
    </script>
@endsection