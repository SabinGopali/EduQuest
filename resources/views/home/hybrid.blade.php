@extends('layouts.app')

@section('content')
<style>
  .container { max-width: 1200px; margin: 200px auto 60px; padding: 0 20px; }
  .page-title { text-align: center; font-size: 2.2rem; font-weight: 800; color: #222; margin-bottom: 14px; }
  .subtitle { text-align: center; color: #555; margin-bottom: 30px; }
  .controls { max-width: 800px; margin: 0 auto 24px; display: flex; gap: 10px; }
  .controls input { flex: 1; border-radius: 8px; border: 1px solid #ced4da; padding: 10px 14px; }
  .btn { border-radius: 8px; font-weight: 600; padding: 10px 20px; border: none; cursor: pointer; transition: all 0.2s ease; }
  .btn-primary { background-color: #0d6efd; color: white; }
  .btn-primary:hover { background-color: #0b5ed7; }

  .grid { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
  .card { width: 320px; background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 16px; box-shadow: 0 4px 10px rgb(0 0 0 / 0.08); }
  .logo { height: 80px; width: 80px; object-fit: contain; border-radius: 50%; border: 2px solid #0d6efd; background: #fff; padding: 5px; display:block; margin: 6px auto 12px; }
  .name { text-align: center; font-weight: 700; color: #222; margin-bottom: 6px; }
  .meta { text-align: center; color: #666; font-size: 14px; margin-bottom: 10px; }
  .scores { display: flex; justify-content: center; gap: 10px; font-size: 12px; color: #333; margin-bottom: 10px; }
  .scores span { background: #f4f6f9; padding: 6px 10px; border-radius: 6px; }
  .actions { display: flex; justify-content: center; }

  .explain { max-width: 900px; margin: 44px auto 0; background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 18px; box-shadow: 0 4px 10px rgb(0 0 0 / 0.06); }
  .explain h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 6px; }
  .explain ul { padding-left: 20px; margin: 0; }
  .explain li { margin: 4px 0; color: #444; }

  @media(max-width: 768px){ .controls{ flex-direction: column; } .card{ width: 100%; } .container{ margin-top: 140px; }}
</style>

<div class="container">
  <h1 class="page-title">Smart College Recommendations</h1>
  <p class="subtitle">Hybrid ranking that blends content relevance, popularity and proximity.</p>

  <form class="controls" method="GET">
    <input id="addressInput" type="text" name="q" placeholder="Optional: enter your city/street to improve proximity scoring" value="" />
    <input type="hidden" id="latitude" name="latitude" />
    <input type="hidden" id="longitude" name="longitude" />
    <button class="btn btn-primary" type="submit">Refresh</button>
  </form>

  <div class="grid">
    @forelse($recommendations as $item)
      <div class="card">
        <img class="logo" src="{{ isset($item['college']->logo) ? asset('storage/' . $item['college']->logo) : asset('img/landing.jpg') }}" alt="Logo" />
        <div class="name">{{ $item['college']->name }}</div>
        <div class="meta">{{ $item['college']->address }}</div>
        <div class="scores">
          <span>Score: {{ number_format($item['score'] * 100, 0) }}%</span>
          <span>Content: {{ number_format($item['content'] * 100, 0) }}%</span>
          <span>Popularity: {{ number_format($item['popularity'] * 100, 0) }}%</span>
          @if($hasLocation)
            <span>Proximity: {{ number_format($item['proximity'] * 100, 0) }}%</span>
          @endif
        </div>
        <div class="actions">
          <a class="btn btn-primary" href="/college/detail/{{ $item['college']->id }}">View</a>
        </div>
      </div>
    @empty
      <div>No colleges available.</div>
    @endforelse
  </div>

  <div class="explain">
    <h3>How this hybrid algorithm works</h3>
    <ul>
      <li><strong>Content relevance</strong>: We tokenize your interest and goal and compare them with each college's combined course descriptions using TF-IDF and cosine similarity.</li>
      <li><strong>Popularity</strong>: We normalize the number of inquiries each college has received to reflect student interest.</li>
      <li><strong>Proximity</strong>: If you provide a location, we compute distance with the Haversine formula and reward closer colleges.</li>
      <li><strong>Final score</strong>: A weighted sum (content 50%, popularity 30%, proximity 20% when available) ranks colleges.</li>
    </ul>
  </div>
</div>

<script>
  const addressInput = document.getElementById('addressInput');
  const latEl = document.getElementById('latitude');
  const lonEl = document.getElementById('longitude');

  function geocodeAddress(address) {
    if (!address) return;
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
      .then(r => r.json())
      .then(data => {
        if (data && data.length > 0) {
          latEl.value = parseFloat(data[0].lat).toFixed(6);
          lonEl.value = parseFloat(data[0].lon).toFixed(6);
          // Allow form submit with updated lat/lon
        }
      })
      .catch(() => {});
  }

  // If the user types an address and presses Enter, we'll geocode before submit
  const form = document.querySelector('form.controls');
  form.addEventListener('submit', (e) => {
    if (addressInput.value && !latEl.value && !lonEl.value) {
      e.preventDefault();
      geocodeAddress(addressInput.value);
      setTimeout(() => form.submit(), 450);
    }
  });
</script>
@endsection