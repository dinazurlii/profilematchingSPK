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
        .table-striped tbody tr,
        .table-striped tbody td,
        .table-striped thead th,
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

        /* Criteria Page Specific Styles */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: nowrap; /* UBAH dari wrap ke nowrap */
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
    max-width: 300px; /* TAMBAH max-width */
    flex-shrink: 0; /* TAMBAH ini agar tidak menyusut */
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

        /* Add the btn-add styling for the Tambah button */
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

        /* Criteria Table - Enhanced Glass Effect */
        .criteria-table-container {
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

        .criteria-table-container::before {
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

        .criteria-table {
            width: 100%;
            margin: 0;
            background: transparent;
            border-collapse: separate;
            border-spacing: 0;
        }

        .criteria-table thead {
            background: transparent;
        }

        .criteria-table tbody {
            background: transparent;
        }

        .criteria-table th {
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

        .criteria-table th:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 25%;
            bottom: 25%;
            width: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        .criteria-table td {
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1.2rem 1rem;
            color: rgba(255, 255, 255, 0.85);
            vertical-align: middle;
            font-size: 0.95rem;
            position: relative;
        }

        .criteria-table td:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 20%;
            bottom: 20%;
            width: 1px;
            background: rgba(255, 255, 255, 0.05);
        }

        .criteria-table tbody {
            background: transparent;
        }

        .criteria-table tbody tr {
            transition: all 0.3s ease;
            position: relative;
            background: transparent;
        }

        .criteria-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .criteria-table tbody tr:hover td {
            color: rgba(255, 255, 255, 0.95);
        }

        .criteria-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Role Name Link Styling - Updated with Smoother Transitions */
.criteria-table td a {
    color: rgba(255, 255, 255, 0.95); /* Changed to white */
    text-decoration: none;
    font-weight: 400; /* Normal weight by default */
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); /* Smoother easing function */
    position: relative;
    transform: translateZ(0); /* Hardware acceleration */
    will-change: color, font-weight, text-shadow; /* Optimize for changes */
}

.criteria-table td a:hover {
    color: rgba(255, 255, 255, 1); /* Pure white on hover */
    font-weight: 600; /* Bold on hover */
    text-shadow: 0 0 12px rgba(255, 255, 255, 0.4); /* Enhanced glow */
    transform: translateZ(0) scale(1.02); /* Subtle scale effect */
}

.criteria-table td a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.8));
    transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1); /* Smoother underline animation */
    transform: translateZ(0); /* Hardware acceleration */
}

.criteria-table td a:hover::after {
    width: 100%;
}

        /* Force transparency - override any existing styles */
        .criteria-table-container .criteria-table,
        .criteria-table-container .criteria-table *,
        .criteria-table-container .criteria-table thead,
        .criteria-table-container .criteria-table thead tr,
        .criteria-table-container .criteria-table thead th,
        .criteria-table-container .criteria-table tbody,
        .criteria-table-container .criteria-table tbody tr,
        .criteria-table-container .criteria-table tbody td,
        .criteria-table-container table,
        .criteria-table-container table *,
        table.criteria-table,
        table.criteria-table *,
        .table,
        .table *,
        .table tbody tr,
        .table tbody td,
        .table thead th {
            background: transparent !important;
            background-color: transparent !important;
            background-image: none !important;
        }

        .criteria-table-container .criteria-table tbody tr:hover,
        .criteria-table-container .criteria-table tbody tr:hover td {
            background: rgba(255, 255, 255, 0.12) !important;
            background-color: rgba(255, 255, 255, 0.12) !important;
        }

        /* Empty State Styling */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h5 {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: rgba(255, 255, 255, 0.6);
            margin: 0;
        }


            /* FORCE HORIZONTAL LAYOUT - OVERRIDE SEMUA */
.page-header {
    display: flex !important;
    flex-direction: row !important;
    justify-content: space-between !important;
    align-items: center !important;
    flex-wrap: nowrap !important;
    margin-bottom: 2rem !important;
    width: 100% !important;
    min-height: auto !important;
}

.page-title {
    color: rgba(255, 255, 255, 0.95) !important;
    font-size: 2rem !important;
    font-weight: 600 !important;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3) !important;
    margin: 0 !important;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}

.header-actions {
    display: flex !important;
    gap: 1rem !important;
    align-items: center !important;
    flex-wrap: nowrap !important;
    flex-shrink: 0 !important;
    margin-left: auto !important;
}

       @media (max-width: 768px) {
    .page-header {
        flex-direction: row; /* TETAP horizontal di mobile */
        align-items: center;
        justify-content: space-between;
        flex-wrap: nowrap; /* TETAP nowrap */
    }

    .header-actions {
        justify-content: flex-end;
        flex-wrap: wrap; /* Allow wrapping on mobile for better layout */
    }

    .search-box {
        min-width: 180px; /* LEBIH KECIL di mobile */
        max-width: 220px;
    }

    .page-title {
        font-size: 1.3rem; /* LEBIH KECIL agar muat */
        flex-shrink: 1; /* BISA menyusut jika perlu */
    }
    
    .btn-add {
        padding: 0.6rem 1.2rem; /* Slightly smaller on mobile */
        font-size: 0.9rem;
    }
}
        
    </style>

    <div class="content-wrapper">
        <div class="page-header">
    <h1 class="page-title">Data Kriteria</h1>
    <div class="header-actions">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" placeholder="Cari role atau division..." id="searchInput">
        </div>
        <a href="/criteria/add" class="btn-add">
            <i class="fas fa-plus"></i>
            Tambah
        </a>
    </div>
</div>

        <div class="criteria-table-container">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-list-alt"></i>
                    Daftar Kriteria
                </h3>
            </div>
            <div class="table-responsive">
                <table class="table criteria-table" id="criteriaTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Role Name</th>
                            <th>Division Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $index => $role)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ route('criteria.detail', ['role_id' => $role->role_id]) }}">
                                    {{ $role->role_name }}
                                </a>
                            </td>
                            <td>{{ $role->division_name }}</td>
                        </tr>
                        @endforeach
                        @if($roles->isEmpty())
                        <tr>
                            <td colspan="3">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h5>Belum ada data kriteria</h5>
                                    <p>Data kriteria akan ditampilkan di sini ketika tersedia</p>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#criteriaTable tbody tr');

            tableRows.forEach(row => {
                const roleName = row.cells[1]?.textContent.toLowerCase() || '';
                const divisionName = row.cells[2]?.textContent.toLowerCase() || '';

                if (roleName.includes(searchTerm) || divisionName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
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

        document.querySelectorAll('.criteria-table-container').forEach((el) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });
    </script>
@endsection