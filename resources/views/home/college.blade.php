@extends('layouts.app')
@section('content')

<head>
  <title>Course - College</title>
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
      background-image: url('{{ asset('images/courses_background.jpg') }}');
      background-size: cover;
      background-position: center;
      filter: brightness(0.3);
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

    .intro_section {
      max-width: 1000px;
      margin: 50px auto 20px auto;
      text-align: center;
      padding: 0 20px;
    }

    .intro_section h2 {
      font-size: 36px;
      font-weight: 700;
      color: #1b4d3e;
      margin-bottom: 12px;
    }

    .intro_section p {
      font-size: 18px;
      color: #4a5a48;
      line-height: 1.6;
    }

    .college_section {
      max-width: 1300px;
      margin: 40px auto 60px auto;
      padding: 20px;
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: center;
    }

    .college_card {
      flex: 1 1 calc(33.333% - 30px);
      background: linear-gradient(145deg, #f6f9f7, #ffffff);
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

    .college_card:hover {
      transform: translateY(-8px) scale(1.05);
      box-shadow:
        0 12px 20px rgb(255, 255, 255),
        0 15px 40px rgb(255, 255, 255);
      background: linear-gradient(145deg, #ffffff, #ffffff);
    }

    .college_card img {
      height: 120px;
      width: 120px;
      object-fit: contain;
      margin-bottom: 22px;
      border-radius: 16px;
      background-color: #fafafa;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.4);
      transition: box-shadow 0.3s ease;
    }

    .college_card:hover img {
      box-shadow: 0 0 18px 4px #000000;
    }

    .card-title {
      font-size: 26px;
      font-weight: 700;
      color: #1b4d3e;
      margin-bottom: 8px;
      letter-spacing: 0.04em;
      text-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
    }

    .card-text {
      font-size: 16px;
      color: #4a5a48;
      margin-bottom: 24px;
      font-weight: 500;
      letter-spacing: 0.02em;
      line-height: 1.4;
      max-height: 52px;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .college_card a {
      width: 100%;
      margin-top: auto;
      text-decoration: none;
    }

    .college_card a button {
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

    .college_card a button:hover {
      background: black;
      color: white;
    }

    @media (max-width: 992px) {
      .college_card {
        flex: 1 1 calc(50% - 30px);
      }
    }

    @media (max-width: 600px) {
      .college_card {
        flex: 1 1 100%;
      }
    }
  </style>
</head>

<!-- Hero Banner -->
<div class="home">
  <div class="home_background"></div>
  <div class="home_content" style="width: 100%; height: 38vh; overflow: hidden;">
    <img src="{{ asset('img/College_banner.jpg') }}" alt="Colleges" 
         style="width: 100%; height: 100%; object-fit: cover;">
  </div>
</div>

<!-- Intro Section -->
<div class="intro_section">
  <h2>Explore Top Colleges</h2>
  <p>Browse through a curated list of colleges offering diverse programs and academic excellence. 
     Find detailed information about each institution and start your journey towards success by choosing 
     the right place for your education.</p>
</div>

<!-- College Cards Section -->
<div class="college_section">
  @foreach($college as $college)
    <div class="college_card">
      <img src="{{ asset('storage/' . $college->logo) }}" alt="{{ $college->name }} Logo">
      <div class="card-title">{{ $college->name }}</div>
      <div class="card-text">{{ $college->address }}</div>
      <a href="/college/detail/{{ $college->id }}">
        <button>View</button>
      </a>
    </div>
  @endforeach
</div>

@endsection
