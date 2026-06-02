<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    @php $siteConfig = \App\Models\SettingWebsite::first(); @endphp
    <title>{{ $title ?? ($siteConfig->nama_sekolah ?? 'SDN Demakijo 1') }} - Smart School</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#004aad">
    
    <!-- Apple Mobile Web App / iOS support -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SDN Demakijo 1">
    <link rel="apple-touch-icon" href="/logo-192.png">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #0056b3;
            --secondary: #ffc107;
            --dark: #1a1a1a;
            --light: #f4f6f9;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fff;
            color: #333;
            overflow-x: hidden;
        }

        /* Topbar */
        .topbar {
            background-color: var(--primary);
            color: white;
            font-size: 0.85rem;
            padding: 8px 0;
        }
        .topbar a { color: white; text-decoration: none; margin-left: 15px; transition: 0.3s;}
        .topbar a:hover { color: var(--secondary); }

        /* Navbar */
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        .navbar-brand {
            color: var(--primary) !important;
            font-weight: 800;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
        }
        .navbar-brand img { height: 40px; margin-right: 10px; }
        
        .nav-link {
            font-weight: 700;
            color: var(--primary) !important;
            margin: 0 5px;
            font-size: 0.95rem;
            transition: 0.3s;
        }
        .nav-link:hover, .nav-item.dropdown:hover .nav-link {
            color: var(--secondary) !important;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .dropdown-item {
            font-weight: 500;
            padding: 10px 20px;
            transition: 0.3s;
        }
        .dropdown-item:hover {
            background-color: var(--light);
            color: var(--primary);
        }

        /* Hero / Page Header */
        .page-header {
            background: linear-gradient(rgba(0, 86, 179, 0.85), rgba(0, 86, 179, 0.85)), url('https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1920&q=80') center/cover;
            padding: 100px 0 60px;
            color: white;
            text-align: center;
            margin-bottom: 50px;
        }
        .page-header h1 { font-weight: 800; color: white !important; }

        /* Footer */
        footer {
            background-color: var(--primary);
            color: #f8f9fa !important;
            padding: 70px 0 30px;
            margin-top: 80px;
            font-size: 0.9rem;
            border-top: 5px solid var(--secondary);
        }
        footer,
        footer p,
        footer span,
        footer li,
        footer a,
        footer div,
        footer strong,
        footer small {
            color: #e2e8f0 !important;
        }
        footer h5 {
            color: #ffffff !important;
            font-weight: 700;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }
        footer h5::after {
            content: '';
            position: absolute;
            left: 0; bottom: 0;
            width: 50px; height: 3px;
            background-color: var(--secondary);
        }
        footer ul { list-style: none; padding: 0; }
        footer ul li { margin-bottom: 12px; }
        footer ul li a { color: #cbd5e1 !important; text-decoration: none; transition: 0.3s; }
        footer ul li a:hover { color: var(--secondary) !important; padding-left: 5px; }
        footer .contact-info i { color: var(--secondary) !important; width: 25px; }
        footer .copyright-text { color: #cbd5e1 !important; font-size: 0.95rem; }
        footer .copyright-text strong { color: #ffffff !important; }
        footer .btn-outline-light { color: #fff !important; border-color: rgba(255,255,255,0.4) !important; }
        footer .btn-outline-light:hover { background: rgba(255,255,255,0.15) !important; }

        /* Custom UI Tools */
        .floating-wa {
            position: fixed; bottom: 30px; right: 30px;
            background-color: #25d366; color: white;
            width: 60px; height: 60px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 30px; z-index: 1000; box-shadow: 0 10px 20px rgba(37, 211, 102, 0.3);
            transition: all 0.3s; text-decoration: none;
        }
        .floating-wa:hover { transform: scale(1.1); color: white; }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        .section-title h2 {
            font-weight: 800;
            color: var(--primary);
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
        }
        .section-title h2::after {
            content: ''; position: absolute;
            bottom: 0; left: 50%; transform: translateX(-50%);
            width: 80px; height: 4px; border-radius: 2px;
            background-color: var(--secondary);
        }

        /* Mobile and Safe Area Optimization */
        @supports (padding-top: env(safe-area-inset-top)) {
            .navbar.sticky-top {
                padding-top: calc(15px + env(safe-area-inset-top));
            }
        }
        
        .floating-wa {
            bottom: calc(30px + env(safe-area-inset-bottom));
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: #ffffff;
                padding: 20px;
                border-radius: 12px;
                margin-top: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
            .nav-link {
                padding: 10px 0;
                border-bottom: 1px solid #f1f5f9;
            }
            .nav-item:last-child .nav-link {
                border-bottom: none;
            }
            .dropdown-menu {
                box-shadow: none;
                padding-left: 15px;
                background-color: #f8fafc;
                border: 1px solid #e2e8f0;
            }
            .nav-item.ms-lg-3 {
                margin-top: 15px;
                width: 100%;
            }
            .nav-item.ms-lg-3 .btn {
                width: 100%;
                justify-content: center;
            }
        }

        {{ $custom_css ?? '' }}
    </style>
</head>
<body>
    
    <!-- Topbar -->
    <div class="topbar d-none d-lg-block">
        <div class="container d-flex justify-content-between">
            <div>
                <i class="fas fa-envelope me-2 text-warning"></i> {{ $siteConfig->email ?? 'info@sdndemakijo1.sch.id' }}
                <span class="mx-3">|</span>
                <i class="fas fa-phone-alt me-2 text-warning"></i> {{ $siteConfig->telepon ?? '0274-123456' }}
            </div>
            <div>
                @if($siteConfig && $siteConfig->facebook)
                    <a href="{{ $siteConfig->facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                @else
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                @endif
                @if($siteConfig && $siteConfig->instagram)
                    <a href="{{ $siteConfig->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                @else
                    <a href="#"><i class="fab fa-instagram"></i></a>
                @endif
                @if($siteConfig && $siteConfig->youtube)
                    <a href="{{ $siteConfig->youtube }}" target="_blank"><i class="fab fa-youtube"></i></a>
                @else
                    <a href="#"><i class="fab fa-youtube"></i></a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                @if($siteConfig && $siteConfig->logo)
                    <img src="{{ asset('storage/'.$siteConfig->logo) }}" alt="{{ $siteConfig->nama_sekolah ?? 'SDN Demakijo 1' }}" style="height:40px; margin-right:10px;">
                    <div>
                        <span class="d-block lh-1">{{ strtoupper($siteConfig->nama_sekolah ?? 'SDN DEMAKIJO 1') }}</span>
                        <small class="text-muted fw-normal" style="font-size:0.7rem;">Nogotirto, Gamping, Sleman</small>
                    </div>
                @else
                    <i class="fas fa-graduation-cap text-warning fs-1 me-2"></i>
                    <div>
                        <span class="d-block lh-1">{{ strtoupper($siteConfig->nama_sekolah ?? 'SDN DEMAKIJO 1') }}</span>
                        <small class="text-muted fw-normal" style="font-size:0.7rem;">Nogotirto, Gamping, Sleman</small>
                    </div>
                @endif
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Profil</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/identitas-sekolah">Identitas Sekolah</a></li>
                            <li><a class="dropdown-item" href="/sejarah">Sejarah</a></li>
                            <li><a class="dropdown-item" href="/akreditasi-sekolah">Akreditasi Sekolah</a></li>
                            <li><a class="dropdown-item" href="/sarana-prasarana">Sarana Prasarana</a></li>
                            <li><a class="dropdown-item" href="/komite-sekolah">Struktur Komite</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Akademik</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/guru-tendik">Guru dan Tendik</a></li>
                            <li><a class="dropdown-item" href="/prestasi">Prestasi</a></li>
                            <li><a class="dropdown-item" href="/ekstrakurikuler">Ekstrakurikuler</a></li>
                            <li><a class="dropdown-item" href="/agenda">Agenda Sekolah</a></li>
                            <li><a class="dropdown-item" href="/alumni">Alumni</a></li>
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="/berita">Berita</a></li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Galeri</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/galeri/foto">Foto</a></li>
                            <li><a class="dropdown-item" href="/galeri/video">Video</a></li>
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="/unduhan">Unduhan</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold text-warning" href="/ppdb-online"><i class="fas fa-star me-1"></i>PPDB Online</a></li>
                    <li class="nav-item ms-lg-3"><a class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" href="/login"><i class="fas fa-sign-in-alt me-2"></i>Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    @if(!request()->is('/'))
    <header class="page-header">
        <div class="container" data-aos="fade-up">
            <h1 class="display-5" style="color:white !important;">{{ $header_title ?? $title ?? 'Halaman' }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mt-3">
                    <li class="breadcrumb-item"><a href="/" class="text-white text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active text-warning" aria-current="page">{{ $title ?? 'Detail' }}</li>
                </ol>
            </nav>
        </div>
    </header>
    @endif

    <!-- Content -->
    <main style="min-height: 50vh;">
        @if(!request()->is('/'))
            <div class="container">
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        @else
            {{ $slot ?? '' }}
            @yield('content')
        @endif
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-5">
                <!-- Kolom 1: Identitas -->
                <div class="col-lg-4">
                    <a class="d-flex align-items-center mb-4 text-decoration-none" href="/" style="font-size:1.5rem; font-weight:800;">
                        @if($siteConfig && $siteConfig->logo)
                            <img src="{{ asset('storage/'.$siteConfig->logo) }}" alt="Logo" style="height:50px; margin-right:10px; filter:brightness(0) invert(1);">
                        @else
                            <i class="fas fa-graduation-cap text-warning me-2"></i>
                        @endif
                        <span style="color:#ffffff !important;">{{ strtoupper($siteConfig->nama_sekolah ?? 'SDN DEMAKIJO 1') }}</span>
                    </a>
                    <p class="mb-4 pe-lg-4" style="color:#e2e8f0; font-size:0.95rem; line-height:1.6;">
                        {{ $siteConfig->visi ? \Illuminate\Support\Str::limit($siteConfig->visi, 120) : 'Beriman, Kreatif, Berprestasi, Berkarakter, dan Berbudaya. Mencetak generasi unggul yang siap menghadapi tantangan masa depan.' }}
                    </p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="{{ $siteConfig->facebook ?? '#' }}" target="_blank"
                           class="btn btn-outline-light rounded-circle d-flex justify-content-center align-items-center"
                           style="width:40px;height:40px;"><i class="fab fa-facebook-f" style="color:#fff !important;"></i></a>
                        <a href="{{ $siteConfig->instagram ?? '#' }}" target="_blank"
                           class="btn btn-outline-light rounded-circle d-flex justify-content-center align-items-center"
                           style="width:40px;height:40px;"><i class="fab fa-instagram" style="color:#fff !important;"></i></a>
                        <a href="{{ $siteConfig->youtube ?? '#' }}" target="_blank"
                           class="btn btn-outline-light rounded-circle d-flex justify-content-center align-items-center"
                           style="width:40px;height:40px;"><i class="fab fa-youtube" style="color:#fff !important;"></i></a>
                    </div>
                </div>
                
                <!-- Kolom 2: Menu Cepat -->
                <div class="col-lg-2 col-md-6">
                    <h5>Menu Cepat</h5>
                    <ul class="list-unstyled" style="line-height:2.2;">
                        <li><a href="/" style="color:#cbd5e1 !important;"><i class="fas fa-chevron-right text-warning me-2" style="font-size:0.8rem;"></i>Beranda</a></li>
                        <li><a href="/profil" style="color:#cbd5e1 !important;"><i class="fas fa-chevron-right text-warning me-2" style="font-size:0.8rem;"></i>Profil Sekolah</a></li>
                        <li><a href="/berita" style="color:#cbd5e1 !important;"><i class="fas fa-chevron-right text-warning me-2" style="font-size:0.8rem;"></i>Berita & Artikel</a></li>
                        <li><a href="/ppdb-online" style="color:#cbd5e1 !important;"><i class="fas fa-chevron-right text-warning me-2" style="font-size:0.8rem;"></i>PPDB Online</a></li>
                        <li><a href="/unduhan" style="color:#cbd5e1 !important;"><i class="fas fa-chevron-right text-warning me-2" style="font-size:0.8rem;"></i>Dokumen Unduhan</a></li>
                    </ul>
                </div>
                
                <!-- Kolom 3: Kontak -->
                <div class="col-lg-3 col-md-6">
                    <h5>Kontak Kami</h5>
                    <div class="d-flex mb-3 align-items-start">
                        <i class="fas fa-map-marker-alt text-warning mt-1 me-3 fs-5"></i>
                        <div>
                            <strong class="d-block" style="color:#fff !important;">Alamat</strong>
                            <span style="color:#cbd5e1 !important;">{{ $siteConfig->alamat ?? 'Jl. Godean, Nogotirto, Gamping, Sleman, Yogyakarta 55293' }}</span>
                        </div>
                    </div>
                    <div class="d-flex mb-3 align-items-start">
                        <i class="fas fa-phone-alt text-warning mt-1 me-3 fs-5"></i>
                        <div>
                            <strong class="d-block" style="color:#fff !important;">Telepon</strong>
                            <span style="color:#cbd5e1 !important;">{{ $siteConfig->telepon ?? '0274-123456' }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <i class="fas fa-envelope text-warning mt-1 me-3 fs-5"></i>
                        <div>
                            <strong class="d-block" style="color:#fff !important;">Email</strong>
                            <span style="color:#cbd5e1 !important;">{{ $siteConfig->email ?? 'info@sdndemakijo1.sch.id' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Kolom 4: Maps -->
                <div class="col-lg-3">
                    <h5>Lokasi Sekolah</h5>
                    <div class="rounded-3 overflow-hidden shadow-sm border border-light border-opacity-25" style="height:200px;">
                        @if($siteConfig && $siteConfig->google_maps)
                            <iframe src="{{ $siteConfig->google_maps }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        @else
                            <iframe src="https://maps.google.com/maps?q=SD%20Negeri%20Demakijo%201,%20Sleman&t=&z=14&ie=UTF8&iwloc=&output=embed"
                                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                        @endif
                    </div>
                </div>
            </div>
            
            <hr class="mt-5 border-secondary" style="opacity:0.3;">
            <div class="text-center mt-4 pt-2 pb-3">
                <p class="mb-0 copyright-text" style="color:#cbd5e1 !important; font-size:0.95rem;">
                    &copy; {{ date('Y') }} <strong style="color:#ffffff !important;">{{ $siteConfig->nama_sekolah ?? 'SDN Demakijo 1' }}</strong>. All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp -->
    @if($siteConfig && $siteConfig->whatsapp)
    <a href="https://wa.me/{{ $siteConfig->whatsapp }}" target="_blank" class="floating-wa shadow">
        <i class="fab fa-whatsapp"></i>
    </a>
    @else
    <a href="https://wa.me/6281234567890" target="_blank" class="floating-wa shadow">
        <i class="fab fa-whatsapp"></i>
    </a>
    @endif

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, offset: 50, duration: 800 });
        
        // PWA SW
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>