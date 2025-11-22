@extends('layouts.app')

@section('container_class', 'container-fluid')

@section('content')
<style>
  .nearest-hero {
    position: relative;
    height: 280px;
    background-color: #111;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .nearest-hero_background {
    position: absolute;
    width: 100%;
    height: 100%;
    background-image: url('{{ asset('img/nearby.jpg') }}');
    background-size: cover;
    background-position: center;
    filter: brightness(0.9);
    z-index: 1;
  }
  .nearest-hero_overlay {
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
  }
  .nearest-hero_title {
    font-size: 42px;
    font-weight: 800;
    margin: 0;
    text-transform: uppercase;
  }
  .nearest-hero_subtitle {
    font-size: 18px;
    margin-top: 12px;
    max-width: 680px;
    color: #fff;
  }
  .nearest-intro {
    max-width: 1000px;
    margin: 40px auto 10px auto;
    text-align: center;
    padding: 0 20px;
  }
  .nearest-intro h2 {
    font-size: 34px;
    font-weight: 700;
    color: #1b4d3e;
    margin-bottom: 12px;
  }
  .nearest-intro p {
    font-size: 18px;
    color: #4a5a48;
    line-height: 1.6;
  }
  .nearest-section {
    max-width: 1200px;
    margin: 20px auto 60px auto;
    padding: 0 20px;
  }
  @media (max-width: 768px) {
    .nearest-hero_title { font-size: 32px; }
    .nearest-hero_subtitle { font-size: 16px; }
  }
  /* lift the widget a bit closer to hero for cohesion */
  .nearest-section .nearest-container { margin-top: 20px; }
</style>

<!-- Hero -->
<div class="nearest-hero">
  <div class="nearest-hero_background"></div>
  <div class="nearest-hero_overlay">
    <h1 class="nearest-hero_title">Find Nearby Colleges</h1>
    <p class="nearest-hero_subtitle">
      Use your live location or search an address to discover colleges closest to you.
    </p>
  </div>
  </div>

<!-- Intro copy -->
<div class="nearest-intro">
  <h2>Locate Colleges Around You</h2>
  <p>
    Quickly explore institutions based on precise distance from your current location or a place you search,
    and open the map preview for exact positioning before visiting details.
  </p>
  </div>

<div class="nearest-section">
    @include('home.components.nearest-widget', [
        'nearestColleges'  => $nearestColleges ?? collect(),
        'shouldAutoLocate' => $shouldAutoLocate ?? false,
    ])
  </div>
@endsection