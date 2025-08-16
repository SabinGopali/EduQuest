@extends('layouts.app')

@section('content')
<style>
    body { background-color: #f7f9fc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
    h3 { font-weight: 600; color: #333; margin-bottom: 20px; text-align: center; }
    #addressInput { border-radius: 8px; border: 1px solid #ced4da; padding: 10px 15px; width: 100%; transition: border-color 0.3s ease, box-shadow 0.3s ease; }
    #addressInput:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25); outline: none; }
    .alert-location { background: #fff3cd; border: 1px solid #ffeeba; color: #856404; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
    .btn { border-radius: 8px; font-weight: 500; padding: 10px 20px; border: none; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 3px 8px rgba(0,0,0,0.1); }
    .btn-primary { background-color: #0d6efd; color: #fff; }
    .btn-primary:hover { background-color: #0b5ed7; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(13,110,253,0.3); }
    .form-group label { font-weight: 500; color: #555; }
    .show { display: none; margin-top: 30px; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
    #map { height: 400px; width: 100%; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-top: 30px; }
    .searchresult { margin-top: 40px; padding: 20px; background: #fff; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
    .course_boxes { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
    .course_box { flex: 1 1 calc(33.333% - 20px); min-width: 250px; transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .course_box:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.12); }
    .course_box .card { border-radius: 12px; border: none; height: 360px; display: flex; flex-direction: column; justify-content: space-between; padding: 15px; text-align: center; background: #fff; }
    .course_box img { height: 100px; width: 100px; object-fit: contain; margin: 0 auto 10px; border-radius: 50%; border: 2px solid #0d6efd; background: #fff; padding: 5px; }
    .card-title a { font-size: 18px; font-weight: 600; color: #0d6efd; text-decoration: none; }
    .card-title a:hover { text-decoration: underline; }
    .card-text { color: #555; margin-top: 5px; font-size: 14px; }
    .card button { border-radius: 8px; font-weight: 500; transition: all 0.3s ease; padding: 8px 20px; margin-top: 10px; box-shadow: 0 3px 8px rgba(0,0,0,0.1); }
    .card button:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(13,110,253,0.3); }
    @media(max-width: 992px) { .course_box { flex: 1 1 calc(50% - 20px); } }
    @media(max-width: 600px) { .course_box { flex: 1 1 100%; } }
</style>

<div class="container">
    <div id="autoLocateStatus" class="mt-2"></div>
    <div id="locationAlert" class="alert-location" style="display:none;">
        Enable location access in your browser to see nearby colleges.
    </div>

    <div class="a" id="manualBlock">
        <h3>Search your location or use your current location</h3>
        <input type="text" class="form-control" id="addressInput" placeholder="Enter an address (e.g., city, street)">
        <button type="button" class="btn btn-primary text-center w-100 mt-3" id="geocodeButton">Search</button>
        <div id="coordinates" class="mt-2"></div>
    </div>

    <div class="show">
        <form action="{{ route('algorithm.nearest') }}" method="GET" id="locationForm">
            @csrf
            <div class="form-group row mt-3">
                <label for="latitude" class="col-lg-3 col-form-label">Latitude</label>
                <div class="col-lg-9">
                    <input type="text" id="latitude" class="form-control" name="latitude" required placeholder="Enter Latitude">
                </div>
            </div>

            <div class="form-group row">
                <label for="longitude" class="col-lg-3 col-form-label">Longitude</label>
                <div class="col-lg-9">
                    <input type="text" id="longitude" class="form-control" name="longitude" required placeholder="Enter Longitude">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-12">
                    <input type="submit" id="updatelonlan" name="submit" value="Update value" class="btn btn-primary w-100">
                </div>
            </div>
        </form>
    </div>

    <div class="col-xl-6" style="display: none;">
        <div id="map"></div>
    </div>

    <div class="searchresult full-row border shadow-sm" style="{{ count($colleges) ? '' : 'display:none;' }}">
        <h3>Nearest Colleges</h3>
        <div class="row course_boxes">
            @foreach($colleges as $college)
            <div class="col-lg-4 course_box">
                <div class="card">
                    <img src="{{ asset('storage/' . $college->logo) }}" alt="College Logo">
                    <div class="card-title"><a>{{ $college->name }}</a></div>
                    <div class="card-text">{{ $college->address }}</div>
                    
                    @if(property_exists($college, 'distance'))
                        @php
                            $distanceMeters = (float) $college->distance;
                            $distanceFormatted = $distanceMeters >= 1000
                                ? number_format($distanceMeters / 1000, 2) . ' km'
                                : number_format($distanceMeters, 0) . ' m';
                        @endphp
                        <div class="card-text mt-2"><strong>{{ $distanceFormatted }}</strong></div>
                    @endif
                    
                    <div class="d-flex justify-content-center">
                        <a href="/college/detail/{{ $college->id }}">
                            <button class="btn btn-primary">View</button>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([27.708317, 85.320582], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker = L.marker([0,0], { draggable:true }).addTo(map);
    var current_lat = '', current_lon = '';
    var hasResults = {{ count($colleges) ? 'true' : 'false' }};
    var statusEl = document.getElementById('autoLocateStatus');
    var locationAlert = document.getElementById('locationAlert');

    if (hasResults && statusEl) statusEl.style.display = 'none';

    function updateMarkerPosition(latlng) {
        marker.setLatLng(latlng);
        current_lat = latlng.lat.toFixed(6);
        current_lon = latlng.lng.toFixed(6);
        document.getElementById('latitude').value = current_lat;
        document.getElementById('longitude').value = current_lon;
    }

    function submitLocation() { document.getElementById('updatelonlan').click(); }

    function handleGeoError(err) {
        if (statusEl) statusEl.style.display = 'none';
        if (locationAlert) locationAlert.style.display = 'block';
    }

    function requestLocation() {
        if (!navigator.geolocation) return handleGeoError();
        if (statusEl) { statusEl.textContent = 'Detecting your location…'; statusEl.style.display = 'block'; }

        navigator.geolocation.getCurrentPosition(function(pos){
            var latlng = L.latLng(pos.coords.latitude, pos.coords.longitude);
            map.setView(latlng, 13);
            updateMarkerPosition(latlng);
            if (statusEl) statusEl.style.display = 'none';
            if (locationAlert) locationAlert.style.display = 'none';
            submitLocation();
        }, function(err){ handleGeoError(err); }, { enableHighAccuracy: true, timeout: 20000, maximumAge: 10000 });
    }

    function initGeo() {
        if (navigator.permissions && navigator.permissions.query) {
            navigator.permissions.query({ name: 'geolocation' }).then(function(p){
                if (p.state === 'granted' || p.state === 'prompt') requestLocation();
                else handleGeoError();
            }).catch(function(){ requestLocation(); });
        } else { requestLocation(); }
    }

    var addressInput = document.getElementById('addressInput');
    var geocodeButton = document.getElementById('geocodeButton');

    geocodeButton.addEventListener('click', function(){
        if(addressInput.value) geocodeAddress(addressInput.value);
        else alert('Please enter an address.');
    });

    function geocodeAddress(address) {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
            .then(response => response.json())
            .then(data => {
                if(data.length > 0){
                    var latlng = L.latLng(parseFloat(data[0].lat), parseFloat(data[0].lon));
                    map.setView(latlng, 13);
                    updateMarkerPosition(latlng);
                    submitLocation();
                } else { alert('Address not found.'); }
            }).catch(err => console.error(err));
    }

    addressInput.addEventListener('keyup', function(e){ if(e.key==='Enter') geocodeButton.click(); });
    marker.on('dragend', function(event){ updateMarkerPosition(event.target.getLatLng()); submitLocation(); });

    if(!hasResults) initGeo();
</script>
@endsection
