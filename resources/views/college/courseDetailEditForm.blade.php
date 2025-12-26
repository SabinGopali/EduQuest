@extends('layouts.college')
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course Detail</title>
    <style>
    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f3f4f6;
        margin: 0;
        padding: 0;
    }

    .form-container {
        max-width: 1000px;  /* Increased width */
        margin-top: -15px;  /* Reduced top margin */
        margin-left: auto;
        margin-right: auto;
        background: #ffffff;
        padding: 35px 40px;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        border: 1px solid #e5e7eb;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .form-container:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
    }

    h2 {
        margin-bottom: 28px;
        color: #1f2937;
        font-size: 1.8rem;
        font-weight: 700;
        text-align: center;
    }

    .form-group {
        margin-bottom: 22px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        color: #374151;
    }

    select,
    textarea,
    input[type="text"] {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-size: 0.95rem;
        background-color: #f9fafb;
        transition: all 0.25s ease;
    }

    select:hover,
    textarea:hover,
    input[type="text"]:hover {
        background-color: #f3f4f6;
    }

    select:focus,
    textarea:focus,
    input[type="text"]:focus {
        border-color: #6366f1;
        outline: none;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    textarea {
        resize: vertical;
        min-height: 140px;
    }

    .submit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 14px;
        background: white;
        color: black;
        font-weight: 600;
        font-size: 1rem;
        border: 2px solid black;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.25);
    }

    .submit-btn:hover {
        background: black;
        color: white;
        box-shadow: 0 6px 14px rgba(99, 102, 241, 0.3);
    }

    .submit-btn:active {
        transform: translateY(0);
        box-shadow: 0 3px 8px rgba(99, 102, 241, 0.2);
    }

    @media (max-width: 640px) {
        .form-container {
            padding: 25px 20px;
            margin: 30px 15px;
        }
    }
</style>
</head>

<div class="form-container">
    <h2>Edit Course Detail</h2>
    <form id="editForm" action="{{ route('college-coursedetail.update', $courseDetail->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Use PUT method for update -->
        
        <div class="form-group">
            <label for="courseid">Select Course:</label>
            <select id="courseid" name="course_id" required>
                <option value="" disabled>Select a course</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" {{ $course->id == $courseDetail->course_id ? 'selected' : '' }}>
                        {{ $course->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <small style="display:block;color:#6b7280;margin-top:4px;margin-bottom:8px;">Provide clear details for both +2 and Bachelor students (eligibility, subjects, GPA, entrance/fees, scholarships, outcomes). Keep it concise.</small>
            <textarea id="description" name="description" rows="8" required placeholder="Suggested structure:

 +2 (Higher Secondary):
 - Eligibility: SEE passed (e.g., GPA ≥ 2.0)
 - Streams: Science, Management, Humanities, Law, etc.
 - Key Subjects: ...
 - Minimum GPA/Grade: ...
 - Entrance Requirements: ...
 - Fees/Scholarships: ...
 - Career Pathways after +2: ...

 Bachelor (Undergraduate):
 - Eligibility: +2 passed (e.g., GPA ≥ 2.0)
 - Duration: e.g., 4 years (8 semesters)
 - Entrance Exams/Intake: ...
 - Specializations/Majors: ...
 - Fees/Scholarships: ...
 - Internship/Placement Support: ...
 - Career Outcomes: ...">{{ $courseDetail->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="tuition_fee">Tuition Fee:</label>
            <input type="text" id="tuition_fee" name="tuition_fee" value="{{ $courseDetail->tuition_fee }}" />
        </div>

        <div class="form-group">
            <label for="seats">Seats Available:</label>
            <input type="text" id="seats" name="seats" value="{{ $courseDetail->seats }}" />
        </div>

        <div class="form-group">
            <label for="eligibility">Eligibility:</label>
            <textarea id="eligibility" name="eligibility" rows="4">{{ $courseDetail->eligibility }}</textarea>
        </div>

        <div class="form-group">
            <label for="admission_process">Admission Process:</label>
            <textarea id="admission_process" name="admission_process" rows="4">{{ $courseDetail->admission_process }}</textarea>
        </div>

        <div class="form-group">
            <label for="placement">Placement Info:</label>
            <textarea id="placement" name="placement" rows="4">{{ $courseDetail->placement }}</textarea>
        </div>

        <div class="form-group">
            <label for="scholarship">Scholarship:</label>
            <textarea id="scholarship" name="scholarship" rows="3">{{ $courseDetail->scholarship }}</textarea>
        </div>

        <div class="form-group">
            <label for="hostel">Hostel Available:</label>
            <select id="hostel" name="hostel">
                <option value="">Select</option>
                <option value="1" {{ $courseDetail->hostel === 1 ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ $courseDetail->hostel === 0 ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="form-group">
            <label for="application_deadline">Application Deadline:</label>
            <input type="date" id="application_deadline" name="application_deadline" value="{{ $courseDetail->application_deadline }}" />
        </div>

        <input type="text" id="collegeid" name="college_id" value="{{ Auth::guard('college')->user()->id }}" hidden>

        <button type="submit" class="submit-btn">Update</button>
    </form>
</div>
@endsection