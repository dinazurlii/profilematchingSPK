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

        /* Criteria Detail Page Specific Styles */
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

        .page-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            font-weight: 400;
            margin-top: 0.5rem;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        /* Role Detail Header */
        .role-detail-header {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.2) !important;
            backdrop-filter: blur(25px) !important;
            -webkit-backdrop-filter: blur(25px) !important;
        }

        .role-detail-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        }

        .role-title {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .division-title {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 400;
            margin-bottom: 1.5rem;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .remarks-section h6 {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .remarks-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            line-height: 1.6;
            text-align: justify;
        }

        /* Criteria Card - Enhanced Glass Effect */
        .criteria-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 0;
            margin-bottom: 2rem;
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            overflow: hidden;
            position: relative;
            transition: all 0.3s ease;
            transform: translateZ(0);
        }

        .criteria-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        }

        /* Sticky Header for Criteria */
        .criteria-header {
            background: rgba(79, 172, 254, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            position: sticky;
            top: 0;
            z-index: 10;
            pointer-events: auto;
        }

        .criteria-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1.5rem;
            right: 1.5rem;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        }

        .criteria-header h6 {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            cursor: pointer;
            user-select: none;
        }

        .criteria-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .criteria-percentage {
            background: rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.95);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            backdrop-filter: blur(5px);
        }

        .criteria-dropdown-icon {
            transition: transform 0.3s ease;
            font-size: 0.9rem;
            margin-left: 0.5rem;
        }

        .criteria-dropdown-icon.rotated {
            transform: rotate(180deg);
        }

        .criteria-body {
            padding: 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
            opacity: 0;
        }

        .criteria-body.expanded {
            max-height: 1000px;
            opacity: 1;
        }

        /* Sub-criteria List (for saved items) */
        .subcriteria-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .subcriteria-item {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 0.8rem;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transform: translateZ(0);
            will-change: transform, background, box-shadow;
        }

        .subcriteria-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            color: rgba(255, 255, 255, 0.95);
        }

        .subcriteria-item:last-child {
            margin-bottom: 0;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.9);
            padding: 0.8rem 1rem;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(79, 172, 254, 0.5);
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
            color: rgba(255, 255, 255, 0.95);
        }

        /* Button Styling */
        .btn-primary {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border: none;
            color: rgba(255, 255, 255, 0.9);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #00f2fe, #4facfe);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4);
            color: rgba(255, 255, 255, 1);
        }

        .btn-secondary {
            background: rgba(108, 117, 125, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.9);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: rgba(108, 117, 125, 1);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
            color: rgba(255, 255, 255, 1);
        }

        .btn-edit {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: rgba(255, 255, 255, 0.9);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
            transform: translateZ(0);
            will-change: transform, box-shadow, background;
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #764ba2, #667eea);
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 18px rgba(102, 126, 234, 0.4);
            color: rgba(255, 255, 255, 1);
        }

        /* Action Buttons Container */
        .action-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-top: 2rem;
            justify-content: space-between;
        }

        .left-actions {
            display: flex;
            gap: 0.5rem;
        }

        .right-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Section Title */
        .section-title {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Content Wrapper */
        .content-wrapper {
            position: relative;
            overflow: visible !important;
        }

        body, html {
            overflow-x: hidden;
            overflow-y: auto !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .criteria-card {
                border-radius: 15px;
            }

            .criteria-header,
            .criteria-body {
                padding: 1rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 1rem;
            }

            .left-actions,
            .right-actions {
                justify-content: center;
                width: 100%;
            }

            .subcriteria-item {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }

            .btn-edit {
                align-self: flex-end;
            }

            .criteria-header h6 {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .role-title {
                font-size: 1.4rem;
            }

            .division-title {
                font-size: 1rem;
            }

            .role-detail-header {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.5rem;
            }

            .criteria-body {
                padding: 1rem;
            }

            .btn-primary,
            .btn-secondary {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }

            .role-title {
                font-size: 1.2rem;
            }

            .division-title {
                font-size: 0.9rem;
            }
        }
    </style>

    <div class="content-wrapper">
        {{-- Role Detail Header --}}
        <div class="role-detail-header">
            <h2 class="role-title">{{ $criteria[0]->role_name ?? 'Dev Ops Engineer' }}</h2>
            <p class="division-title">{{ $criteria[0]->division_name ?? 'Div. Managed Service' }}</p>

            <div class="remarks-section">
                <h6>Remarks</h6>
                <p class="remarks-text">
                    Setiap kriteria memiliki bobot penilaian yang telah ditentukan dan terdiri dari beberapa
                    sub-kriteria. Sub-kriteria ini perlu disesuaikan dengan tanggung jawab dan
                    karakteristik masing-masing role agar penilaian lebih relevan dan objektif.
                </p>
            </div>
        </div>

        {{-- Formulir Sub-Kriteria --}}
        <form action="{{ route('subcriteria.store') }}" method="POST" id="subcriteriaForm">
            @csrf
            <input type="hidden" name="role_id" value="{{ $role_id }}">

            @foreach ($criteria as $c)
                <div class="criteria-card">
                    <div class="criteria-header" onclick="toggleCriteria({{ $c->role_criteria_id }})">
                        <h6>
                            <div class="criteria-title">
                                <i class="fas fa-clipboard-list"></i>
                                {{ $c->criteria_name }}
                                <i class="fas fa-chevron-down criteria-dropdown-icon"
                                    id="icon-{{ $c->role_criteria_id }}"></i>
                            </div>
                            <span class="criteria-percentage">
                                @php
    // Define percentage for each criteria - sesuai requirement
    $percentages = [
        'Teamwork' => '10%',
        'Integritas Kerja' => '10%',
        'Inisiatif' => '10%',
        'Profesional Responsible' => '30%',
        'Kontribusi' => '40%',
        // Alternative names (jika ada variasi nama di database)
        'Team Work' => '10%',
        'Integrity' => '10%',
        'Work Integrity' => '10%',
        'Initiative' => '10%',
        'Professional Responsible' => '30%',
        'Team Contribution' => '40%',
        'Contribution' => '40%',
    ];
    echo $percentages[$c->criteria_name] ?? '0%';
@endphp
                            </span>
                        </h6>
                    </div>
                    <div class="criteria-body" id="body-{{ $c->role_criteria_id }}">
                        {{-- Sub-kriteria sudah disimpan --}}
                        @if (!empty($subKriterias[$c->role_criteria_id]))
                            <h6 class="section-title">Sub-Kriteria Tersimpan:</h6>
                            <ul class="subcriteria-list">
                                @foreach ($subKriterias[$c->role_criteria_id] as $sub)
                                    <li class="subcriteria-item">
                                        <span>{{ $sub->description }}</span>
                                        <button type="button" class="btn-edit"
                                            onclick="editSubcriteria({{ $sub->id }}, '{{ $sub->description }}')">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            {{-- Form input hanya muncul jika belum ada data --}}
                            <h6 class="section-title">Tambah Sub-Kriteria Baru:</h6>
                            @for ($i = 1; $i <= 4; $i++)
                                <div class="form-group">
                                    <label for="sub_{{ $c->role_criteria_id }}_{{ $i }}" class="form-label">
                                        Sub Kriteria {{ $i }}
                                    </label>
                                    <input type="text" class="form-control"
                                        id="sub_{{ $c->role_criteria_id }}_{{ $i }}"
                                        name="sub_kriteria[{{ $c->role_criteria_id }}][]"
                                        placeholder="Masukkan sub kriteria {{ $i }}" required>
                                </div>
                            @endfor
                        @endif
                    </div>
                </div>
            @endforeach

            {{-- Action Buttons --}}
            <div class="action-buttons">
                <div class="left-actions">
                    <a href="/criteria" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                </div>
                <div class="right-actions">
                    {{-- HANYA 1 BUTTON SIMPAN --}}
                    <button type="button" class="btn-primary" onclick="saveAllCriteria()">
                        <i class="fas fa-save"></i>
                        Simpan Semua Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px);">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel" style="color: #333;">Edit Sub-Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editDescription" class="form-label" style="color: #333;">Deskripsi Sub-Kriteria:</label>
                            <input type="text" class="form-control" id="editDescription" name="description"
                                style="background: rgba(0,0,0,0.05); border: 1px solid #ddd; color: #333;" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle criteria dropdown
        function toggleCriteria(criteriaId) {
            const body = document.getElementById(`body-${criteriaId}`);
            const icon = document.getElementById(`icon-${criteriaId}`);
            const card = body.closest('.criteria-card');

            body.classList.toggle('expanded');
            icon.classList.toggle('rotated');

            // Prevent background movement during dropdown interaction
            if (body.classList.contains('expanded')) {
                card.classList.add('dropdown-active');
            } else {
                card.classList.remove('dropdown-active');
            }
        }

        // Edit subcriteria function
        function editSubcriteria(id, description) {
            document.getElementById('editDescription').value = description;
            document.getElementById('editForm').action = `/subcriteria/update/${id}`;

            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        }

        // Save all criteria function - REAL SUBMISSION
        function saveAllCriteria() {
            const btn = event.target;
            const originalHTML = btn.innerHTML;
            const form = document.getElementById('subcriteriaForm');
            
            // Check if there are any filled input fields
            const formInputs = form.querySelectorAll('input[type="text"]');
            let hasData = false;
            
            formInputs.forEach(input => {
                if (input.value.trim() !== '') {
                    hasData = true;
                }
            });
            
            if (!hasData) {
                alert('Tidak ada data yang perlu disimpan.');
                return;
            }
            
            // Show loading state
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;
            
            // Validate required fields
            let isValid = true;
            const requiredInputs = form.querySelectorAll('input[required]');
            
            requiredInputs.forEach(input => {
                if (input.offsetParent !== null && input.value.trim() === '') { // Check if visible and empty
                    input.style.borderColor = '#dc3545';
                    isValid = false;
                } else {
                    input.style.borderColor = 'rgba(255, 255, 255, 0.2)';
                }
            });
            
            if (!isValid) {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
                alert('Mohon lengkapi semua field yang wajib diisi!');
                return;
            }
            
            // Submit form
            form.submit();
        }

        // Add smooth animations - SAME AS criteria.blade.php
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });

        document.querySelectorAll('.criteria-card, .role-detail-header').forEach((el) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });

        // Edit form submission handling
        document.getElementById('editForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            submitBtn.disabled = true;
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                if (editModal) {
                    editModal.hide();
                }
            }
        });
    </script>
@endsection