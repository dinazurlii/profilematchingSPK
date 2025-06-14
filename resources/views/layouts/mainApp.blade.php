<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aigen Performance Monitor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background particles 
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 119, 198, 0.2) 0%, transparent 50%);
            animation: float 6s ease-in-out infinite;
            pointer-events: none;
            z-index: -1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        } */ 

        /* Glassmorphism Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0 25px 25px 0;
            color: white;
            padding: 2rem 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out; /* Smooth Transition */
            position: relative;
            z-index: 10;
            min-height: 100vh;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            border-radius: 0 25px 25px 0;
            transition: transform 0.3s ease-in-out; /* Smooth Transition */
            pointer-events: none;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .sidebar-header h4 {
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .sidebar-header .icon {
            font-size: 2rem;
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            border-radius: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            font-weight: 500;
            gap: 1rem;
        }

        .nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateX(10px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .nav-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            margin: 2rem 0;
        }

        .logout-section {
            margin-top: auto;
            padding-top: 2rem;
        }

        /* Main Content Area */
        .main {
    flex-grow: 1;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    margin: 2rem;
    margin-left: 0; /* UBAH DARI 20 KE 0 */
    border-radius: 25px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
    transition: margin-left 0.3s ease-in-out;
    min-height: calc(100vh - 4rem); /* TAMBAH INI */
    width: auto; /* TAMBAH INI */
}

/* Saat sidebar disembunyikan */
.main.fullscreen {
    margin-left: 0cm;
}

        .main::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.02) 100%);
            border-radius: 25px;
            pointer-events: none;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
            color: white;
        }

        /* Welcome Card */
        .welcome-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .welcome-card h2 {
            color: white;
            font-weight: 600;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .welcome-card p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            line-height: 1.6;
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
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    
        .search-box {
            position: relative;
            min-width: 300px;
        }

        .search-input {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            color: white;
            padding: 0.75rem 1rem 0.75rem 3rem;
            width: 100%;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-input:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            outline: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 500;
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
            color: white;
        }

        /* Employee Table */
        .employee-table-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 0;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-header {
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .table-title {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .employee-table {
            width: 100%;
            margin: 0;
            background: transparent;
            color: black;
        }

        .employee-table th {
            background: rgba(255, 255, 255, 0.05);
            border: none;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            padding: 1rem;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .employee-table td {
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem;
            color: rgba(255, 255, 255, 0.9);
            vertical-align: middle;
        }

        .employee-table tbody tr {
            transition: all 0.3s ease;
        }

        .employee-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: scale(1.01);
        }

        /* Action Dropdown */
        .action-dropdown {
            position: relative;
        }

        .action-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 15px;
            font-size: 0.85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: linear-gradient(135deg, #764ba2, #667eea);
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .dropdown-item {
            color: white;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .dropdown-item.text-danger:hover {
            background: rgba(220, 53, 69, 0.2);
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
                transition: transform 0.3s ease-in-out; /* Smooth Transition */
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
            }
        }

        /* Sidebar */

/* Sidebar Hidden */
.sidebar.hidden {
    transform: translateX(-100%);
}

/* Hamburger Button */
.hamburger-btn {
    position: fixed;
    bottom: 15px; /* Pindahkan ke bawah */
    left: 15px;
    font-size: 24px;
    background: none;
    border: none;
    cursor: pointer;
    z-index: 1001;
    color: white;
    background-color: transparent;
    padding: 8px 12px;
    border-radius: 6px;
}
    </style>
</head>
<body>
    <button id="hamburger-btn" class="hamburger-btn">☰</button>
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>
                <span class="icon">🏢</span>
                Aigen Corp
            </h4>
        </div>

        
        <nav>
            <ul class="nav-menu">
                <li class="nav-item">
    <a href="/dashboard" class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
</li>
<li class="nav-item">
    <a href="/performance" class="nav-link {{ Request::is('performance*') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span>Performance Review</span>
    </a>
</li>
<li class="nav-item">
    <a href="/employees" class="nav-link {{ Request::is('employees*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <span>Karyawan</span>
    </a>
</li>
<li class="nav-item">
    <a href="/criteria" class="nav-link {{ Request::is('criteria*') ? 'active' : '' }}">
        <i class="fas fa-clipboard-list"></i>
        <span>Kriteria</span>
    </a>
</li>
            </ul>
        </nav>
        
        <div class="nav-divider"></div>
        
        <div class="logout-section">
            <a href="/logout" class="nav-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>

      

    </div>
    
        <div class="main">
        @yield('content')
      
            </div>
     

    <script>

    console.log('Script loaded!');  // Untuk cek apakah script jalan

    document.addEventListener('DOMContentLoaded', function() {
    // Remove all active
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });

    // Add active based on current page
    const path = window.location.pathname;
    
    if (path.includes('dashboard')) {
        document.querySelector('a[href="/dashboard"]').classList.add('active');
    }
    else if (path.includes('criteria')) {
        document.querySelector('a[href="/criteria"]').classList.add('active');
    }
    else if (path.includes('employees')) {
        document.querySelector('a[href="/employees"]').classList.add('active');
    }
    else if (path.includes('performance')) {
        document.querySelector('a[href="/performance"]').classList.add('active');
    }
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

        document.querySelectorAll('.welcome-card').forEach((el) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });

      const hamburgerBtn = document.getElementById('hamburger-btn');
const sidebar = document.querySelector('.sidebar');
const main = document.querySelector('.main');

hamburgerBtn.addEventListener('click', () => {
    sidebar.classList.toggle('hidden');
    main.classList.toggle('fullscreen');
});

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Your existing script content...
        console.log('Script loaded!');
        
        document.addEventListener('DOMContentLoaded', function() {
            // Your existing navigation code...
            // Remove all active
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });

            // Add active based on current page
            const path = window.location.pathname;
            
            if (path.includes('dashboard')) {
                document.querySelector('a[href="/dashboard"]').classList.add('active');
            }
            else if (path.includes('criteria')) {
                document.querySelector('a[href="/criteria"]').classList.add('active');
            }
            else if (path.includes('employees')) {
                document.querySelector('a[href="/employees"]').classList.add('active');
            }
            else if (path.includes('performance')) {
                document.querySelector('a[href="/performance"]').classList.add('active');
            }
        });

        // Your existing animation and sidebar code...
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });

        document.querySelectorAll('.welcome-card').forEach((el) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });

        const hamburgerBtn = document.getElementById('hamburger-btn');
        const sidebar = document.querySelector('.sidebar');
        const main = document.querySelector('.main');

        hamburgerBtn.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
            main.classList.toggle('fullscreen');
        });
    </script>
</body>
</html>