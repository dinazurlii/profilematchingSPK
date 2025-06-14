@extends('layouts.mainApp')

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
        .edit-form-container {
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

        .edit-form-container::before {
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

        /* Form Inputs */
        .form-control,
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

        .form-control::placeholder,
        .form-select option {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        .form-control:focus,
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
        }

        .form-select option {
            background: rgba(50, 50, 70, 0.95) !important;
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 0.5rem !important;
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

        .alert ul {
            margin-bottom: 0 !important;
            padding-left: 1.2rem;
        }

        .alert li {
            color: rgba(255, 255, 255, 0.95) !important;
            margin-bottom: 0.3rem;
        }

        /* Button Container */
        .button-container {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
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
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #00f2fe, #4facfe) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4) !important;
            color: rgba(255, 255, 255, 1) !important;
        }

        /* Secondary Button (Batal) */
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
            .edit-form-container {
                padding: 1.5rem;
                border-radius: 20px;
                margin: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }

            .button-container {
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
            .edit-form-container {
                padding: 1rem;
                margin: 0.5rem;
            }

            .page-title {
                font-size: 1.3rem;
            }

            .form-control,
            .form-select {
                padding: 0.8rem 1rem !important;
                font-size: 0.9rem;
            }
        }

        /* Animation */
        .edit-form-container {
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
        <div class="container">
            <div class="edit-form-container">
                <h2 class="page-title">
                    <i class="fas fa-user-edit"></i>
                    Edit Data Karyawan
                </h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="nip" class="form-label">
                            <i class="fas fa-id-card"></i> NIP
                        </label>
                        <input type="text" class="form-control" id="nip" name="nip" 
                               value="{{ old('nip', $employee->nip) }}" 
                               placeholder="Masukkan NIP karyawan" required>
                    </div>

                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i> Nama Lengkap
                        </label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $employee->name) }}" 
                               placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> Email (Opsional)
                        </label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ old('email', $employee->email) }}"
                               placeholder="contoh@email.com">
                    </div>

                    <div class="form-group">
                        <label for="division_id" class="form-label">
                            <i class="fas fa-building"></i> Divisi
                        </label>
                        <select class="form-select" id="division_id" name="division_id" required>
                            <option value="">Pilih Divisi</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}" {{ $division->id == $employee->division_id ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="group_id" class="form-label">
                            <i class="fas fa-users"></i> Group
                        </label>
                        <select class="form-select" id="group_id" name="group_id" required>
                            <option value="">Pilih Group</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->group_id }}" {{ $group->group_id == $employee->group_id ? 'selected' : '' }}>
                                    {{ $group->groupname }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="role_id" class="form-label">
                            <i class="fas fa-briefcase"></i> Role
                        </label>
                        <select class="form-select" id="role_id" name="role_id" required>
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ $role->id == $employee->role_id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="button-container">
                        <a href="{{ url('/employees') }}" class="btn-secondary">
                            <i class="fas fa-times"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection