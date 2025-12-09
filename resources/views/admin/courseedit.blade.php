@extends('layouts.admin')

@section('content')
@php
    $oldValues = old();
@endphp

<style>
    .course-form-wrapper {
        max-width: 1100px;
        margin: 0 auto;
    }
    .section-card {
        background: #fff;
        border-radius: 18px;
        padding: 32px;
        box-shadow: 0 12px 38px rgba(15, 23, 42, 0.08);
    }
    .form-heading {
        margin-bottom: 24px;
    }
    .form-heading h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        color: #111827;
    }
    .grid-two {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 18px 24px;
    }
    .form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
    }
    .form-control {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 12px 14px;
        font-size: 0.95rem;
    }
    .form-control:focus {
        border-color: #111827;
        box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.15);
    }
    textarea.form-control {
        min-height: 160px;
        resize: vertical;
    }
    .submit-row {
        margin-top: 24px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    .btn-primary-dark {
        background: white;
        color: black;
        border: 2px solid black;
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 600;
        font-size: 1rem;
        transition: background 0.2s ease;
        cursor: pointer;
    }
    .btn-primary-dark:hover {
        background: black;
        color: white;
    }
    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .btn-secondary:hover {
        background: #e5e7eb;
        color: #111827;
    }
    .error-text {
        color: #dc2626;
        font-size: 0.85rem;
        margin-top: 4px;
    }
</style>

<div class="course-form-wrapper">
    <div class="section-card">
        <div class="form-heading">
            <p class="text-muted mb-2">Admin • Course Management</p>
            <h1>Edit Course</h1>
        </div>
        <form action="{{ route('course.update', $course->id) }}" method="post">
            @csrf
            <div class="grid-two mb-4">
                <div>
                    <label for="field_name" class="form-label">Course Name</label>
                    <input type="text" class="form-control" id="field_name" name="fname" value="{{ old('fname', $course->name) }}" placeholder="e.g., Bachelor of Science in IT">
                    @error('fname') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="stream" class="form-label">Stream</label>
                    <input type="text" class="form-control" id="stream" name="stream" value="{{ old('stream', $course->stream) }}" placeholder="Science, Management, etc.">
                    @error('stream') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="substream" class="form-label">Sub Stream</label>
                    <input type="text" class="form-control" id="substream" name="substream" value="{{ old('substream', $course->substream) }}" placeholder="IT, Finance, Humanities...">
                    @error('substream') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="short_name" class="form-label">Short Name</label>
                    <input type="text" class="form-control" id="short_name" name="sname" value="{{ old('sname', $course->shortName) }}" placeholder="e.g., BScIT">
                    @error('sname') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="gpa_limit" class="form-label">Minimum GPA</label>
                    <input type="text" class="form-control" id="gpa_limit" name="gpalimit" value="{{ old('gpalimit', $course->gpa_limit) }}" placeholder="e.g., 2.4">
                    @error('gpalimit') <p class="error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="duration" class="form-label">Duration</label>
                    <input type="text" class="form-control" id="duration" name="duration" value="{{ old('duration', $course->duration) }}" placeholder="e.g., 4 years">
                    @error('duration') <p class="error-text">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" rows="6" name="description" placeholder="Provide a brief overview of the course objectives, ideal candidates, and outcomes.">{{ old('description', $course->description) }}</textarea>
                @error('description') <p class="error-text">{{ $message }}</p> @enderror
            </div>
            <div class="submit-row">
                <a href="{{ route('course.show') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary-dark">Update Course</button>
            </div>
        </form>
    </div>
</div>
@endsection

