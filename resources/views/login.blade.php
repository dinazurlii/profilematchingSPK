@extends('layouts.loginApp')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #4a6cf7 0%, #667eea 50%, #764ba2 100%) !important;
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }
    
    /* Floating background particles */
    .bg-particles {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }
    
    .particle {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite;
    }
    
    .particle:nth-child(1) {
        width: 20px;
        height: 20px;
        top: 20%;
        left: 10%;
        animation-delay: 0s;
        animation-duration: 6s;
    }
    
    .particle:nth-child(2) {
        width: 15px;
        height: 15px;
        top: 60%;
        left: 85%;
        animation-delay: 2s;
        animation-duration: 8s;
    }
    
    .particle:nth-child(3) {
        width: 25px;
        height: 25px;
        top: 80%;
        left: 20%;
        animation-delay: 4s;
        animation-duration: 7s;
    }
    
    .particle:nth-child(4) {
        width: 12px;
        height: 12px;
        top: 30%;
        left: 70%;
        animation-delay: 1s;
        animation-duration: 9s;
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px) translateX(0px) rotate(0deg);
            opacity: 0.3;
        }
        25% {
            transform: translateY(-20px) translateX(10px) rotate(90deg);
            opacity: 0.6;
        }
        50% {
            transform: translateY(-40px) translateX(-10px) rotate(180deg);
            opacity: 1;
        }
        75% {
            transform: translateY(-20px) translateX(15px) rotate(270deg);
            opacity: 0.6;
        }
    }
    
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
        z-index: 10;
    }
    
    .welcome-header {
        position: absolute;
        top: 15%;
        left: 50%;
        transform: translateX(-50%);
        text-align: center;
        color: white;
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 60px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 10;
        opacity: 0;
        animation: fadeInDown 1s ease-out 0.3s forwards;
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
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
        opacity: 0;
        transform: translateY(50px);
        animation: fadeInUp 1s ease-out 0.6s forwards;
        position: relative;
        overflow: hidden;
    }
    
    .glass-card::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        border-radius: 25px;
        opacity: 0;
        animation: borderGlow 3s ease-in-out infinite;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes borderGlow {
        0%, 100% {
            opacity: 0;
            transform: rotate(0deg);
        }
        50% {
            opacity: 1;
            transform: rotate(180deg);
        }
    }
    
    .login-title {
        color: white;
        font-weight: 500;
        font-size: 2rem;
        text-align: center;
        margin-bottom: 40px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        opacity: 0;
        animation: fadeIn 1s ease-out 1s forwards;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .input-container {
        position: relative;
        margin-bottom: 25px;
        opacity: 0;
        transform: translateX(-30px);
        animation: slideInLeft 0.8s ease-out forwards;
    }
    
    .input-container:nth-child(2) {
        animation-delay: 1.2s;
    }
    
    .input-container:nth-child(3) {
        animation-delay: 1.4s;
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .glass-input {
        background: rgba(255, 255, 255, 0.2) !important;
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
        border-radius: 50px !important;
        color: white !important;
        padding: 15px 50px 15px 20px !important;
        font-size: 1rem;
        width: 100%;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-sizing: border-box;
        position: relative;
    }
    
    .glass-input:focus {
        background: rgba(255, 255, 255, 0.3) !important;
        border-color: rgba(255, 255, 255, 0.6) !important;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        outline: none !important;
        transform: translateY(-2px);
    }
    
    .glass-input::placeholder {
        color: rgba(255, 255, 255, 0.8) !important;
        font-weight: 400;
        transition: all 0.3s ease;
    }
    
    .glass-input:focus::placeholder {
        transform: translateY(-2px);
        opacity: 0.6;
    }
    
    .input-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.7);
        font-size: 18px;
        pointer-events: none;
        transition: all 0.3s ease;
    }
    
    .glass-input:focus + .input-icon {
        color: rgba(255, 255, 255, 1);
        transform: translateY(-50%) scale(1.1);
    }
    
    .remember-container {
        display: flex;
        align-items: center;
        margin: 30px 0;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.95rem;
        opacity: 0;
        animation: fadeIn 0.8s ease-out 1.6s forwards;
    }
    
    .remember-checkbox {
        width: 18px;
        height: 18px;
        margin-right: 10px;
        accent-color: rgba(255, 255, 255, 0.8);
        background: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    .remember-checkbox:checked {
        transform: scale(1.1);
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
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        cursor: pointer;
        margin-top: 10px;
        position: relative;
        overflow: hidden;
        opacity: 0;
        animation: fadeInScale 0.8s ease-out 1.8s forwards;
    }
    
    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .glass-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .glass-btn:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .glass-btn:hover {
        background: rgba(255, 255, 255, 0.3) !important;
        border-color: rgba(255, 255, 255, 0.6) !important;
        color: white !important;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    
    .glass-btn:active {
        transform: translateY(-1px);
        transition: transform 0.1s;
    }
    
    /* Loading state */
    .glass-btn.loading {
        pointer-events: none;
    }
    
    .glass-btn.loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .welcome-header {
            font-size: 2rem;
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
            font-size: 1.6rem;
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
    
    /* Icons */
    .user-icon::before {
        content: "👤";
    }
    
    .lock-icon::before {
        content: "🔒";
    }
</style>

<div class="bg-particles">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
</div>

<div class="login-container">
    <div class="welcome-header">
        Aigen Performance Review
    </div>
    
    <div class="glass-card">
        <h2 class="login-title">Login</h2>
        
        <form method="POST" action="{{ route('login.process') }}" id="loginForm">
            @csrf
            <div class="input-container">
                <input type="text" 
                       name="username" 
                       class="form-control glass-input" 
                       placeholder="Username"
                       required />
                <div class="input-icon user-icon"></div>
            </div>
            
            <div class="input-container">
                <input type="password" 
                       name="password" 
                       class="form-control glass-input" 
                       placeholder="Password"
                       required />
                <div class="input-icon lock-icon"></div>
            </div>
            
            <div class="remember-container">
                <input type="checkbox" 
                       name="remember" 
                       class="remember-checkbox" 
                       id="remember">
                <label for="remember">Remember me</label>
            </div>
            
            <button type="submit" class="btn glass-btn" id="loginBtn">
                Login
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const btn = document.getElementById('loginBtn');
    
    form.addEventListener('submit', function(e) {
        btn.classList.add('loading');
        btn.innerHTML = '';
        
        // Remove loading state after 3 seconds if form hasn't submitted
        setTimeout(() => {
            if (btn.classList.contains('loading')) {
                btn.classList.remove('loading');
                btn.innerHTML = 'Login';
            }
        }, 3000);
    });
});
</script>
@endsection