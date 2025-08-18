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

        <!-- Structured fields -->
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
        <textarea id="description" name="description" rows="6" style="display:none;" required>{{ $courseDetail->description }}</textarea>

        <input type="text" id="collegeid" name="college_id" value="{{ Auth::guard('college')->user()->id }}" hidden>

        <button type="submit" class="submit-btn">Update</button>
    </form>
</div>
<script>
    (function() {
        function parseExistingDescription(text) {
            var map = {};
            if (!text) return map;
            var lines = text.split(/\r?\n/);
            lines.forEach(function(line) {
                var idx = line.indexOf(':');
                if (idx > -1) {
                    var key = line.slice(0, idx).trim().toLowerCase();
                    var val = line.slice(idx + 1).trim();
                    map[key] = val;
                }
            });
            return map;
        }

        function setIf(map, keyVariants, elId) {
            for (var i = 0; i < keyVariants.length; i++) {
                var v = map[keyVariants[i]];
                if (v) {
                    var el = document.getElementById(elId);
                    if (el) el.value = v;
                    return;
                }
            }
        }

        function appendIfPresent(parts, label, value) {
            if (value && value.trim()) {
                parts.push(label + ': ' + value.trim());
            }
        }

        var existing = document.getElementById('description').textContent || document.getElementById('description').value;
        var parsed = parseExistingDescription(existing);
        setIf(parsed, ['eligibility (+2)'], 'eligibility_plus_two');
        setIf(parsed, ['eligibility (bachelor)'], 'eligibility_bachelor');
        setIf(parsed, ['duration'], 'duration');
        setIf(parsed, ['seats'], 'seats');
        setIf(parsed, ['fees'], 'fees');
        setIf(parsed, ['scholarships'], 'scholarships');
        setIf(parsed, ['syllabus highlights'], 'syllabus_highlights');
        setIf(parsed, ['admission process'], 'admission_process');
        setIf(parsed, ['career paths'], 'career_paths');
        setIf(parsed, ['contact', 'contact info'], 'contact_info');

        var form = document.getElementById('editForm');
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
