@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 1050px;">
    <h2 class="mt-4 mb-3">Multi‑Criteria College Ranking (TOPSIS)</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p class="mb-2"><strong>What this is:</strong> A powerful multi‑criteria decision‑making method that ranks colleges based on several factors you choose.</p>
            <p class="mb-2"><strong>How TOPSIS works (in brief):</strong></p>
            <ul class="mb-0">
                <li>Build a decision matrix of alternatives (colleges) × criteria (e.g., popularity, variety, eligibility, distance).</li>
                <li>Normalize each criterion to remove unit differences and apply your weights.</li>
                <li>Define an ideal best point (maximize benefits, minimize costs) and an ideal worst point.</li>
                <li>Rank colleges by how close they are to the ideal best and far from the ideal worst (closeness coefficient in [0,1]).</li>
            </ul>
        </div>
    </div>

    <form method="POST" action="{{ route('algorithm.topsis.rank') }}" id="topsis-form">
        @csrf
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Popularity weight</label>
                <input type="number" step="0.01" min="0" class="form-control" name="w_popularity" value="{{ old('w_popularity', $weights['w_popularity'] ?? 1) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Variety weight</label>
                <input type="number" step="0.01" min="0" class="form-control" name="w_variety" value="{{ old('w_variety', $weights['w_variety'] ?? 1) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Eligibility weight</label>
                <input type="number" step="0.01" min="0" class="form-control" name="w_eligibility" value="{{ old('w_eligibility', $weights['w_eligibility'] ?? 1) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Distance weight</label>
                <input type="number" step="0.01" min="0" class="form-control" name="w_distance" value="{{ old('w_distance', $weights['w_distance'] ?? 1) }}">
            </div>
        </div>

        <div class="row g-3 mt-3">
            <div class="col-md-6">
                <label class="form-label">Use my location (optional)</label>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-primary" id="detect-location">Detect Location</button>
                    <small class="text-muted" id="location-status">@if($latitude && $longitude) Using location: {{$latitude}}, {{$longitude}} @else Not set @endif</small>
                </div>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $latitude) }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $longitude) }}">
            </div>
            <div class="col-md-6 d-flex align-items-end justify-content-end">
                <button type="submit" class="btn btn-dark">Rank Colleges</button>
            </div>
        </div>
    </form>

    @error('w_popularity')<div class="text-danger mt-2">{{ $message }}</div>@enderror
    @error('w_variety')<div class="text-danger mt-2">{{ $message }}</div>@enderror
    @error('w_eligibility')<div class="text-danger mt-2">{{ $message }}</div>@enderror
    @error('w_distance')<div class="text-danger mt-2">{{ $message }}</div>@enderror

    @if($results)
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="mb-3">Ranked Colleges</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                        <tr>
                            <th>Rank</th>
                            <th>College</th>
                            <th>Score</th>
                            <th>Popularity</th>
                            <th>Variety</th>
                            <th>Eligibility</th>
                            <th>Distance (km)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $res)
                            <tr>
                                <td>#{{ $res['rank'] }}</td>
                                <td>{{ $res['college']->name }}</td>
                                <td>{{ number_format($res['score'] * 100, 2) }}%</td>
                                <td>{{ $res['values']['popularity'] }}</td>
                                <td>{{ $res['values']['variety'] }}</td>
                                <td>{{ number_format($res['values']['eligibility'] * 100, 0) }}%</td>
                                <td>
                                    @if(!is_null($res['values']['distance']))
                                        {{ number_format($res['values']['distance'], 1) }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <small class="text-muted">Note: Scores are closeness coefficients (higher is better), derived from your weights.</small>
            </div>
        </div>
    @endif
</div>

<script>
(function() {
    const btn = document.getElementById('detect-location');
    if (!btn) return;
    const latEl = document.getElementById('latitude');
    const lonEl = document.getElementById('longitude');
    const statusEl = document.getElementById('location-status');

    btn.addEventListener('click', function() {
        if (!navigator.geolocation) {
            statusEl.textContent = 'Geolocation is not supported by your browser.';
            return;
        }
        statusEl.textContent = 'Detecting...';
        navigator.geolocation.getCurrentPosition(function(pos) {
            const { latitude, longitude } = pos.coords;
            latEl.value = latitude;
            lonEl.value = longitude;
            statusEl.textContent = `Using location: ${latitude.toFixed(4)}, ${longitude.toFixed(4)}`;
        }, function(err) {
            statusEl.textContent = 'Failed to get location: ' + (err && err.message ? err.message : 'Unknown error');
        }, { enableHighAccuracy: true, timeout: 10000 });
    });
})();
</script>
@endsection