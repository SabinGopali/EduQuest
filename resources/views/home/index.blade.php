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
            font-family: 'Arial Black', sans-serif;
            font-size: 3.8rem;
            font-weight: bolder;
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
                font-size: 3.6rem;
            }
            .carousel {
                margin-top: 2rem;
            }
        }

        /* KMeans demo section */
        .kmeans-section {
            padding: 4rem 8vw;
            background-color: #fafafa;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }
        .kmeans-grid {
            display: grid;
            grid-template-columns: 1.3fr 0.7fr;
            gap: 2rem;
        }
        .kmeans-title {
            font-size: 2rem;
            font-weight: 800;
            color: #222;
            margin-bottom: 0.5rem;
        }
        .kmeans-subtitle {
            color: #555;
            margin-bottom: 1.5rem;
        }
        .kmeans-steps {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
            max-height: 320px;
            overflow: auto;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        .kmeans-steps li {
            margin: 0.25rem 0;
        }
        .kmeans-badge {
            display: inline-block;
            background: #111;
            color: #fff;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.8rem;
            margin-bottom: 1rem;
        }
        @media (max-width: 900px) {
            .kmeans-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<section class="hero-section">
    <div class="hero-text" >
        <h1>
            Find Your <strong>Perfect College</strong><br> with EduQuest
        </h1>
        <p class="description">
            Discover Top Educational Institutions Tailored to Your Goals.<br>
            Start Your Journey to Success with Personalized Guidance Today!
        </p>
        <a href="{{ route('algorithm.hybrid') }}" class="hero-btn">Get Started</a>
    </div>

    <!-- Image Carousel -->
    <div class="carousel">
        <div class="carousel-track">
            <img src="{{ asset('img/carousel1.jpg') }}" alt="Smiling Graduate">
            <img src="{{ asset('img/carousel2.jpg') }}" alt="Campus View">
            <img src="{{ asset('img/carousel3.jpg') }}" alt="Students Group">
        </div>
    </div>
</section>

<section id="kmeans-demo" class="kmeans-section">
    <div class="kmeans-grid">
        <div>
            <span class="kmeans-badge">On-scroll demo</span>
            <h2 class="kmeans-title">K-Means Clustering</h2>
            <p class="kmeans-subtitle">
                As you reach this section, we run K-Means on a small dataset and narrate each step: initialization, assignment, centroid update, and convergence.
            </p>
            <ul id="kmeans-steps" class="kmeans-steps"></ul>
        </div>
        <div>
            <div id="kmeans-summary" class="kmeans-steps" style="min-height: 140px;"></div>
        </div>
    </div>
</section>
@endsection