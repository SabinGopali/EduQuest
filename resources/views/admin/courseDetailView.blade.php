@extends('layouts.admin')
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Description</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<div class="container mt-5">
        <h1 class="mb-4">Course Description</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="field_name"><strong>Field Name:</strong></label>
                    <input type="text" class="form-control" id="field_name" value="{{ $course->name }}" readonly>
                </div>
                
                <div class="form-group">
                    <label for="stream"><strong>Stream:</strong></label>
                    <input type="text" class="form-control" id="stream" value="{{ $course->stream }}" readonly>
                </div>

                <div class="form-group">
                    <label for="gpa_limit"><strong>GPA Limit:</strong></label>
                    <input type="text" class="form-control" id="gpa_limit" value="{{ $course->gpa_limit }}" readonly>
                </div>
                
            </div>
            <div class="col-md-6">
            <div class="form-group">
                    <label for="short_name"><strong>Short Name:</strong></label>
                    <input type="text" class="form-control" id="short_name" value="{{ $course->shortName}}"  readonly>
                </div>
                <div class="form-group">
                    <label for="substream"><strong>Substream:</strong></label>
                    <input type="text" class="form-control" id="substream" value="{{ $course->subStream }}"  readonly>
                </div>
                
                <div class="form-group">
                    <label for="duration"><strong>Duration:</strong></label>
                    <input type="text" class="form-control" id="duration" value="{{ $course->duration }}"readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="description"><strong>Description:</strong></label>
                    <textarea class="form-control" id="description" rows="10" readonly>{{ $course->description }}</textarea>
                </div>
            </div>
        </div>
    </div>
    @endsection
    <!-- Include Bootstrap JS and jQuery (optional) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

