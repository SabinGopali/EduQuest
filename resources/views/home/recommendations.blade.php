@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: white;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Hero Banner */
    .home {
        position: relative;
        height: 280px;
        background-color: #111;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100vw;
        margin-left: calc(-50vw + 50%);
        margin-right: calc(-50vw + 50%);
        margin-top: 0;
        margin-bottom: 0;
    }

    .home_background {
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: url('{{ asset('img/courseyoulike.jpg') }}');
        background-size: cover;
        background-position: center;
        filter: brightness(0.9);
        z-index: 1;
    }

    .intro_section {
        max-width: 1000px;
        margin: 50px auto 20px auto;
        text-align: center;
        padding: 0 20px;
    }

    .intro_section h2 {
        font-size: 36px;
        font-weight: 700;
        color: #1b4d3e;
        margin-bottom: 12px;
    }

    .intro_section p {
        font-size: 18px;
        color: #4a5a48;
        line-height: 1.6;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .rec-grid {
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        margin-bottom: 60px;
    }
    .rec-card {
        border: 1px solid #ddd; 
        border-radius: 10px; 
        background: #fff;
        box-shadow: 0 4px 10px rgb(0 0 0 / 0.1);
        overflow: hidden; 
        display: flex; 
        flex-direction: column;
        transition: box-shadow 0.3s ease;
    }
    .rec-card:hover { 
        box-shadow: 0 8px 20px rgb(0 0 0 / 0.15);
    }
    .rec-card-header {
        padding: 20px; 
        border-bottom: 1px solid #ddd; 
        background: #fff;
    }
    .rec-title { 
        font-size: 1.3rem; 
        font-weight: 700; 
        margin: 0 0 12px 0; 
        color: #222; 
        line-height: 1.4;
    }
    .rec-badge { 
        background: black; 
        color: white; 
        padding: 6px 14px; 
        border-radius: 6px; 
        font-size: 0.85rem; 
        font-weight: 600;
        display: inline-block;
    }
    .rec-body { 
        padding: 20px; 
        flex-grow: 1;
    }
    .rec-field {
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .rec-field:last-child {
        margin-bottom: 0;
    }
    .rec-field-label {
        font-weight: 600;
        color: #333;
    }
    .rec-muted { 
        color: #666; 
        font-size: 0.95rem; 
    }
    .rec-actions { 
        padding: 20px; 
        border-top: 1px solid #ddd; 
        background: #fff;
        display: flex; 
        gap: 10px; 
        flex-wrap: wrap;
    }
    .btn-small { 
        padding: 10px 20px; 
        border-radius: 6px; 
        font-size: 0.9rem; 
        border: 2px solid black; 
        background: white; 
        color: black; 
        text-decoration: none; 
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
    }
    .btn-small.primary { 
        background: white; 
        color: black; 
        border-color: black;
    }
    .btn-small:hover { 
        background: black;
        color: white;
        border-color: black;
    }
    .btn-small.primary:hover {
        background: black;
        color: white;
        border-color: black;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border: 2px dashed #ddd;
        border-radius: 10px;
        max-width: 800px;
        margin: 0 auto;
    }
    .empty-icon {
        font-size: 3rem;
        color: #666;
        margin-bottom: 20px;
    }
    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 10px;
    }
    .empty-description {
        color: #666;
        font-size: 1rem;
        margin-bottom: 20px;
    }
    .hint-list { 
        margin: 0; 
        color: #666;
        text-align: left;
        max-width: 300px;
        margin: 0 auto;
    }
    .hint-list li { 
        margin: 8px 0;
        padding-left: 20px;
        position: relative;
    }
    .hint-list li::before {
        content: '•';
        position: absolute;
        left: 0;
        color: black;
        font-weight: bold;
    }
    @media (max-width: 768px) {
        .rec-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        .container {
            padding: 0 15px;
        }
        .rec-actions {
            flex-direction: column;
        }
        .btn-small {
            width: 100%;
            text-align: center;
        }
        .home {
            height: 220px;
            width: 100vw;
            margin-left: calc(-50vw + 50%);
            margin-right: calc(-50vw + 50%);
        }
        .home h1 {
            font-size: 32px !important;
        }
        .home p {
            font-size: 16px !important;
        }
        .intro_section h2 {
            font-size: 28px;
        }
        .intro_section p {
            font-size: 16px;
        }
    }
</style>

<!-- Hero Banner -->
<div class="home">
    <div class="home_background"></div>
    <div style="
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.45);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        text-align: center;
        z-index: 2;
        padding: 0 20px;
        color: #fff;
    ">
        <h1 style="font-size: 42px; font-weight: 800; margin: 0; text-transform: uppercase;">Personalized Course Recommendations</h1>
        <p style="font-size: 18px; margin-top: 12px; max-width: 600px; color:white;">
            Discover courses recommended specifically for you based on collaborative filtering and your preferences.
        </p>
    </div>
</div>

<!-- Intro Section -->
<div class="intro_section">
    <h2>Courses You Might Like</h2>
    
</div>

<div class="container">
    @if(empty($ranked))
        <div class="empty-state">
            <div class="empty-icon">📋</div>
            <h3 class="empty-title">No Course Recommendations Yet</h3>
            <p class="empty-description">
                We're working on finding the perfect courses for you! Help us understand your preferences better.
            </p>
            <ul class="hint-list">
                <li>Complete your profile with your interests and goals</li>
                <li>Browse and explore different course categories</li>
                <li>Book courses that catch your attention</li>
                <li>Check back here for personalized suggestions</li>
            </ul>
        </div>
    @else
        <div class="rec-grid">
            @foreach($ranked as $item)
                @php
                    $detail = $item['detail'];
                    $score = $item['score'];
                    $courseTitle = optional($detail->course)->title ?? (optional($detail->course)->name ?? 'Course');
                @endphp
                <div class="rec-card">
                    <div class="rec-card-header">
                        <h4 class="rec-title">{{ $courseTitle }}</h4>
                        <span class="rec-badge">Perfect Match {{ number_format($score * 100, 1) }}%</span>
                    </div>
                    <div class="rec-body">
                        <div class="rec-field">
                            <span class="rec-field-label">Institution:</span>
                            <span class="rec-muted">{{ optional($detail->college)->name ?? 'N/A' }}</span>
                        </div>
                        <div class="rec-field">
                            <span class="rec-field-label">Tuition Fee:</span>
                            <span class="rec-muted">{{ $detail->tuition_fee ?? 'N/A' }}</span>
                        </div>
                        <div class="rec-field">
                            <span class="rec-field-label">Available Seats:</span>
                            <span class="rec-muted">{{ $detail->seats ?? 'N/A' }}</span>
                        </div>
                        @if(!empty($detail->application_deadline))
                            <div class="rec-field">
                                <span class="rec-field-label">Application Deadline:</span>
                                <span class="rec-muted">{{ \Carbon\Carbon::parse($detail->application_deadline)->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="rec-actions">
                        <a class="btn-small primary" href="{{ route('coursedetail.getById', ['id' => $detail->id]) }}">
                            View Course Details
                        </a>
                        <a class="btn-small" href="{{ route('course.getCollegeByCourseId', ['id' => $detail->course_id]) }}">
                            Explore Institution
                        </a>
                        <a class="btn-small" href="{{ route('home.inquiry.form', ['coursedetail_id' => $detail->id]) }}">
                            Ask Questions
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection