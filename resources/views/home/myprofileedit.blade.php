@extends('layouts.app')
@section('content')

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
        max-width: 1000px;
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
    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-group label {
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
        display: block;
        font-size: 1rem;
    }

    .form-control,
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

    textarea {
        resize: vertical;
        min-height: 80px;
    }

    .btn-submit {
        background-color: white;
        color: black;
        border: 2px solid black;
        padding: 14px 40px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        display: inline-block;
    }

    .btn-submit:hover {
        background-color: black;
        color: white;
    }

    .btn-cancel {
        background-color: white;
        color: black;
        border: 2px solid black;
        padding: 14px 40px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        text-decoration: none;
        display: inline-block;
        margin-right: 15px;
    }

    .btn-cancel:hover {
        background-color: black;
        color: white;
        text-decoration: none;
    }

    .form-actions {
        text-align: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #ddd;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-input-label {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        border: 2px dashed #ddd;
        border-radius: 8px;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        justify-content: center;
        font-weight: 600;
        color: #666;
    }

    .file-input-label:hover {
        border-color: black;
        background: #f0f8ff;
    }

    .file-input {
        display: none;
    }

    .current-image {
        max-width: 100px;
        max-height: 100px;
        border-radius: 8px;
        border: 2px solid #ddd;
        margin-top: 10px;
    }

    .interest-checkboxes {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 10px;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 15px;
        background: #fff;
        border: 1.8px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .checkbox-item:hover {
        background: #f0f8ff;
        border-color: black;
    }

    .checkbox-item input[type="checkbox"] {
        width: auto;
        margin: 0;
    }

    .checkbox-item label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .form-card {
            padding: 30px 25px;
            margin: 2rem 1.5rem 3rem 1.5rem;
            max-width: 100%;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .form-section {
            margin-bottom: 25px;
        }

        button[type="submit"],
        .btn-cancel {
            padding: 14px 0;
            font-size: 8px;
            width: 100%;
            margin-bottom: 10px;
        }

        .btn-cancel {
            margin-right: 0;
        }
    }
</style>



<div class="container">
    <div class="form-card">
        <div class="form-title">Edit Profile</div>

                <form id="userForm" method="POST" action="{{ route('students.update', $student->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <!-- User Information Section -->
                    <div class="form-section">
                        <h3>Personal Information</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" placeholder="Enter your full name" value="{{ $student->name }}" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" placeholder="Enter your email address" value="{{ $student->email }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact">Contact Number</label>
                                <input type="tel" id="contact" name="contact" placeholder="Enter your contact number" value="{{ $student->contact }}" required>
                            </div>

                            <div class="form-group">
                                <label for="image">Profile Picture</label>
                                <div class="file-input-wrapper">
                                    <label for="image" class="file-input-label">
                                        Choose Profile Picture
                                    </label>
                                    <input type="file" id="image" name="image" class="file-input" accept="image/*">
                                </div>
                                @if($student->image)
                                    <div>
                                        <small class="text-muted">Current image:</small>
                                        <img src="{{ asset('storage/uploads/' . $student->image) }}" alt="Current Profile" class="current-image">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Academic Details Section -->
                    <div class="form-section">
                        <h3>Academic Information</h3>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="educationLevel">Education Level</label>
                                <select id="educationLevel" name="educationLevel" required>
                                    <option value="">Select Education Level</option>
                                    <option value="SEE" @if($student->educationLevel === 'SEE') selected @endif>SEE (School Leaving Certificate)</option>
                                    <option value="+2" @if($student->educationLevel === '+2') selected @endif>+2 (Higher Secondary)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="passedYear">Passed Year</label>
                                <input type="text" id="passedYear" name="passedyear" placeholder="e.g., 2023" value="{{ $student->passedyear }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="previousschool">Previous School/College</label>
                                <input type="text" id="previousschool" name="previousschool" placeholder="Enter your previous school/college name" value="{{ $student->previousschool }}" required>
                            </div>

                            <div class="form-group">
                                <label for="gpa">GPA/Percentage</label>
                                <input type="text" id="gpa" name="gpa" placeholder="Enter your GPA or percentage" value="{{ $student->gpa }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Interests Section -->
                    <div class="form-section">
                        <h3>Areas of Interest</h3>
                        <div class="form-group">
                            <label>Select your areas of interest (you can select multiple)</label>
                            <div class="interest-checkboxes">
                                <div class="checkbox-item">
                                    <input type="checkbox" name="interest[]" value="biology" id="biology" {{ in_array('biology', explode(',', $student['interest'] ?? '')) ? 'checked' : '' }}>
                                    <label for="biology">Biology</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="interest[]" value="physics" id="physics" {{ in_array('physics', explode(',', $student['interest'] ?? '')) ? 'checked' : '' }}>
                                    <label for="physics">Physics</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="interest[]" value="science" id="science" {{ in_array('science', explode(',', $student['interest'] ?? '')) ? 'checked' : '' }}>
                                    <label for="science">Science</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="interest[]" value="computer" id="computer" {{ in_array('computer', explode(',', $student['interest'] ?? '')) ? 'checked' : '' }}>
                                    <label for="computer">Computer</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="interest[]" value="hotel" id="hotel" {{ in_array('hotel', explode(',', $student['interest'] ?? '')) ? 'checked' : '' }}>
                                    <label for="hotel">Hotel Management</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="interest[]" value="business" id="business" {{ in_array('business', explode(',', $student['interest'] ?? '')) ? 'checked' : '' }}>
                                    <label for="business">Business</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Goals Section -->
                    <div class="form-section">
                        <h3>Career Goals</h3>
                        <div class="form-group">
                            <label for="goals">Describe your career goals and aspirations</label>
                            <textarea id="goals" name="goal" rows="4" placeholder="Tell us about your career goals, what you want to achieve, and how you plan to reach your objectives..." required>{{ $student->goal }}</textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="/myprofile" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-submit">Update Profile</button>
                    </div>
                </form>
    </div>
</div>

<script>
    // File input preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const existingPreview = document.querySelector('.image-preview');
                if (existingPreview) {
                    existingPreview.remove();
                }
                
                const preview = document.createElement('div');
                preview.className = 'image-preview';
                preview.innerHTML = `
                    <div style="margin-top: 10px;">
                        <small class="text-muted">New image preview:</small>
                        <img src="${e.target.result}" alt="Preview" class="current-image">
                    </div>
                `;
                document.querySelector('.file-input-wrapper').appendChild(preview);
            };
            reader.readAsDataURL(file);
        }
    });
</script>

@endsection