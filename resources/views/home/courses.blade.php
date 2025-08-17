@extends('layouts.app')
@section('content')

<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    background-color: white;
  }

  /* Hero Banner */
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
    background-image: url('{{ asset('img/course.jpg') }}');
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

  /* Page Intro */
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
  }

  .page-intro p {
    font-size: 16px;
    color: black;
    font-weight: 500;
    max-width: 650px;
    margin: 0 auto;
  }

  /* Course Grid (same as first page design) */
  .course_section {
    max-width: 1200px;
    margin: 60px auto;
    padding: 0 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    justify-content: center;
  }

  .course_box {
    flex: 1 1 300px;
    max-width: 320px;
  }

  .card {
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgb(0 0 0 / 0.1);
    height: 250px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 20px;
    background-color: #fff;
    transition: box-shadow 0.3s ease;
    text-align: center;
  }

  .card:hover {
    box-shadow: 0 8px 20px rgb(0 0 0 / 0.15);
  }

  .card-title h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #222;
    margin: 0;
  }

  .button-wrapper {
    display: flex;
    justify-content: center;
  }

  .btn-primary {
    background-color: #ff4d4d;
    border: none;
    color: white;
    padding: 12px 28px;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    text-decoration: none;
    display: inline-block;
  }

  .btn-primary:hover,
  .btn-primary:focus {
    background-color: #e04343;
  }

  @media (max-width: 768px) {
    .course_box {
      max-width: 100%;
      flex: 1 1 100%;
    }
    .course_section {
      gap: 20px;
    }
  }
</style>

<!-- Hero Banner -->
<div class="home">
  <div class="home_background"></div>
  <div style="
    position: absolute;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    z-index: 2;
    padding: 0 20px;
    color: #fff;
  ">
    <h1 style="font-size: 42px; font-weight: 800; margin: 0; text-transform: uppercase;">Our Courses</h1>
    <p style="font-size: 18px; margin-top: 12px; max-width: 600px; color:white;">
      Discover courses across streams and disciplines to shape your academic and career journey.
    </p>
  </div>
</div>

<!-- Page Intro -->
<div class="page-intro">
  <h2>Explore Our Courses</h2>
  <p>Browse through a curated list of courses across multiple streams and disciplines. Whether you’re looking to start a new academic journey or advance your skills, you’ll find the right fit here.</p>
</div>

<!-- Course Cards Section -->
<div class="course_section">
  @foreach($course as $course)
    <div class="course_box">
      <div class="card">
        <div class="card-title">
          <h3>{{ $course->name }}</h3>
        </div>
        <div class="button-wrapper">
          <a href="/view/course/description/{{ $course->id }}" class="btn-primary">View Details</a>
        </div>
      </div>
    </div>
  @endforeach
</div>

@endsection
