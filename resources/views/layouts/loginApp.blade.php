<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aigen Performance Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(135deg, #4a6cf7 0%, #667eea 50%, #764ba2 100%) !important;
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
    }
    
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
    }
    
    .welcome-header {
        position: absolute;
        top: 15%;
        left: 50%;
        transform: translateX(-50%);
        text-align: center;
        color: white;
        font-size: 3rem;
        font-weight: 600;
        margin-bottom: 60px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }
    
    .aigen-logo {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .logo-shield {
        width: 40px;
        height: 40px;
        background: rgba(0, 0, 0, 0.8);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 25px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        padding: 50px 40px;
        max-width: 400px;
        width: 100%;
        margin-top: 120px;
    }
    
    .login-title {
        color: white;
        font-weight: 500;
        font-size: 2rem;
        text-align: center;
        margin-bottom: 40px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .input-container {
        position: relative;
        margin-bottom: 25px;
    }
    
    .glass-input {
        background: rgba(255, 255, 255, 0.2) !important;
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
        border-radius: 50px !important;
        color: white !important;
        padding: 15px 50px 15px 20px !important;
        font-size: 1rem;
        width: 100%;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-sizing: border-box;
    }
    
    .glass-input:focus {
        background: rgba(255, 255, 255, 0.3) !important;
        border-color: rgba(255, 255, 255, 0.6) !important;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        outline: none !important;
    }
    
    .glass-input::placeholder {
        color: rgba(255, 255, 255, 0.8) !important;
        font-weight: 400;
    }
    
    .input-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.7);
        font-size: 18px;
        pointer-events: none;
    }
    
    .remember-container {
        display: flex;
        align-items: center;
        margin: 30px 0;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.95rem;
    }
    
    .remember-checkbox {
        width: 18px;
        height: 18px;
        margin-right: 10px;
        accent-color: rgba(255, 255, 255, 0.8);
        background: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
    }
    
    .glass-btn {
        background: rgba(255, 255, 255, 0.2) !important;
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
        border-radius: 50px !important;
        color: white !important;
        font-weight: 500;
        padding: 15px !important;
        font-size: 1.1rem;
        width: 100%;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        cursor: pointer;
        margin-top: 10px;
    }
    
    .glass-btn:hover {
        background: rgba(255, 255, 255, 0.3) !important;
        border-color: rgba(255, 255, 255, 0.6) !important;
        color: white !important;
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .glass-btn:active {
        transform: translateY(0);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .welcome-header {
            font-size: 2.2rem;
            top: 10%;
        }
        
        .glass-card {
            padding: 40px 30px;
            margin-top: 100px;
            margin-left: 15px;
            margin-right: 15px;
        }
        
        .login-title {
            font-size: 1.8rem;
        }
    }
    
    @media (max-width: 576px) {
        .welcome-header {
            font-size: 1.8rem;
        }
        
        .glass-card {
            padding: 35px 25px;
        }
        
        .login-title {
            font-size: 1.5rem;
        }
        
        .glass-input {
            padding: 12px 45px 12px 18px !important;
        }
        
        .glass-btn {
            padding: 12px !important;
        }
    }
    
    /* Icons menggunakan Unicode symbols */
    .user-icon::before {
        content: "👤";
    }
    
    .lock-icon::before {
        content: "🔒";
    }
</style>
</head>
<body>
    <div class="main">
        @yield('content')
    </div>
</body>
</html>
