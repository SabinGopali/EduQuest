<!DOCTYPE html>
<html lang="en">
<head>
<title>EduQuest</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Course Project">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" type="text/css" href="{{asset('home/plugins/OwlCarousel2-2.2.1/owl.carousel.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('home/plugins/OwlCarousel2-2.2.1/owl.theme.default.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('home/plugins/OwlCarousel2-2.2.1/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('home/styles/main_styles.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('home/styles/responsive.css')}}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    /* Floating navbar style (namespaced) */
    .eq-navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: auto;
        box-sizing: border-box;
        background: white;
        backdrop-filter: blur(10px);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 80px;
        box-shadow: 0 4px 15px rgb(247, 242, 242);
        z-index: 9999;
        transition: all 0.3s ease;
        flex-wrap: wrap;
        border-bottom: 2px solid black;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .eq-navbar.scrolled {
        background: rgba(255, 255, 255, 0.85);
        padding: 10px 50px;
    }
    .eq-navbar .logo {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
        cursor: default;
        width: 50px;       
        height: 50px;      
        object-fit: contain; 
    }
    .eq-navbar .logo svg {
        width: 36px;
        height: 36px;
        fill: #ff4d4d;
        flex-shrink: 0;
    }
    .eq-navbar .logo span {
        font-size: 1.6rem;
        font-weight: bold;
        color: #ff4d4d;
        letter-spacing: 1px;
        user-select: none;
    }
    .eq-navbar ul {
        
        display: flex;
        gap: 25px;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .eq-navbar ul li a {
        text-decoration: none;
        font-size: 1rem;
        font-weight: 500;
        color: #333;
        transition: color 0.3s ease;
        padding: 6px 0;
    }
    .eq-navbar ul li a:hover {
        color: #ff4d4d;
    }
    .eq-navbar .auth-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }
    .eq-navbar .auth-buttons a button {
        background: white;
        color: black;
        border: 1px solid black;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s ease;
        font-size: 0.95rem;
    }
    .eq-navbar .auth-buttons a button:hover {
        background: black;
        color: white;
    }

    /* Mobile menu toggle button */
    .eq-navbar .menu-toggle {
        display: none;
        background: none;
        border: 1px solid #000;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 0.95rem;
        cursor: pointer;
        align-items: center;
        gap: 8px;
    }
    .eq-navbar .menu-toggle .icon {
        font-size: 1.2rem;
        line-height: 1;
    }

    /* New Welcome Button Style */
    .eq-navbar .dropdown button {
        background: linear-gradient(135deg, #ff6b6b, #ff4d4d);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 50px; /* pill-shaped */
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .eq-navbar .dropdown button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(255, 77, 77, 0.4);
    }

    /* Dropdown menu tweaks to match new design */
    .eq-navbar .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 110%;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        padding: 10px 0;
        min-width: 180px;
        z-index: 10000;
    }
    .eq-navbar .dropdown:hover .dropdown-menu {
        display: block;
    }
    .eq-navbar .dropdown-menu li {
        list-style: none;
    }
    .eq-navbar .dropdown-menu li a {
        display: block;
        padding: 10px 18px;
        font-size: 0.95rem;
        color: #333;
        transition: all 0.2s ease;
        border-radius: 6px;
    }
    .eq-navbar .dropdown-menu li a:hover {
        background: #ff4d4d;
        color: white;
    }

    /* Mobile */
    @media (max-width: 768px) {
        .eq-navbar {
            padding: 12px 20px;
            flex-direction: column;
            align-items: flex-start;
        }
        .eq-navbar .menu-toggle {
            display: inline-flex;
        }
        .eq-navbar ul {
            display: none;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }
        .eq-navbar .auth-buttons {
            display: none;
            margin-top: 10px;
            width: 100%;
            justify-content: flex-start;
        }
        /* When menu is open on mobile */
        .eq-navbar.open ul,
        .eq-navbar.open .auth-buttons {
            display: flex;
        }
    }
</style>

</head>
<body>

<div class="super_container">

    <!-- Floating Navbar -->
    <header class="eq-navbar" id="eqNavbar">
       <div class="logo" aria-label="EduQuest logo">
            <img 
                src="{{ asset('img/logo.png') }}"
                alt="EduQuest Logo"
                width="156"
                height="90"
                style="flex-shrink: 0;"
            />
            
        </div>
        <button class="menu-toggle" id="eqMenuToggle" aria-controls="eqNavList" aria-expanded="false" aria-label="Toggle navigation">
            <span class="icon">☰</span> Menu
        </button>
        <ul id="eqNavList">
            <li><a href="{{ url('/') }}">Home</a></li>

            @auth('student')
                {{-- <li><a href="{{ url('/recommend') }}">Collaborative Recommend</a></li> --}}
                <li><a href="{{ url('/recommend-content') }}">Recommend Courses</a></li>
            @else
                {{-- <li><a href="{{ url('/login') }}">Collaborative Recommend</a></li> --}}
                <li><a href="{{ url('/login') }}">Recommend Courses</a></li>
            @endauth

            <li><a href="{{ url('/courses') }}">Courses</a></li>
            <li><a href="{{ url('/college') }}">College</a></li>
            <li><a href="{{ route('home.nearest') }}">Find Nearby Colleges</a></li>
            {{-- <li><a href="{{ route('algorithm.knn') }}">KNN</a></li> --}}
            {{-- <li><a href="{{ url('/aboutus') }}">About Us</a></li>
            <li><a href="{{ url('/contact') }}">Contact</a></li> --}}
        </ul>

        <div class="auth-buttons">
            @auth('student')
                <div class="dropdown">
                    <button>👋 Welcome, {{ Auth::guard('student')->user()->name }}</button>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('/myprofile') }}">My Profile</a></li>
                        <li><a href="{{ url('/inquiry') }}">My Inquiry</a></li>
                        <li><a href="{{ url('/student/bookings') }}">My Bookings</a></li>
                        <li><a href="{{ url('/changepassword') }}">Change Password</a></li>
                        <li><a href="{{ url('/student/logout') }}">Logout</a></li>
                    </ul>
                </div>
            @else
                <a href="{{ url('/login') }}"><button>Login</button></a>
                <a href="{{ url('/register') }}"><button>Sign Up</button></a>
            @endauth
        </div>
    </header>

    <div class="@yield('container_class', 'container')" style="margin-top: 120px; min-height: 100vh">
        @yield('content')
    </div>
    <x-footer/>

</div>

<script>
    // Change navbar style on scroll
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('eqNavbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    // Mobile menu toggle
    (function() {
        const navbar = document.getElementById('eqNavbar');
        const toggle = document.getElementById('eqMenuToggle');
        if (!navbar || !toggle) return;
        toggle.addEventListener('click', function() {
            const isOpen = navbar.classList.toggle('open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
        // Close menu on resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768 && navbar.classList.contains('open')) {
                navbar.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!navbar.contains(e.target) && navbar.classList.contains('open')) {
                navbar.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    })();
</script>

</body>
</html>