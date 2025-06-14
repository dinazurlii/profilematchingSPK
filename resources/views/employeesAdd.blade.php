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
            max-width: 800px !important;
            margin: 0 auto !important;
            padding: 0 !important;
        }

        /* Main Form Container - Glassmorphism */
        .add-form-container {
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

        .add-form-container::before {
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

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.8rem;
        }

        /* Form Labels */
        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-bottom: 0.8rem;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }

        .required {
            color: rgba(255, 107, 107, 0.9);
            font-weight: 600;
        }

        /* Form Inputs */
        .form-input,
        .form-select {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 15px !important;
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 1rem 1.2rem !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: all 0.3s ease !important;
            font-size: 0.95rem;
            width: 100%;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        .form-input:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(79, 172, 254, 0.5) !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1) !important;
            color: rgba(255, 255, 255, 0.95) !important;
        }

        /* Select Dropdown Styling */
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%28255,255,255,0.7%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 1rem center !important;
            background-size: 16px 12px !important;
            padding-right: 3rem !important;
            cursor: pointer;
        }

        .form-select option {
            background: rgba(50, 50, 70, 0.95) !important;
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 0.5rem !important;
        }

        /* Input Hint */
        .input-hint {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 0.4rem;
            font-style: italic;
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

        .text-danger {
            color: rgba(255, 107, 107, 0.9) !important;
            font-size: 0.85rem;
            margin-top: 0.4rem;
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

        /* Primary Button (Simpan) */
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

        /* Secondary Button (Kembali) */
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

        /* Content Wrapper */
        .content-wrapper {
            position: relative;
            z-index: 1;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .add-form-container {
                padding: 1.5rem;
                border-radius: 20px;
                margin: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-group {
                margin-bottom: 1.5rem;
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
        }

        @media (max-width: 480px) {
            .add-form-container {
                padding: 1rem;
                margin: 0.5rem;
            }

            .page-title {
                font-size: 1.3rem;
            }

            .form-input,
            .form-select {
                padding: 0.8rem 1rem !important;
                font-size: 0.9rem;
            }
        }

        /* Animation */
        .add-form-container {
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
        <div class="breadcrumb">
            <a href="/employees">Employee</a> / <a href="/employees/add">Tambah Employees</a>
        </div>

        <div class="container">
            <div class="add-form-container">
                <h2 class="page-title">
                    <i class="fas fa-user-plus"></i>
                    New Employee's
                </h2>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('employees.store') }}" id="employeeForm">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label" for="nip">
                                <i class="fas fa-id-card"></i> NIP <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="nip" 
                                name="nip" 
                                class="form-input" 
                                placeholder="2025001" 
                                value="{{ old('nip') }}" 
                                required>
                            <div class="input-hint">
                                Format: Tahun + 3 digit urut (contoh: 2025001)
                            </div>
                            @error('nip')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="name">
                                <i class="fas fa-user"></i> Nama Lengkap <span class="required">*</span>
                            </label>
                            <input type="text" id="name" name="name" class="form-input" 
                                   placeholder="John Doe" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="group_id">
                                <i class="fas fa-users"></i> Group <span class="required">*</span>
                            </label>
                            <select id="group_id" name="group_id" class="form-select" required>
                                <option value="">Pilih Group</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->group_id }}" {{ old('group_id') == $group->group_id ? 'selected' : '' }}>
                                        {{ $group->groupname }}
                                    </option>
                                @endforeach
                            </select>
                            @error('group_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="role">
                                <i class="fas fa-briefcase"></i> Role <span class="required">*</span>
                            </label>
                            <select id="role" name="role_id" class="form-select" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" data-division="{{ $role->division_id }}" 
                                            {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="division">
                                <i class="fas fa-building"></i> Division <span class="required">*</span>
                            </label>
                            <select name="division_id" id="division" class="form-select" required>
                                <option value="">-- Pilih Division --</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('division_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label" for="email">
                                <i class="fas fa-envelope"></i> Email <span class="required">*</span>
                            </label>
                            <input type="email" id="email" name="email" class="form-input"
                                   placeholder="john.doe@company.com" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.history.back()">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const divisionSelect = document.getElementById('division');
            const roleSelect = document.getElementById('role');
            const allRoleOptions = Array.from(roleSelect.options).slice(1); // skip the first default option

            divisionSelect.addEventListener('change', function () {
                const selectedDivision = this.value;

                // clear current options except the first one
                roleSelect.innerHTML = '<option value="">-- Pilih Role --</option>';

                const filtered = allRoleOptions.filter(option =>
                    option.dataset.division === selectedDivision
                );

                filtered.forEach(option => {
                    roleSelect.appendChild(option);
                });
            });

            // NIP placeholder suggestion
            document.getElementById('nip').addEventListener('focus', function() {
                if (!this.value) {
                    const currentYear = new Date().getFullYear();
                    const randomNum = Math.floor(Math.random() * 900) + 100; // 3 digit random
                    this.placeholder = `Saran: ${currentYear}${randomNum}`;
                }
            });
        });
    </script>
@endsection