<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIMATEKKOM - Kabinet Sentra Sinergi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --purple: #7C3AED;
            --purple-dark: #5B21B6;
            --purple-light: #A78BFA;
            --yellow: #F59E0B;
            --yellow-light: #FCD34D;
            --orange: #F97316;
            --gradient-1: linear-gradient(135deg, #7C3AED 0%, #F59E0B 50%, #F97316 100%);
            --gradient-2: linear-gradient(135deg, #5B21B6 0%, #7C3AED 100%);
            --gradient-3: linear-gradient(135deg, #F59E0B 0%, #F97316 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #0F0F1A;
            color: #fff;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(15, 15, 26, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.25rem;
            text-decoration: none;
            color: #fff;
        }

        .navbar-brand img {
            height: 40px;
        }

        .navbar-brand span {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .nav-links a:hover {
            color: var(--yellow);
        }

        .btn-login {
            background: var(--gradient-1);
            color: #fff;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(124, 58, 237, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(124, 58, 237, 0.6);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 6rem 2rem 4rem;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(ellipse at top right, rgba(124, 58, 237, 0.3) 0%, transparent 50%),
                        radial-gradient(ellipse at bottom left, rgba(245, 158, 11, 0.2) 0%, transparent 50%);
        }

        /* Puzzle Elements */
        .puzzle {
            position: absolute;
            opacity: 0.15;
            font-size: 120px;
            animation: float 6s ease-in-out infinite;
        }

        .puzzle-1 { top: 10%; left: 5%; animation-delay: 0s; color: var(--purple); }
        .puzzle-2 { top: 20%; right: 10%; animation-delay: 1s; color: var(--yellow); font-size: 80px; }
        .puzzle-3 { bottom: 20%; left: 15%; animation-delay: 2s; color: var(--orange); font-size: 100px; }
        .puzzle-4 { bottom: 30%; right: 5%; animation-delay: 0.5s; color: var(--purple-light); font-size: 60px; }
        .puzzle-5 { top: 50%; left: 2%; animation-delay: 1.5s; color: var(--yellow-light); font-size: 50px; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .hero-content {
            text-align: center;
            position: relative;
            z-index: 10;
            max-width: 900px;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(124, 58, 237, 0.2);
            border: 1px solid rgba(124, 58, 237, 0.5);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            color: var(--purple-light);
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1rem;
        }

        .hero-title .highlight {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(255,255,255,0.7);
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: var(--gradient-1);
            color: #fff;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(124, 58, 237, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 40px rgba(124, 58, 237, 0.5);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid rgba(255,255,255,0.3);
            color: #fff;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-outline:hover {
            border-color: var(--yellow);
            color: var(--yellow);
            background: rgba(245, 158, 11, 0.1);
        }

        /* Sections */
        section {
            padding: 6rem 2rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-badge {
            display: inline-block;
            background: var(--gradient-3);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .section-title .highlight {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-desc {
            color: rgba(255,255,255,0.6);
            max-width: 600px;
            margin: 0 auto;
        }

        /* About Section */
        .about {
            background: linear-gradient(180deg, #0F0F1A 0%, #1A1A2E 100%);
        }

        .about-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .about-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s;
        }

        .about-card:hover {
            transform: translateY(-5px);
            border-color: var(--purple);
            box-shadow: 0 20px 40px rgba(124, 58, 237, 0.2);
        }

        .about-card-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .about-card h3 {
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
        }

        .about-card p {
            color: rgba(255,255,255,0.6);
            line-height: 1.7;
        }

        /* Structure Section */
        .structure {
            background: #0F0F1A;
            position: relative;
        }

        .structure-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .dept-card {
            background: linear-gradient(145deg, rgba(124, 58, 237, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s;
        }

        .dept-card:hover {
            transform: scale(1.03);
            border-color: var(--yellow);
        }

        .dept-icon {
            width: 70px;
            height: 70px;
            background: var(--gradient-2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin: 0 auto 1rem;
        }

        .dept-card h4 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .dept-card p {
            color: rgba(255,255,255,0.5);
            font-size: 0.85rem;
        }

        /* Programs Section */
        .programs {
            background: linear-gradient(180deg, #1A1A2E 0%, #0F0F1A 100%);
        }

        .programs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .program-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .program-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-1);
        }

        .program-card:hover {
            background: rgba(255,255,255,0.06);
            transform: translateY(-3px);
        }

        .program-card h4 {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .program-card p {
            color: rgba(255,255,255,0.5);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* Footer */
        footer {
            background: #0A0A14;
            padding: 4rem 2rem 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .footer-brand h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer-brand p {
            color: rgba(255,255,255,0.5);
            line-height: 1.7;
        }

        .footer-links h4 {
            margin-bottom: 1.5rem;
            color: var(--yellow);
        }

        .footer-links a {
            display: block;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            margin-bottom: 0.75rem;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--yellow);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-links a:hover {
            background: var(--gradient-1);
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            margin-top: 3rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.4);
            font-size: 0.9rem;
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: rgba(15, 15, 26, 0.98);
                flex-direction: column;
                padding: 2rem;
                gap: 1.5rem;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }

            .nav-links.active {
                display: flex;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero {
                padding: 5rem 1rem 3rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .puzzle {
                font-size: 60px !important;
                opacity: 0.1;
            }

            section {
                padding: 4rem 1rem;
            }

            .footer-content {
                text-align: center;
            }

            .social-links {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="/" class="navbar-brand">
            <span>🔗 HIMATEKKOM</span>
        </a>
        <button class="mobile-menu-btn" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="nav-links" id="navLinks">
            <a href="#about">Tentang</a>
            <a href="#structure">Struktur</a>
            <a href="#programs">Program</a>
            <a href="{{ route('login') }}" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login Pengurus
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg"></div>
        
        <!-- Puzzle Decorations -->
        <i class="fas fa-puzzle-piece puzzle puzzle-1"></i>
        <i class="fas fa-puzzle-piece puzzle puzzle-2"></i>
        <i class="fas fa-puzzle-piece puzzle puzzle-3"></i>
        <i class="fas fa-puzzle-piece puzzle puzzle-4"></i>
        <i class="fas fa-puzzle-piece puzzle puzzle-5"></i>
        
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-star"></i> Kabinet Periode 2025/2026
            </div>
            <h1 class="hero-title">
                Kabinet <span class="highlight">Sentra Sinergi</span>
            </h1>
            <p class="hero-subtitle">
                Himpunan Mahasiswa Teknik Komputer - Bersatu dalam keberagaman, 
                berkolaborasi untuk kemajuan bersama.
            </p>
            <div class="hero-buttons">
                <a href="#about" class="btn-primary">
                    <i class="fas fa-info-circle"></i> Tentang Kami
                </a>
                <a href="#programs" class="btn-outline">
                    <i class="fas fa-rocket"></i> Program Kerja
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="section-header">
            <div class="section-badge">Tentang Kami</div>
            <h2 class="section-title">Visi & <span class="highlight">Misi</span></h2>
            <p class="section-desc">
                Membangun sinergi untuk mencapai keunggulan bersama
            </p>
        </div>
        
        <div class="about-grid">
            <div class="about-card">
                <div class="about-card-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3>Visi</h3>
                <p>Menjadi organisasi mahasiswa yang inovatif, kolaboratif, dan berdampak positif bagi seluruh anggota dan masyarakat.</p>
            </div>
            <div class="about-card">
                <div class="about-card-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3>Misi</h3>
                <p>Mengembangkan potensi anggota melalui program-program berkualitas, membangun jejaring yang kuat, dan berkontribusi nyata.</p>
            </div>
            <div class="about-card">
                <div class="about-card-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3>Nilai</h3>
                <p>Integritas, Profesionalisme, Kolaborasi, Inovasi - menjadi fondasi dalam setiap langkah dan keputusan kami.</p>
            </div>
        </div>
    </section>

    <!-- Structure Section -->
    <section class="structure" id="structure">
        <div class="section-header">
            <div class="section-badge">Struktur</div>
            <h2 class="section-title">Departemen <span class="highlight">Kami</span></h2>
            <p class="section-desc">
                Tim yang solid untuk pelayanan terbaik
            </p>
        </div>
        
        <div class="structure-grid">
            @php
                $departments = \App\Models\Department::active()->get();
            @endphp
            @forelse($departments as $dept)
            <div class="dept-card">
                <div class="dept-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h4>{{ $dept->name }}</h4>
                <p>{{ $dept->users_count ?? $dept->users()->count() }} Anggota</p>
            </div>
            @empty
            <div class="dept-card">
                <div class="dept-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h4>Departemen</h4>
                <p>Segera hadir</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Programs Section -->
    <section class="programs" id="programs">
        <div class="section-header">
            <div class="section-badge">Program Kerja</div>
            <h2 class="section-title">Program <span class="highlight">Unggulan</span></h2>
            <p class="section-desc">
                Rangkaian kegiatan berkualitas untuk pengembangan anggota
            </p>
        </div>
        
        <div class="programs-grid">
            @php
                $programs = \App\Models\Program::with('department')->orderByDesc('id')->take(6)->get();
            @endphp
            @forelse($programs as $program)
            <div class="program-card">
                <h4>{{ $program->name }}</h4>
                <p>{{ Str::limit($program->description, 100) }}</p>
            </div>
            @empty
            <div class="program-card">
                <h4>Program Kerja</h4>
                <p>Program kerja akan segera diumumkan. Stay tuned!</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-brand">
                <h3>🔗 HIMATEKKOM</h3>
                <p>Kabinet Sentra Sinergi<br>Himpunan Mahasiswa Teknik Komputer</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                    <a href="#"><i class="fab fa-discord"></i></a>
                </div>
            </div>
            <div class="footer-links">
                <h4>Quick Links</h4>
                <a href="#about">Tentang Kami</a>
                <a href="#structure">Struktur Organisasi</a>
                <a href="#programs">Program Kerja</a>
                <a href="{{ route('login') }}">Login Pengurus</a>
            </div>
            <div class="footer-links">
                <h4>Kontak</h4>
                <a href="#">himatekkom@univ.ac.id</a>
                <a href="#">Gedung Teknik Komputer</a>
                <a href="#">Universitas</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} HIMATEKKOM - Kabinet Sentra Sinergi. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            document.getElementById('navLinks').classList.toggle('active');
        }

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                    document.getElementById('navLinks').classList.remove('active');
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(15, 15, 26, 0.95)';
            } else {
                navbar.style.background = 'rgba(15, 15, 26, 0.8)';
            }
        });
    </script>
</body>
</html>
