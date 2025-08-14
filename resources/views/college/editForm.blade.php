@extends('layouts.college')

@section('content')
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Smooth fade for alert */
        #successAlert {
            transition: opacity 0.5s ease;
        }
        .fw-bold{
            font-weight: 800;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }
        .btn-update{
            padding: 4px;
            border: 2px solid black;
            background: white;
            border-radius: 6px;
            width: 100px;
        }
        .btn-update:hover{
            background: black;
            color: white;
            transition: 2ms ease-in-out;
        }

        /* Form section headings */
        .form-section {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 2rem 1.5rem;
            margin-bottom: 2rem;
            background-color: #fff;
            box-shadow: 0 0.125rem 0.25rem rgb(0 0 0 / 0.075);
        }
        .form-section h5 {
            font-weight: 700;
            color: black;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid black;
            padding-bottom: 0.5rem;
            letter-spacing: 0.03em;
        }

        /* File input styling */
        input[type="file"] {
            cursor: pointer;
        }

        /* Gallery thumbnails */
        .gallery-container {
            position: relative;
        }
        .gallery-container input.form-check-input {
            cursor: pointer;
        }

        /* Align "Remove" checkbox label with checkbox nicely */
        .form-check-label {
            user-select: none;
        }

        /* Responsive adjustments */
        @media (max-width: 575.98px) {
            .form-section {
                padding: 1.5rem 1rem;
            }
            .gallery-container {
                flex-direction: column !important;
                align-items: stretch !important;
            }
            .gallery-container input.form-control {
                width: 100% !important;
            }
        }
    </style>
</head>

<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" id="successAlert">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(function () {
                $('#successAlert').fadeOut('slow');
            }, 2000);
        </script>
    @endif



    <div class="mb-5 text-center">
        <h1 class="fw-bold">Update College Form</h1>
    </div>

    <form method="POST" action="{{ route('college.update', $college) }}" enctype="multipart/form-data" class="rounded shadow bg-white p-4 p-md-5">
        @csrf

        {{-- PERSONAL INFO --}}
        <div class="form-section">
            <h5>Personal Information</h5>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $college->name) }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Email Address</label>
                    <input type="email" class="form-control bg-light" value="{{ old('email', $college->email) }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Contact</label>
                    <input type="text" class="form-control" name="contact" value="{{ old('contact', $college->contact) }}" required>
                    @error('contact') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea class="form-control" name="description" rows="3" required>{{ old('description', $college->description) }}</textarea>
                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- ADDRESS --}}
        <div class="form-section">
            <h5>Address</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">City</label>
                    <input type="text" class="form-control" name="address" value="{{ old('address', $college->address) }}" required>
                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        {{-- LOGO --}}
        <div class="form-section">
            <h5>Logo</h5>
            <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                <img src="{{ asset('storage/' . $college->logo) }}" alt="College Logo" class="rounded border shadow-sm" style="height: 100px; width: 100px; object-fit: contain;">
                <input type="file" class="form-control w-100 w-md-auto" name="logo" accept="image/*">
            </div>
            @error('logo') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- GALLERY --}}
        <div class="form-section">
            <h5>Gallery</h5>

            <div class="row g-3 mb-3">
                @foreach($college->images as $gallery)
                    <div class="col-md-3 col-6 text-center position-relative">
                        <img src="{{ asset('storage/'. $gallery->path) }}" alt="Gallery Image" class="img-thumbnail shadow-sm" style="height: 120px; width: 100%; object-fit: cover;">
                        <div class="form-check mt-1 d-flex justify-content-center">
                            <input class="form-check-input me-1" type="checkbox" name="remove_gallery[]" value="{{ $gallery->id }}" id="galleryRemove{{ $gallery->id }}">
                            <label class="form-check-label text-danger small" for="galleryRemove{{ $gallery->id }}">Remove</label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="gallery-wrapper">
                <div class="gallery-container d-flex align-items-center gap-2 mb-2">
                    <input type="file" class="form-control" name="gallery[]" accept="image/*">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" name="remove_new[]" value="0" disabled>
                        <label class="form-check-label text-muted small mb-0">Remove</label>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-secondary mt-2 add-gallery">Add More</button>
        </div>

        <div class="text-end">
            <button type="submit" class="btn-update">Update</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $(".add-gallery").click(function () {
            const newGallery = `
                <div class="gallery-container d-flex align-items-center gap-2 mb-2">
                    <input type="file" class="form-control" name="gallery[]" accept="image/*">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" name="remove_new[]" value="0" disabled>
                        <label class="form-check-label text-muted small mb-0">Remove</label>
                    </div>
                </div>`;
            $(".gallery-wrapper").append(newGallery);
        });
    });
</script>
@endsection
