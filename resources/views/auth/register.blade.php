@extends('layouts.app')
@section('content')

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <style>
        body {
            background-color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-card {
            background: #fff;
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: 3rem auto 5rem auto;
            transition: box-shadow 0.3s ease;
        }
        .form-card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .form-title {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 30px;
            color: #222;
            text-align: center;
            letter-spacing: 1px;
        }

        .form-section {
            margin-bottom: 35px;
        }
        .form-section h3 {
            font-weight: 700;
            color: red;
            margin-bottom: 20px;
            border-bottom: 2px solid black;
            padding-bottom: 6px;
            letter-spacing: 0.05em;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
            display: block;
            font-size: 1rem;
        }

        input,
        textarea,
        select {
            font-weight: 400;
            color: #444;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1.8px solid #ddd;
            width: 100%;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input::placeholder,
        textarea::placeholder {
            font-weight: 600;
            color: #999;
            opacity: 1;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: black;
            
            background-color: #f0f8ff;
        }

        select[multiple] {
            height: 120px;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        button[type="submit"] {
            background-color: white;
            color: black;
            border: none;
            padding: 14px 40px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
            border: 2px solid black;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            
        }

        button[type="submit"]:hover {
            background-color: black;
            color: white;
            
        }

        .form-link {
            margin-top: 30px;
            text-align: center;
            font-weight: 600;
            font-size: 1rem;
        }

        .form-link a {
            color: red;
            text-decoration: none;
            border-bottom: 1.5px solid transparent;
            transition: border-color 0.3s ease;
        }

        .form-link a:hover {
            border-color: black;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-card {
                padding: 30px 25px;
                margin: 2rem 1.5rem 3rem 1.5rem;
                max-width: 100%;
            }

            button[type="submit"] {
                padding: 14px 0;
                font-size: 16px;
            }
        }
    </style>
</head>

<div class="container">
    <div class="form-card">
        <div class="form-title">Student Signup Form</div>

        <form id="userForm" method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- User Information Section -->
            <div class="form-section">
                <h3>User Information</h3>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter Name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter Email Address" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="text" id="password" name="password" placeholder="Enter Password" required>
                </div>

                <div class="form-group">
                    <label for="contact">Contact Number</label>
                    <input type="tel" id="contact" name="contact" placeholder="Enter Contact Number" required>
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
                        <option value="SEE">SEE</option>
                        <option value="+2">+2</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="passedYear">Passed Year</label>
                    <input type="text" id="passedYear" name="passedYear" placeholder="Enter Passed Year" required>
                </div>

                <div class="form-group">
                    <label for="previouscollege">Previous College/School</label>
                    <input type="text" id="previouscollege" name="previouscollege" placeholder="Enter Previous College/School" required>
                </div>

                <div class="form-group">
                    <label for="gpa">GPA</label>
                    <input type="text" id="gpa" name="gpa" placeholder="Enter GPA" required>
                </div>
            </div>

            <!-- Interests Section -->
            <div class="form-section">
                <h3>Interests</h3>
                <div class="form-group">
                    <select class="form-control" name="interest[]" id="interest" multiple required>
                        <option value="biology">Biology</option>
                        <option value="physics">Physics</option>
                        <option value="science">Science</option>
                        <option value="computer">Computer</option>
                        <option value="hotel">Hotel</option>
                        <option value="business">Business</option>
                    </select>
                </div>
            </div>

            <!-- Goals Section -->
            <div class="form-section">
                <h3>Goals</h3>
                <div class="form-group">
                    <textarea id="goals" name="goals" rows="3" placeholder="Enter Goals" required></textarea>
                </div>
            </div>

            <button class="btn-submit" type="submit">Submit</button>

            <div class="form-link">
                <a href="/college-signup">Sign up as College</a>
            </div>
        </form>
    </div>
</div>

@endsection
