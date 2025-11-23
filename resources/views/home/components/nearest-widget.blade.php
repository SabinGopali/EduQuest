@php
    $nearestColleges = $nearestColleges instanceof \Illuminate\Support\Collection
        ? $nearestColleges
        : collect($nearestColleges ?? []);

    $shouldAutoLocate = (bool) ($shouldAutoLocate ?? false);
    $locationSubmitRoute = $locationSubmitRoute ?? route('algorithm.nearest');
@endphp

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<style>
    .nearest-container { max-width: 1440px; margin: 40px auto; padding: 0 24px; }
    h3 { font-weight: 600; color: #333; margin-bottom: 20px; text-align: center; }
    #addressInput { border-radius: 8px; border: 1px solid #ced4da; padding: 10px 15px; width: 100%; transition: border-color 0.3s ease, box-shadow 0.3s ease; }
    #addressInput:focus { border-color: black; box-shadow: 0 0 0 0.2rem outline: none; }
    .alert-location { background: #fff3cd; border: 1px solid #ffeeba; color: #856404; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
    .scr-btn { border-radius: 8px; font-weight: 600; padding: 10px 20px; border: none; cursor: pointer; transition: all 0.2s ease; text-decoration: none; display: inline-block; }
    .scr-btn-primary { background-color: white; color: black; border: 1px solid black; }
    .scr-btn-primary:hover { background-color: black; color: white; }
    .nearest-search-btn {
        width: 1140px;
        margin-top: 16px;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid black;
        color: black;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .nearest-search-btn:hover {
        background: black;
        color: white;
    }
    .nearest-search-btn:focus {
        outline: none;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.25);
    }
    .form-group label { font-weight: 500; color: #555; }
    .searchresult { margin-top: 40px; padding: 20px; background: #fff; }
    .course_boxes { display: flex; flex-wrap: wrap; gap: 30px; justify-content: center; }
    .course_box { flex: 1 1 300px; max-width: 320px; transition: box-shadow 0.3s ease; }
    .course_box:hover .card { box-shadow: 0 8px 20px rgb(0 0 0 / 0.15); }
    .course_box .card { border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 4px 10px rgb(0 0 0 / 0.1); min-height: 250px; display: flex; flex-direction: column; justify-content: space-between; padding: 20px; background-color: #fff; text-align: center; transition: box-shadow 0.3s ease; }
    .course_box img { height: 80px; width: 80px; object-fit: contain; margin: 0 auto 12px; border-radius: 8px; }
    .card-title { margin: 0; }
    .card-title a { font-size: 1.3rem; font-weight: 700; color: #222; text-decoration: none; }
    .card-text { font-size: 14px; color: #666; margin: 8px 0 16px 0; min-height: 40px; font-weight: 500; }
    .button-wrapper { margin-top: auto; }
    .scr-btn { background-color: white; border: 2px solid black; color: black; padding: 10px 24px; font-size: 1rem; font-weight: 600; border-radius: 6px; cursor: pointer; transition: background-color 0.3s ease; text-decoration: none; display: inline-block; }
    .scr-btn:hover, .scr-btn:focus { background-color: black; color: white; }
    @media(max-width: 992px) { .course_box { flex: 1 1 calc(50% - 20px); } }
    @media(max-width: 600px) { .course_box { flex: 1 1 100%; max-width: 100%; } }
    .map-modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,0.45); display: none; align-items: center; justify-content: center; z-index: 1050; padding: 20px; }
    .map-modal { background: #fff; border-radius: 12px; width: 100%; max-width: 900px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); overflow: hidden; }
    .map-modal-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-bottom: 1px solid #eee; }
    .map-modal-title { font-weight: 600; color: #333; }
    .map-modal-close { background: transparent; border: none; font-size: 22px; line-height: 1; cursor: pointer; color: #555; }
    .map-modal-body { padding: 0; }
    #modalMap { height: 480px; width: 100%; }
    .user-marker-dot {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #ff3b30;
        border: 2px solid #ffffff;
        box-shadow: 0 0 0 2px #ff3b30;
    }
</style>

<div class="nearest-container">
    <div id="autoLocateStatus" class="mt-2"></div>
    <div id="locationAlert" class="alert-location" style="display:none;">
        Enable location access in your browser or search your address to find nearby colleges.
    </div>

    <div id="manualBlock">
        <h3>Search your location or use your current location</h3>
        <input type="text" class="form-control" id="addressInput" placeholder="Enter an address (e.g., city, street)">
        <button type="button" class="nearest-search-btn" id="geocodeButton">
            <span>Search Location</span>
        </button>
        <div id="coordinates" class="mt-2"></div>
    </div>

    <form action="{{ $locationSubmitRoute }}" method="GET" id="locationForm" style="display:none;">
        <input type="hidden" id="latitude" name="latitude" required>
        <input type="hidden" id="longitude" name="longitude" required>
        <input type="hidden" id="algo" name="algo" value="">
        <!-- Cache live user location separately (not submitted) so modal can show both points -->
        <input type="hidden" id="user_latitude">
        <input type="hidden" id="user_longitude">
        <button type="submit" id="submitLocation"></button>
    </form>

    <div class="searchresult" style="{{ $nearestColleges->count() ? '' : 'display:none;' }}">
        <h3>Nearest Colleges</h3>
        <div class="course_boxes">
            @foreach($nearestColleges as $nearCollege)
                <div class="course_box">
                    <div class="card">
                        <img src="{{ asset('storage/' . $nearCollege->logo) }}" alt="{{ $nearCollege->name }} Logo">
                        <div class="card-title"><a>{{ $nearCollege->name }}</a></div>
                        <div class="card-text">{{ $nearCollege->address }}</div>

                        @php
                            $distanceMeters = isset($nearCollege->distance) ? (int) $nearCollege->distance : null;
                        @endphp
                        @if(!is_null($distanceMeters))
                            @php
                                $distanceFormatted = $distanceMeters >= 1000
                                    ? number_format($distanceMeters / 1000, 2) . ' km'
                                    : number_format($distanceMeters, 0) . ' m';
                            @endphp
                            <div class="card-text" style="margin-top: 4px;">
                                <strong style="color: #666;">
                                    <span
                                        class="distance-label"
                                        data-college-lat="{{ $nearCollege->latitude }}"
                                        data-college-lon="{{ $nearCollege->longitude }}"
                                        title="Distance based on your current location if available"
                                    >{{ $distanceFormatted }}</span>
                                </strong>
                            </div>
                        @endif

                        <div class="button-wrapper">
                            <a class="scr-btn" href="/college/detail/{{ $nearCollege->id }}">View Details</a>
                            <button
                                class="scr-btn"
                                style="margin-top: 8px;"
                                onclick="openMapForCollege({{ json_encode($nearCollege->latitude) }}, {{ json_encode($nearCollege->longitude) }}, {{ json_encode($nearCollege->name) }})"
                                type="button"
                            >
                                View in Map
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="map-modal-backdrop" id="mapModalBackdrop" aria-hidden="true">
    <div class="map-modal" role="dialog" aria-modal="true" aria-labelledby="mapModalTitle">
        <div class="map-modal-header">
            <div class="map-modal-title" id="mapModalTitle">Location</div>
            <button class="map-modal-close" aria-label="Close" onclick="closeMapModal()">×</button>
        </div>
        <div class="map-modal-body">
            <div id="modalMap"></div>
        </div>
    </div>
    <div style="position: fixed; inset: 0;" onclick="closeMapModal()"></div>
</div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    // Nepal bounds (approx) — south, west / north, east
    const NEPAL_BOUNDS = {
        minLat: 26.347,  // south
        maxLat: 30.447,  // north
        minLon: 80.0586, // west
        maxLon: 88.2015  // east
    };
    function isWithinNepal(lat, lon) {
        return (+lat) >= NEPAL_BOUNDS.minLat && (+lat) <= NEPAL_BOUNDS.maxLat &&
               (+lon) >= NEPAL_BOUNDS.minLon && (+lon) <= NEPAL_BOUNDS.maxLon;
    }

    function setCoords(lat, lon) {
        // Keep full precision for server-side geodesic calculation (Vincenty)
        const latNum = +lat;
        const lonNum = +lon;
        document.getElementById('latitude').value = latNum;
        document.getElementById('longitude').value = lonNum;
        // Display formatted preview for users
        document.getElementById('coordinates').textContent = 'Lat: ' + latNum.toFixed(6) + '  Lon: ' + lonNum.toFixed(6);
    }
    function submitLocation() { document.getElementById('submitLocation').click(); }

    const statusEl = document.getElementById('autoLocateStatus');
    const locationAlert = document.getElementById('locationAlert');
    const geocodeBtn = document.getElementById('geocodeButton');
    const addressInput = document.getElementById('addressInput');

    function handleGeoError() {
        if (statusEl) statusEl.textContent = '';
        if (locationAlert) locationAlert.style.display = 'block';
    }

    function requestLocation() {
        if (!navigator.geolocation) {
            handleGeoError();
            return;
        }
        if (statusEl) {
            statusEl.textContent = 'Detecting your location…';
            statusEl.classList.add('text-muted');
        }
        navigator.geolocation.getCurrentPosition(function(position){
            if (statusEl) statusEl.textContent = '';
            if (locationAlert) locationAlert.style.display = 'none';
            const { latitude, longitude } = position.coords;
            if (!isWithinNepal(latitude, longitude)) {
                alert('Your current location appears to be outside Nepal. Please search for a place within Nepal.');
                return;
            }
            setCoords(latitude, longitude);
            // Prefer highest accuracy: let server use default (Vincenty) by clearing algo
            document.getElementById('algo').value = '';
            submitLocation();
        }, function(){
            handleGeoError();
        }, { enableHighAccuracy: true, timeout: 30000, maximumAge: 0 });
    }

    // Background: try to cache user's live location for modal display (no submit)
    function cacheUserLocation() {
        if (!navigator.geolocation) return;
        navigator.geolocation.getCurrentPosition(function(position){
            const { latitude, longitude } = position.coords;
            if (!isWithinNepal(latitude, longitude)) return;
            document.getElementById('user_latitude').value = (+latitude).toFixed(6);
            document.getElementById('user_longitude').value = (+longitude).toFixed(6);
        }, function(){ /* ignore */ }, { enableHighAccuracy: true, timeout: 15000, maximumAge: 60000 });
    }

    document.getElementById('geocodeButton').addEventListener('click', function(){
        var address = document.getElementById('addressInput').value.trim();
        if (!address) return alert('Please enter an address.');
        const originalText = geocodeBtn.innerHTML;
        geocodeBtn.disabled = true;
        geocodeBtn.innerHTML = 'Searching…';
        // Limit search to Nepal only using countrycodes, bounded, and a Nepal viewbox
        const viewbox = '80.0586,30.447,88.2015,26.347'; // left,top,right,bottom
        fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&countrycodes=np&bounded=1&viewbox=${viewbox}&addressdetails=1&q=${encodeURIComponent(address)}`, {
            headers: {
                'Accept': 'application/json',
                'User-Agent': 'EduQuest/1.0 (contact: support@eduquest.local)',
                'Accept-Language': 'en'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                var lat = parseFloat(data[0].lat), lon = parseFloat(data[0].lon);
                if (isFinite(lat) && isFinite(lon) && isWithinNepal(lat, lon)) {
                    setCoords(lat, lon);
                    // Use default (Vincenty on server) for manual search; ensure algo blank
                    document.getElementById('algo').value = '';
                    submitLocation();
                } else {
                    alert('Address is not within Nepal. Please refine your search.');
                }
            } else {
                alert('Address not found.');
            }
        })
        .catch(() => alert('Geocoding failed. Try again.'))
        .finally(() => {
            geocodeBtn.disabled = false;
            geocodeBtn.innerHTML = originalText;
        });
    });

    // Allow pressing Enter to trigger search
    addressInput.addEventListener('keydown', function(ev) {
        if (ev.key === 'Enter') {
            ev.preventDefault();
            geocodeBtn.click();
        }
    });

    let modalMap, modalMapInited = false, modalMarker, modalUserMarker;
    const modalEl = document.getElementById('mapModalBackdrop');
    const titleEl = document.getElementById('mapModalTitle');
    const GEO_OPTS_STRICT = { enableHighAccuracy: true, timeout: 30000, maximumAge: 0 };
    const GEO_OPTS_FALLBACK = { enableHighAccuracy: true, timeout: 15000, maximumAge: 15000 };
    let geoWatchId = null;

    function openMapForCollege(lat, lon, name) {
        if (lat == null || lon == null) { alert('Location unavailable for this college.'); return; }
        modalEl.style.display = 'flex';
        titleEl.textContent = name ? ('Location — ' + name) : 'Location';
        setTimeout(function(){
            if (!modalMapInited) {
                const bounds = L.latLngBounds([NEPAL_BOUNDS.minLat, NEPAL_BOUNDS.minLon], [NEPAL_BOUNDS.maxLat, NEPAL_BOUNDS.maxLon]);
                modalMap = L.map('modalMap', { maxBounds: bounds, maxBoundsViscosity: 1.0 })
                    .setView([+lat, +lon], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(modalMap);
                modalMarker = L.marker([+lat, +lon]).addTo(modalMap);
                // Prefer fresh high-accuracy geolocation for current user marker
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(pos){
                        const uLat = +pos.coords.latitude;
                        const uLon = +pos.coords.longitude;
                        if (isWithinNepal(uLat, uLon)) {
                            modalUserMarker = L.circleMarker([uLat, uLon], {
                                radius: 6,
                                color: '#2563eb',
                                fillColor: '#2563eb',
                                fillOpacity: 1
                            }).addTo(modalMap).bindPopup('You are here');
                            const fitBounds = L.latLngBounds([[+lat, +lon], [uLat, uLon]]);
                            modalMap.fitBounds(fitBounds.pad(0.25));
                        }
                        // Start watching to refine the user position in real-time
                        try {
                            if (geoWatchId != null) {
                                navigator.geolocation.clearWatch(geoWatchId);
                                geoWatchId = null;
                            }
                            geoWatchId = navigator.geolocation.watchPosition(function(wp){
                                const wLat = +wp.coords.latitude;
                                const wLon = +wp.coords.longitude;
                                if (!isWithinNepal(wLat, wLon)) return;
                                if (modalUserMarker) {
                                    modalUserMarker.setLatLng([wLat, wLon]);
                                } else {
                                    modalUserMarker = L.circleMarker([wLat, wLon], {
                                        radius: 6,
                                        color: '#2563eb',
                                        fillColor: '#2563eb',
                                        fillOpacity: 1
                                    }).addTo(modalMap).bindPopup('You are here');
                                }
                            }, function(){ /* ignore */ }, GEO_OPTS_FALLBACK);
                        } catch(e) { /* ignore */ }
                    }, function(){ /* fallback to cached below */ }, GEO_OPTS_STRICT);
                }
                // Fallback to cached live coords or request coords
                (function fallbackUserMarker(){
                    const uLat = parseFloat(document.getElementById('user_latitude').value || document.getElementById('latitude').value);
                    const uLon = parseFloat(document.getElementById('user_longitude').value || document.getElementById('longitude').value);
                    if (isFinite(uLat) && isFinite(uLon) && !modalUserMarker) {
                        modalUserMarker = L.circleMarker([uLat, uLon], {
                            radius: 6,
                            color: '#2563eb',
                            fillColor: '#2563eb',
                            fillOpacity: 1
                        }).addTo(modalMap).bindPopup('You are here');
                        const fitBounds = L.latLngBounds([[+lat, +lon], [uLat, uLon]]);
                        modalMap.fitBounds(fitBounds.pad(0.25));
                    }
                })();
                modalMapInited = true;
            } else {
                modalMap.setView([+lat, +lon], 15);
                if (modalMarker) {
                    modalMarker.setLatLng([+lat, +lon]);
                } else {
                    modalMarker = L.marker([+lat, +lon]).addTo(modalMap);
                }
                // Update user marker too: prefer fresh geolocation
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(pos){
                        const uLat = +pos.coords.latitude;
                        const uLon = +pos.coords.longitude;
                        if (isWithinNepal(uLat, uLon)) {
                            if (modalUserMarker) {
                                modalUserMarker.setLatLng([uLat, uLon]);
                            } else {
                                modalUserMarker = L.circleMarker([uLat, uLon], {
                                    radius: 6,
                                    color: '#2563eb',
                                    fillColor: '#2563eb',
                                    fillOpacity: 1
                                }).addTo(modalMap).bindPopup('You are here');
                            }
                            const fitBounds = L.latLngBounds([[+lat, +lon], [uLat, uLon]]);
                            modalMap.fitBounds(fitBounds.pad(0.25));
                        }
                        // Refresh watch on reopen
                        try {
                            if (geoWatchId != null) {
                                navigator.geolocation.clearWatch(geoWatchId);
                                geoWatchId = null;
                            }
                            geoWatchId = navigator.geolocation.watchPosition(function(wp){
                                const wLat = +wp.coords.latitude;
                                const wLon = +wp.coords.longitude;
                                if (!isWithinNepal(wLat, wLon)) return;
                                if (modalUserMarker) {
                                    modalUserMarker.setLatLng([wLat, wLon]);
                                } else {
                                    modalUserMarker = L.circleMarker([wLat, wLon], {
                                        radius: 6,
                                        color: '#2563eb',
                                        fillColor: '#2563eb',
                                        fillOpacity: 1
                                    }).addTo(modalMap).bindPopup('You are here');
                                }
                            }, function(){ /* ignore */ }, GEO_OPTS_FALLBACK);
                        } catch(e) { /* ignore */ }
                    }, function(){
                        const uLat = parseFloat(document.getElementById('user_latitude').value || document.getElementById('latitude').value);
                        const uLon = parseFloat(document.getElementById('user_longitude').value || document.getElementById('longitude').value);
                        if (isFinite(uLat) && isFinite(uLon)) {
                            if (modalUserMarker) {
                                modalUserMarker.setLatLng([uLat, uLon]);
                            } else {
                                modalUserMarker = L.circleMarker([uLat, uLon], {
                                    radius: 6,
                                    color: '#2563eb',
                                    fillColor: '#2563eb',
                                    fillOpacity: 1
                                }).addTo(modalMap).bindPopup('You are here');
                            }
                            const fitBounds = L.latLngBounds([[+lat, +lon], [uLat, uLon]]);
                            modalMap.fitBounds(fitBounds.pad(0.25));
                        }
                    }, GEO_OPTS_STRICT);
                }
            }
            modalMap.invalidateSize();
        }, 50);
    }
    function closeMapModal() { modalEl.style.display = 'none'; }
    window.openMapForCollege = openMapForCollege;
    window.closeMapModal = closeMapModal;

    const initialLat = @json(request('latitude'));
    const initialLon = @json(request('longitude'));
    if (initialLat && initialLon) {
        setCoords(initialLat, initialLon);
    }

    if (@json($shouldAutoLocate)) {
        requestLocation();
    }
    // Always try to cache user location for later modal rendering (doesn't trigger a submit)
    cacheUserLocation();

    // Prevent auto-refresh loop:
    // Only auto-locate once on the store page when there's no algo and no coords in URL.
    (function enforceLiveLocationOnStoreWithoutAlgo(){
        try {
            var path = window.location.pathname || '';
            var params = new URLSearchParams(window.location.search || '');
            var algo = (params.get('algo') || '').trim();
            var onStore = path.indexOf('/nearest-college/store') !== -1;
            var hasCoords = params.has('latitude') && params.has('longitude');
            var alreadyAutoLocated = false;
            try { alreadyAutoLocated = sessionStorage.getItem('eqNearestAutoOnce') === '1'; } catch(e) {}

            if (onStore && (!algo || algo.length === 0) && !hasCoords && !alreadyAutoLocated) {
                // Mark as done to avoid repeated submits
                try { sessionStorage.setItem('eqNearestAutoOnce', '1'); } catch(e) {}
                // Set algo to haversine so the resulting URL breaks the condition next load
                var algoInput = document.getElementById('algo');
                if (algoInput) algoInput.value = 'haversine';
                requestLocation();
            }
        } catch(e) { /* no-op */ }
    })();

    // Distances are computed on the server to ensure consistency with search URL lat/lon.
    // Enhance accuracy for display by recalculating from the user's live location (no page reload).
    (function updateDistancesFromLiveLocation(){
        try {
            if (!navigator.geolocation) return;
            var labels = Array.prototype.slice.call(document.querySelectorAll('.distance-label'));
            if (!labels.length) return;

            function toNum(v) { var n = parseFloat(v); return isFinite(n) ? n : null; }
            // Vincenty inverse formula on client (WGS84) for better accuracy in meters
            function vincentyMeters(lat1, lon1, lat2, lon2) {
                // Short-circuit identical points
                if (Math.abs(lat1 - lat2) < 1e-12 && Math.abs(lon1 - lon2) < 1e-12) return 0;
                var a = 6378137.0; // semi-major axis (m)
                var f = 1 / 298.257223563; // flattening
                var b = (1 - f) * a; // semi-minor axis

                var toRad = function(deg){ return deg * Math.PI / 180; };
                var phi1 = toRad(lat1), phi2 = toRad(lat2);
                var L = toRad(lon2 - lon1);

                var U1 = Math.atan((1 - f) * Math.tan(phi1));
                var U2 = Math.atan((1 - f) * Math.tan(phi2));
                var sinU1 = Math.sin(U1), cosU1 = Math.cos(U1);
                var sinU2 = Math.sin(U2), cosU2 = Math.cos(U2);

                var lambda = L, lambdaPrev, iterLimit = 200;
                var sinLambda, cosLambda, sinSigma, cosSigma, sigma, sinAlpha, cosSqAlpha, cos2SigmaM;

                do {
                    sinLambda = Math.sin(lambda);
                    cosLambda = Math.cos(lambda);
                    var t1 = (cosU2 * sinLambda);
                    var t2 = (cosU1 * sinU2) - (sinU1 * cosU2 * cosLambda);
                    sinSigma = Math.sqrt(t1 * t1 + t2 * t2);
                    if (sinSigma === 0) return 0; // co-incident
                    cosSigma = (sinU1 * sinU2) + (cosU1 * cosU2 * cosLambda);
                    sigma = Math.atan2(sinSigma, cosSigma);
                    sinAlpha = (cosU1 * cosU2 * sinLambda) / sinSigma;
                    cosSqAlpha = 1 - sinAlpha * sinAlpha;
                    cos2SigmaM = (cosSqAlpha === 0) ? 0 : (cosSigma - (2 * sinU1 * sinU2) / cosSqAlpha);
                    var C = (f / 16) * cosSqAlpha * (4 + f * (4 - 3 * cosSqAlpha));
                    lambdaPrev = lambda;
                    lambda = L + (1 - C) * f * sinAlpha * (sigma + C * sinSigma * (cos2SigmaM + C * cosSigma * (-1 + 2 * cos2SigmaM * cos2SigmaM)));
                } while (Math.abs(lambda - lambdaPrev) > 1e-12 && --iterLimit > 0);

                // If not converged, fallback to Haversine (rare near antipodal)
                if (iterLimit <= 0) {
                    var R = 6371000;
                    var dphi = toRad(lat2 - lat1);
                    var dlmb = toRad(lon2 - lon1);
                    var h = Math.sin(dphi/2) * Math.sin(dphi/2) +
                            Math.cos(phi1) * Math.cos(phi2) *
                            Math.sin(dlmb/2) * Math.sin(dlmb/2);
                    var c = 2 * Math.atan2(Math.sqrt(h), Math.sqrt(1-h));
                    return R * c;
                }

                var uSq = (cosSqAlpha * (a*a - b*b)) / (b*b);
                var A = 1 + (uSq / 16384) * (4096 + uSq * (-768 + uSq * (320 - 175 * uSq)));
                var B = (uSq / 1024) * (256 + uSq * (-128 + uSq * (74 - 47 * uSq)));
                var deltaSigma = B * sinSigma * (cos2SigmaM + (B/4) * (cosSigma * (-1 + 2 * cos2SigmaM * cos2SigmaM) - (B/6) * cos2SigmaM * (-3 + 4 * sinSigma * sinSigma) * (-3 + 4 * cos2SigmaM * cos2SigmaM)));
                var s = b * A * (sigma - deltaSigma);
                return s; // meters
            }
            function formatDistance(meters) {
                if (!isFinite(meters)) return '';
                if (meters >= 1000) return (meters/1000).toFixed(2) + ' km';
                return Math.round(meters).toString() + ' m';
            }

            navigator.geolocation.getCurrentPosition(function(pos){
                var uLat = +pos.coords.latitude;
                var uLon = +pos.coords.longitude;
                labels.forEach(function(el){
                    var cLat = toNum(el.getAttribute('data-college-lat'));
                    var cLon = toNum(el.getAttribute('data-college-lon'));
                    if (cLat == null || cLon == null) return;
                    var meters = vincentyMeters(uLat, uLon, cLat, cLon);
                    var text = formatDistance(meters);
                    if (text) el.textContent = text;
                });
            }, function(){ /* ignore if denied */ }, { enableHighAccuracy: true, timeout: 20000, maximumAge: 0 });
        } catch(e) { /* no-op */ }
    })();

    // Correct inaccurate URL coordinates once using a fresh high-accuracy geolocation fix.
    (function refineAndResubmitIfOff(){
        try {
            var path = window.location.pathname || '';
            var onStore = path.indexOf('/nearest-college/store') !== -1;
            if (!onStore) return;
            var params = new URLSearchParams(window.location.search || '');
            var hasCoords = params.has('latitude') && params.has('longitude');
            if (!hasCoords) return;
            var refinedKey = 'eqNearestRefinedOnce';
            try {
                if (sessionStorage.getItem(refinedKey) === '1') return;
            } catch(e) {}

            var urlLat = parseFloat(params.get('latitude'));
            var urlLon = parseFloat(params.get('longitude'));
            if (!isFinite(urlLat) || !isFinite(urlLon)) return;
            if (!navigator.geolocation) return;

            function vincentyMeters(lat1, lon1, lat2, lon2) {
                if (Math.abs(lat1 - lat2) < 1e-12 && Math.abs(lon1 - lon2) < 1e-12) return 0;
                var a = 6378137.0, f = 1/298.257223563, b = (1 - f) * a;
                var toRad = function(deg){ return deg * Math.PI / 180; };
                var phi1 = toRad(lat1), phi2 = toRad(lat2);
                var L = toRad(lon2 - lon1);
                var U1 = Math.atan((1 - f) * Math.tan(phi1));
                var U2 = Math.atan((1 - f) * Math.tan(phi2));
                var sinU1 = Math.sin(U1), cosU1 = Math.cos(U1);
                var sinU2 = Math.sin(U2), cosU2 = Math.cos(U2);
                var lambda = L, lambdaPrev, iterLimit = 200;
                var sinLambda, cosLambda, sinSigma, cosSigma, sigma, sinAlpha, cosSqAlpha, cos2SigmaM;
                do {
                    sinLambda = Math.sin(lambda);
                    cosLambda = Math.cos(lambda);
                    var t1 = (cosU2 * sinLambda);
                    var t2 = (cosU1 * sinU2) - (sinU1 * cosU2 * cosLambda);
                    sinSigma = Math.sqrt(t1 * t1 + t2 * t2);
                    if (sinSigma === 0) return 0;
                    cosSigma = (sinU1 * sinU2) + (cosU1 * cosU2) * cosLambda;
                    sigma = Math.atan2(sinSigma, cosSigma);
                    sinAlpha = (cosU1 * cosU2 * sinLambda) / sinSigma;
                    cosSqAlpha = 1 - sinAlpha * sinAlpha;
                    cos2SigmaM = (cosSqAlpha === 0) ? 0 : (cosSigma - (2 * sinU1 * sinU2) / cosSqAlpha);
                    var C = (f / 16) * cosSqAlpha * (4 + f * (4 - 3 * cosSqAlpha));
                    lambdaPrev = lambda;
                    lambda = L + (1 - C) * f * sinAlpha * (sigma + C * sinSigma * (cos2SigmaM + C * cosSigma * (-1 + 2 * cos2SigmaM * cos2SigmaM)));
                } while (Math.abs(lambda - lambdaPrev) > 1e-12 && --iterLimit > 0);
                if (iterLimit <= 0) {
                    var R = 6371000;
                    var dphi = toRad(lat2 - lat1);
                    var dlmb = toRad(lon2 - lon1);
                    var h = Math.sin(dphi/2) * Math.sin(dphi/2) +
                            Math.cos(phi1) * Math.cos(phi2) *
                            Math.sin(dlmb/2) * Math.sin(dlmb/2);
                    var c = 2 * Math.atan2(Math.sqrt(h), Math.sqrt(1-h));
                    return R * c;
                }
                var uSq = (cosSqAlpha * (a*a - b*b)) / (b*b);
                var A = 1 + (uSq / 16384) * (4096 + uSq * (-768 + uSq * (320 - 175 * uSq)));
                var B = (uSq / 1024) * (256 + uSq * (-128 + uSq * (74 - 47 * uSq)));
                var deltaSigma = B * sinSigma * (cos2SigmaM + (B/4) * (cosSigma * (-1 + 2 * cos2SigmaM * cos2SigmaM) - (B/6) * cos2SigmaM * (-3 + 4 * sinSigma * sinSigma) * (-3 + 4 * cos2SigmaM * cos2SigmaM)));
                return b * A * (sigma - deltaSigma);
            }

            navigator.geolocation.getCurrentPosition(function(pos){
                var liveLat = +pos.coords.latitude;
                var liveLon = +pos.coords.longitude;
                if (!isWithinNepal(liveLat, liveLon)) return;
                var delta = vincentyMeters(urlLat, urlLon, liveLat, liveLon);
                if (isFinite(delta) && delta > 150) {
                    try { sessionStorage.setItem(refinedKey, '1'); } catch(e) {}
                    setCoords(liveLat, liveLon);
                    var algo = (params.get('algo') || '').trim();
                    document.getElementById('algo').value = algo;
                    submitLocation();
                }
            }, function(){ /* ignore if denied */ }, { enableHighAccuracy: true, timeout: 20000, maximumAge: 0 });
        } catch(e) { /* no-op */ }
    })();
</script>

