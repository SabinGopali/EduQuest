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
        background: rgba(255, 255, 255, 0.95);
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
        width: 50px;       /* increased size */
        height: 50px;      /* keep aspect ratio by setting both */
        object-fit: contain; /* ensures no distortion */
        /* display: block; */
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
    .badass-navbar .auth-buttons button {
        background: white;
        color: black;
        border-color: black;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s ease;
        font-size: 0.95rem;
    }
    .badass-navbar .auth-buttons button:hover {
        background: black;
        color: white;
    }
    .badass-navbar .dropdown {
        position: relative;
    }
    .badass-navbar .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        background: white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        border-radius: 6px;
        padding: 8px 0;
        min-width: 160px;
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
        padding: 8px 16px;
        text-decoration: none;
        color: #333;
        font-size: 0.95rem;
    }
    .badass-navbar .dropdown-menu li a:hover {
        background: #f5f5f5;
        color: #ff4d4d;
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
        src="{{ asset('img/logo.jpg') }}"
        alt="EduQuest Logo"
        width="36"
        height="36"
        style="flex-shrink: 0;"
    />
</div>
        
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>

            @auth('student')
                <li><a href="{{ url('/recommend') }}">Recommend Me</a></li>
            @else
                <li><a href="{{ url('/student/login') }}">Recommend Me</a></li>
            @endauth

            <li><a href="{{ url('/courses') }}">Courses</a></li>
            <li><a href="{{ url('/college') }}">College</a></li>
            <li><a href="{{ url('/aboutus') }}">About Us</a></li>
            <li><a href="{{ url('/contact') }}">Contact</a></li>
        </ul>

        <div class="auth-buttons">
            @auth('student')
                <div class="dropdown">
                    <button>Welcome, {{ Auth::guard('student')->user()->name }}</button>
                    <div class="dropdown-content">
                        <a href="{{ url('/myprofile') }}">My Profile</a>
                        <a href="{{ url('/inquiry') }}">My Inquiry</a>
                        <a href="{{ url('/changepassword') }}">Change Password</a>
                        <a href="{{ url('/student/logout') }}">Logout</a>
                    </div>
                </div>
            @else
                <a href="{{ url('/student/login') }}"><button>Login</button></a>
                <a href="{{ url('/student/register') }}"><button>Sign Up</button></a>
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
