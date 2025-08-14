
@extends('layouts.app')

@section('content')
<style>
    #map {
			height: 400px;
			width: 500px;
		}
    .show{
        display: none;
    }
</style>
<div class="container" >
    <div class="a">
    <h3>
        Search your location or use your current location
    </h3>
        <input type="text" class="form-control" id="addressInput"
            placeholder="Enter an address (e.g., city, street)">
        <button type="button" class="btn btn-outline-primary text-center w-100 mt-3 " id="geocodeButton">Search</button>
            <br>
            <div id="coordinates"></div>
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
                <input type="submit" id="updatelonlan" name="submit" value="Update value" class="btn btn-primary">

            </div>
        </div>
    </form>
</div>

</div>
<div class="col-xl-6" style="display: none;">
    <div class="abc">
    <div id="map"></div>
    </div>

</div>
</div>
<div class="searchresult full-row border shadow-sm " style="{{ count($colleges) ? '' : 'display:none;' }}">
<h3>Nearest Colleges</h3>
<div class="row course_boxes">
    @foreach($colleges as $college)
    <div class="col-lg-4 course_box">
        <div class="card" style="height:360px; border: 1px solid black; border-radius:5px;">
            <div class="card-body text-center">
            <img src="{{ asset('storage/' . $college->logo) }}" alt="College Logo" style="object-fit: contain; height: 100px; width:100px; margin-top: 10px; border: 2px solid black;">
                <div class="card-title"><a>{{ $college->name }}</a></div>
                <div class="card-text">{{ $college->address }}</div>
                @if(property_exists($college, 'distance'))
                <div class="card-text mt-2"><strong>{{ number_format($college->distance, 2) }} km</strong></div>
                @endif
            </div>
            <br/>
            <div class="d-flex justify-content-center">
                <a href="/college/detail/{{ $college->id }}">
                    <button class="btn btn-primary">View</button>
                </a>
            </div>
            <br/>
        </div>
    </div>
    @endforeach

</div>
</div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

	<script>
		// Initialize the map
		var map = L.map('map').setView([27.708317, 85.320582], 13);
		// Default view at [0, 0] with zoom level 13

		// Add a tile layer (you can choose a different tile layer)
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);

		// Initialize a marker with a default location (0, 0)
		var marker = L.marker([0, 0], {
			draggable: true // Make the marker draggable
		}).addTo(map);

        var current_lan = '';
        var current_lon = '';
		// Function to update the marker's position and display coordinates
		function updateMarkerPosition(latlng) {
			marker.setLatLng(latlng);
            current_lan = latlng.lat.toFixed(6);
            current_lon = latlng.lng.toFixed(6);
            document.getElementById('latitude').value = current_lan;
            document.getElementById('longitude').value = current_lon;
			document.getElementById('coordinates').innerHTML = 'Latitude: ' + current_lan + '   Longitude: ' + current_lon;
            document.getElementById('updatelonlan').click();
		}

		// Function to geocode an address and update the map and marker
		function geocodeAddress(address) {
			fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
				.then(response => response.json())
				.then(data => {
					if (data && data.length > 0) {
						var result = data[0];
						var lat = parseFloat(result.lat);
						var lon = parseFloat(result.lon);
						var latlng = L.latLng(lat, lon);
						map.setView(latlng, 13); // Set the map view to the geocoded coordinates
						updateMarkerPosition(latlng); // Update the marker's position and display coordinates
					} else {
						alert('Address not found.');
					}
				})
				.catch(error => {
					console.error('Error:', error);
				});
		}

		// Get references to the input field and geocode button
		var addressInput = document.getElementById('addressInput');
		var geocodeButton = document.getElementById('geocodeButton');

		// Add a click event listener to the geocode button
		geocodeButton.addEventListener('click', function () {
			var address = addressInput.value;
			if (address) {
				geocodeAddress(address);
			} else {
				alert('Please enter an address.');
			}

		});

        // Allow pressing Enter to trigger search
        addressInput.addEventListener('keyup', function (e) {
            if (e.key === 'Enter') {
                geocodeButton.click();
            }
        });

		// Listen for marker drag events and update the displayed coordinates
		marker.on('drag', function (event) {
			updateMarkerPosition(event.target.getLatLng());
		});

        // Try to auto-detect current location and submit immediately
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (pos) {
                var latlng = L.latLng(pos.coords.latitude, pos.coords.longitude);
                map.setView(latlng, 13);
                updateMarkerPosition(latlng);
            });
        }

		</script>

@endsection
