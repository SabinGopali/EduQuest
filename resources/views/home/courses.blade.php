@extends('layouts.app')
@section('content')

<head>
  <title>Course - Courses</title>
  <meta charset="utf-8">
  <meta name="description" content="Course Project">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="{{ asset('home/plugins/fontawesome-free-5.0.1/css/fontawesome-all.css') }}">

  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      background-color: white;
    }

    .home {
      position: relative;
      height: 280px;
      background-color: #111;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .home_background {
      position: absolute;
      width: 100%;
      height: 100%;
      background-image: url('{{ asset('img/Contact_banner.jpg') }}');
      background-size: cover;
      background-position: center;
      z-index: 1;
    }

    .home_content {
      position: relative;
      z-index: 2;
      text-align: center;
    }

    .home_content h1 {
      font-size: 48px;
      font-weight: bold;
    }

    /* New header text section */
    .page-intro {
      max-width: 900px;
      margin: 40px auto 20px auto;
      padding: 0 20px;
      text-align: center;
      color: black;
    }

    .page-intro h2 {
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 8px;
      letter-spacing: 0.05em;
    }

    .page-intro p {
      font-size: 16px;
      color: black;
      font-weight: 500;
      letter-spacing: 0.02em;
      max-width: 650px;
      margin: 0 auto;
    }

    .course_section {
      max-width: 1300px;
      margin: 60px auto;
      padding: 20px;
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: center;
    }

    .course_card {
      flex: 1 1 calc(33.333% - 30px);
      background: white;
      border-radius: 20px;
      box-shadow: 
        0 4px 6px rgba(0, 0, 0, 0.06),
        0 8px 15px rgba(0, 0, 0, 0.1);
      padding: 30px 20px;
      text-align: center;
      transition: transform 0.35s cubic-bezier(0.25, 0.8, 0.25, 1), 
                  box-shadow 0.35s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
      cursor: pointer;
      user-select: none;
      position: relative;
      overflow: hidden;
    }

    .course_card:hover {
      transform: translateY(-8px) scale(1.05);
      box-shadow:
        0 12px 20px rgba(0, 0, 0, 0.2),
        0 15px 40px rgba(0, 0, 0, 0.15);
      background: white;
    }

    .card-title {
      font-size: 26px;
      font-weight: 700;
      color: black;
      margin-bottom: 8px;
      letter-spacing: 0.04em;
      text-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
    }

    .card-text {
      font-size: 16px;
      color: black;
      margin-bottom: 24px;
      font-weight: 500;
      letter-spacing: 0.02em;
      line-height: 1.4;
      min-height: 48px;
    }

    .course_card a {
      width: 100%;
      margin-top: auto;
      text-decoration: none;
    }

    .course_card a button {
      width: 100%;
      background: white;
      color: black;
      padding: 12px 0;
      border: 2px solid black;
      border-radius: 6px;
      font-size: 17px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease, box-shadow 0.3s ease;
    }

    .course_card a button:hover {
      background: black;
      color: white;
      box-shadow: 0 6px 15px rgba(27, 77, 62, 0.6);
    }

    @media (max-width: 992px) {
      .course_card {
        flex: 1 1 calc(50% - 30px);
      }
    }

    @media (max-width: 600px) {
      .course_card {
        flex: 1 1 100%;
      }
    }
  </style>
</head>

<!-- Hero Banner -->
<div class="home">
  <div class="home_background"></div>
</div>

<!-- Page Intro -->
<div class="page-intro">
  <h2>Explore Our Courses</h2>
  <p>Browse through a curated list of courses across multiple streams and disciplines. Whether you’re looking to start a new academic journey or advance your skills, you’ll find the right fit here.</p>
</div>

<!-- Course Cards Section -->
<div class="course_section">
  @foreach($course as $course)
    <div class="course_card">
      <div class="card-title">{{ $course->name }}</div>
      <div class="card-text">{{ $course->stream }}, {{ $course->subStream }}</div>
      <a href="/view/course/description/{{ $course->id }}">
        <button>View</button>
      </a>
    </div>
  @endforeach
</div>

@endsection
