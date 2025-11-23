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
    width: 100vw;
    margin-left: calc(-50vw + 50%);
    margin-right: calc(-50vw + 50%);
    margin-top: -50px;
    margin-bottom: 0;
  }

  .home_background {
    position: absolute;
    width: 100%;
    height: 100%;
    background-image: url('{{ asset('img/recommendcourse.jpg') }}');
    background-size: cover;
    background-position: center;
    filter: brightness(0.9);
    z-index: 1;
    
  }

  .home_content {
    position: relative;
    z-index: 2;
    text-align: center;
  }

  /* Container */
  .container {
    max-width: 1200px;
    margin: 0 auto 60px;
    padding: 50px 20px 0;
  }

  /* Heading */
  .page-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 30px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  /* Search Bar */
  .search-container {
    max-width: 600px;
    margin: 0 auto 40px auto;
    padding: 0 20px;
  }

  .search-box {
    width: 100%;
    padding: 14px 20px;
    font-size: 1rem;
    border: 2px solid #ddd;
    border-radius: 8px;
    outline: none;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .search-box:focus {
    border-color: #222;
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
  }

  .search-box::placeholder {
    color: #999;
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
    min-height: 280px;
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
    margin: 0 0 8px 0;
  }

  /* Card info */
  .card-info {
    text-align: center;
    margin: 8px 0;
  }

  .card-info-item {
    font-size: 0.9rem;
    color: #666;
    margin: 4px 0;
    font-weight: 500;
  }

  .card-info-label {
    font-weight: 600;
    color: #333;
  }

  /* Button container */
  .button-wrapper {
    display: flex;
    justify-content: center;
  }

  /* Button style */
  .btn-primary {
    background-color: white;
    border: 1px solid black;
    color: black;
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
    background-color: black;
    color: white;
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
      margin-top: 30px;
      padding: 0 15px;
    }
    .nearest-college {
      margin: 50px 15px;
    }
    .home {
      height: 220px;
      width: 100vw;
      margin-left: calc(-50vw + 50%);
      margin-right: calc(-50vw + 50%);
    }
    .home h1 {
      font-size: 32px !important;
    }
    .home p {
      font-size: 16px !important;
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
    <h1 style="font-size: 42px; font-weight: 800; margin: 0; text-transform: uppercase;">Recommended Courses</h1>
    <p style="font-size: 18px; margin-top: 12px; max-width: 600px; color:white;">
      Discover personalized course recommendations tailored to your interests and academic goals.
    </p>
  </div>
</div>

<div class="container">

  <h1 class="page-title">Recommended Courses Just for You!</h1>

  <!-- Search Bar -->
  <div class="search-container">
    <input type="text" id="searchInput" class="search-box" placeholder="Search courses by name...">
  </div>

  <div class="course_boxes" id="courseContainer">
    @if(count($topRecommendedCourses) > 0)
      @foreach($topRecommendedCourses as $course)
        <div class="course_box">
          <div class="card">
            <div class="card-title">
              <h3>{{ $course['name'] }}</h3>
            </div>
            <div class="card-info">
              @if(!empty($course['shortName']))
                <div class="card-info-item">
                  <span class="card-info-label">Short Name:</span> {{ $course['shortName'] }}
                </div>
              @endif
              @if(!empty($course['stream']))
                <div class="card-info-item">
                  <span class="card-info-label">Stream:</span> {{ $course['stream'] }}
                </div>
              @endif
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



<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const courseContainer = document.getElementById('courseContainer');
    const courseBoxes = courseContainer.querySelectorAll('.course_box');

    searchInput.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase().trim();
      
      courseBoxes.forEach(function(box) {
        const courseName = box.querySelector('.card-title h3').textContent.toLowerCase();
        
        if (courseName.includes(searchTerm)) {
          box.style.display = '';
        } else {
          box.style.display = 'none';
        }
      });

      // Show no results message if needed
      const visibleBoxes = Array.from(courseBoxes).filter(box => box.style.display !== 'none');
      let noResultsMsg = document.getElementById('noResultsMsg');
      
      if (visibleBoxes.length === 0 && searchTerm !== '') {
        if (!noResultsMsg) {
          noResultsMsg = document.createElement('div');
          noResultsMsg.id = 'noResultsMsg';
          noResultsMsg.className = 'no-data';
          noResultsMsg.textContent = 'No courses found matching your search.';
          courseContainer.appendChild(noResultsMsg);
        }
      } else {
        if (noResultsMsg) {
          noResultsMsg.remove();
        }
      }
    });
  });
</script>

@endsection
