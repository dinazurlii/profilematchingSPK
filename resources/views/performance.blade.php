@extends('layouts.mainApp')

@if (!session()->has('user'))
    <script>
        window.location.href = '/login';
    </script>
@endif

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

    /* Page Container - Same as Dashboard */
    .container {
        max-width: 1000px !important;
        margin: 0 auto !important;
        padding: 0 !important;
    }

    /* Content Wrapper - Same as Dashboard */
    .content-wrapper {
        position: relative;
        z-index: 1;
        color: white;
    }

    /* Main Form Container - Same as Dashboard */
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

    /* Page Title - Same as Dashboard */
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

    /* Breadcrumb - Same as Dashboard */
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

    /* Section Cards - Same as Dashboard */
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

    /* Division Items - Same styling as Dashboard cards */
    .division-grid {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .glass-item {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        cursor: pointer;
    }

    .glass-item:hover {
        background: rgba(255, 255, 255, 0.12);
        transform: translateY(-2px);
    }

    /* Division Icons - Enhanced */
    .division-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(255, 255, 255, 0.2);
        flex-shrink: 0;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
    }

    .glass-item:hover .division-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4);
        border-color: rgba(255, 255, 255, 0.4);
    }

    .division-icon i {
        font-size: 1.5rem;
        color: white;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .glass-item:hover .division-icon i {
        transform: scale(1.1);
        filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.5));
    }

    /* Division Content Layout */
    .division-content {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        width: 100%;
    }

    .division-text {
        flex: 1;
    }

    .division-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        transition: all 0.3s ease;
        text-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .division-name:hover {
        color: rgba(255, 255, 255, 1);
        text-decoration: none;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        transform: translateX(5px);
    }

    /* Arrow Icon Enhancement */
    .fa-arrow-right {
        transition: all 0.3s ease;
        opacity: 0.8;
        font-size: 0.9rem;
    }

    .glass-item:hover .fa-arrow-right {
        opacity: 1;
        transform: translateX(5px);
    }

    /* Icon Color Variations */
    .division-managed-service .division-icon {
        background: linear-gradient(135deg, #28a745, #20c997);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }

    .division-research .division-icon {
        background: linear-gradient(135deg, #fd7e14, #e67e22);
        box-shadow: 0 5px 15px rgba(253, 126, 20, 0.3);
    }

    .division-project .division-icon {
        background: linear-gradient(135deg, #6f42c1, #8e44ad);
        box-shadow: 0 5px 15px rgba(111, 66, 193, 0.3);
    }

    .glass-item:hover .division-managed-service .division-icon {
        box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
    }

    .glass-item:hover .division-research .division-icon {
        box-shadow: 0 8px 20px rgba(253, 126, 20, 0.4);
    }

    .glass-item:hover .division-project .division-icon {
        box-shadow: 0 8px 20px rgba(111, 66, 193, 0.4);
    }

    /* Responsive Design - Same as Dashboard */
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

        .section-card {
            padding: 1.5rem;
        }

        .glass-item {
            padding: 1.2rem;
            border-radius: 12px;
        }

        .division-icon {
            width: 50px;
            height: 50px;
        }

        .division-icon i {
            font-size: 1.3rem;
        }

        .division-name {
            font-size: 1.1rem;
        }

        .division-content {
            gap: 1rem;
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

        .section-card {
            padding: 1rem;
        }

        .glass-item {
            padding: 1rem;
        }

        .division-icon {
            width: 45px;
            height: 45px;
        }

        .division-icon i {
            font-size: 1.2rem;
        }

        .division-name {
            font-size: 1rem;
        }
    }

    /* Animation - Same as Dashboard */
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

    /* Focus States for Accessibility */
    .glass-item:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(79, 172, 254, 0.5);
        box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.2);
    }

    /* Pulse animation for icons */
    @keyframes iconPulse {
        0% {
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
        }
        50% {
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.5);
        }
        100% {
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
        }
    }

    .division-icon:hover {
        animation: iconPulse 2s infinite;
    }
</style>

@section('content')
<div class="content-wrapper">
    <!-- Breadcrumb - Same as Dashboard -->
    <div class="breadcrumb">
        <a href="/dashboard">Dashboard</a> / 
        <a href="/performance">Performance Reviews</a>
    </div>

    <!-- Main Container - Same Structure as Dashboard -->
    <div class="container">
        <div class="evaluation-form-container">
            <!-- Page Title - Same as Dashboard -->
            <h2 class="page-title">
                <i class="fas fa-chart-line"></i>
                Employee's Review
            </h2>

            <!-- Division Selection Section -->
            <div class="section-card">
                <h3 class="section-title">
                    <i class="fas fa-building"></i>
                    Pilih Divisi yang akan di review
                </h3>

                <!-- Division List -->
                <div class="division-grid">
                    <!-- Managed Service -->
                    <div class="glass-item division-item">
                        <div class="division-content">
                            <div class="division-icon division-managed-service">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div class="division-text">
                                <a href="{{ route('performance.reviews', ['division_id' => 1]) }}"
                                    class="division-name">
                                    <i class="fas fa-arrow-right"></i>
                                    Managed Service
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Research And Development -->
                    <div class="glass-item division-item">
                        <div class="division-content">
                            <div class="division-icon division-research">
                                <i class="fas fa-flask"></i>
                            </div>
                            <div class="division-text">
                                <a href="{{ route('performance.reviews', ['division_id' => 2]) }}"
                                    class="division-name">
                                    <i class="fas fa-arrow-right"></i>
                                    Research And Development
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Project Delivery -->
                    <div class="glass-item division-item">
                        <div class="division-content">
                            <div class="division-icon division-project">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <div class="division-text">
                                <a href="{{ route('performance.reviews', ['division_id' => 4]) }}"
                                    class="division-name">
                                    <i class="fas fa-arrow-right"></i>
                                    Project Delivery
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Enhanced animations like kriteria - Smooth entrance animations
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });

        // Animate header with initial delay
        const headerSection = document.querySelector('.header-section');
        if (headerSection) {
            headerSection.style.opacity = '0';
            headerSection.style.transform = 'translateY(20px)';
            headerSection.style.transition = 'all 0.6s ease';
            observer.observe(headerSection);
        }

        // Animate glass header
        const glassHeader = document.querySelector('.glass-header');
        if (glassHeader) {
            glassHeader.style.opacity = '0';
            glassHeader.style.transform = 'translateY(20px)';
            glassHeader.style.transition = 'all 0.6s ease 0.2s';
            observer.observe(glassHeader);
        }

        // Animate division items with staggered delay (like kriteria)
        document.querySelectorAll('.division-item').forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = `all 0.6s ease ${0.4 + (index * 0.15)}s`;
            observer.observe(item);
        });

        // Enhanced hover interactions for division items
        document.querySelectorAll('.glass-item').forEach(item => {
            const icon = item.querySelector('.division-icon');
            const name = item.querySelector('.division-name');
            const arrow = item.querySelector('.fa-arrow-right');

            item.addEventListener('mouseenter', function() {
                // Icon animation
                if (icon) {
                    icon.style.transform = 'scale(1.1) rotate(5deg)';
                }
                
                // Arrow animation
                if (arrow) {
                    arrow.style.transform = 'translateX(5px)';
                    arrow.style.opacity = '1';
                }
                
                // Name animation
                if (name) {
                    name.style.transform = 'translateX(10px)';
                }
            });

            item.addEventListener('mouseleave', function() {
                // Reset icon
                if (icon) {
                    icon.style.transform = 'scale(1) rotate(0deg)';
                }
                
                // Reset arrow
                if (arrow) {
                    arrow.style.transform = 'translateX(0)';
                    arrow.style.opacity = '0.8';
                }
                
                // Reset name
                if (name) {
                    name.style.transform = 'translateX(0)';
                }
            });

            // Click feedback animation
            item.addEventListener('click', function() {
                this.style.transform = 'translateY(-3px) scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-3px) scale(1.02)';
                }, 150);
            });
        });

        // Add smooth scroll behavior for any internal navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Enhanced loading feedback for external links
        document.querySelectorAll('a[href^="{{ route"]').forEach(link => {
            link.addEventListener('click', function() {
                const item = this.closest('.glass-item');
                const icon = item.querySelector('.division-icon');
                
                if (icon) {
                    icon.style.animation = 'iconPulse 0.5s ease-in-out';
                    
                    // Add loading state
                    setTimeout(() => {
                        icon.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    }, 200);
                }
            });
        });

        // Smooth appearance animation for the whole page
        const contentWrapper = document.querySelector('.content-wrapper, section');
        if (contentWrapper) {
            contentWrapper.style.opacity = '0';
            contentWrapper.style.transform = 'translateY(10px)';
            contentWrapper.style.transition = 'all 0.8s ease';
            
            setTimeout(() => {
                contentWrapper.style.opacity = '1';
                contentWrapper.style.transform = 'translateY(0)';
            }, 100);
        }
    });

    // Add keyboard navigation support
    document.addEventListener('keydown', function(e) {
        const items = document.querySelectorAll('.glass-item');
        const currentFocus = document.activeElement;
        const currentIndex = Array.from(items).indexOf(currentFocus);

        if (e.key === 'ArrowDown' && currentIndex < items.length - 1) {
            e.preventDefault();
            items[currentIndex + 1].focus();
        } else if (e.key === 'ArrowUp' && currentIndex > 0) {
            e.preventDefault();
            items[currentIndex - 1].focus();
        } else if (e.key === 'Enter' && currentFocus.classList.contains('glass-item')) {
            e.preventDefault();
            const link = currentFocus.querySelector('a');
            if (link) {
                link.click();
            }
        }
    });

    // Make glass items focusable for accessibility
    document.querySelectorAll('.glass-item').forEach(item => {
        item.setAttribute('tabindex', '0');
        item.addEventListener('focus', function() {
            this.style.background = 'rgba(255, 255, 255, 0.25)';
            this.style.transform = 'translateY(-2px)';
        });
        item.addEventListener('blur', function() {
            this.style.background = 'rgba(255, 255, 255, 0.12)';
            this.style.transform = 'translateY(0)';
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection