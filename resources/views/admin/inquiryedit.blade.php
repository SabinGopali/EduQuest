@extends('layouts.admin')
@section('content')
<div class="container mt-5">
    <h2 class="text-center">Update Inquiry</h2>
    <form action="{{ route('inquiry.update', $inquiry->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="studentId" class="form-label">Student ID</label>
            <input type="text" class="form-control" id="studentId" name="student_id" value="{{ $inquiry->student_id }}" required>
        </div>
        <div class="mb-3">
            <label for="collegedetailId" class="form-label">CollegeDetail ID</label>
            <input type="text" class="form-control" id="collegedetailId" name="collegedetail_id" value="{{ $inquiry->collegedetail_id }}" required>
        </div>
        <div class="mb-3">
            <label for="inquiryDate" class="form-label">Date</label>
            <input type="date" class="form-control" id="inquiryDate" name="inquirydate" value="{{ $inquiry->inquirydate }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Inquiry</button>
    </form>
</div>
@endsection
