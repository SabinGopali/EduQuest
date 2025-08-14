@extends('layouts.app')
@section('content')

<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #f4f6f8;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .course-container {
        max-width: 1100px;
        margin: 60px auto;
        padding: 40px 30px;
        background-color: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
    }

    .course-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .course-header h1 {
        font-size: 36px;
        font-weight: 700;
        color: black;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 500;
        margin-bottom: 30px;
        text-align: center;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 30px;
    }

    .info-card {
        background: #f9f9f9;
        border-left: 6px solid black;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.03);
    }

    .info-card h3 {
        margin: 0 0 10px;
        color: black;
        font-size: 18px;
    }

    .info-card p {
        margin: 0;
        font-size: 16px;
        color: #333;
    }

    .description-box {
        background: #eef1f2;
        padding: 25px;
        border-radius: 10px;
        border-left: 6px solid black;
        font-size: 16px;
        color: #333;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .btn-primary {
        background-color: white;
        color: black;
        padding: 12px 30px;
        font-size: 16px;
        border: 2px solid black;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
        display: inline-block;
        margin-top: 30px;
        text-decoration: none;
    }

    .btn-primary:hover {
        background-color: black;
        color: white;
    }

    @media (max-width: 768px) {
        .course-container {
            margin: 30px 15px;
            padding: 30px 20px;
        }

        .course-header h1 {
            font-size: 28px;
        }
    }
</style>

<div class="course-container">
    <div class="course-header">
        <h1>Course Description</h1>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="info-grid">
        <div class="info-card">
            <h3>Field Name</h3>
            <p>{{ $courseDetail->course->name }}</p>
        </div>

        <div class="info-card">
            <h3>Short Name</h3>
            <p>{{ $courseDetail->course->shortName }}</p>
        </div>

        <div class="info-card">
            <h3>Stream</h3>
            <p>{{ $courseDetail->course->stream }}</p>
        </div>

        <div class="info-card">
            <h3>Substream</h3>
            <p>{{ $courseDetail->course->subStream }}</p>
        </div>

        <div class="info-card">
            <h3>GPA Limit</h3>
            <p>{{ $courseDetail->course->gpa_limit }}</p>
        </div>

        <div class="info-card">
            <h3>Duration</h3>
            <p>{{ $courseDetail->course->duration }}</p>
        </div>
    </div>

    <div class="description-box">
        <strong>Description:</strong><br>
        {{ $courseDetail->course->description }}
    </div>

    <div class="description-box">
        <strong>Our Description:</strong><br>
        {{ $courseDetail->description }}
    </div>

    <a href="{{ route('home.inquiry.form', $courseDetail->id) }}" class="btn-primary">Inquiry</a>
</div>

@endsection
