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
            background: white;
            padding: 40px 35px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: 3rem auto;
        }

        .form-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #000;
            text-align: center;
        }

        label,
        .form-group label,
        .file-label {
            font-weight: 700 !important;
            color: #000 !important;
            margin-bottom: 8px;
            display: block;
            cursor: pointer;
            user-select: none;
            transition: color 0.3s ease;
        }

        label:hover {
            color: black;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"],
        textarea,
        select {
            font-weight: 400;
            color: #000;
            padding: 12px 14px;
            border-radius: 6px;
            border: 1.5px solid #ccc;
            box-sizing: border-box;
            width: 100%;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="file"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: black;
            
            background-color: #f0f8ff;
        }

        select[multiple] {
            height: 110px;
        }

        /* Placeholder text bold and black */
        input::placeholder,
        textarea::placeholder {
            font-weight: 700;
            color: #000;
            opacity: 1;
        }

        /* Custom file upload label */
        .custom-file-upload {
            display: inline-block;
            padding: 12px 20px;
            cursor: pointer;
            border-radius: 6px;
            border: 2px dashed #6c757d;
            background-color: #f8f9fa;
            transition: background 0.3s ease;
            text-align: center;
            width: 100%;
            font-weight: 700;
            color: #000;
        }

        .custom-file-upload:hover {
            background-color: #e2e6ea;
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
            font-weight: 700;
        }

        .btn {
            border-radius: 6px;
            padding: 12px 25px;
            font-weight: 700;
            color: #000;
            transition: color 0.3s ease;
        }

        .btn-primary {
            color: black ;
            font-weight: 700;
            background-color: white;
            border: 2px solid black;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-primary:hover {
            background-color: black;
            color: white;
        }

        .file-name {
            font-size: 0.9rem;
            color: #555;
            font-weight: 700;
            margin-top: 6px;
        }

        .image-preview {
            max-width: 80px;
            max-height: 80px;
            border-radius: 6px;
            border: 1px solid #ddd;
            object-fit: cover;
        }

        #coordinates {
            font-weight: 600;
            margin-top: 10px;
            color: #333;
        }

        /* Buttons and form spacing */
        .row.g-4 > [class*="col-"] {
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .form-card {
                padding: 30px 20px;
                margin: 2rem 1rem;
            }

            .btn {
                width: 100%;
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

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="form-card">
                <div class="form-title text-center">College Registration Form</div>

                <form id="Collegeform" method="POST" action="{{ route('college.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <!-- Basic Fields -->
                        <div class="col-md-6">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
                        </div>

                        <div class="col-md-12">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" name="address" placeholder="Enter Address" required>
                        </div>

                        <!-- Location Search -->
                        <div class="col-md-12">
                            <label for="location">Search Location</label>
                            <input type="text" class="form-control mb-2" id="addressInput" placeholder="Enter The Location">
                            <button type="button" class="btn btn-outline-primary w-100" id="geocodeButton">Search</button>
                            <div id="coordinates"></div>
                        </div>

                        <div class="col-md-6">
                            <label>Latitude</label>
                            <input type="text" class="form-control" name="latitude" required placeholder="Latitude">
                        </div>

                        <div class="col-md-6">
                            <label>Longitude</label>
                            <input type="text" class="form-control" name="longitude" required placeholder="Longitude">
                        </div>

                        <div class="col-12" style="display: none;">
                            <div id="map"></div>
                        </div>

                        <!-- Other Inputs -->
                        <div class="col-md-6">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
                        </div>

                        <div class="col-md-6">
                            <label for="contact">Contact</label>
                            <input type="text" class="form-control" name="contact" placeholder="Enter Contact" required>
                        </div>

                        <div class="col-md-12">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Enter Description" required></textarea>
                        </div>

                        <!-- Logo Upload -->
                        <div class="col-md-12">
                            <label for="logo" class="file-label">College Logo</label>
                            <label class="custom-file-upload">
                                <input type="file" name="logo" id="logo" hidden onchange="document.getElementById('logoName').textContent = this.files[0]?.name || 'No file chosen'">
                                Click to upload logo
                            </label>
                            <div id="logoName" class="file-name mt-1">No file chosen</div>
                        </div>

                        <!-- Gallery Uploads -->
                        <div class="col-md-12 gallery-container">
                            <label for="gallery" class="file-label">Gallery Image</label>
                            <div class="gallery-group">
                                <input type="file" class="form-control-file gallery-input" name="gallery[]">
                                <button type="button" class="remove-gallery" title="Remove">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Add More Gallery -->
                        <div class="col-md-12">
                            <button type="button" class="btn btn-outline-success add-gallery"><i class="bi bi-plus-circle me-1"></i> Add More Gallery</button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary px-5">Submit</button>
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
                    var map = L.map('map').setView([27.708317, 85.320582], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    var marker = L.marker([0, 0], { draggable: true }).addTo(map);

                    function updateMarkerPosition(latlng) {
                        marker.setLatLng(latlng);
                        document.getElementById('coordinates').innerHTML = 'Latitude: ' + latlng.lat.toFixed(6) + '   Longitude: ' + latlng.lng.toFixed(6);
                        document.querySelector('input[name="latitude"]').value = latlng.lat.toFixed(6);
                        document.querySelector('input[name="longitude"]').value = latlng.lng.toFixed(6);
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
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }

                    document.getElementById('geocodeButton').addEventListener('click', function () {
                        var address = document.getElementById('addressInput').value;
                        if (address) {
                            geocodeAddress(address);
                        } else {
                            alert('Please enter an address.');
                        }
                    });

                    marker.on('drag', function (event) {
                        updateMarkerPosition(event.target.getLatLng());
                    });
                </script>

            </div>
        </div>
    </div>
</div>

@endsection