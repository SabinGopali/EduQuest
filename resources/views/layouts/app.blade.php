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

    .dkjiGx { height: 65px; }
</style>
</head>
<body>
<div class="super_container">

	<nav class="badass-navbar">
		<div class="logo">
			<img src="{{asset('home/images/logo_12.png')}}" alt="Logo" class="logo">
			<span>EduQuest</span>
		</div>
		<ul>
			<li><a href="/">Home</a></li>
			<li><a href="{{ route('course.showForStudent') }}">Courses</a></li>
			<li><a href="{{ route('college.showForStudent') }}">Colleges</a></li>
			<li><a href="{{ route('algorithm.hybrid') }}">Smart Recommend</a></li>
			<li><a href="{{ route('search.index') }}">Search</a></li>
		</ul>
		<div class="auth-buttons">
			@if(!Auth::guard('student')->check())
				<a href="{{ route('student.loginFrom') }}"><button>Login</button></a>
				<a href="{{ route('student.registerFrom') }}"><button>Signup</button></a>
			@else
				<div class="dropdown">
					<a href="{{ route('student.getById') }}"><button>Welcome</button></a>
				</div>
			@endif
		</div>
	</nav>

	<div class="dkjiGx"></div>

	@yield('content')
</div>

<script src="{{asset('home/js/jquery-3.2.1.min.js')}}"></script>
<script src="{{asset('home/styles/bootstrap4/popper.js')}}"></script>
<script src="{{asset('home/styles/bootstrap4/bootstrap.min.js')}}"></script>
<script src="{{asset('home/plugins/greensock/TweenMax.min.js')}}"></script>
<script src="{{asset('home/plugins/greensock/TimelineMax.min.js')}}"></script>
<script src="{{asset('home/plugins/scrollmagic/ScrollMagic.min.js')}}"></script>
<script src="{{asset('home/plugins/greensock/animation.gsap.min.js')}}"></script>
<script src="{{asset('home/plugins/greensock/ScrollToPlugin.min.js')}}"></script>
<script src="{{asset('home/plugins/OwlCarousel2-2.2.1/owl.carousel.js')}}"></script>
<script src="{{asset('home/plugins/easing/easing.js')}}"></script>
<script src="{{asset('home/plugins/parallax-js-master/parallax.min.js')}}"></script>
<script src="{{asset('home/js/custom.js')}}"></script>
</body>
</html>
