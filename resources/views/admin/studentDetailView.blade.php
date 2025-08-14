@extends('layouts.admin')
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student</title>

    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include Bootstrap JavaScript (Popper.js is required) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Include Custom CSS for Styling -->
    <link rel="stylesheet" href="styles.css">

    <!-- Custom CSS to align content to the left -->
    <style>
        .text-left {
            text-align: left;
        }
    </style>
</head>
    <div class="container">
        <div class="row border">
            <div class="col-12 text-center">
                <img src="{{ asset('storage/uploads/' . $student->image) }}" alt="Student Image" style="height: 150px; width: 200px; margin-top: 10px; border: 2px solid black;">

            </div>
        </div>
        <div class="row mt-4 border">
            <div class="col-12">
                <h2>Personal Details</h2>
                <p class="text-left"><strong>Name:</strong> {{$student->name}}</p>
                <p class="text-left"><strong>Email:</strong> {{$student->email}}</p>
                <p class="text-left"><strong>Contact:</strong> {{$student->contact}}</p>
            </div>
        </div>
        <div class="row mt-4 border">
            <div class="col-12">
                <h2>Academic Detail</h2>
                <p class="text-left"><strong>Passed Year:</strong> {{$student->passedyear}}</p>
                <p class="text-left"><strong>Previous School/College:</strong> {{$student->previousschool}}   </p>
                <p class="text-left"><strong>GPA:</strong> {{$student->gpa}}</p>
                <p class="text-left"><strong>Education Level:</strong> {{$student->educationLevel}}</p>
            </div>
        </div>
        <div class="row mt-4 border">
            <div class="col-12">
                <h2>Interest</h2>
                <p class="text-left">{{$student->interest}}</p>
            </div>
        </div>
        <div class="row mt-4 border">
            <div class="col-12">
                <h2>Goal</h2>
                <p class="text-left">{{$student->goal}}</p>
            </div>
        </div>
    </div>
@endsection

