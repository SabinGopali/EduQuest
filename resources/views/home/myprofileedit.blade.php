@extends('layouts.app')
@section('content')

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .form-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .form-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #000;
            text-align: center;
        }

        .form-group label {
            font-weight: 700;
            color: #000;
            margin-bottom: 6px;
            display: block;
        }

        input,
        textarea,
        select {
            font-weight: 400;
            color: #000;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #007BFF;
            box-shadow: 0 0 6px rgba(0, 123, 255, 0.4);
            background-color: #f0f8ff;
        }

        input::placeholder,
        textarea::placeholder {
            font-weight: 700;
            color: #000;
        }

        button[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .form-section {
            margin-bottom: 30px;
        }

        .form-section h3 {
            font-weight: 700;
            color: #007BFF;
            margin-bottom: 15px;
        }
    </style>
</head>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-title">Update Student</div>

                <form id="userForm" method="POST" action="{{ route('students.update', $student->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <!-- User Information Section -->
                    <div class="form-section">
                        <h3>User Information</h3>

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" placeholder="Enter Name" value="{{ $student->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter Email Address" value="{{ $student->email }}" required>
                        </div>

                        <div class="form-group">
                            <label for="contact">Contact Number</label>
                            <input type="tel" id="contact" name="contact" placeholder="Enter Contact Number" value="{{ $student->contact }}" required>
                        </div>

                        <div class="form-group">
                            <label for="image">Upload Image</label>
                            <input type="file" id="image" name="image">
                        </div>
                    </div>

                    <!-- Academic Details Section -->
                    <div class="form-section">
                        <h3>Academic Details</h3>

                        <div class="form-group">
                            <label for="educationLevel">Education Level</label>
                            <select id="educationLevel" name="educationLevel" required>
                                <option value="SEE" @if($student->educationLevel === 'SEE') selected @endif>SEE</option>
                                <option value="+2" @if($student->educationLevel === '+2') selected @endif>+2</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="passedYear">Passed Year</label>
                            <input type="text" id="passedYear" name="passedyear" placeholder="Enter Passed Year" value="{{ $student->passedyear }}" required>
                        </div>

                        <div class="form-group">
                            <label for="previouscollege">Previous College/School</label>
                            <input type="text" id="previousschool" name="previousschool" placeholder="Enter Previous College/School" value="{{ $student->previousschool }}" required>
                        </div>

                        <div class="form-group">
                            <label for="gpa">GPA</label>
                            <input type="text" id="gpa" name="gpa" placeholder="Enter GPA" value="{{ $student->gpa }}" required>
                        </div>
                    </div>

                    <!-- Interests Section -->
                    <div class="form-section">
                        <h3>Interests</h3>
                        <div class="form-group">
                            <select class="form-control" name="interest[]" id="interest" multiple>
                                <option value="biology" {{ in_array('biology', explode(',', $student['interest'] ?? '')) ? 'selected' : '' }}>Biology</option>
                                <option value="physics" {{ in_array('physics', explode(',', $student['interest'] ?? '')) ? 'selected' : '' }}>Physics</option>
                                <option value="science" {{ in_array('science', explode(',', $student['interest'] ?? '')) ? 'selected' : '' }}>Science</option>
                                <option value="computer" {{ in_array('computer', explode(',', $student['interest'] ?? '')) ? 'selected' : '' }}>Computer</option>
                                <option value="hotel" {{ in_array('hotel', explode(',', $student['interest'] ?? '')) ? 'selected' : '' }}>Hotel</option>
                                <option value="business" {{ in_array('business', explode(',', $student['interest'] ?? '')) ? 'selected' : '' }}>Business</option>
                            </select>
                        </div>
                    </div>

                    <!-- Goals Section -->
                    <div class="form-section">
                        <h3>Goals</h3>
                        <div class="form-group">
                            <textarea id="goals" name="goal" rows="3" placeholder="Enter Goals" required>{{ $student->goal }}</textarea>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
