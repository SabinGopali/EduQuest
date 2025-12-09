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

  /* College Cards - Same as Course Card Design */
  .college_section {
    max-width: 1200px;
    margin: 40px auto 60px auto;
    padding: 0 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    justify-content: center;
  }

  .college_box {
    flex: 1 1 300px;
    max-width: 320px;
  }

  .card {
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgb(0 0 0 / 0.1);
    min-height: 350px;
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* ensures button stays at bottom */
    padding: 20px;
    background-color: #fff;
    transition: box-shadow 0.3s ease;
    text-align: center;
  }

  .card:hover {
    box-shadow: 0 8px 20px rgb(0 0 0 / 0.15);
  }

  .card img {
    height: 80px;
    width: 80px;
    object-fit: contain;
    margin: 0 auto 12px;
    border-radius: 8px;
  }

  .card-title h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #222;
    margin: 0;
  }

  .card-title a {
    text-decoration: none;
    color: inherit;
  }

  .card-title a:hover {
    color: #222;
  }

  .card-text {
    font-size: 14px;
    color: #666;
    margin: 8px 0 16px 0;
    min-height: 40px;
    font-weight: 500;
  }

  .button-wrapper {
    margin-top: auto; /* pushes button to bottom inside card */
    display: flex;
    justify-content: center;
  }

  .btn-primary {
    background-color: white;
    border: 2px solid black;
    color: black;
    padding: 10px 24px;
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
    background-color: black;
    color: white;
  }

  .no-data {
    text-align: center;
    font-size: 18px;
    color: #666;
    padding: 80px 0;
    width: 100%;
  }

  @media (max-width: 768px) {
    .college_box {
      max-width: 100%;
      flex: 1 1 100%;
    }
    .college_section {
      gap: 20px;
    }
  }
</style>

<div class="page-header">
  <h2>Recommended Colleges For Your Courses</h2>
  <p>Explore top colleges carefully matched to the courses you are interested in.</p>
</div>

<!-- College Cards Section -->
<div class="college_section">
  @if (!empty($courseDetails) && count($courseDetails) > 0)
    @foreach ($courseDetails as $detail)
      <div class="college_box">
        <div class="card">
          <img src="{{ asset('storage/' . $detail->college->logo) }}" alt="{{ $detail->college->name }} Logo">
          <div class="card-title">
            <h3><a href="/college/detail/{{ $detail->college->id }}">{{ $detail->college->name }}</a></h3>
          </div>
          <div class="card-text">{{ $detail->college->address }}</div>
          <div class="button-wrapper">
            <a href="/college/detail/course/description/{{ $detail->id }}" class="btn-primary">View Details</a>
          </div>
        </div>
      </div>
    @endforeach
  @else
    <div class="no-data">No data available.</div>
  @endif
</div>

@endsection