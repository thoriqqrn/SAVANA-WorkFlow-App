<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $appName = \App\Models\Setting::get('app_name', 'SAVANA');
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
    <title>@yield('title', 'Dashboard') - {{ $appName }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🔗</text></svg>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Dynamic Theme Colors -->
    <style>
        :root {
            --primary: {{ $colors['primary'] }};
            --primary-hover: {{ $colors['hover'] }};
            --primary-soft: {{ $colors['soft'] }};
            --primary-light: {{ $colors['light'] }};
            --shadow-purple: 0 4px 24px {{ $colors['primary'] }}26;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay"></div>
        
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="main-header">
                <div class="header-left">
                    <button type="button" class="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="header-right">
                    <button type="button" class="header-btn theme-toggle" title="Toggle Dark Mode">
                        <i class="fas fa-moon"></i>
                    </button>
                    <button type="button" class="header-btn" title="Notifikasi">
                        <i class="fas fa-bell"></i>
                        @if(isset($unreadNotifications) && $unreadNotifications > 0)
                            <span class="badge"></span>
                        @endif
                    </button>
                    <div class="dropdown user-dropdown">
                        <button type="button" class="header-btn dropdown-toggle" id="userDropdown" onclick="toggleUserDropdown()">
                            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="avatar-sm">
                            <span class="user-name-header">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" id="userDropdownMenu">
                            <div class="dropdown-header">
                                <img src="{{ auth()->user()->avatar_url }}" alt="" class="dropdown-avatar">
                                <div class="dropdown-user-info">
                                    <div class="dropdown-user-name">{{ auth()->user()->name }}</div>
                                    <div class="dropdown-user-role">{{ auth()->user()->role_name }}</div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fas fa-user-edit"></i>
                                <span>Edit Profil</span>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('settings.index') }}" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                <span>Pengaturan</span>
                            </a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="page-content">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success animate-fadeIn">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger animate-fadeIn">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger animate-fadeIn">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- Flash message to SweetAlert -->
    @if(session('swal'))
        <script>
            Swal.fire({
                icon: '{{ session("swal.type", "success") }}',
                title: '{{ session("swal.title") }}',
                text: '{{ session("swal.text", "") }}',
                confirmButtonColor: '{{ $colors["primary"] ?? "#7C3AED" }}'
            });
        </script>
    @endif
    
    @stack('scripts')
    
    <!-- Floating Chat -->
    @auth
        @include('components.floating-chat')
    @endauth
    
    <script>
    function toggleUserDropdown() {
        const menu = document.getElementById('userDropdownMenu');
        menu.classList.toggle('show');
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdown = document.querySelector('.user-dropdown');
        const menu = document.getElementById('userDropdownMenu');
        if (dropdown && menu && !dropdown.contains(e.target)) {
            menu.classList.remove('show');
        }
    });
    </script>
</body>
</html>


