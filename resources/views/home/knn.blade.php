@extends('layouts.app')

@section('content')
<style>
  .knn-container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
  .knn-title { font-weight: 700; color: #222; text-align:center; margin-bottom: 8px; }
  .knn-sub { text-align:center; color:#666; margin-bottom: 18px; }
  .knn-controls { display:flex; gap:10px; align-items:center; max-width: 800px; margin: 0 auto 20px; }
  .knn-controls input[type="text"], .knn-controls input[type="number"] { flex:1; border-radius:8px; border:1px solid #ced4da; padding:10px 14px; }
  .knn-btn { border-radius:8px; font-weight:600; padding:10px 20px; border:none; cursor:pointer; transition: all 0.2s ease; background:#0d6efd; color:white; }
  .knn-btn:hover { background:#0b5ed7; }
  .knn-alert { background:#fff3cd; border:1px solid #ffeeba; color:#856404; padding: 12px 16px; border-radius:8px; text-align:center; margin-bottom: 16px; }

  .knn-grid { display:flex; flex-wrap:wrap; gap:20px; justify-content:center; margin-top: 16px; }
  .knn-card { width: 320px; background:#fff; border:1px solid #eee; border-radius:12px; padding:16px; box-shadow:0 4px 12px rgba(0,0,0,0.06); text-align:center; transition: transform .2s ease, box-shadow .2s ease; }
  .knn-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.10); }
  .knn-logo { height:90px; width:90px; object-fit:contain; margin: 6px auto 12px; border-radius:50%; border:2px solid #0d6efd; background:#fff; padding:6px; display:block; }
  .knn-name { font-weight:700; color:#222; }
  .knn-meta { color:#555; font-size:14px; margin:4px 0; }
  .knn-distance { font-weight:700; color:#333; margin-top: 6px; }
  .knn-actions a { display:inline-block; margin-top: 10px; }

  @media(max-width:768px){ .knn-controls{ flex-direction:column; } .knn-card{ width:100%; } }
</style>

<div class="knn-container">
  <h2 class="knn-title">K-Nearest Colleges</h2>
  <div class="knn-sub">Find the top K closest colleges from your location using geospatial KNN.</div>

  <div id="locationAlert" class="knn-alert" style="display:none;">
    Enable location access in your browser or search your address.
  </div>

  <form id="knnForm" class="knn-controls" action="{{ route('algorithm.knn.find') }}" method="GET">
    <input id="addressInput" type="text" placeholder="Enter an address (e.g., city, street)" />
    <input id="kInput" name="k" type="number" min="1" max="50" value="{{ (int)($k ?? 5) }}" />
    <input type="hidden" id="latitude" name="latitude" required />
    <input type="hidden" id="longitude" name="longitude" required />
    <button class="knn-btn" type="submit">Find</button>
  </form>

  <div class="knn-grid" style="{{ count($colleges) ? '' : 'display:none;' }}">
    @foreach($colleges as $college)
      <div class="knn-card">
        <img class="knn-logo" src="{{ !empty($college->logo) ? asset('storage/' . $college->logo) : asset('img/landing.jpg') }}" alt="Logo" />
        <div class="knn-name">{{ $college->name }}</div>
        <div class="knn-meta">{{ $college->address }}</div>
        @if(property_exists($college, 'distance'))
          @php
            $m = (int) $college->distance;
            $dist = $m >= 1000 ? number_format($m/1000, 2) . ' km' : number_format($m, 0) . ' m';
          @endphp
          <div class="knn-distance">{{ $dist }}</div>
        @endif
        <div class="knn-actions">
          <a class="knn-btn" href="/college/detail/{{ $college->id }}">View</a>
        </div>
      </div>
    @endforeach
  </div>
</div>

<script>
  const addressInput = document.getElementById('addressInput');
  const kInput = document.getElementById('kInput');
  const latEl = document.getElementById('latitude');
  const lonEl = document.getElementById('longitude');
  const locationAlert = document.getElementById('locationAlert');
  const form = document.getElementById('knnForm');

  function setCoords(lat, lon){
    latEl.value = (+lat).toFixed(6);
    lonEl.value = (+lon).toFixed(6);
  }

  function geocodeAddress(address){
    if (!address) return Promise.resolve();
    return fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
      .then(r => r.json())
      .then(data => {
        if (data && data.length > 0) {
          setCoords(parseFloat(data[0].lat), parseFloat(data[0].lon));
        }
      })
      .catch(() => {});
  }

  function handleGeoError(){
    if (locationAlert) locationAlert.style.display = 'block';
  }

  function requestLocation(){
    if (!navigator.geolocation) return handleGeoError();
    navigator.geolocation.getCurrentPosition(function(pos){
      setCoords(pos.coords.latitude, pos.coords.longitude);
    }, function(){ handleGeoError(); }, { enableHighAccuracy: true, timeout: 20000, maximumAge: 10000 });
  }

  // Try to auto-fill coords on load
  if (!latEl.value || !lonEl.value) {
    requestLocation();
  }

  form.addEventListener('submit', async (e) => {
    if ((!latEl.value || !lonEl.value) && addressInput.value) {
      e.preventDefault();
      await geocodeAddress(addressInput.value);
      setTimeout(() => form.submit(), 300);
    }
  });
</script>
@endsection