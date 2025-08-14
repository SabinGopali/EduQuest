@extends('layouts.app')
@section('content')

<style>
  body {
    background-color: #f4f6f8;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
  }

  .page-header {
    max-width: 1200px;
    margin: 40px auto 10px auto;
    padding: 0 20px;
    text-align: center;
    color: black;
  }

  .page-header h2 {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 6px;
    letter-spacing: 0.05em;
    text-shadow: 0 1px 1px rgba(0,0,0,0.05);
  }

  .page-header p {
    font-size: 16px;
    color: #4a5a48;
    font-weight: 500;
    letter-spacing: 0.02em;
    max-width: 600px;
    margin: 0 auto;
  }

  .course-section {
    max-width: 1200px;
    margin: 30px auto 60px auto;
    padding: 30px 20px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px; /* consistent spacing between cards */
    box-sizing: border-box;
  }

  .course-card {
    flex: 1 1 calc(33.333% - 30px);
    max-width: calc(33.333% - 30px);
    background: linear-gradient(145deg, #f6f9f7, #ffffff);
    border-radius: 20px;
    box-shadow: 
      0 4px 6px rgba(0, 0, 0, 0.06),
      0 8px 15px rgba(0, 0, 0, 0.1);
    padding: 28px 22px;
    text-align: center;
    transition: transform 0.35s cubic-bezier(0.25, 0.8, 0.25, 1),
                box-shadow 0.35s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-sizing: border-box;
    cursor: default;
    user-select: none;
  }

  .course-card:hover {
    transform: translateY(-8px) scale(1.05);
    box-shadow:
      0 12px 20px rgba(0, 0, 0, 0.15),
      0 15px 40px rgba(0, 0, 0, 0.12);
    background: linear-gradient(145deg, #ffffff, #ffffff);
  }

  .course-card img {
    height: 100px;
    width: 100px;
    object-fit: contain;
    margin-bottom: 22px;
    border-radius: 14px;
    border: 3px solid #ccc;
    background-color: #fafafa;
    box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
  }

  .course-card:hover img {
    box-shadow: 0 0 18px 4px black;
  }

  .card-title {
    font-size: 20px;
    font-weight: 700;
    color: black;
    margin-bottom: 8px;
    letter-spacing: 0.04em;
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
  }

  .card-title a {
    text-decoration: none;
    color: inherit;
  }

  .card-title a:hover {
    color: black;
  }

  .card-text {
    font-size: 15px;
    color: #4a5a48;
    margin-bottom: 28px;
    font-weight: 500;
    letter-spacing: 0.02em;
    line-height: 1.4;
    min-height: 48px;
  }

  .course-card a {
    width: 100%;
    display: flex;
    justify-content: center;
  }

  .course-card a button {
    width: auto;
    min-width: 320px;
    padding: 12px 28px;
    background: white;
    color: black;
    border: 2px solid black;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease, box-shadow 0.3s ease;
  }

  .course-card a button:hover {
    background: black;
    color: white;
  }

  .no-data {
    text-align: center;
    font-size: 18px;
    color: #666;
    padding: 80px 0;
    width: 100%;
  }

  /* Responsive tweaks */
  @media (max-width: 992px) {
    .course-card {
      flex: 1 1 calc(50% - 30px);
      max-width: calc(50% - 30px);
    }
    .page-header h2 {
      font-size: 28px;
    }
  }

  @media (max-width: 600px) {
    .course-card {
      flex: 1 1 100%;
      max-width: 100%;
    }
    .page-header h2 {
      font-size: 24px;
    }
  }
</style>

<div class="page-header">
  <h2>Recommended Colleges For Your Courses</h2>
  <p>Explore top colleges carefully matched to the courses you are interested in.</p>
</div>

<div class="course-section">
  @if (!empty($courseDetails) && count($courseDetails) > 0)
    @foreach ($courseDetails as $detail)
      <div class="course-card">
        <img src="{{ asset('storage/' . $detail->college->logo) }}" alt="College Logo">
        <div class="card-title">
          <a href="courses.html">{{ $detail->college->name }}</a>
        </div>
        <div class="card-text">{{ $detail->college->address }}</div>
        <a href="/college/detail/course/description/{{ $detail->id }}">
          <button>View</button>
        </a>
      </div>
    @endforeach
  @else
    <div class="no-data">No data available.</div>
  @endif
</div>

@endsection
