@extends('layouts.app')
@section('content')

<style>
  :root {
    --primary-color: black;
    --primary-hover: #1e3a8a;
    --bg-light: #f9fafb;
    --text-dark: #111827;
    --text-light: #6b7280;
    --border-color: #e5e7eb;
    --radius: 14px;
  }

  * {
    box-sizing: border-box;
  }

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--bg-light);
    margin: 0;
    padding: 15px;
    color: var(--text-dark);
  }

  .section-container {
    max-width: 1000px;
    margin: 20px auto;
    background: #fff;
    border-radius: var(--radius);
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    padding: 25px;
    transition: transform 0.2s ease;
  }

  .section-container:hover {
    transform: translateY(-2px);
  }

  .profile-header {
    display: flex;
    align-items: center;
    gap: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border-color);
    flex-wrap: wrap;
  }

  .profile-header img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 100%;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }

  .profile-header-details h2 {
    margin: 0;
    font-size: 1.9rem;
    font-weight: 700;
  }

  .profile-header-details small {
    font-size: 0.95rem;
    color: var(--text-light);
  }

  .section-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 16px;
    padding-bottom: 6px;
    border-bottom: 2px solid var(--primary-color);
  }

  .info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 15px;
  }

  .info-item {
    background: var(--bg-light);
    padding: 14px;
    border-radius: 10px;
    border: 1px solid var(--border-color);
    transition: background 0.2s ease;
  }

  .info-item:hover {
    background: #f3f4f6;
  }

  .info-item .label {
    font-weight: 600;
    color: #374151;
    font-size: 0.9rem;
    margin-bottom: 6px;
  }

  .info-item .value {
    font-size: 1.05rem;
  }

  /* Gallery */
  .gallery-container {
    position: relative;
    overflow: hidden;
    height: 400px;
    border-radius: 12px;
  }

  .gallery-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.8s ease-in-out;
  }

  .gallery-slide.active {
    opacity: 1;
  }

  .gallery-slide img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background: #fff;
  }

  .gallery-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: white;
    border: 2px solid black;
    color: black;
    font-size: 1.4rem;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 50%;
    z-index: 5;
    transition: background 0.2s ease;
  }

  .gallery-btn:hover {
    background: rgba(0,0,0,0.6);
  }

  .gallery-btn.prev {
    left: 10px;
  }

  .gallery-btn.next {
    right: 10px;
  }

  /* Buttons */
  .btn-primary {
    background-color: white;
    border: 2px solid black;
    padding: 10px 18px;
    border-radius: 6px;
    font-weight: 600;
    color: black;
    font-size: 0.95rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background 0.2s ease;
  }

  .btn-primary:hover {
    background-color: black;
    color: white;
  }

  /* Mobile tweaks */
  @media (max-width: 600px) {
    .profile-header {
      text-align: center;
      justify-content: center;
    }
  }
</style>

<!-- Profile Header -->
<div class="section-container">
  <div class="profile-header">
    <img src="{{ asset('storage/' . $college->logo) }}" alt="College Logo">
    <div class="profile-header-details">
      <h2>{{ $college->name }}</h2>
      <small>Contact: {{ $college->contact }}</small>
    </div>
  </div>
</div>

<!-- Description -->
<div class="section-container">
  <div class="section-title">Description</div>
  <p>{{ $college->description }}</p>
</div>

<!-- Personal Information -->
@if($college->user)
  <div class="section-container">
    <div class="section-title">Personal Information</div>
    <div class="info-grid">
      @if(!empty($college->user->first_name))
        <div class="info-item">
          <div class="label">First Name</div>
          <div class="value">{{ $college->user->first_name }}</div>
        </div>
      @endif
      @if(!empty($college->user->last_name))
        <div class="info-item">
          <div class="label">Last Name</div>
          <div class="value">{{ $college->user->last_name }}</div>
        </div>
      @endif
      @if(!empty($college->user->dob))
        <div class="info-item">
          <div class="label">Date of Birth</div>
          <div class="value">{{ \Carbon\Carbon::parse($college->user->dob)->format('d-m-Y') }}</div>
        </div>
      @endif
      @if(!empty($college->user->email))
        <div class="info-item">
          <div class="label">Email</div>
          <div class="value">{{ $college->user->email }}</div>
        </div>
      @endif
      @if(!empty($college->user->phone))
        <div class="info-item">
          <div class="label">Phone</div>
          <div class="value">{{ $college->user->phone }}</div>
        </div>
      @endif
      @if(!empty($college->user->role))
        <div class="info-item">
          <div class="label">User Role</div>
          <div class="value">{{ $college->user->role }}</div>
        </div>
      @endif
    </div>
  </div>
@endif

<!-- Courses -->
<div class="section-container">
  <div class="section-title">Courses</div>
  <div class="info-grid">
    @foreach ($college->courseDetails as $courseDetail)
      <div class="info-item">
        <div class="label">{{ $courseDetail->course->name }}</div>
        <div class="value">{{ $courseDetail->course->stream }}, {{ $courseDetail->course->subStream }}</div>
        <br>
        <a href="/college/detail/course/description/{{ $courseDetail->id }}" class="btn-primary">View</a>
      </div>
    @endforeach
  </div>
</div>

<!-- Gallery -->
@if($college->images && count($college->images))
  <div class="section-container">
    <div class="section-title">Gallery</div>
    <div class="gallery-container">
      @foreach($college->images as $index => $gallery)
        <div class="gallery-slide @if($index === 0) active @endif">
          <img src="{{ asset('storage/' . $gallery->path) }}" alt="Gallery Image {{ $index + 1 }}">
        </div>
      @endforeach

      @if(count($college->images) > 1)
        <button class="gallery-btn prev" onclick="changeSlide(-1)">&#10094;</button>
        <button class="gallery-btn next" onclick="changeSlide(1)">&#10095;</button>
      @endif
    </div>
  </div>
@endif

<script>
  const slides = document.querySelectorAll('.gallery-slide');
  let currentSlide = 0;

  function showSlide(index) {
    slides.forEach((slide, i) => slide.classList.toggle('active', i === index));
  }

  function changeSlide(direction) {
    currentSlide = (currentSlide + direction + slides.length) % slides.length;
    showSlide(currentSlide);
  }

  if (slides.length > 1) {
    setInterval(() => changeSlide(1), 4000);
  }
</script>

@endsection
