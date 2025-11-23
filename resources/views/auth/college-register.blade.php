@extends('layouts.app')
@section('content')

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-card {
            background: #fff;
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: 3rem auto 5rem auto;
            transition: box-shadow 0.3s ease;
        }
        .form-card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .form-title {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 30px;
            color: #222;
            text-align: center;
            letter-spacing: 1px;
        }

        .form-section {
            margin-bottom: 35px;
        }
        .form-section h3 {
            font-weight: 700;
            color: red;
            margin-bottom: 20px;
            border-bottom: 2px solid black;
            padding-bottom: 6px;
            letter-spacing: 0.05em;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label,
        .form-group label,
        .file-label {
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
            display: block;
            font-size: 1rem;
            cursor: pointer;
            user-select: none;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="file"],
        textarea,
        select {
            font-weight: 400;
            color: #444;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1.8px solid #ddd;
            width: 100%;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input::placeholder,
        textarea::placeholder {
            font-weight: 600;
            color: #999;
            opacity: 1;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: black;
            background-color: #f0f8ff;
        }

        select[multiple] {
            height: 120px;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Custom file upload label */
        .custom-file-upload {
            display: inline-block;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 8px;
            border: 2px dashed #ddd;
            background-color: #f8f9fa;
            transition: background 0.3s ease, border-color 0.3s ease;
            text-align: center;
            width: 100%;
            font-weight: 600;
            color: #666;
        }

        .custom-file-upload:hover {
            background-color: #e2e6ea;
            border-color: #999;
        }

        /* Gallery group flex with gap */
        .gallery-group {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .remove-gallery {
            border: none;
            background: transparent;
            color: #dc3545;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            transition: color 0.3s ease;
        }

        .remove-gallery:hover {
            color: #a71d2a;
        }

        .add-gallery {
            margin-top: 16px;
            width: 100%;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            border: 1.8px solid #ddd;
            background-color: white;
            color: #333;
            transition: all 0.3s ease;
        }

        .add-gallery:hover {
            background-color: #f0f8ff;
            border-color: black;
        }

        .btn {
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 600;
            color: #333;
            transition: all 0.3s ease;
            border: 1.8px solid #ddd;
            background-color: white;
        }

        .btn:hover {
            background-color: #f0f8ff;
            border-color: black;
        }

        button[type="submit"] {
            background-color: white;
            color: black;
            border: none;
            padding: 14px 40px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
            border: 2px solid black;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: black;
            color: white;
        }

        .file-name {
            font-size: 0.9rem;
            color: #666;
            font-weight: 600;
            margin-top: 6px;
        }

        .image-preview {
            max-width: 80px;
            max-height: 80px;
            border-radius: 8px;
            border: 1px solid #ddd;
            object-fit: cover;
        }

        #coordinates {
            font-weight: 600;
            margin-top: 10px;
            color: #333;
        }

        .form-link {
            margin-top: 30px;
            text-align: center;
            font-weight: 600;
            font-size: 1rem;
        }

        .form-link a {
            color: red;
            text-decoration: none;
            border-bottom: 1.5px solid transparent;
            transition: border-color 0.3s ease;
        }

        .form-link a:hover {
            border-color: black;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-card {
                padding: 30px 25px;
                margin: 2rem 1.5rem 3rem 1.5rem;
                max-width: 100%;
            }

            button[type="submit"] {
                padding: 14px 0;
                font-size: 16px;
            }

            .gallery-group {
                flex-direction: column;
                align-items: flex-start;
            }

            .remove-gallery {
                align-self: flex-end;
            }
        }
    </style>
</head>

<div class="container">
    <div class="form-card">
        <div class="form-title">College Registration Form</div>

        <form id="Collegeform" method="POST" action="{{ route('college.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Basic Information Section -->
            <div class="form-section">
                <h3>Basic Information</h3>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" placeholder="Enter Name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Enter Email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Enter Password" required>
                </div>

                <div class="form-group">
                    <label for="contact">Contact</label>
                    <input type="text" name="contact" placeholder="Enter Contact" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" placeholder="Enter Address" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" rows="3" placeholder="Enter Description" required></textarea>
                </div>
            </div>

            <!-- Location Section -->
            <div class="form-section">
                <h3>Location</h3>

                <div class="form-group">
                    <label for="location">Search Location</label>
                    <input type="text" id="addressInput" placeholder="Enter The Location">
                    <button type="button" class="btn" id="geocodeButton" style="margin-top: 10px; width: 100%;">Search Location</button>
                    <div id="coordinates"></div>
                </div>

                <div class="form-group">
                    <label>Latitude</label>
                    <input type="number" step="any" name="latitude" required placeholder="Latitude">
                </div>

                <div class="form-group">
                    <label>Longitude</label>
                    <input type="number" step="any" name="longitude" required placeholder="Longitude">
                </div>

                <div class="form-group" style="display: none;">
                    <div id="map"></div>
                </div>
            </div>

            <!-- Media Section -->
            <div class="form-section">
                <h3>Media</h3>

                <div class="form-group">
                    <label for="logo" class="file-label">College Logo</label>
                    <label class="custom-file-upload">
                        <input type="file" name="logo" id="logo" hidden onchange="document.getElementById('logoName').textContent = this.files[0]?.name || 'No file chosen'">
                        Click to upload logo
                    </label>
                    <div id="logoName" class="file-name">No file chosen</div>
                </div>

                <div class="form-group gallery-container">
                    <label for="gallery" class="file-label">Gallery Image</label>
                    <div class="gallery-group">
                        <input type="file" class="gallery-input" name="gallery[]">
                        <button type="button" class="remove-gallery" title="Remove">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <button type="button" class="add-gallery"><i class="bi bi-plus-circle me-1"></i> Add More Gallery</button>
                </div>
            </div>

            <button type="submit">Submit</button>

            <div class="form-link">
                <a href="/register">Sign up as Student</a>
            </div>

            @include('partials.errors')
        </form>

                <script>
                    $(document).ready(function () {
                        // Add More Gallery
                        $(".add-gallery").click(function () {
                            const galleryGroup = $(".gallery-group").first().clone();

                            galleryGroup.find("input[type='file']").val(""); // Clear file
                            galleryGroup.find(".image-preview").remove(); // Remove old preview if any

                            $(".gallery-container").append(galleryGroup);
                        });

                        // Remove Gallery
                        $(document).on("click", ".remove-gallery", function () {
                            if ($(".gallery-group").length > 1) {
                                $(this).closest(".gallery-group").remove();
                            } else {
                                alert("At least one image is required.");
                            }
                        });

                        // Preview Image on File Select
                        $(document).on("change", ".gallery-input", function () {
                            const input = this;
                            const file = input.files[0];

                            // Remove existing preview if any
                            $(input).siblings(".image-preview").remove();

                            if (file && file.type.startsWith("image/")) {
                                const reader = new FileReader();
                                reader.onload = function (e) {
                                    const img = $('<img class="image-preview ms-2" alt="Preview">').attr("src", e.target.result);
                                    $(input).after(img);
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    });
                </script>

                <!-- Your existing Leaflet map script remains unchanged -->
                <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
                <script>
                    // Nepal bounds (approx)
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

                    // Initialize map constrained to Nepal
                    const nepalBoundsLeaflet = L.latLngBounds(
                        [NEPAL_BOUNDS.minLat, NEPAL_BOUNDS.minLon],
                        [NEPAL_BOUNDS.maxLat, NEPAL_BOUNDS.maxLon]
                    );
                    var map = L.map('map', { maxBounds: nepalBoundsLeaflet, maxBoundsViscosity: 1.0 })
                        .setView([27.708317, 85.320582], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    var marker = L.marker([27.708317, 85.320582], { draggable: true }).addTo(map);

                    function updateMarkerPosition(latlng) {
                        if (!isWithinNepal(latlng.lat, latlng.lng)) {
                            alert('Please select a location within Nepal.');
                            return;
                        }
                        marker.setLatLng(latlng);
                        document.getElementById('coordinates').innerHTML = 'Latitude: ' + latlng.lat.toFixed(6) + '   Longitude: ' + latlng.lng.toFixed(6);
                        document.querySelector('input[name="latitude"]').value = latlng.lat.toFixed(6);
                        document.querySelector('input[name="longitude"]').value = latlng.lng.toFixed(6);
                    }

                    function geocodeAddress(address) {
                        const viewbox = '80.0586,30.447,88.2015,26.347'; // Nepal bbox: left,top,right,bottom
                        fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&countrycodes=np&bounded=1&viewbox=${viewbox}&addressdetails=1&q=${encodeURIComponent(address)}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.length > 0) {
                                    var result = data[0];
                                    var lat = parseFloat(result.lat);
                                    var lon = parseFloat(result.lon);
                                    if (!isWithinNepal(lat, lon)) {
                                        alert('Please search for an address within Nepal.');
                                        return;
                                    }
                                    var latlng = L.latLng(lat, lon);
                                    map.setView(latlng, 13);
                                    updateMarkerPosition(latlng);
                                } else {
                                    alert('Address not found.');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }

                    document.getElementById('geocodeButton').addEventListener('click', function () {
                        var address = document.getElementById('addressInput').value.trim();
                        if (address) {
                            geocodeAddress(address);
                        } else {
                            alert('Please enter an address.');
                        }
                    });

                    marker.on('drag', function (event) {
                        const ll = event.target.getLatLng();
                        if (!isWithinNepal(ll.lat, ll.lng)) {
                            // Snap back to last valid position
                            const currentLat = parseFloat(document.querySelector('input[name="latitude"]').value || '27.708317');
                            const currentLon = parseFloat(document.querySelector('input[name="longitude"]').value || '85.320582');
                            marker.setLatLng([currentLat, currentLon]);
                            alert('Please keep the marker within Nepal.');
                            return;
                        }
                        updateMarkerPosition(ll);
                    });

                    // Prevent form submission with out-of-Nepal coordinates
                    document.getElementById('Collegeform').addEventListener('submit', function(e){
                        const lat = parseFloat(document.querySelector('input[name="latitude"]').value);
                        const lon = parseFloat(document.querySelector('input[name="longitude"]').value);
                        if (!isFinite(lat) || !isFinite(lon) || !isWithinNepal(lat, lon)) {
                            e.preventDefault();
                            alert('Latitude and Longitude must be within Nepal.');
                        }
                    });
                </script>
    </div>
</div>

@endsection