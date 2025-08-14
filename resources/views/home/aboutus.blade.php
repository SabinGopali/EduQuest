@extends('layouts.app')
@section('content')

<head>
  <title>About Us</title>
  <meta charset="utf-8">
  <meta name="description" content="Course Project">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="{{ asset('home/plugins/fontawesome-free-5.0.1/css/fontawesome-all.css') }}">

  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: white;
      margin: 0;
      padding: 0;
    }

    .home {
      position: relative;
      height: 280px;
      background-color: white;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .home_background {
      position: absolute;
      width: 100%;
      height: 100%;
      background-size: cover;
      background-position: center;
      
      z-index: 1;
    }

    .about-container {
      max-width: 1300px;
      margin: 60px auto;
      padding: 0 20px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      align-items: center;
    }

    .about-heading {
      color: #fb923c;
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .about-title {
      font-size: 44px;
      font-weight: 800;
      color: #111827;
      line-height: 1.25;
      margin-bottom: 24px;
    }

    .about-description {
      color: #4b5563;
      font-size: 17px;
      line-height: 1.7;
      text-align: justify;
    }

    .about-image {
      width: 100%;
      max-height: 500px;
      border-radius: 16px;
      object-fit: cover;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
      margin-top: 40px;
    }

    .stat-box {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      text-align: center;
    }

    .stat-box h3 {
      font-size: 30px;
      font-weight: 700;
      margin-bottom: 6px;
      color: #1f2937;
    }

    .stat-box p {
      font-size: 15px;
      color: #6b7280;
      margin: 0;
    }

    @media (max-width: 1024px) {
      .about-container {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .about-title {
        font-size: 36px;
      }

      .about-image {
        margin-top: 30px;
        max-height: 400px;
      }

      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 600px) {
      .stats-grid {
        grid-template-columns: 1fr;
      }

      .about-title {
        font-size: 30px;
      }

      .about-image {
        max-height: 300px;
      }
    }
  </style>
</head>

<!-- Hero Section -->
<div class="home" style="height: 280px; position: relative;">
  <div class="home_background" style="
    background-image: url('{{ asset('img/aboutusbanner.jpg') }}');
    background-size: cover;
    background-position: center;
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 1;
  "></div>
</div>

<!-- About Section -->
<div class="about-container">
  <!-- Text Content -->
  <div>
    <p class="about-heading">How It Started</p>
    <h1 class="about-title">Our Dream is<br>Transforming Education Access</h1>
    <p class="about-description">
      EduQuest is a student-led initiative designed as part of our final-year college project.
      We recognized a major challenge: students struggle to find the right college and course that fits their goals and interests.
      So, we created a platform that personalizes college recommendations based on user input, interest, and academic performance.
      With teamwork, research, and feedback from peers, EduQuest aims to guide students through their educational choices with clarity and confidence.
    </p>

    <!-- Stats Section -->
    <div class="stats-grid">
      <div class="stat-box">
        <h3>6+</h3>
        <p>Team Members</p>
      </div>
      <div class="stat-box">
        <h3>12+</h3>
        <p>Institutions Analyzed</p>
      </div>
      <div class="stat-box">
        <h3>85%</h3>
        <p>Test Accuracy</p>
      </div>
      <div class="stat-box">
        <h3>100+</h3>
        <p>Student Feedbacks</p>
      </div>
    </div>
  </div>

  <!-- Image Section -->
  <div>
    <img src="{{ asset('img/Aboutus_banner.jpg') }}" alt="Team Working" class="about-image" />
  </div>
</div>

@endsection
