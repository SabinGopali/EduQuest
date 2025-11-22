@extends('layouts.app')
@section('content')

<style>
    .page-header {
        background: #f8f9fa;
        padding: 30px 0;
        margin-bottom: 30px;
        border-bottom: 1px solid #e9ecef;
    }
    .page-title {
        font-size: 2rem;
        font-weight: 600;
        color: #333;
        margin: 0 0 10px 0;
    }
    .page-subtitle {
        color: #666;
        font-size: 1.1rem;
        margin: 0;
    }
    .form-card {
        background: #fff;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: 1px solid #e9ecef;
    }
    .form-title {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 30px;
        color: #333;
        text-align: center;
        border-bottom: 2px solid #007bff;
        padding-bottom: 15px;
    }
    .form-section {
        margin-bottom: 35px;
        padding: 25px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }
    .form-section h3 {
        font-size: 1.3rem;
        font-weight: 600;
        color: #007bff;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-section h3::before {
        content: '';
        width: 4px;
        height: 20px;
        background: #007bff;
        border-radius: 2px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group:last-child {
        margin-bottom: 0;
    }
    .form-group label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        display: block;
        font-size: 0.95rem;
    }
    .form-control,
    input,
    textarea,
    select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 0.95rem;
        color: #495057;
        background: #fff;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }
    .form-control:focus,
    input:focus,
    textarea:focus,
    select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        background: #fff;
    }
    .form-control::placeholder,
    input::placeholder,
    textarea::placeholder {
        color: #6c757d;
        font-weight: 400;
    }
    .btn-submit {
        background: #007bff;
        color: #fff;
        border: none;
        padding: 12px 30px;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-submit:hover {
        background: #0056b3;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }
    .btn-cancel {
        background: #6c757d;
        color: #fff;
        border: none;
        padding: 12px 30px;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-right: 15px;
    }
    .btn-cancel:hover {
        background: #5a6268;
        transform: translateY(-1px);
        color: #fff;
        text-decoration: none;
    }
    .form-actions {
        text-align: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
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
        padding: 12px 16px;
        border: 2px dashed #dee2e6;
        border-radius: 6px;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        justify-content: center;
    }
    .file-input-label:hover {
        border-color: #007bff;
        background: #f0f8ff;
    }
    .file-input {
        display: none;
    }
    .current-image {
        max-width: 100px;
        max-height: 100px;
        border-radius: 8px;
        border: 2px solid #e9ecef;
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
        border: 1px solid #dee2e6;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .checkbox-item:hover {
        background: #f8f9fa;
        border-color: #007bff;
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
            padding: 20px;
        }
        .form-row {
            grid-template-columns: 1fr;
        }
        .page-header {
            padding: 20px 0;
        }
        .page-title {
            font-size: 1.5rem;
        }
        .form-section {
            padding: 20px;
        }
    }
</style>



<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                <div class="form-title">Profile Information</div>

                <form id="userForm" method="POST" action="{{ route('students.update', $student->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <!-- User Information Section -->
                    <div class="form-section">
                        <h3>👤 Personal Information</h3>

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
                                        📷 Choose Profile Picture
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
                        <h3>🎓 Academic Information</h3>

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
                        <h3>🎯 Areas of Interest</h3>
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
                        <h3>🎯 Career Goals</h3>
                        <div class="form-group">
                            <label for="goals">Describe your career goals and aspirations</label>
                            <textarea id="goals" name="goal" rows="4" placeholder="Tell us about your career goals, what you want to achieve, and how you plan to reach your objectives..." required>{{ $student->goal }}</textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="/myprofile" class="btn-cancel">Cancel</a>
                        <button type="submit" class="btn-submit">
                            💾 Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
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