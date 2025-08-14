@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f7f9fc;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
        max-width: 1100px;
        margin: 40px auto;
        padding: 0 20px;
    }

    h3 {
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
    }

    .search-section {
        background: #fff;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        margin-bottom: 40px;
        transition: all 0.3s ease;
    }

    .search-section input {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 10px 15px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .search-section input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    /* Button base style */
    .search-section button,
    .card button {
        border-radius: 8px;
        font-weight: 500;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Primary button */
    .btn-primary {
        background: #0d6efd;
        color: #fff;
    }

    .btn-primary:hover {
        background: #0b5ed7;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(13,110,253,0.3);
    }

    /* Success button */
    .btn-success {
        background: #28a745;
        color: #fff;
    }

    .btn-success:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(40,167,69,0.3);
    }

    /* Secondary button */
    .btn-secondary {
        background: #6c757d;
        color: #fff;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(108,117,125,0.3);
    }

    #coordinates {
        margin-top: 15px;
        font-weight: 500;
        color: #555;
    }

    .form-group label {
        font-weight: 500;
        color: #555;
    }

    #map {
        height: 400px;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .searchresult h3 {
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
    }

    .course_boxes {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .course_box {
        flex: 1 1 calc(33.333% - 20px);
        min-width: 250px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .course_box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .course_box .card {
        border-radius: 12px;
        border: none;
        height: 360px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 15px;
        text-align: center;
        background: #fff;
    }

    .course_box img {
        height: 100px;
        width: 100px;
        object-fit: contain;
        margin: 0 auto 10px;
        border-radius: 50%;
        border: 2px solid #0d6efd;
        background: #fff;
        padding: 5px;
    }

    .card-title a {
        font-size: 18px;
        font-weight: 600;
        color: #0d6efd;
        text-decoration: none;
    }

    .card-title a:hover {
        text-decoration: underline;
    }

    .card-text {
        color: #555;
        margin-top: 5px;
        font-size: 14px;
    }

    @media(max-width: 992px) {
        .course_box {
            flex: 1 1 calc(50% - 20px);
        }
    }

    @media(max-width: 600px) {
        .course_box {
            flex: 1 1 100%;
        }
    }
</style>

<div class="container">

    <div class="search-section">
        <h3>Search The Location And Get Longitude and Latitude</h3>
        <input type="text" class="form-control mb-3" id="addressInput" placeholder="Enter The Location">
        <button type="button" class="btn btn-primary w-100" id="geocodeButton">Search</button>
        <div id="coordinates"></div>

        <div class="show mt-4">
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
                        <input type="submit" id="updatelonlan" name="submit" value="Update value" class="btn btn-success w-100">
                    </div>
                </div>
            </form>
        </div>

        <button type="button" id="bb" class="btn btn-secondary w-100 btn-show-nearest">Show Nearest College</button>
    </div>

    <div class="mt-5" style="display: none;">
        <div id="map"></div>
    </div>

    <div class="searchresult full-row border shadow-sm mt-5" style="display:none; padding:20px; border-radius:12px; background:#fff;">
        <h3>Show Nearest College</h3>
        <div class="row course_boxes">
            @foreach($colleges as $college)
            <div class="col-lg-4 course_box">
                <div class="card">
                    <img src="{{ asset('storage/' . $college->logo) }}" alt="College Logo">
                    <div class="card-title"><a>{{$college->name}}</a></div>
                    <div class="card-text">{{$college->address}}</div>
                    @if(isset($college->distance))
                        <div class="card-text"><strong>Distance:</strong> {{ number_format($college->distance, 2) }} km</div>
                    @endif
                    <div class="d-flex justify-content-center mt-3">
                        <a href="/college/detail/{{$college->id}}">
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
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker = L.marker([0, 0], { draggable: true }).addTo(map);

    var current_lan = '';
    var current_lon = '';

    function updateMarkerPosition(latlng) {
        marker.setLatLng(latlng);
        current_lan = latlng.lat.toFixed(6);
        current_lon = latlng.lng.toFixed(6);
        document.getElementById('latitude').value = current_lan;
        document.getElementById('longitude').value = current_lon;
        document.getElementById('coordinates').innerHTML = 'Latitude: ' + current_lan + '   Longitude: ' + current_lon;
        document.getElementById('updatelonlan').click();
    }

    function geocodeAddress(address) {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    var result = data[0];
                    var lat = parseFloat(result.lat);
                    var lon = parseFloat(result.lon);
                    var latlng = L.latLng(lat, lon);
                    map.setView(latlng, 13);
                    updateMarkerPosition(latlng);
                } else {
                    alert('Address not found.');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    var addressInput = document.getElementById('addressInput');
    var geocodeButton = document.getElementById('geocodeButton');

    geocodeButton.addEventListener('click', function () {
        var address = addressInput.value;
        if (address) geocodeAddress(address);
        else alert('Please enter an address.');
    });

    marker.on('drag', function (event) {
        updateMarkerPosition(event.target.getLatLng());
    });

    var bbButton = document.getElementById('bb');
    bbButton.addEventListener('click', function () {
        var searchResultDiv = document.querySelector('.searchresult');
        searchResultDiv.style.display = 'block';
    });

    (function() {
        var hasColleges = {{ $colleges->count() > 0 ? 'true' : 'false' }};
        if (hasColleges) {
            var searchResultDiv = document.querySelector('.searchresult');
            searchResultDiv.style.display = 'block';
        }
    })();
</script>
@endsection
