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
    background-image: url('{{ asset('img/college.jpg') }}');
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

  /* Search Bar */
  .search-container {
    max-width: 600px;
    margin: 30px auto 40px auto;
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
    display: flex;
    flex-direction: column; /* Stack content vertically */
    justify-content: space-between; /* Ensures content is spaced out */
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgb(0 0 0 / 0.1);
    height: 350px; /* Fixed height for consistency */
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

  .card-text {
    font-size: 14px;
    color: #666;
    margin: 8px 0 16px 0;
    font-weight: 500;
    flex-grow: 1; /* Allows content to grow and fill available space */
  }

  .button-wrapper {
    margin-top: auto; /* Pushes the button to the bottom */
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
    <h1 style="font-size: 42px; font-weight: 800; margin: 0; text-transform: uppercase;">Explore Colleges & Courses</h1>
    <p style="font-size: 18px; margin-top: 12px; max-width: 600px; color:white;">
      Discover the best colleges and programs tailored to your academic goals and interests.
    </p>
  </div>
</div>

<!-- Intro Section -->
<div class="intro_section">
  <h2>Explore Our Colleges</h2>
  <p>Browse through a curated list of colleges offering diverse programs and academic excellence. 
     Find detailed information about each institution and start your journey towards success by choosing 
     the right place for your education.</p>
</div>

<!-- Search Bar -->
<div class="search-container">
  <input type="text" id="searchInput" class="search-box" placeholder="Search colleges by name or address...">
</div>

<!-- College Cards Section -->
<div class="college_section" id="collegeContainer">
  @foreach($college as $collegeItem)
    <div class="college_box">
      <div class="card">
        <img src="{{ asset('storage/' . $collegeItem->logo) }}" alt="{{ $collegeItem->name }} Logo">
        <div class="card-title"><h3>{{ $collegeItem->name }}</h3></div>
        <div class="card-text">{{ $collegeItem->address }}</div>
        <div class="button-wrapper">
          <a href="/college/detail/{{ $collegeItem->id }}" class="btn-primary">View Details</a>
        </div>
      </div>
    </div>
  @endforeach
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const collegeContainer = document.getElementById('collegeContainer');
    const collegeBoxes = collegeContainer.querySelectorAll('.college_box');

    searchInput.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase().trim();
      
      collegeBoxes.forEach(function(box) {
        const collegeName = box.querySelector('.card-title h3').textContent.toLowerCase();
        const collegeAddress = box.querySelector('.card-text').textContent.toLowerCase();
        
        if (collegeName.includes(searchTerm) || collegeAddress.includes(searchTerm)) {
          box.style.display = '';
        } else {
          box.style.display = 'none';
        }
      });

      // Show no results message if needed
      const visibleBoxes = Array.from(collegeBoxes).filter(box => box.style.display !== 'none');
      let noResultsMsg = document.getElementById('noResultsMsg');
      
      if (visibleBoxes.length === 0 && searchTerm !== '') {
        if (!noResultsMsg) {
          noResultsMsg = document.createElement('div');
          noResultsMsg.id = 'noResultsMsg';
          noResultsMsg.style.cssText = 'text-align: center; font-size: 1.25rem; color: #666; margin-top: 40px; width: 100%; padding: 0 20px;';
          noResultsMsg.textContent = 'No colleges found matching your search.';
          collegeContainer.appendChild(noResultsMsg);
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
