@extends('layouts.mainApp')

@if (!session()->has('user'))
    <script>
        window.location.href = '/login';
    </script>
@endif

@section('content')
<style>
    /* Base Styles - Same as employees.blade.php */
    * {
        box-sizing: border-box;
    }

    /* Page Header */
    .breadcrumb {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .breadcrumb a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb a:hover {
        color: rgba(255, 255, 255, 1);
    }

    /* Main Container */
    .detail-container {
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

    .detail-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    }

    /* Header Section */
    .detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        position: relative;
    }

    .employee-profile {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .employee-avatar {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .employee-details h5 {
        color: white;
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .employee-details small {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
    }

    /* Detail Body */
    .detail-body {
        padding: 2rem;
    }

    .detail-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .detail-meta {
        display: flex;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .detail-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .detail-meta-item i {
        color: rgba(255, 255, 255, 0.7);
    }

    /* Final Score Card */
    .final-score-card {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 1.5rem;
        border-radius: 15px;
        text-align: center;
        min-width: 150px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .final-score-card h3 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0.5rem 0 0 0;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
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
        color: rgba(255, 255, 255, 0.95);
    }

    .alert-warning {
        border-left-color: #ffc107;
    }

    /* Criteria Section */
    .criteria-section {
        margin-bottom: 2rem;
    }

    .criteria-header {
        background: rgba(255, 255, 255, 0.1);
        padding: 1.5rem;
        border-radius: 15px;
        margin-bottom: 1rem;
        position: relative;
    }

    .criteria-title {
        color: rgba(102, 126, 234, 0.9);
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .criteria-average {
        background: rgba(102, 126, 234, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 700;
        color: rgba(255, 255, 255, 1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Sub-criteria Results */
    .sub-criteria-result {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 1rem;
        transition: all 0.3s ease;
    }

    .sub-criteria-result:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .result-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }

    .result-description {
        flex: 1;
    }

    .result-description p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
        line-height: 1.5;
    }

    .result-meta {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .result-meta i {
        margin-right: 0.3rem;
    }

    /* Score Display */
    .score-display {
        text-align: center;
        min-width: 120px;
    }

    .score-badge {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 0.75rem;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .star-rating {
        display: flex;
        justify-content: center;
        gap: 0.2rem;
    }

    .star-rating i {
        font-size: 1.2rem;
    }

    .star-rating .fas.fa-star {
        color: #ffc107;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .star-rating .far.fa-star {
        color: rgba(255, 255, 255, 0.4);
    }

    /* Performance Summary */
    .performance-summary {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .performance-summary h6 {
        color: rgba(255, 255, 255, 0.95);
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0 0 1rem 0;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.95rem;
    }

    .summary-item strong {
        color: rgba(255, 255, 255, 0.95);
    }

    /* Performance Badges */
    .performance-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .performance-badge.excellent {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    .performance-badge.good {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .performance-badge.fair {
        background: linear-gradient(135deg, #ffc107, #ffeb3b);
        color: rgba(0, 0, 0, 0.8);
    }

    .performance-badge.needs-improvement {
        background: linear-gradient(135deg, #dc3545, #ff6b6b);
        color: white;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .btn-large {
        padding: 1rem 2rem;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        cursor: pointer;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        min-width: 160px;
        justify-content: center;
    }

    .btn-warning {
        background: linear-gradient(135deg, #ffc107, #ffeb3b);
        color: rgba(0, 0, 0, 0.8);
    }

    .btn-warning:hover {
        background: linear-gradient(135deg, #ffeb3b, #ffc107);
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
        color: rgba(0, 0, 0, 0.9);
    }

    .btn-secondary {
        background: rgba(108, 117, 125, 0.8);
        color: rgba(255, 255, 255, 0.9);
    }

    .btn-secondary:hover {
        background: rgba(108, 117, 125, 1);
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        color: white;
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .detail-header,
        .detail-body {
            padding: 1.5rem;
        }

        .detail-info {
            flex-direction: column;
            align-items: stretch;
        }

        .final-score-card {
            width: 100%;
        }

        .result-content {
            flex-direction: column;
            gap: 1rem;
        }

        .score-display {
            text-align: left;
            min-width: auto;
        }

        .star-rating {
            justify-content: flex-start;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-large {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .employee-profile {
            flex-direction: column;
            text-align: center;
        }

        .detail-meta {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>

<div class="content-wrapper">
    <div class="breadcrumb">
        <a href="/performance">Performance</a> / <a href="/performance/reviews?division_id={{ $employee->division_id }}">Review</a> / Detail
    </div>

    <div class="detail-container">
        <div class="detail-header">
            <div class="employee-profile">
                <div class="employee-avatar">{{ strtoupper(substr($employee->name, 0, 2)) }}</div>
                <div class="employee-details">
                    <h5>{{ $employee->name }}</h5>
                    <small>{{ $employee->role->name ?? 'Unknown Role' }}, {{ $employee->division->name ?? 'Unknown Division' }}</small>
                </div>
            </div>
        </div>

        <div class="detail-body">
            <div class="detail-info">
                <div class="detail-meta">
                    <div class="detail-meta-item">
                        <i class="fas fa-user"></i>
                        <span>NIP: {{ $employee->nip }}</span>
                    </div>
                    <div class="detail-meta-item">
                        <i class="fas fa-calendar"></i>
                        <span>Submitted: {{ $employee->submission ?? 'Not submitted' }}</span>
                    </div>
                </div>
                <div class="final-score-card">
                    <div>Final Score</div>
                    <h3>{{ $finalScore }}</h3>
                </div>
            </div>

            @if(empty($groupedScores))
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    No performance review data found for this employee.
                </div>
            @else
                @foreach($groupedScores as $criteriaId => $criteria)
                <div class="criteria-section">
                    <div class="criteria-header">
                        <h5 class="criteria-title">
                            {{ $criteria['name'] }} ({{ $criteria['code'] ?? '10%' }})
                            @php
                                $criteriaAvg = collect($criteria['sub_criteria'])->avg('score');
                            @endphp
                            <span class="criteria-average">Average Score: {{ number_format($criteriaAvg, 1) }}</span>
                        </h5>
                    </div>

                    @foreach($criteria['sub_criteria'] as $subCriteria)
                    <div class="sub-criteria-result">
                        <div class="result-content">
                            <div class="result-description">
                                <p>{{ $subCriteria['description'] }}</p>
                                <div class="result-meta">
                                    <span>
                                        <i class="fas fa-user"></i>Evaluated by: {{ $subCriteria['evaluator_name'] ?? 'Unknown' }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock"></i>{{ date('d M Y H:i', strtotime($subCriteria['evaluated_at'])) }}
                                    </span>
                                </div>
                            </div>
                            <div class="score-display">
                                <div class="score-badge">Score: {{ $subCriteria['score'] }}/5</div>
                                <div class="star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $subCriteria['score'])
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach

                <div class="performance-summary">
                    <h6>Performance Summary</h6>
                    <div class="summary-grid">
                        <div class="summary-item">
                            <strong>Total Criteria Evaluated:</strong>
                            <span>{{ count($groupedScores) }}</span>
                        </div>
                        <div class="summary-item">
                            <strong>Final Score:</strong>
                            <span>{{ $finalScore }}/5.0</span>
                        </div>
                        <div class="summary-item">
                            <strong>Overall Performance:</strong>
                            @if($finalScore >= 4.5)
                                <span class="performance-badge excellent">Excellent</span>
                            @elseif($finalScore >= 3.5)
                                <span class="performance-badge good">Good</span>
                            @elseif($finalScore >= 2.5)
                                <span class="performance-badge fair">Fair</span>
                            @else
                                <span class="performance-badge needs-improvement">Needs Improvement</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="action-buttons">
                <a href="{{ route('scores.create', $employee->nip) }}" class="btn-large btn-warning">
                    <i class="fas fa-edit"></i>Edit Review
                </a>
                <a href="{{ route('performance.reviews', ['division_id' => $employee->division_id]) }}" class="btn-large btn-secondary">
                    <i class="fas fa-arrow-left"></i>Back to Reviews
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth entrance animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    // Animate all sections
    document.querySelectorAll('.criteria-section, .sub-criteria-result, .performance-summary').forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(el);
    });

    // Animate star ratings on scroll
    document.querySelectorAll('.star-rating').forEach(rating => {
        const stars = rating.querySelectorAll('i');
        
        const animateStars = () => {
            stars.forEach((star, index) => {
                if (star.classList.contains('fas')) {
                    setTimeout(() => {
                        star.style.transform = 'scale(1.3)';
                        star.style.filter = 'drop-shadow(0 0 4px #ffc107)';
                        
                        setTimeout(() => {
                            star.style.transform = 'scale(1)';
                            star.style.filter = 'none';
                        }, 200);
                    }, index * 100);
                }
            });
        };

        // Trigger animation when section comes into view
        observer.observe(rating.closest('.sub-criteria-result'));
        
        // Add hover effect for interactivity
        rating.addEventListener('mouseenter', animateStars);
    });

    // Add subtle pulse animation to performance badges
    document.querySelectorAll('.performance-badge').forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
            this.style.boxShadow = '0 4px 15px rgba(255, 255, 255, 0.3)';
        });
        
        badge.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.1)';
        });
    });

    // Add loading animation for score badges
    document.querySelectorAll('.score-badge').forEach((badge, index) => {
        setTimeout(() => {
            badge.style.animation = 'none';
            badge.style.transform = 'scale(1.1)';
            badge.style.transition = 'transform 0.3s ease';
            
            setTimeout(() => {
                badge.style.transform = 'scale(1)';
            }, 200);
        }, index * 150);
    });

    // Enhanced button interactions
    document.querySelectorAll('.btn-large').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.02)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>
@endsection