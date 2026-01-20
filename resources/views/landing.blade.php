<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kabinet Sentra Sinergi - HIMATEKKOM ITS</title>
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
            --gradient-4: linear-gradient(180deg, #7C3AED 0%, #5B21B6 100%);
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
            font-size: 0.95rem;
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
            background: radial-gradient(ellipse at top right, rgba(124, 58, 237, 0.4) 0%, transparent 50%),
                        radial-gradient(ellipse at bottom left, rgba(245, 158, 11, 0.3) 0%, transparent 50%),
                        radial-gradient(ellipse at center, rgba(249, 115, 22, 0.1) 0%, transparent 70%);
        }

        .puzzle {
            position: absolute;
            opacity: 0.12;
            font-size: 100px;
            animation: float 6s ease-in-out infinite;
        }

        .puzzle-1 { top: 10%; left: 3%; animation-delay: 0s; color: var(--purple); }
        .puzzle-2 { top: 15%; right: 8%; animation-delay: 1s; color: var(--yellow); font-size: 70px; }
        .puzzle-3 { bottom: 25%; left: 10%; animation-delay: 2s; color: var(--orange); font-size: 80px; }
        .puzzle-4 { bottom: 15%; right: 3%; animation-delay: 0.5s; color: var(--purple-light); font-size: 50px; }
        .puzzle-5 { top: 45%; left: 1%; animation-delay: 1.5s; color: var(--yellow-light); font-size: 40px; }
        .puzzle-6 { top: 60%; right: 2%; animation-delay: 2.5s; color: var(--purple); font-size: 60px; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .hero-content {
            text-align: left;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(124, 58, 237, 0.2);
            border: 1px solid rgba(124, 58, 237, 0.5);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            color: var(--purple-light);
        }

        .hero-title {
            font-size: clamp(2rem, 5vw, 3.5rem);
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
            font-size: 1.1rem;
            color: rgba(255,255,255,0.7);
            margin-bottom: 2rem;
            line-height: 1.7;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
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

        /* Candidate Photo */
        .hero-photo {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .candidate-card {
            position: relative;
            background: linear-gradient(145deg, rgba(124, 58, 237, 0.2) 0%, rgba(245, 158, 11, 0.1) 100%);
            border: 2px solid rgba(255,255,255,0.1);
            border-radius: 30px;
            padding: 2rem;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .candidate-photo {
            width: 280px;
            height: 280px;
            border-radius: 20px;
            background: linear-gradient(145deg, var(--purple) 0%, var(--orange) 100%);
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 6rem;
            overflow: hidden;
            border: 4px solid rgba(255,255,255,0.2);
        }

        .candidate-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .candidate-name {
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .candidate-title {
            color: rgba(255,255,255,0.7);
            font-size: 1rem;
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
            font-size: clamp(1.75rem, 4vw, 2.75rem);
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
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* Visi Misi */
        .visi-misi {
            background: linear-gradient(180deg, #0F0F1A 0%, #1A1A2E 100%);
        }

        .visi-card {
            background: linear-gradient(145deg, rgba(124, 58, 237, 0.15) 0%, rgba(245, 158, 11, 0.08) 100%);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            padding: 2.5rem;
            max-width: 900px;
            margin: 0 auto 3rem;
            text-align: center;
        }

        .visi-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .visi-card p {
            color: rgba(255,255,255,0.8);
            line-height: 1.8;
            font-size: 1.05rem;
        }

        .misi-grid {
            max-width: 1000px;
            margin: 0 auto;
        }

        .misi-item {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            align-items: flex-start;
            background: rgba(255,255,255,0.03);
            padding: 1.5rem;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .misi-number {
            width: 50px;
            height: 50px;
            background: var(--gradient-1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .misi-item p {
            color: rgba(255,255,255,0.8);
            line-height: 1.7;
        }

        /* Programs */
        .programs {
            background: #0F0F1A;
        }

        .program-category {
            margin-bottom: 4rem;
        }

        .category-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .category-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .category-title {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .program-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .program-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 1.75rem;
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
            height: 4px;
            background: var(--gradient-1);
        }

        .program-card:hover {
            background: rgba(255,255,255,0.06);
            transform: translateY(-5px);
            border-color: var(--purple);
        }

        .program-card h4 {
            font-size: 1.15rem;
            margin-bottom: 0.5rem;
            color: var(--yellow);
        }

        .program-card .dept-tag {
            font-size: 0.75rem;
            color: var(--purple-light);
            margin-bottom: 1rem;
            display: block;
        }

        .program-card p {
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
            line-height: 1.7;
        }

        /* CMOS Section */
        .cmos {
            background: linear-gradient(180deg, #1A1A2E 0%, #0F0F1A 100%);
        }

        .cmos-container {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }

        .cmos-content h3 {
            font-size: 2rem;
            margin-bottom: 1rem;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .cmos-content p {
            color: rgba(255,255,255,0.7);
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }

        .cmos-features {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .cmos-feature {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.8);
        }

        .cmos-feature i {
            color: var(--yellow);
            font-size: 1.25rem;
        }

        .cmos-visual {
            background: linear-gradient(145deg, var(--purple-dark) 0%, var(--purple) 100%);
            border-radius: 24px;
            padding: 2rem;
            text-align: center;
        }

        .cmos-visual i {
            font-size: 5rem;
            margin-bottom: 1rem;
            background: var(--gradient-3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .cmos-visual h4 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .cmos-visual p {
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
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

        /* Mobile */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
        }

        @media (max-width: 992px) {
            .hero-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-content {
                text-align: center;
            }

            .hero-buttons {
                justify-content: center;
            }

            .cmos-container {
                grid-template-columns: 1fr;
            }
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

            .candidate-photo {
                width: 220px;
                height: 220px;
            }

            .puzzle {
                font-size: 50px !important;
                opacity: 0.08;
            }

            section {
                padding: 4rem 1rem;
            }

            .program-grid {
                grid-template-columns: 1fr;
            }

            .misi-item {
                flex-direction: column;
                text-align: center;
            }

            .misi-number {
                margin: 0 auto;
            }

            .category-header {
                flex-direction: column;
                text-align: center;
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
            <span>🔗 HIMATEKKOM ITS</span>
        </a>
        <button class="mobile-menu-btn" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="nav-links" id="navLinks">
            <a href="#visi-misi">Visi Misi</a>
            <a href="#programs">Program</a>
            <a href="#cmos">CMOS</a>
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
        <i class="fas fa-puzzle-piece puzzle puzzle-6"></i>
        
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-star"></i> Calon Ketua HIMATEKKOM ITS 2025/2026
                </div>
                <h1 class="hero-title">
                    Kabinet <span class="highlight">Sentra Sinergi</span>
                </h1>
                <p class="hero-subtitle">
                    Mewujudkan HIMATEKKOM ITS sebagai poros pergerakan yang unggul melalui optimalisasi sistem dan kolaborasi strategis, demi tercapainya ekspansi kebermanfaatan yang berkelanjutan.
                </p>
                <div class="hero-buttons">
                    <a href="#visi-misi" class="btn-primary">
                        <i class="fas fa-eye"></i> Visi & Misi
                    </a>
                    <a href="#programs" class="btn-outline">
                        <i class="fas fa-rocket"></i> Program Unggulan
                    </a>
                </div>
            </div>
            
            <div class="hero-photo">
                <div class="candidate-card">
                    <div class="candidate-photo">
                        <!-- Ganti dengan foto calon ketua -->
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="candidate-name">Muhammad Panji Fathuroni</h2>
                    <p class="candidate-title">Calon Ketua Himpunan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi Misi Section -->
    <section class="visi-misi" id="visi-misi">
        <div class="section-header">
            <div class="section-badge">Visi & Misi</div>
            <h2 class="section-title">Arah <span class="highlight">Pergerakan</span> Kami</h2>
        </div>
        
        <div class="visi-card">
            <h3><i class="fas fa-eye"></i> VISI</h3>
            <p>Mewujudkan HIMATEKKOM ITS sebagai poros pergerakan yang unggul melalui <strong>optimalisasi sistem</strong> dan <strong>kolaborasi strategis</strong>, demi tercapainya <strong>ekspansi kebermanfaatan</strong> yang berkelanjutan.</p>
        </div>
        
        <h3 style="text-align: center; margin-bottom: 2rem; font-size: 1.5rem;"><i class="fas fa-bullseye" style="color: var(--yellow);"></i> MISI</h3>
        
        <div class="misi-grid">
            <div class="misi-item">
                <div class="misi-number">1</div>
                <p><strong>Mengoptimalkan</strong> tata kelola dan sistem monitoring organisasi yang terintegrasi, guna menjamin solidaritas internal serta konsistensi kinerja yang berkelanjutan</p>
            </div>
            <div class="misi-item">
                <div class="misi-number">2</div>
                <p><strong>Membangun kolaborasi strategis</strong> dengan stakeholder eksternal untuk memperkuat relasi, menjawab kebutuhan mahasiswa, serta mewujudkan kemandirian organisasi</p>
            </div>
            <div class="misi-item">
                <div class="misi-number">3</div>
                <p><strong>Melakukan ekspansi</strong> ekosistem pengembangan mahasiswa yang adaptif serta meningkatkan eksistensi HIMATEKKOM ITS di lingkup eksternal</p>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section class="programs" id="programs">
        <div class="section-header">
            <div class="section-badge">Program Unggulan</div>
            <h2 class="section-title">Rangkaian <span class="highlight">Program Kerja</span></h2>
            <p class="section-desc">Tiga pilar utama pergerakan: Optimalisasi, Kolaborasi, dan Ekspansi</p>
        </div>
        
        <!-- Optimalisasi -->
        <div class="program-category">
            <div class="category-header">
                <div class="category-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div>
                    <h3 class="category-title">Optimalisasi</h3>
                    <p style="color: rgba(255,255,255,0.6);">Penguatan sistem internal organisasi</p>
                </div>
            </div>
            
            <div class="program-grid">
                <div class="program-card">
                    <h4>CMOS (Computer Monitoring System)</h4>
                    <span class="dept-tag">Sistem Terintegrasi</span>
                    <p>Sistem monitoring dan pelaporan program kerja serta kinerja organisasi yang terintegrasi melalui website. Meningkatkan transparansi, akuntabilitas, dan efektivitas manajemen organisasi dengan data terpusat.</p>
                </div>
                <div class="program-card">
                    <h4>Personalia</h4>
                    <span class="dept-tag">Sumber Daya Manusia</span>
                    <p>Biro khusus pengelolaan SDM organisasi: rekrutmen, upgrading, pengembangan kapasitas, rapor staf, hingga sistem apresiasi. Menciptakan lingkungan profesional dan berorientasi pertumbuhan.</p>
                </div>
            </div>
        </div>
        
        <!-- Kolaborasi -->
        <div class="program-category">
            <div class="category-header">
                <div class="category-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div>
                    <h3 class="category-title">Kolaborasi</h3>
                    <p style="color: rgba(255,255,255,0.6);">Penguatan relasi dan jejaring</p>
                </div>
            </div>
            
            <div class="program-grid">
                <div class="program-card">
                    <h4>Hi Alumni</h4>
                    <span class="dept-tag">Hublu</span>
                    <p>Program penguatan relasi mahasiswa aktif dan alumni melalui database terstruktur serta publikasi konten pengalaman alumni dari kuliah hingga karier profesional.</p>
                </div>
                <div class="program-card">
                    <h4>Sosmas</h4>
                    <span class="dept-tag">Hublu</span>
                    <p>Biro baru berfokus pada isu sosial kemasyarakatan: charity, penggalangan bantuan, dan kolaborasi dengan BEM Fakultas. Wadah aktualisasi kepedulian sosial mahasiswa.</p>
                </div>
                <div class="program-card">
                    <h4>Advocation Corner</h4>
                    <span class="dept-tag">Kesma</span>
                    <p>Penguatan advokasi kesejahteraan dengan PIC aktif sebagai contact person. Pendampingan UKT, FRS, beasiswa, dan isu kesejahteraan mahasiswa lainnya.</p>
                </div>
            </div>
        </div>
        
        <!-- Ekspansi -->
        <div class="program-category">
            <div class="category-header">
                <div class="category-icon">
                    <i class="fas fa-expand-arrows-alt"></i>
                </div>
                <div>
                    <h3 class="category-title">Ekspansi</h3>
                    <p style="color: rgba(255,255,255,0.6);">Pengembangan dan perluasan dampak</p>
                </div>
            </div>
            
            <div class="program-grid">
                <div class="program-card">
                    <h4>COD (Career Orientation & Development)</h4>
                    <span class="dept-tag">PSDM</span>
                    <p>Program pengembangan karier: persiapan wawancara, pembuatan CV, dan optimalisasi LinkedIn. Mempersiapkan mahasiswa menghadapi dunia profesional.</p>
                </div>
                <div class="program-card">
                    <h4>BIOS (Bicara, Isu, Opini, dan Solusi)</h4>
                    <span class="dept-tag">Risprof</span>
                    <p>Program diskusi dan kajian isu keprofesian terkini di bidang Teknik Komputer. Ruang kritis untuk gagasan, opini, dan solusi tantangan profesional.</p>
                </div>
                <div class="program-card">
                    <h4>TEKKOM Insight</h4>
                    <span class="dept-tag">Medfo</span>
                    <p>Media informasi dengan konten fun fact dan wawasan Teknik Komputer dalam bentuk feed dan reels video kreatif. Meningkatkan literasi teknologi dan engagement.</p>
                </div>
                <div class="program-card">
                    <h4>Buku Panduan Kaderisasi</h4>
                    <span class="dept-tag">Kader</span>
                    <p>Standarisasi proses kaderisasi dengan nilai dasar, alur, dan pedoman sistematis. Menjaga konsistensi nilai dan memudahkan regenerasi kepemimpinan.</p>
                </div>
                <div class="program-card">
                    <h4>Website HIMATEKKOM</h4>
                    <span class="dept-tag">Digital</span>
                    <p>Platform digital resmi sebagai pusat informasi, dokumentasi, dan layanan. Terintegrasi dengan CMOS untuk efektivitas pengelolaan dan citra profesional.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CMOS Section -->
    <section class="cmos" id="cmos">
        <div class="section-header">
            <div class="section-badge">Flagship Product</div>
            <h2 class="section-title">CMOS - <span class="highlight">Computer Monitoring System</span></h2>
        </div>
        
        <div class="cmos-container">
            <div class="cmos-content">
                <h3>Sistem Monitoring Terintegrasi</h3>
                <p>CMOS merupakan sistem monitoring dan pelaporan program kerja serta kinerja organisasi yang terintegrasi melalui website. Program ini bertujuan meningkatkan transparansi, akuntabilitas, dan efektivitas manajemen organisasi.</p>
                
                <div class="cmos-features">
                    <div class="cmos-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Monitoring Program Kerja Real-time</span>
                    </div>
                    <div class="cmos-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Evaluasi Kinerja Pengurus</span>
                    </div>
                    <div class="cmos-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Dokumentasi Kegiatan Terpusat</span>
                    </div>
                    <div class="cmos-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Pengambilan Keputusan Berbasis Data</span>
                    </div>
                </div>
                
                <a href="{{ route('login') }}" class="btn-primary" style="margin-top: 2rem;">
                    <i class="fas fa-sign-in-alt"></i> Akses CMOS
                </a>
            </div>
            
            <div class="cmos-visual">
                <i class="fas fa-desktop"></i>
                <h4>Dashboard Terintegrasi</h4>
                <p>Akses data organisasi kapan saja, di mana saja</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-brand">
                <h3>🔗 HIMATEKKOM ITS</h3>
                <p>Kabinet Sentra Sinergi<br>Himpunan Mahasiswa Teknik Komputer<br>Institut Teknologi Sepuluh Nopember</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
            <div class="footer-links">
                <h4>Quick Links</h4>
                <a href="#visi-misi">Visi & Misi</a>
                <a href="#programs">Program Unggulan</a>
                <a href="#cmos">CMOS</a>
                <a href="{{ route('login') }}">Login Pengurus</a>
            </div>
            <div class="footer-links">
                <h4>Kontak</h4>
                <a href="#">himatekkom@its.ac.id</a>
                <a href="#">Gedung Teknik Komputer</a>
                <a href="#">Kampus ITS Sukolilo, Surabaya</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} HIMATEKKOM ITS - Kabinet Sentra Sinergi. Semua hak dilindungi.</p>
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
