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

<style>
    /* Floating navbar style */
    .badass-navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 12%;
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
    .badass-navbar.scrolled {
        background: rgba(255, 255, 255, 0.85);
        padding: 10px 50px;
    }
    .badass-navbar .logo {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
        cursor: default;
        width: 50px;       
        height: 50px;      
        object-fit: contain; 
    }
    .badass-navbar .logo svg {
        width: 36px;
        height: 36px;
        fill: #ff4d4d;
        flex-shrink: 0;
    }
    .badass-navbar .logo span {
        font-size: 1.6rem;
        font-weight: bold;
        color: #ff4d4d;
        letter-spacing: 1px;
        user-select: none;
    }
    .badass-navbar ul {
        
        display: flex;
        gap: 25px;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .badass-navbar ul li a {
        text-decoration: none;
        font-size: 1rem;
        font-weight: 500;
        color: #333;
        transition: color 0.3s ease;
        padding: 6px 0;
    }
    .badass-navbar ul li a:hover {
        color: #ff4d4d;
    }
    .badass-navbar .auth-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }
    .badass-navbar .auth-buttons a button {
        background: white;
        color: black;
        border: 1px solid black;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s ease;
        font-size: 0.95rem;
    }
    .badass-navbar .auth-buttons a button:hover {
        background: black;
        color: white;
    }

    /* New Welcome Button Style */
    .badass-navbar .dropdown button {
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
    .badass-navbar .dropdown button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(255, 77, 77, 0.4);
    }

    /* Dropdown menu tweaks to match new design */
    .badass-navbar .dropdown-menu {
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
    .badass-navbar .dropdown:hover .dropdown-menu {
        display: block;
    }
    .badass-navbar .dropdown-menu li {
        list-style: none;
    }
    .badass-navbar .dropdown-menu li a {
        display: block;
        padding: 10px 18px;
        font-size: 0.95rem;
        color: #333;
        transition: all 0.2s ease;
        border-radius: 6px;
    }
    .badass-navbar .dropdown-menu li a:hover {
        background: #ff4d4d;
        color: white;
    }

    /* Mobile */
    @media (max-width: 768px) {
        .badass-navbar {
            padding: 12px 20px;
            flex-direction: column;
            align-items: flex-start;
        }
        .badass-navbar ul {
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }
        .badass-navbar .auth-buttons {
            margin-top: 10px;
            width: 100%;
            justify-content: flex-start;
        }
    }
</style>

</head>
<body>

<div class="super_container">

    <!-- Floating Navbar -->
    <header class="badass-navbar" id="badassNavbar">
       <div class="logo" aria-label="EduQuest logo">
            <img 
                src="{{ asset('img/logo.png') }}"
                alt="EduQuest Logo"
                width="126"
                height="126"
                style="flex-shrink: 0;"
            />
            <h2 style="color:black;">EDU<span style="color:red;">QUEST</span></h2>
        </div>
        
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>

            @auth('student')
                <li><a href="{{ url('/recommend') }}">Recommend Me</a></li>
                <li><a href="{{ url('/kmeans-student') }}">My Clusters</a></li>
            @else
                <li><a href="{{ url('/login') }}">Recommend Me</a></li>
            @endauth

            <li><a href="{{ url('/courses') }}">Courses</a></li>
            <li><a href="{{ url('/college') }}">College</a></li>
            <li><a href="{{ url('/kmeans') }}">K-Means</a></li>
            <li><a href="{{ url('/aboutus') }}">About Us</a></li>
            <li><a href="{{ url('/contact') }}">Contact</a></li>
        </ul>

        <div class="auth-buttons">
            @auth('student')
                <div class="dropdown">
                    <button>👋 Welcome, {{ Auth::guard('student')->user()->name }}</button>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('/myprofile') }}">My Profile</a></li>
                        <li><a href="{{ url('/inquiry') }}">My Inquiry</a></li>
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

    <div class="container" style="margin-top: 120px; min-height: 100vh">
        @yield('content')
    </div>
    <x-footer/>

</div>

<script>
    // Change navbar style on scroll
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('badassNavbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>

</body>
</html>