@extends('layouts.app')

@section('content')
<head>
    <title>Course Recommendation System</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Course Project" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: white;       
        }

        /* Hero section without orange background */
        .hero-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 4rem 8vw;
            position: relative;
            overflow: hidden;
            min-height: 80vh;
            background-color: transparent;
        }

        /* Left text */
        .hero-text {
            flex: 1 1 500px;
            z-index: 1;
            text-transform: uppercase;
            font-weight: 1300;
            
        }

        .hero-text h1 {
            font-size: 3.8rem;
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .hero-text .description {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: #555;
            font-weight: 500;
            max-width: 500px;
        }

        .hero-btn {
            background-color: white;
            color: black;
            padding: 14px 36px;
            border-radius: 8px;
            border: black;
             border: 2px solid black; /* added border */
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.3s ease, background-color 0.3s ease;
            display: inline-block;
        }

        .hero-btn:hover {
            background-color: #111;
            color: white;
            transform: translateY(-3px);
        }

        /* Carousel container */
        .carousel {
            flex: 1 1 450px;
            max-width: 500px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
            position: relative;
        }

        .carousel-track {
            display: flex;
            width: 100%;
            animation: slide 12s infinite;
        }

        .carousel img {
            width: 100%;
            object-fit: cover;
        }

        /* Auto-slide animation */
        @keyframes slide {
            0%   { transform: translateX(0%); }
            30%  { transform: translateX(0%); }
            33%  { transform: translateX(-100%); }
            63%  { transform: translateX(-100%); }
            66%  { transform: translateX(-200%); }
            96%  { transform: translateX(-200%); }
            100% { transform: translateX(0%); }
        }

        /* Responsive */
        @media (max-width: 900px) {
            .hero-section {
                flex-direction: column;
                text-align: center;
                padding: 3rem 2rem;
            }
            .hero-text h1 {
                font-size: 2.6rem;
            }
            .carousel {
                margin-top: 2rem;
            }
        }
    </style>
</head>

<section class="hero-section">
    <div class="hero-text">
        <h1>
            Find Your <strong>Perfect College</strong><br> with EduQuest
        </h1>
        <p class="description">
            Discover Top Educational Institutions Tailored to Your Goals.<br>
            Start Your Journey to Success with Personalized Guidance Today!
        </p>
        <a href="#" class="hero-btn">Get Started</a>
    </div>

    <!-- Image Carousel -->
    <div class="carousel">
        <div class="carousel-track">
            <img src="{{ asset('img/landing.jpg') }}" alt="Smiling Graduate">
            <img src="{{ asset('img/landing.jpg') }}" alt="Campus View">
            <img src="{{ asset('img/landing.jpg') }}" alt="Students Group">
        </div>
    </div>
</section>
@endsection
