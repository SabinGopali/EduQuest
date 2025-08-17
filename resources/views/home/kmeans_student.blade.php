@extends('layouts.app')

@section('content')
<style>
  .skm-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
  .skm-title { text-align: center; font-size: 2rem; font-weight: 800; color: #222; margin-bottom: 8px; }
  .skm-subtitle { text-align: center; color: #555; margin-bottom: 24px; }
  .skm-controls { max-width: 980px; margin: 0 auto 24px; display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px; align-items: end; }
  .skm-controls input[type="number"], .skm-controls input[type="text"] { border-radius: 8px; border: 1px solid #ced4da; padding: 10px 12px; }
  .skm-controls label { font-size: 12px; color: #666; display: block; margin-bottom: 6px; }
  .skm-btn { border-radius: 8px; font-weight: 600; padding: 10px 16px; border: none; cursor: pointer; transition: all 0.2s ease; background-color: #0d6efd; color: white; width: 100%; }
  .skm-btn:hover { background-color: #0b5ed7; }

  #skm-map { height: 420px; width: 100%; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); margin: 20px 0 28px; }

  .skm-grid { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
  .skm-card { width: 320px; background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 16px; box-shadow: 0 4px 10px rgb(0 0 0 / 0.06); }
  .skm-card h3 { display: flex; align-items: center; gap: 10px; font-size: 1.1rem; margin: 0 0 10px; }
  .skm-pill { display:inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; color: #fff; }
  .skm-logo { height: 70px; width: 70px; object-fit: contain; border-radius: 50%; border: 2px solid #0d6efd; background: #fff; padding: 5px; display:block; margin: 6px auto 10px; }
  .skm-name { text-align: center; font-weight: 700; color: #222; margin-bottom: 6px; }
  .skm-meta { text-align: center; color: #666; font-size: 13px; margin-bottom: 8px; }
  .skm-badges { display: flex; gap: 6px; justify-content: center; flex-wrap: wrap; margin-bottom: 8px; }
  .skm-badges span { background: #f4f6f9; padding: 4px 8px; border-radius: 6px; font-size: 12px; color: #333; }
  .skm-actions { display: flex; justify-content: center; }

  .skm-summary { text-align:center; color:#555; margin-bottom: 6px; }
  .skm-explain { max-width: 940px; margin: 36px auto 0; background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 18px; box-shadow: 0 4px 10px rgb(0 0 0 / 0.06); }
  .skm-explain h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 6px; }
  .skm-explain ul { padding-left: 20px; margin: 0; }
  .skm-explain li { margin: 4px 0; color: #444; }

  @media(max-width: 1024px){ .skm-controls{ grid-template-columns: repeat(3, 1fr); } }
  @media(max-width: 640px){ .skm-controls{ grid-template-columns: 1fr; } .skm-card{ width: 100%; } }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<div class="skm-container">
  <h1 class="skm-title">Personalized K‑Means Clustering</h1>
  <p class="skm-subtitle">Group colleges by how they match your profile: content relevance, eligibility, popularity, and proximity.</p>

  <form method="GET" class="skm-controls" action="{{ route('algorithm.kmeans.student') }}">
    <div>
      <label for="k">Clusters (k)</label>
      <input id="k" type="number" min="1" max="20" step="1" name="k" value="{{ $requestedK }}" />
    </div>

    <div>
      <label for="w_content">Weight: Content</label>
      <input id="w_content" type="number" min="0" step="0.1" name="w_content" value="{{ $weights['content'] }}" />
    </div>
    <div>
      <label for="w_eligibility">Weight: Eligibility</label>
      <input id="w_eligibility" type="number" min="0" step="0.1" name="w_eligibility" value="{{ $weights['eligibility'] }}" />
    </div>
    <div>
      <label for="w_popularity">Weight: Popularity</label>
      <input id="w_popularity" type="number" min="0" step="0.1" name="w_popularity" value="{{ $weights['popularity'] }}" />
    </div>
    <div>
      <label for="addressInput">Location (optional)</label>
      <input id="addressInput" type="text" name="q" placeholder="City/Street" value="" />
      <input type="hidden" id="latitude" name="latitude" value="{{ $latitude }}" />
      <input type="hidden" id="longitude" name="longitude" value="{{ $longitude }}" />
    </div>

    <div>
      <button class="skm-btn" type="submit">Cluster</button>
    </div>
  </form>

  <div class="skm-summary">
    <span>Total colleges used: <strong>{{ $totalColleges }}</strong></span>
    <span style="margin: 0 10px;">|</span>
    <span>k: <strong>{{ $k }}</strong></span>
    <span style="margin: 0 10px;">|</span>
    <span>Iterations: <strong>{{ $iterations }}</strong></span>
    <span style="margin: 0 10px;">|</span>
    <span>SSE: <strong>{{ number_format($sse, 6) }}</strong></span>
  </div>

  <div id="skm-map"></div>

  <div class="skm-grid">
    @forelse($clusters as $idx => $cluster)
      @php $color = ['#1f77b4','#ff7f0e','#2ca02c','#d62728','#9467bd','#8c564b','#e377c2','#7f7f7f','#bcbd22','#17becf'][$idx % 10]; @endphp
      <div class="skm-card">
        <h3>
          <span class="skm-pill" style="background: {{ $color }};">Cluster {{ $idx + 1 }}</span>
          <span style="color:#666; font-weight: 500;">({{ count($cluster['colleges']) }} colleges)</span>
        </h3>
        @if(!empty($cluster['centroidFeatures']))
          <div style="font-size:12px; color:#666; margin-bottom: 10px;">
            Centroid features:
            C={{ number_format($cluster['centroidFeatures'][0] ?? 0, 3) }},
            E={{ number_format($cluster['centroidFeatures'][1] ?? 0, 3) }},
            P={{ number_format($cluster['centroidFeatures'][2] ?? 0, 3) }},
            X={{ number_format($cluster['centroidFeatures'][3] ?? 0, 3) }}
          </div>
        @endif
        <div>
          @forelse($cluster['colleges'] as $c)
            <img class="skm-logo" src="{{ isset($c->logo) ? asset('storage/' . $c->logo) : asset('img/landing.jpg') }}" alt="Logo" />
            <div class="skm-name">{{ $c->name }}</div>
            <div class="skm-meta">{{ $c->address }}</div>
            <div class="skm-badges">
              <span>Content: {{ number_format($c->content * 100, 0) }}%</span>
              <span>Eligibility: {{ number_format($c->eligibility * 100, 0) }}%</span>
              <span>Popularity: {{ number_format($c->popularity * 100, 0) }}%</span>
              <span>Proximity: {{ number_format($c->proximity * 100, 0) }}%</span>
            </div>
            <div class="skm-actions"><a class="skm-btn" style="background:#0d6efd; color:white; width:auto;" href="/college/detail/{{ $c->id }}">View</a></div>
            @if(!$loop->last)
            <hr style="margin: 12px 0; border: none; border-top: 1px solid #eee;" />
            @endif
          @empty
            <div style="color:#777;">No colleges in this cluster.</div>
          @endforelse
        </div>
      </div>
    @empty
      <div>No colleges available.</div>
    @endforelse
  </div>

  <div class="skm-explain">
    <h3>What this does for you</h3>
    <ul>
      <li><strong>Content</strong>: Matches your interests and goals to each college's courses.</li>
      <li><strong>Eligibility</strong>: Estimates how many offered courses you likely qualify for (by GPA).</li>
      <li><strong>Popularity</strong>: Uses inquiry counts as a proxy for demand.</li>
      <li><strong>Proximity</strong>: If you provide a location, closer colleges score higher.</li>
      <li><strong>Clustering</strong>: Groups similar colleges so you can compare within clusters quickly.</li>
    </ul>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
  const clusters = @json($clusters);
  const colors = ['#1f77b4','#ff7f0e','#2ca02c','#d62728','#9467bd','#8c564b','#e377c2','#7f7f7f','#bcbd22','#17becf'];

  let map;
  function initMap() {
    const defaultCenter = [27.708317, 85.320582];
    map = L.map('skm-map').setView(defaultCenter, 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' }).addTo(map);

    const bounds = L.latLngBounds();

    clusters.forEach((cluster, idx) => {
      const color = colors[idx % colors.length];

      if (cluster.centroidGeo && cluster.centroidGeo.lat !== null && cluster.centroidGeo.lon !== null) {
        const clat = parseFloat(cluster.centroidGeo.lat);
        const clon = parseFloat(cluster.centroidGeo.lon);
        const centroidMarker = L.circleMarker([clat, clon], {
          radius: 8,
          color: '#000',
          weight: 1,
          fillColor: color,
          fillOpacity: 0.9
        }).addTo(map);
        centroidMarker.bindTooltip(`Centroid C${idx + 1}`);
        bounds.extend([clat, clon]);
      }

      cluster.colleges.forEach(c => {
        if (c.latitude !== null && c.longitude !== null) {
          const lat = parseFloat(c.latitude);
          const lon = parseFloat(c.longitude);
          const marker = L.circleMarker([lat, lon], {
            radius: 6,
            color: color,
            weight: 1,
            fillColor: color,
            fillOpacity: 0.6
          }).addTo(map);
          marker.bindTooltip(`${c.name}`);
          bounds.extend([lat, lon]);
        }
      });
    });

    if (bounds.isValid()) {
      map.fitBounds(bounds.pad(0.1));
    }
  }

  function geocodeAddress(address) {
    if (!address) return;
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
      .then(r => r.json())
      .then(data => {
        if (data && data.length > 0) {
          document.getElementById('latitude').value = parseFloat(data[0].lat).toFixed(6);
          document.getElementById('longitude').value = parseFloat(data[0].lon).toFixed(6);
        }
      })
      .catch(() => {});
  }

  const form = document.querySelector('form.skm-controls');
  const addressInput = document.getElementById('addressInput');
  form.addEventListener('submit', (e) => {
    const lat = document.getElementById('latitude').value;
    const lon = document.getElementById('longitude').value;
    if (addressInput.value && (!lat || !lon)) {
      e.preventDefault();
      geocodeAddress(addressInput.value);
      setTimeout(() => form.submit(), 450);
    }
  });

  document.addEventListener('DOMContentLoaded', initMap);
</script>
@endsection