<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <!-- Sidebar Header -->
    @php
        $appName = \App\Models\Setting::get('app_name', 'SAVANA');
    @endphp
    <div class="sidebar-header">
        <div class="sidebar-logo">🔗</div>
        <span class="sidebar-brand">{{ $appName }}</span>
    </div>
    
    <!-- Sidebar Navigation -->
    <nav class="sidebar-nav">
        <!-- Main Menu -->
        <div class="nav-section">
            <div class="nav-section-title">Menu Utama</div>
            
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="{{ route('announcements.index') }}" class="nav-item {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn"></i>
                <span>Pengumuman</span>
            </a>
        </div>
        
        <!-- Management (Admin, BPH) -->
        @if(auth()->user()->hasRole(['admin', 'bph']))
        <div class="nav-section">
            <div class="nav-section-title">Manajemen</div>
            
            @if(auth()->user()->isAdmin())
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.index') || request()->routeIs('users.show') || request()->routeIs('users.create') || request()->routeIs('users.edit') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Data User</span>
            </a>
            <a href="{{ route('users.import') }}" class="nav-item {{ request()->routeIs('users.import*') ? 'active' : '' }}">
                <i class="fas fa-file-csv"></i>
                <span>Import User CSV</span>
            </a>
            @endif
            
            <a href="{{ route('departments.index') }}" class="nav-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i>
                <span>Departemen</span>
            </a>
            
            <a href="{{ route('cabinets.index') }}" class="nav-item {{ request()->routeIs('cabinets.*') ? 'active' : '' }}">
                <i class="fas fa-landmark"></i>
                <span>Kabinet</span>
            </a>
        </div>
        @endif
        
        <!-- Programs -->
        <div class="nav-section">
            <div class="nav-section-title">Program Kerja</div>
            
            @if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
            <a href="{{ route('programs.index') }}" class="nav-item {{ request()->routeIs('programs.index') || request()->routeIs('programs.show') || request()->routeIs('programs.create') || request()->routeIs('programs.edit') ? 'active' : '' }}">
                <i class="fas fa-project-diagram"></i>
                <span>Daftar Proker</span>
            </a>
            @endif
            
            <a href="{{ route('programs.my') }}" class="nav-item {{ request()->routeIs('programs.my') ? 'active' : '' }}">
                <i class="fas fa-folder-open"></i>
                <span>Proker Saya</span>
            </a>
            
            <a href="{{ route('tasks.index') }}" class="nav-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <i class="fas fa-tasks"></i>
                <span>Task Saya</span>
            </a>
        </div>
        
        <!-- Timeline & Calendar -->
        <div class="nav-section">
            <div class="nav-section-title">Kalender</div>
            
            <a href="{{ route('timelines.calendar') }}" class="nav-item {{ request()->routeIs('timelines.calendar') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Kalender</span>
            </a>
            
            <a href="{{ route('timelines.index') }}" class="nav-item {{ request()->routeIs('timelines.index') ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                <span>Daftar Timeline</span>
            </a>
            
            @if(auth()->user()->hasRole(['admin', 'bph']))
            <a href="{{ route('timelines.global') }}" class="nav-item {{ request()->routeIs('timelines.global') ? 'active' : '' }}">
                <i class="fas fa-globe"></i>
                <span>Timeline Global</span>
            </a>
            @endif
        </div>
        
        <!-- Akses -->
        <div class="nav-section">
            <div class="nav-section-title">Akses</div>
            
            <a href="{{ route('drives.index') }}" class="nav-item {{ request()->routeIs('drives.*') ? 'active' : '' }}">
                <i class="fab fa-google-drive"></i>
                <span>Google Drive</span>
            </a>
            
            <a href="{{ route('links.index') }}" class="nav-item {{ request()->routeIs('links.*') ? 'active' : '' }}">
                <i class="fas fa-external-link-alt"></i>
                <span>Kumpulan Link</span>
            </a>
        </div>
        
        <!-- Evaluation (Admin, BPH, Kabinet) -->
        @if(auth()->user()->hasRole(['admin', 'bph', 'kabinet']))
        <div class="nav-section">
            <div class="nav-section-title">Penilaian</div>
            
            <a href="{{ route('evaluations.index') }}" class="nav-item {{ request()->routeIs('evaluations.*') ? 'active' : '' }}">
                <i class="fas fa-star"></i>
                <span>Evaluasi Staff</span>
            </a>
            
            @if(auth()->user()->hasRole(['admin', 'bph']))
            <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span>Laporan</span>
            </a>
            @endif
        </div>
        @endif
        
        {{-- Staff can view their own evaluation --}}
        @if(auth()->user()->isStaff())
        <div class="nav-section">
            <div class="nav-section-title">Penilaian</div>
            
            <a href="{{ route('evaluations.my') }}" class="nav-item {{ request()->routeIs('evaluations.my') ? 'active' : '' }}">
                <i class="fas fa-star"></i>
                <span>Nilai Saya</span>
            </a>
        </div>
        @endif
        
        <!-- Settings (Admin Only) -->
        @if(auth()->user()->isAdmin())
        <div class="nav-section">
            <div class="nav-section-title">Pengaturan</div>
            
            <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
        </div>
        @endif
    </nav>
    
    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="sidebar-user-avatar">
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">{{ auth()->user()->role_name }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-secondary w-100">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
