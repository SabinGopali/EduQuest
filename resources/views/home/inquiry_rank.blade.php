@extends('layouts.app')

@section('content')
<style>
  .scr-container { max-width: 1200px; margin: 200px auto 60px; padding: 0 20px; }
  .scr-page-title { text-align: center; font-size: 2.2rem; font-weight: 800; color: #222; margin-bottom: 8px; }
  .scr-subtitle { text-align: center; color: #555; margin-bottom: 28px; }
  .scr-grid { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
  .scr-card { width: 320px; background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 16px; box-shadow: 0 4px 10px rgb(0 0 0 / 0.08); }
  .scr-rank { text-align: center; font-weight: 700; color: #0d6efd; margin-bottom: 6px; }
  .scr-logo { height: 80px; width: 80px; object-fit: contain; border-radius: 50%; border: 2px solid #0d6efd; background: #fff; padding: 5px; display:block; margin: 6px auto 12px; }
  .scr-name { text-align: center; font-weight: 700; color: #222; margin-bottom: 6px; }
  .scr-meta { text-align: center; color: #666; font-size: 14px; margin-bottom: 8px; }
  .scr-badge { display: inline-block; background: #f4f6f9; padding: 6px 10px; border-radius: 6px; font-size: 12px; color: #333; }
  .scr-actions { display: flex; justify-content: center; margin-top: 10px; }
  .scr-btn { border-radius: 8px; font-weight: 600; padding: 10px 20px; border: none; cursor: pointer; transition: all 0.2s ease; }
  .scr-btn-primary { background-color: #0d6efd; color: white; }
  .scr-btn-primary:hover { background-color: #0b5ed7; }
  @media(max-width: 768px){ .scr-card{ width: 100%; } .scr-container{ margin-top: 140px; } }
</style>

<div class="scr-container">
  <h1 class="scr-page-title">Most Booked Colleges</h1>
  <p class="scr-subtitle">Ranked by how many course bookings each college has received.</p>

  <div class="scr-grid">
    @forelse($items as $index => $item)
      @php $college = $item['college']; $count = $item['bookings']; @endphp
      <div class="scr-card">
        <div class="scr-rank">#{{ $index + 1 }}</div>
        <img class="scr-logo" src="{{ isset($college->logo) ? asset('storage/' . $college->logo) : asset('img/landing.jpg') }}" alt="Logo" />
        <div class="scr-name">{{ $college->name }}</div>
        <div class="scr-meta">{{ $college->address }}</div>
        <div class="text-center"><span class="scr-badge">Bookings: {{ $count }}</span></div>
        <div class="scr-actions">
          <a class="scr-btn scr-btn-primary" href="/college/detail/{{ $college->id }}">View</a>
        </div>
      </div>
    @empty
      <div>No colleges available.</div>
    @endforelse
  </div>
</div>
@endsection