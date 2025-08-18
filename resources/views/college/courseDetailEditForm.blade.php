@extends('layouts.college')
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course Detail</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        h2 {
            margin-bottom: 24px;
            color: #1f2937;
            text-align: center;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
        }

        select,
        textarea,
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            background-color: #f9fafb;
            transition: border-color 0.2s ease;
        }

        select:focus,
        textarea:focus,
        input[type="text"]:focus {
            border-color: #6366f1;
            outline: none;
            background-color: #fff;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .submit-btn {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background-color: white;;
            color: black;
            font-weight: 600;
            font-size: 1rem;
            border: 2px solid black;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.25s ease;
        }

        .submit-btn:hover {
            background-color: black;
            color: white;
        }

        @media (max-width: 640px) {
            .form-container {
                padding: 25px 20px;
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
            <textarea id="description" name="description" rows="6" required>{{ $courseDetail->description }}</textarea>
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