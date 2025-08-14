@extends('layouts.app')
@section('content')

<style>
  /* Container */
  .container {
    max-width: 1200px;
    margin: 200px auto 60px;
    padding: 0 20px;
  }

  /* Heading */
  .page-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 40px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  /* Course grid */
  .course_boxes {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    justify-content: center;
  }

  /* Single course box */
  .course_box {
    flex: 1 1 300px;
    max-width: 320px;
  }

  /* Card style */
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
  }
  .card:hover {
    box-shadow: 0 8px 20px rgb(0 0 0 / 0.15);
  }

  /* Card title */
  .card-title h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #222;
    text-align: center;
    margin: 0;
  }

  /* Button container */
  .button-wrapper {
    display: flex;
    justify-content: center;
  }

  /* Button style */
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

  /* No data message */
  .no-data {
    text-align: center;
    font-size: 1.25rem;
    color: #666;
    margin-top: 40px;
  }

  /* Additional section at bottom */
  .nearest-college {
    max-width: 700px;
    margin: 80px auto 40px;
    text-align: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  .nearest-college h2 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 25px;
    color: #333;
  }
  .nearest-college .btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 12px 30px;
    font-size: 1rem;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.3s ease;
  }
  .nearest-college .btn:hover,
  .nearest-college .btn:focus {
    background-color: #0056b3;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .course_boxes {
      gap: 20px;
    }
    .course_box {
      max-width: 100%;
      flex: 1 1 100%;
    }
    .container {
      margin-top: 120px;
      padding: 0 15px;
    }
    .nearest-college {
      margin: 50px 15px;
    }
  }
</style>

<div class="container">

  <h1 class="page-title">Recommended Courses Just for You!</h1>

  <div class="course_boxes">
    @if(count($topRecommendedCourses) > 0)
      @foreach($topRecommendedCourses as $course)
        <div class="course_box">
          <div class="card">
            <div class="card-title">
              <h3>{{ $course['name'] }}</h3>
            </div>
            <div class="button-wrapper">
              <a href="/view/course/description/{{ $course['course_id'] }}" class="btn-primary">View Details</a>
            </div>
          </div>
        </div>
      @endforeach
    @else
      <div class="no-data">No courses available at the moment.</div>
    @endif
  </div>

</div>

<div class="nearest-college">
  <h2>
    Looking for the nearest colleges to your location? We've got you covered!<br>
    Click the button below, and we'll show you the closest colleges based on where you are. 🌍
  </h2>
  <a href="{{ route('home.nearest') }}" class="btn">Nearest College</a>
</div>

@endsection
