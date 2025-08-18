@extends('layouts.app')
@section('content')

<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: white;
    margin: 0;
    padding: 0;
    color: #212121;
  }

  /* Header */
  .page-header {
    max-width: 900px;
    margin: 40px auto 20px auto;
    padding: 0 20px;
    text-align: center;
  }

  .page-header h2 {
    font-size: 34px;
    font-weight: 800;
    color: #000;
    margin-bottom: 8px;
    letter-spacing: 0.05em;
  }

  .page-header p {
    font-size: 16px;
    color: #555;
    font-weight: 500;
    letter-spacing: 0.02em;
    max-width: 600px;
    margin: 0 auto;
  }

  /* Course container */
  .course-container {
    max-width: 1200px;
    margin: 0 auto 80px auto;
    padding: 40px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
  }

  .course-header {
    text-align: center;
    margin-bottom: 40px;
    border-bottom: 2px solid #e0e0e0;
    padding-bottom: 15px;
  }

  .course-header h1 {
    font-size: 38px;
    font-weight: 800;
    letter-spacing: 1px;
    color: #000;
  }

  /* Info cards */
  .course-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 24px;
    margin-bottom: 35px;
  }

  .info-card {
    background: #fdfdfd;
    border-left: 6px solid black;
    border-radius: 12px;
    padding: 22px 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: transform 0.2s ease, box-shadow 0.3s ease;
  }

  .info-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
  }

  .info-card h3 {
    margin: 0 0 8px 0;
    font-size: 18px;
    font-weight: 700;
    color: #000;
  }

  .info-card p {
    margin: 0;
    font-size: 16px;
    color: #333;
  }

  /* Description */
  .description-box {
    background: #fdfdfd;
    padding: 28px;
    border-radius: 14px;
    border-left: 6px solid black;
    font-size: 16px;
    color: #333;
    line-height: 1.6;
    box-shadow: inset 0 2px 6px rgba(0,0,0,0.03);
    margin-bottom: 40px;
  }

  .description-box strong {
    font-weight: 700;
    color: #000;
    font-size: 18px;
    display: block;
    margin-bottom: 10px;
  }

  /* Button */
  .btn-view {
    display: inline-block;
    background: white;
    color: black;
    padding: 14px 36px;
    font-size: 17px;
    font-weight: 700;
    border-radius: 6px;
    text-decoration: none;
    text-align: center;
    transition: background-color 0.3s ease, transform 0.15s ease;
    border: 2px solid black;
  }

  .btn-view:hover {
    background-color: black;
    color: white;
    transform: translateY(-2px);
  }

  @media (max-width: 768px) {
    .course-container {
      padding: 30px 20px;
    }
    .course-header h1 {
      font-size: 28px;
    }
    .btn-view {
      font-size: 15px;
      padding: 12px 28px;
    }
  }
</style>

<div class="page-header">
  <h2>Course Overview</h2>
  <p>Discover essential knowledge, practical skills, and career possibilities with this program.</p>
</div>

<div class="course-container">
  <div class="course-header">
    <h1>{{ $course->name }}</h1>
  </div>

  <div class="course-grid">
    <div class="info-card">
      <h3>Short Name</h3>
      <p>{{ $course->shortName }}</p>
    </div>
    <div class="info-card">
      <h3>Stream</h3>
      <p>{{ $course->stream }}</p>
    </div>
    <div class="info-card">
      <h3>Substream</h3>
      <p>{{ $course->subStream }}</p>
    </div>
    <div class="info-card">
      <h3>Duration</h3>
      <p>{{ $course->duration }}</p>
    </div>
    <div class="info-card">
      <h3>GPA Limit</h3>
      <p>{{ $course->gpa_limit }}</p>
    </div>
  </div>

  <div class="description-box">
    <strong>Description:</strong>
    {{ $course->description }}
  </div>

  <a href="/view/course/description/college/{{ $course->id }}" class="btn-view">View Colleges</a>
  @if(isset($courseDetail))
  <form method="POST" action="{{ route('booking.store', $courseDetail->id) }}" style="display:inline-block;margin-left:10px;">
    @csrf
    <button type="submit" class="btn-view">Book</button>
  </form>
  @endif
</div>

@endsection
