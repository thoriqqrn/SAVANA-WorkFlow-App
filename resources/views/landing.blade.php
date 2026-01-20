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
            /* GSM Colors - Deep Purple & Golden Yellow */
            --purple-deep: #4A148C;
            --purple: #6A1B9A;
            --purple-light: #9C27B0;
            --purple-glow: #BA68C8;
            --gold: #FFD700;
            --gold-dark: #F5B041;
            --gold-light: #FFEB3B;
            --orange: #FF9800;
            
            /* Gradients */
            --gradient-main: linear-gradient(135deg, #4A148C 0%, #6A1B9A 30%, #FFD700 70%, #F5B041 100%);
            --gradient-purple: linear-gradient(135deg, #4A148C 0%, #9C27B0 100%);
            --gradient-gold: linear-gradient(135deg, #FFD700 0%, #F5B041 100%);
            --gradient-mixed: linear-gradient(135deg, #6A1B9A 0%, #FFD700 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #0A0A14;
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
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(10, 10, 20, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 215, 0, 0.2);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            color: #fff;
        }

        .navbar-brand img {
            height: 50px;
        }

        .navbar-brand span {
            background: var(--gradient-gold);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            font-size: 0.95rem;
        }

        .nav-links a:hover {
            color: var(--gold);
            text-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
        }

        .btn-login {
            background: var(--gradient-gold);
            color: var(--purple-deep) !important;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(255, 215, 0, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255, 215, 0, 0.5);
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
            background: 
                radial-gradient(ellipse at top left, rgba(74, 20, 140, 0.6) 0%, transparent 50%),
                radial-gradient(ellipse at bottom right, rgba(255, 215, 0, 0.3) 0%, transparent 50%),
                radial-gradient(ellipse at center, rgba(106, 27, 154, 0.2) 0%, transparent 60%);
        }

        /* Animated Shapes */
        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 8s ease-in-out infinite;
        }

        .shape-1 {
            top: 10%;
            left: 5%;
            width: 200px;
            height: 200px;
            background: var(--gradient-purple);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation-delay: 0s;
        }

        .shape-2 {
            top: 60%;
            right: 5%;
            width: 150px;
            height: 150px;
            background: var(--gradient-gold);
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            animation-delay: 2s;
        }

        .shape-3 {
            bottom: 10%;
            left: 20%;
            width: 100px;
            height: 100px;
            background: var(--gradient-mixed);
            border-radius: 50%;
            animation-delay: 4s;
        }

        .shape-4 {
            top: 30%;
            right: 15%;
            width: 80px;
            height: 80px;
            border: 3px solid var(--gold);
            border-radius: 50%;
            animation-delay: 1s;
        }

        /* Puzzle pieces */
        .puzzle {
            position: absolute;
            opacity: 0.08;
            font-size: 80px;
            animation: floatRotate 10s ease-in-out infinite;
        }

        .puzzle-1 { top: 15%; left: 8%; color: var(--purple-light); animation-delay: 0s; }
        .puzzle-2 { top: 25%; right: 12%; color: var(--gold); font-size: 50px; animation-delay: 2s; }
        .puzzle-3 { bottom: 30%; left: 5%; color: var(--gold-dark); font-size: 60px; animation-delay: 4s; }
        .puzzle-4 { bottom: 15%; right: 8%; color: var(--purple-glow); font-size: 40px; animation-delay: 1s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        @keyframes floatRotate {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(15deg); }
        }

        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .hero-content {
            text-align: left;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.2) 0%, rgba(74, 20, 140, 0.2) 100%);
            border: 1px solid rgba(255, 215, 0, 0.4);
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            color: var(--gold);
            font-weight: 600;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1.5rem;
        }

        .hero-title .line1 {
            display: block;
            color: #fff;
        }

        .hero-title .highlight {
            display: block;
            background: var(--gradient-main);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.2em;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.75);
            margin-bottom: 2rem;
            line-height: 1.8;
            max-width: 550px;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: var(--gradient-gold);
            color: var(--purple-deep);
            padding: 16px 36px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            box-shadow: 0 4px 25px rgba(255, 215, 0, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 40px rgba(255, 215, 0, 0.6);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--gold);
            color: var(--gold);
            padding: 14px 34px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }

        .btn-outline:hover {
            background: rgba(255, 215, 0, 0.1);
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.2);
        }

        /* Candidate Photo */
        .hero-photo {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .candidate-card {
            position: relative;
            background: linear-gradient(145deg, rgba(74, 20, 140, 0.4) 0%, rgba(255, 215, 0, 0.1) 100%);
            border: 2px solid rgba(255, 215, 0, 0.3);
            border-radius: 30px;
            padding: 2rem;
            text-align: center;
            backdrop-filter: blur(15px);
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.5),
                inset 0 0 60px rgba(255, 215, 0, 0.05);
        }

        .candidate-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: var(--gradient-main);
            border-radius: 32px;
            z-index: -1;
            opacity: 0.5;
            filter: blur(20px);
        }

        .candidate-photo {
            width: 280px;
            height: 320px;
            border-radius: 20px;
            background: var(--gradient-purple);
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 6rem;
            overflow: hidden;
            border: 4px solid var(--gold);
            box-shadow: 0 10px 40px rgba(255, 215, 0, 0.3);
        }

        .candidate-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .candidate-name {
            font-size: 1.6rem;
            font-weight: 800;
            background: var(--gradient-gold);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .candidate-title {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
            font-weight: 500;
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
            background: var(--gradient-gold);
            color: var(--purple-deep);
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .section-title .highlight {
            background: var(--gradient-gold);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-desc {
            color: rgba(255,255,255,0.6);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.8;
            font-size: 1.05rem;
        }

        /* Visi Misi */
        .visi-misi {
            background: linear-gradient(180deg, #0A0A14 0%, #12121F 50%, #0A0A14 100%);
            position: relative;
        }

        .visi-card {
            background: linear-gradient(145deg, rgba(74, 20, 140, 0.3) 0%, rgba(255, 215, 0, 0.1) 100%);
            border: 1px solid rgba(255, 215, 0, 0.3);
            border-radius: 30px;
            padding: 3rem;
            max-width: 900px;
            margin: 0 auto 3rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .visi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-gold);
        }

        .visi-card h3 {
            font-size: 1.75rem;
            margin-bottom: 1.25rem;
            background: var(--gradient-gold);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .visi-card p {
            color: rgba(255,255,255,0.85);
            line-height: 1.9;
            font-size: 1.1rem;
        }

        .misi-title {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.75rem;
            color: var(--gold);
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
            background: linear-gradient(135deg, rgba(74, 20, 140, 0.2) 0%, rgba(255, 215, 0, 0.05) 100%);
            padding: 1.75rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 215, 0, 0.15);
            transition: all 0.3s;
        }

        .misi-item:hover {
            border-color: var(--gold);
            transform: translateX(10px);
            box-shadow: 0 10px 40px rgba(255, 215, 0, 0.1);
        }

        .misi-number {
            width: 55px;
            height: 55px;
            background: var(--gradient-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--purple-deep);
            flex-shrink: 0;
            box-shadow: 0 5px 20px rgba(255, 215, 0, 0.3);
        }

        .misi-item p {
            color: rgba(255,255,255,0.85);
            line-height: 1.8;
            font-size: 1.05rem;
        }

        /* Programs */
        .programs {
            background: #0A0A14;
        }

        .program-category {
            margin-bottom: 5rem;
        }

        .category-header {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            margin-bottom: 2rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(255, 215, 0, 0.2);
        }

        .category-icon {
            width: 65px;
            height: 65px;
            background: var(--gradient-purple);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: var(--gold);
            box-shadow: 0 10px 30px rgba(74, 20, 140, 0.4);
        }

        .category-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--gold);
        }

        .category-desc {
            color: rgba(255,255,255,0.6);
            font-size: 0.95rem;
        }

        .program-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .program-card {
            background: linear-gradient(145deg, rgba(74, 20, 140, 0.15) 0%, rgba(10, 10, 20, 0.8) 100%);
            border: 1px solid rgba(255, 215, 0, 0.15);
            border-radius: 24px;
            padding: 2rem;
            transition: all 0.4s;
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
            background: var(--gradient-gold);
            transform: scaleX(0);
            transition: transform 0.4s;
        }

        .program-card:hover::before {
            transform: scaleX(1);
        }

        .program-card:hover {
            border-color: var(--gold);
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(255, 215, 0, 0.15);
        }

        .program-card h4 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: var(--gold);
            font-weight: 700;
        }

        .program-card .dept-tag {
            font-size: 0.75rem;
            color: var(--purple-glow);
            margin-bottom: 1rem;
            display: inline-block;
            background: rgba(156, 39, 176, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
        }

        .program-card p {
            color: rgba(255,255,255,0.7);
            font-size: 0.95rem;
            line-height: 1.8;
        }

        /* CMOS Section */
        .cmos {
            background: linear-gradient(180deg, #12121F 0%, #0A0A14 100%);
            position: relative;
            overflow: hidden;
        }

        .cmos::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(74, 20, 140, 0.2) 0%, transparent 70%);
        }

        .cmos-container {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .cmos-content h3 {
            font-size: 2.25rem;
            margin-bottom: 1.25rem;
            background: var(--gradient-gold);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
        }

        .cmos-content > p {
            color: rgba(255,255,255,0.75);
            line-height: 1.9;
            margin-bottom: 2rem;
            font-size: 1.05rem;
        }

        .cmos-features {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .cmos-feature {
            display: flex;
            align-items: center;
            gap: 14px;
            color: rgba(255,255,255,0.9);
            font-size: 1rem;
        }

        .cmos-feature i {
            color: var(--gold);
            font-size: 1.3rem;
        }

        .cmos-visual {
            background: var(--gradient-purple);
            border-radius: 30px;
            padding: 3rem;
            text-align: center;
            border: 2px solid rgba(255, 215, 0, 0.3);
            box-shadow: 0 30px 60px rgba(74, 20, 140, 0.4);
        }

        .cmos-visual i {
            font-size: 5rem;
            margin-bottom: 1.25rem;
            color: var(--gold);
        }

        .cmos-visual h4 {
            font-size: 1.6rem;
            margin-bottom: 0.5rem;
            color: var(--gold);
        }

        .cmos-visual p {
            color: rgba(255,255,255,0.7);
            font-size: 1rem;
        }

        /* Footer */
        footer {
            background: #050508;
            padding: 4rem 2rem 2rem;
            border-top: 2px solid rgba(255, 215, 0, 0.2);
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
            background: var(--gradient-gold);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer-brand p {
            color: rgba(255,255,255,0.6);
            line-height: 1.8;
        }

        .footer-links h4 {
            margin-bottom: 1.5rem;
            color: var(--gold);
            font-weight: 700;
        }

        .footer-links a {
            display: block;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            margin-bottom: 0.75rem;
            transition: all 0.3s;
        }

        .footer-links a:hover {
            color: var(--gold);
            padding-left: 5px;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .social-links a {
            width: 45px;
            height: 45px;
            background: rgba(255, 215, 0, 0.1);
            border: 1px solid rgba(255, 215, 0, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-links a:hover {
            background: var(--gradient-gold);
            color: var(--purple-deep);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.3);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            margin-top: 3rem;
            border-top: 1px solid rgba(255, 215, 0, 0.1);
            color: rgba(255,255,255,0.4);
            font-size: 0.9rem;
        }

        /* Mobile */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--gold);
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

            .hero-subtitle {
                margin-left: auto;
                margin-right: auto;
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
                padding: 0.75rem 1rem;
            }

            .navbar-brand img {
                height: 40px;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: rgba(10, 10, 20, 0.98);
                flex-direction: column;
                padding: 2rem;
                gap: 1.5rem;
                border-bottom: 2px solid rgba(255, 215, 0, 0.2);
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
                height: 260px;
            }

            .shape, .puzzle {
                opacity: 0.05;
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
            <img src="/images/logokabinet.png" alt="Logo Sentra Sinergi">
            <span>Sentra Sinergi</span>
        </a>
        <button class="mobile-menu-btn" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="nav-links" id="navLinks">
            <a href="#visi-misi">Visi Misi</a>
            <a href="#programs">Program</a>
            <a href="#cmos">CMOS</a>
            <a href="{{ route('login') }}" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg"></div>
        
        <!-- Decorations -->
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <i class="fas fa-puzzle-piece puzzle puzzle-1"></i>
        <i class="fas fa-puzzle-piece puzzle puzzle-2"></i>
        <i class="fas fa-puzzle-piece puzzle puzzle-3"></i>
        <i class="fas fa-puzzle-piece puzzle puzzle-4"></i>
        
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-crown"></i> Calon Ketua HIMATEKKOM ITS 2026
                </div>
                <h1 class="hero-title">
                    <span class="line1">Kabinet</span>
                    <span class="highlight">Sentra Sinergi</span>
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
                        <img src="/images/fotopanji.JPG" alt="Muhammad Panji Fathuroni">
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
        
        <h3 class="misi-title"><i class="fas fa-bullseye"></i> MISI</h3>
        
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
                    <p class="category-desc">Penguatan sistem internal organisasi</p>
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
                    <p class="category-desc">Penguatan relasi dan jejaring</p>
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
                    <p class="category-desc">Pengembangan dan perluasan dampak</p>
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
                navbar.style.background = 'rgba(10, 10, 20, 0.98)';
                navbar.style.boxShadow = '0 5px 30px rgba(0,0,0,0.3)';
            } else {
                navbar.style.background = 'rgba(10, 10, 20, 0.9)';
                navbar.style.boxShadow = 'none';
            }
        });
    </script>
</body>
</html>
