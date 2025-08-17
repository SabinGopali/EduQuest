@extends('layouts.app')

@section('content')
<style>
  .km-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
  .km-title { text-align: center; font-size: 2rem; font-weight: 800; color: #222; margin-bottom: 8px; }
  .km-subtitle { text-align: center; color: #555; margin-bottom: 24px; }
  .km-controls { max-width: 520px; margin: 0 auto 24px; display: flex; gap: 10px; align-items: center; }
  .km-controls input { flex: 1; border-radius: 8px; border: 1px solid #ced4da; padding: 10px 14px; }
  .km-btn { border-radius: 8px; font-weight: 600; padding: 10px 20px; border: none; cursor: pointer; transition: all 0.2s ease; background-color: #0d6efd; color: white; }
  .km-btn:hover { background-color: #0b5ed7; }

  #kmap { height: 420px; width: 100%; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); margin: 20px 0 28px; }

  .km-grid { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
  .km-card { width: 320px; background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 16px; box-shadow: 0 4px 10px rgb(0 0 0 / 0.06); }
  .km-card h3 { display: flex; align-items: center; gap: 10px; font-size: 1.1rem; margin: 0 0 10px; }
  .km-pill { display:inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; color: #fff; }
  .km-logo { height: 80px; width: 80px; object-fit: contain; border-radius: 50%; border: 2px solid #0d6efd; background: #fff; padding: 5px; display:block; margin: 6px auto 12px; }
  .km-name { text-align: center; font-weight: 700; color: #222; margin-bottom: 6px; }
  .km-meta { text-align: center; color: #666; font-size: 14px; margin-bottom: 10px; }
  .km-actions { display: flex; justify-content: center; }

  .km-explain { max-width: 940px; margin: 36px auto 0; background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 18px; box-shadow: 0 4px 10px rgb(0 0 0 / 0.06); }
  .km-explain h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 6px; }
  .km-explain ul { padding-left: 20px; margin: 0; }
  .km-explain li { margin: 4px 0; color: #444; }

  @media(max-width: 768px){ .km-controls{ flex-direction: column; } .km-card{ width: 100%; } }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<div class="km-container">
  <h1 class="km-title">K‑Means Clustering of Colleges</h1>
  <p class="km-subtitle">Group colleges by location. Choose the number of clusters and explore them on the map and list.</p>

  <form method="GET" class="km-controls" action="{{ route('algorithm.kmeans') }}">
    <input type="number" min="1" max="20" step="1" name="k" value="{{ $requestedK }}" />
    <button class="km-btn" type="submit">Cluster</button>
  </form>

  <div class="km-summary" style="text-align:center; color:#555; margin-bottom: 6px;">
    <span>Total colleges used: <strong>{{ $totalColleges }}</strong></span>
    <span style="margin: 0 10px;">|</span>
    <span>k: <strong>{{ $k }}</strong></span>
    <span style="margin: 0 10px;">|</span>
    <span>Iterations: <strong>{{ $iterations }}</strong></span>
    <span style="margin: 0 10px;">|</span>
    <span>SSE: <strong>{{ number_format($sse, 6) }}</strong></span>
  </div>

  <div id="kmap"></div>

  <div class="km-grid">
    @forelse($clusters as $idx => $cluster)
      @php $color = ['#1f77b4','#ff7f0e','#2ca02c','#d62728','#9467bd','#8c564b','#e377c2','#7f7f7f','#bcbd22','#17becf'][$idx % 10]; @endphp
      <div class="km-card">
        <h3>
          <span class="km-pill" style="background: {{ $color }};">Cluster {{ $idx + 1 }}</span>
          <span style="color:#666; font-weight: 500;">({{ count($cluster['colleges']) }} colleges)</span>
        </h3>
        @if($cluster['centroid']['lat'] !== null)
          <div style="font-size:12px; color:#666; margin-bottom: 10px;">Centroid: {{ number_format($cluster['centroid']['lat'], 5) }}, {{ number_format($cluster['centroid']['lon'], 5) }}</div>
        @endif
        <div>
          @forelse($cluster['colleges'] as $c)
            <img class="km-logo" src="{{ isset($c->logo) ? asset('storage/' . $c->logo) : asset('img/landing.jpg') }}" alt="Logo" />
            <div class="km-name">{{ $c->name }}</div>
            <div class="km-meta">{{ $c->address }}</div>
            <div class="km-actions"><a class="km-btn" href="/college/detail/{{ $c->id }}">View</a></div>
            @if(!$loop->last)
            <hr style="margin: 14px 0; border: none; border-top: 1px solid #eee;" />
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

  <div class="km-explain">
    <h3>How K‑Means works (on this page)</h3>
    <ul>
      <li><strong>Initialize</strong>: Start with k random centroids picked from college locations.</li>
      <li><strong>Assign</strong>: Each college is assigned to the nearest centroid by Euclidean distance on (latitude, longitude).</li>
      <li><strong>Update</strong>: For each cluster, recompute the centroid as the average of its members' coordinates.</li>
      <li><strong>Iterate</strong>: Repeat assign and update until assignments stabilize or a max iteration count is reached.</li>
      <li><strong>What you see</strong>: Colored clusters on the map, their centroids, and grouped college lists. SSE (sum of squared errors) indicates compactness (lower is tighter clusters).</li>
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
    map = L.map('kmap').setView(defaultCenter, 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' }).addTo(map);

    const bounds = L.latLngBounds();

    clusters.forEach((cluster, idx) => {
      const color = colors[idx % colors.length];

      if (cluster.centroid && cluster.centroid.lat !== null && cluster.centroid.lon !== null) {
        const clat = parseFloat(cluster.centroid.lat);
        const clon = parseFloat(cluster.centroid.lon);
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
      });
    });

    if (bounds.isValid()) {
      map.fitBounds(bounds.pad(0.1));
    }
  }

  document.addEventListener('DOMContentLoaded', initMap);
</script>
@endsection