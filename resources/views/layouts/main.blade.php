<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - BloodConnect</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Google Fonts: Outfit (Display) & Inter (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-blood: #E63946;
            --primary-dark: #1D3557;
            --accent-light: #F1FAEE;
            --glass-white: rgba(255, 255, 255, 0.85);
            --gradient-blood: linear-gradient(135deg, #E63946 0%, #A81C26 100%);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--accent-light);
            color: #333;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .display-font {
            font-family: 'Outfit', sans-serif;
        }

        /* Premium Navbar */
        .navbar-premium {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        /* Remove white border/outline from hamburger toggler */
        .navbar-toggler {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            padding: 6px 8px;
        }
        .navbar-toggler:focus,
        .navbar-toggler:active {
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--primary-blood) !important;
        }

        .nav-link {
            font-weight: 600;
            color: var(--primary-dark) !important;
            margin: 0 5px;
            padding: 8px 16px !important;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-blood) !important;
            background: rgba(230, 57, 70, 0.05);
        }

        /* Glassmorphism Cards */
        .glass-card {
            background: var(--glass-white);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 24px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }

        /* Buttons */
        .btn-premium {
            background: var(--gradient-blood);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(230, 57, 70, 0.3);
        }

        .btn-premium:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 25px rgba(230, 57, 70, 0.4);
            color: white;
        }

        .btn-premium-outline {
            background: transparent;
            color: var(--primary-blood);
            border: 2px solid var(--primary-blood);
            border-radius: 12px;
            padding: 10px 26px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-premium-outline:hover {
            background: var(--primary-blood);
            color: white;
        }

        /* Animations */
        .reveal {
            animation: revealUp 0.8s ease forwards;
            opacity: 0;
        }

        @keyframes revealUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        footer {
            background: var(--primary-dark);
            color: rgba(255, 255, 255, 0.8);
            border-top: 5px solid var(--primary-blood);
        }

        /* ══════════════════════════════════════════════════
           MOBILE RESPONSIVE — complete overhaul
        ══════════════════════════════════════════════════ */
        @media (max-width: 991.98px) {

            /* Navbar collapse panel */
            #navbarNav {
                margin-top: 0.75rem;
                padding: 1rem;
                border-top: 1px solid rgba(0,0,0,0.07);
                border-radius: 0 0 16px 16px;
                background: rgba(255,255,255,0.98);
            }

            .navbar-nav { gap: 2px; }

            /* Regular nav links */
            .navbar-nav .nav-link {
                padding: 10px 14px !important;
                margin: 0 !important;
                border-radius: 10px;
                font-size: 0.9rem;
            }

            /* Auth action buttons — side by side */
            .navbar-nav .nav-item.ms-lg-3 {
                width: 100%;
            }
            .navbar-nav .nav-link.btn-premium-outline {
                display: block;
                width: 100%;
                text-align: center;
                border-radius: 10px;
                padding: 10px 14px !important;
            }
            .navbar-nav .btn-premium {
                display: block;
                width: 100%;
                text-align: center;
                border-radius: 10px;
                padding: 10px 14px;
                margin: 2px 0 !important;
            }

            /* Auth buttons row — Login + Join side by side on mobile */
            .mobile-auth-row {
                display: flex;
                gap: 8px;
                padding: 6px 0;
            }
            .navbar-toggler { border: none !important; outline: none !important; box-shadow: none !important; }
            .navbar-toggler:focus { box-shadow: none !important; outline: none !important; }
            .mobile-auth-row .nav-item { width: 50% !important; }

            /* Bell icon stays compact */
            .navbar-nav .nav-link.position-relative {
                display: inline-flex;
                align-items: center;
                width: auto;
            }

            /* User dropdown — static inline */
            .navbar-nav .dropdown-menu {
                position: static !important;
                float: none;
                width: 100%;
                margin-top: 4px;
                box-shadow: none;
                border: 1px solid rgba(0,0,0,0.07);
                border-radius: 12px;
            }
            .navbar-nav .dropdown-toggle.btn-premium-outline {
                display: block;
                width: 100%;
                text-align: left;
                border-radius: 10px;
            }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Premium Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top navbar-premium py-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand fs-3" href="{{ url('/') }}">
                <i class="bi bi-droplet-fill"></i> BloodConnect
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('donors*') ? 'active' : '' }}" href="{{ route('donors.index') }}">Find Donors</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('stories*') ? 'active' : '' }}" href="{{ url('/stories') }}">Stories</a></li>

                    @auth
                        @if(Auth::user()->role === 'admin')
                            {{-- Admin Navigation --}}
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-shield-lock-fill me-1"></i>Admin Panel
                                </a>
                            </li>
                        @else
                            {{-- Blood Requests link — shown to donors/recipients --}}
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('requests*') ? 'active' : '' }}" href="{{ route('requests.create') }}">Blood Requests</a>
                            </li>
                        @endif

                        {{-- Notification Bell — donors and willing recipients --}}
                        @if(Auth::user()->role === 'donor' || (Auth::user()->role === 'recipient' && Auth::user()->willing_to_donate))
                            @php
                                $bellCount = \App\Models\DonorAlert::where('donor_id', Auth::id())->where('is_read', false)->count();
                            @endphp
                            <li class="nav-item me-2">
                                <a class="nav-link position-relative" href="{{ route('dashboard') }}#notifications-panel" title="Blood Request Alerts">
                                    <i class="bi bi-bell-fill fs-5 text-danger"></i>
                                    @if($bellCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem;">
                                            {{ $bellCount }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endif

                        {{-- User Dropdown --}}
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle btn-premium-outline" href="#" id="userDrop" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4">
                                <li><a class="dropdown-item py-2" href="{{ url('/dashboard') }}"><i class="bi bi-grid-1x2-fill me-2 text-danger"></i>Dashboard</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person-gear me-2 text-danger"></i>Profile Settings</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('donations.history') }}"><i class="bi bi-clock-history me-2 text-danger"></i>Donation History</a></li>
                                @if(Auth::user()->role !== 'admin')
                                <li><a class="dropdown-item py-2" href="{{ route('requests.create') }}"><i class="bi bi-plus-circle-fill me-2 text-danger"></i>Post Request</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger py-2">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-lg-3">
                            <a class="nav-link btn-premium-outline px-4 py-2 me-2" href="{{ route('login') }}" style="border-width: 2px;">Login</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="btn-premium ms-lg-2" href="{{ route('register') }}">Join Network</a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Premium Footer -->
    <footer class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h3 class="fw-bold text-white mb-3">BloodConnect</h3>
                    <p class="small opacity-75">Empowering communities through seamless blood donation management. Every drop counts.</p>
                </div>
                <div class="col-lg-2 ms-auto">
                    <h6 class="text-white fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#" class="text-white-50 text-decoration-none">About Us</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Our Impact</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="text-white fw-bold mb-3">Contact Us</h6>
                    <p class="small text-white-50 m-0"><i class="bi bi-envelope-fill me-2"></i>waseembhutta723@gmail.com</p>
                    <p class="small text-white-50 mt-2"><i class="bi bi-whatsapp me-2"></i>+923176096120</p>
                    <p class="small text-white-50">Online Blood Donation System — Pakistan</p>
                </div>
            </div>
            <hr class="my-4 border-secondary opacity-25">
            <div class="text-center small opacity-50">
                &copy; {{ date('Y') }} BloodConnect Pakistan. All Rights Reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @stack('scripts')

    <!-- Auto-close mobile nav on scroll (with cooldown + threshold) -->
    <script>
        (function () {
            var navEl        = document.getElementById('navbarNav');
            var bsCollapse   = null;
            var canClose     = false;   // becomes true 400ms after nav opens
            var openTimer    = null;
            var scrollOrigin = 0;       // Y position when nav opened

            if (!navEl) return;

            // When the navbar STARTS opening — start a cooldown
            navEl.addEventListener('show.bs.collapse', function () {
                canClose     = false;
                scrollOrigin = window.scrollY;
                clearTimeout(openTimer);
                openTimer = setTimeout(function () { canClose = true; }, 400);
            });

            // When the navbar closes — reset
            navEl.addEventListener('hide.bs.collapse', function () {
                canClose = false;
                clearTimeout(openTimer);
            });

            // On scroll — only close if cooldown passed AND scrolled >12px
            window.addEventListener('scroll', function () {
                if (!canClose) return;
                if (!navEl.classList.contains('show')) return;
                if (Math.abs(window.scrollY - scrollOrigin) < 12) return;

                if (!bsCollapse) {
                    bsCollapse = bootstrap.Collapse.getOrCreateInstance(navEl);
                }
                bsCollapse.hide();
            }, { passive: true });
        })();
    </script>
</body>
</html>

