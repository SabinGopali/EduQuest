@extends('layouts.college')
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Inquiry Form</title>
</head>
    <div class="container mt-5">
        <h1 class="text-center">Inquiry Form</h1>
        <form action="/store" method="POST">
            @csrf
            <div class="mb-3">
                <label for="studentId" class="form-label">Student ID</label>
                <input type="text" class="form-control" id="studentid" name="studentid" required>
            </div>
            <div class="mb-3">
                <label for="collegeId" class="form-label">College ID</label>
                <input type="text" class="form-control" id="collegeid" name="collegeid" required>
            </div>
            <div class="mb-3">
                <label for="collegeId" class="form-label">Message</label>
                <textarea name="message" id="message" cols="30" rows="10"></textarea>
            </div>
            <div class="mb-3">
                <label for="inquiryDate" class="form-label">Date</label>
                <input type="date" class="form-control" id="inquiryDate" name="inquirydate" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit Inquiry</button>
        </form>
    </div>
    @endsection

    <!-- Bootstrap JS, Popper.js, and jQuery (Optional) -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script> -->


