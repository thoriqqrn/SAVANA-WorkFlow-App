@php
    $appName = \App\Models\Setting::get('app_name', 'CMOS');
    $themeColor = \App\Models\Setting::get('theme_color', 'purple');
    $themeColors = [
        'purple' => ['primary' => '#7C3AED', 'hover' => '#6D28D9', 'soft' => '#A78BFA', 'light' => '#EDE9FE'],
        'blue' => ['primary' => '#3B82F6', 'hover' => '#2563EB', 'soft' => '#60A5FA', 'light' => '#DBEAFE'],
        'green' => ['primary' => '#10B981', 'hover' => '#059669', 'soft' => '#34D399', 'light' => '#D1FAE5'],
        'red' => ['primary' => '#EF4444', 'hover' => '#DC2626', 'soft' => '#F87171', 'light' => '#FEE2E2'],
        'orange' => ['primary' => '#F59E0B', 'hover' => '#D97706', 'soft' => '#FBBF24', 'light' => '#FEF3C7'],
        'pink' => ['primary' => '#EC4899', 'hover' => '#DB2777', 'soft' => '#F472B6', 'light' => '#FCE7F3'],
        'indigo' => ['primary' => '#6366F1', 'hover' => '#4F46E5', 'soft' => '#818CF8', 'light' => '#E0E7FF'],
        'teal' => ['primary' => '#14B8A6', 'hover' => '#0D9488', 'soft' => '#2DD4BF', 'light' => '#CCFBF1'],
        'cyan' => ['primary' => '#06B6D4', 'hover' => '#0891B2', 'soft' => '#22D3EE', 'light' => '#CFFAFE'],
        'rose' => ['primary' => '#F43F5E', 'hover' => '#E11D48', 'soft' => '#FB7185', 'light' => '#FFE4E6'],
        'amber' => ['primary' => '#F59E0B', 'hover' => '#D97706', 'soft' => '#FBBF24', 'light' => '#FEF3C7'],
        'slate' => ['primary' => '#64748B', 'hover' => '#475569', 'soft' => '#94A3B8', 'light' => '#F1F5F9'],
    ];
    $colors = $themeColors[$themeColor] ?? $themeColors['purple'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ $appName }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🔗</text></svg>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary: {{ $colors['primary'] }};
            --primary-hover: {{ $colors['hover'] }};
            --primary-soft: {{ $colors['soft'] }};
            --primary-light: {{ $colors['light'] }};
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-soft) 50%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated Background Shapes */
        .bg-shapes {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }
        
        .bg-shapes::before,
        .bg-shapes::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 15s infinite ease-in-out;
        }
        
        .bg-shapes::before {
            width: 400px;
            height: 400px;
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }
        
        .bg-shapes::after {
            width: 300px;
            height: 300px;
            bottom: -50px;
            left: -50px;
            animation-delay: -7s;
        }
        
        .shape-1, .shape-2, .shape-3 {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            animation: float 20s infinite ease-in-out;
        }
        
        .shape-1 {
            width: 200px;
            height: 200px;
            top: 50%;
            left: 10%;
            animation-delay: -3s;
        }
        
        .shape-2 {
            width: 150px;
            height: 150px;
            top: 20%;
            left: 60%;
            animation-delay: -10s;
        }
        
        .shape-3 {
            width: 100px;
            height: 100px;
            bottom: 30%;
            right: 15%;
            animation-delay: -5s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-20px) rotate(5deg); }
            50% { transform: translateY(10px) rotate(-5deg); }
            75% { transform: translateY(-10px) rotate(3deg); }
        }
        
        /* Login Container */
        .login-container {
            display: flex;
            width: 100%;
            max-width: 1000px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }
        
        /* Left Panel - Branding */
        .login-brand {
            flex: 1;
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-brand::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }
        
        .login-brand::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
        }
        
        .brand-logo {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }
        
        .brand-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }
        
        .brand-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 280px;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }
        
        .brand-features {
            margin-top: 40px;
            text-align: left;
            position: relative;
            z-index: 1;
        }
        
        .brand-feature {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            font-size: 0.9rem;
        }
        
        .brand-feature i {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Right Panel - Form */
        .login-form-panel {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .form-header {
            margin-bottom: 32px;
        }
        
        .form-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .form-header p {
            color: #6b7280;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            transition: color 0.3s;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s;
            background: #f9fafb;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px var(--primary-light);
        }
        
        .form-control:focus + i,
        .input-wrapper:focus-within i {
            color: var(--primary);
        }
        
        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
        }
        
        .form-check span {
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 24px;
            box-shadow: 0 4px 15px rgba(124, 58, 237, 0.3);
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }
        
        .alert-danger {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .form-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
            color: #9ca3af;
            font-size: 0.85rem;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 400px;
            }
            
            .login-brand {
                padding: 40px 30px;
            }
            
            .brand-features {
                display: none;
            }
            
            .brand-title {
                font-size: 2rem;
            }
            
            .login-form-panel {
                padding: 40px 30px;
            }
        }
        
        /* Animation */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-container {
            animation: slideUp 0.6s ease-out;
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-shapes">
        <div class="shape-1"></div>
        <div class="shape-2"></div>
        <div class="shape-3"></div>
    </div>
    
    <div class="login-container">
        <!-- Left Panel - Branding -->
        <div class="login-brand">
            <div class="brand-logo">🔗</div>
            <h1 class="brand-title">{{ $appName }}</h1>
            <p class="brand-subtitle">Sistem Manajemen Program Kerja, Workflow & Evaluasi Organisasi</p>
            
            <div class="brand-features">
                <div class="brand-feature">
                    <i class="fas fa-project-diagram"></i>
                    <span>Kelola Program Kerja</span>
                </div>
                <div class="brand-feature">
                    <i class="fas fa-tasks"></i>
                    <span>Task Management</span>
                </div>
                <div class="brand-feature">
                    <i class="fas fa-chart-line"></i>
                    <span>Evaluasi Performa</span>
                </div>
                <div class="brand-feature">
                    <i class="fas fa-comments"></i>
                    <span>Internal Messaging</span>
                </div>
            </div>
        </div>
        
        <!-- Right Panel - Form -->
        <div class="login-form-panel">
            <div class="form-header">
                <h2>Selamat Datang! 👋</h2>
                <p>Silakan login untuk melanjutkan</p>
            </div>
            
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            
            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-wrapper">
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control" 
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            required 
                            autofocus
                        >
                        <i class="fas fa-envelope"></i>
                    </div>
                    @error('email')
                        <div style="color: #dc2626; font-size: 0.85rem; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control" 
                            placeholder="••••••••"
                            required
                        >
                        <i class="fas fa-lock"></i>
                    </div>
                    @error('password')
                        <div style="color: #dc2626; font-size: 0.85rem; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk ke Dashboard
                </button>
            </form>
            
            <div class="form-footer">
                {{ $appName }} © {{ date('Y') }} - All rights reserved
            </div>
        </div>
    </div>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @if(session('swal'))
        <script>
            Swal.fire({
                icon: '{{ session("swal.type", "success") }}',
                title: '{{ session("swal.title") }}',
                text: '{{ session("swal.text", "") }}',
                confirmButtonColor: '{{ $colors["primary"] }}'
            });
        </script>
    @endif
</body>
</html>
