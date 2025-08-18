@extends('layouts.college')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Detail Form</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 650px;
            margin: 50px auto;
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

        .helper-text {
            display: block;
            margin-top: 6px;
            color: #6b7280; /* Tailwind gray-500 */
            font-size: 0.9rem;
            line-height: 1.3;
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
    <h2>📄 Add Course Details</h2>
    <form id="myForm" action="{{ route('coursedetail.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="courseid">Select Course:</label>
            <select id="courseid" name="course_id" required>
                <option value="" selected disabled>Select a course</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Eligibility (+2):</label>
            <input type="text" id="eligibility_plus_two" placeholder="e.g., +2 Science/Management with minimum GPA 2.0" />
        </div>
        <div class="form-group">
            <label>Eligibility (Bachelor):</label>
            <input type="text" id="eligibility_bachelor" placeholder="e.g., Bachelor in related field with minimum CGPA 2.0" />
        </div>
        <div class="form-group">
            <label>Duration:</label>
            <input type="text" id="duration" placeholder="e.g., 4 years (8 semesters)" />
        </div>
        <div class="form-group">
            <label>Seats:</label>
            <input type="text" id="seats" placeholder="e.g., 120" />
        </div>
        <div class="form-group">
            <label>Fees:</label>
            <input type="text" id="fees" placeholder="e.g., NPR 150,000 per year" />
        </div>
        <div class="form-group">
            <label>Scholarships:</label>
            <input type="text" id="scholarships" placeholder="e.g., Merit-based up to 50%" />
        </div>
        <div class="form-group">
            <label>Syllabus Highlights:</label>
            <textarea id="syllabus_highlights" rows="3" placeholder="Core subjects and highlights"></textarea>
        </div>
        <div class="form-group">
            <label>Admission Process:</label>
            <textarea id="admission_process" rows="3" placeholder="Deadlines, required documents, entrance details"></textarea>
        </div>
        <div class="form-group">
            <label>Career Paths:</label>
            <textarea id="career_paths" rows="3" placeholder="Typical roles and industries"></textarea>
        </div>
        <div class="form-group">
            <label>Contact Info:</label>
            <input type="text" id="contact_info" placeholder="e.g., admissions@college.edu, +977-98xxxxxxx" />
        </div>

        <!-- Hidden field that backend expects -->
        <textarea id="description" name="description" rows="4" style="display:none;" required></textarea>

        <input type="text" id="collegeid" name="college_id" value="{{ Auth::guard('college')->user()->id }}" hidden>

        <button type="submit" class="submit-btn">
     Submit
        </button>
    </form>
</div>
<script>
    (function() {
        function appendIfPresent(parts, label, value) {
            if (value && value.trim()) {
                parts.push(label + ': ' + value.trim());
            }
        }

        var form = document.getElementById('myForm');
        if (form) {
            form.addEventListener('submit', function() {
                var parts = [];
                appendIfPresent(parts, 'Eligibility (+2)', document.getElementById('eligibility_plus_two').value);
                appendIfPresent(parts, 'Eligibility (Bachelor)', document.getElementById('eligibility_bachelor').value);
                appendIfPresent(parts, 'Duration', document.getElementById('duration').value);
                appendIfPresent(parts, 'Seats', document.getElementById('seats').value);
                appendIfPresent(parts, 'Fees', document.getElementById('fees').value);
                appendIfPresent(parts, 'Scholarships', document.getElementById('scholarships').value);
                appendIfPresent(parts, 'Syllabus Highlights', document.getElementById('syllabus_highlights').value);
                appendIfPresent(parts, 'Admission Process', document.getElementById('admission_process').value);
                appendIfPresent(parts, 'Career Paths', document.getElementById('career_paths').value);
                appendIfPresent(parts, 'Contact', document.getElementById('contact_info').value);

                document.getElementById('description').value = parts.join('\n');
            });
        }
    })();
</script>
@endsection
