@extends('layouts.app')

@section('content')
<style>
    .page-header {
        background: #f8f9fa;
        padding: 30px 0;
        margin-bottom: 30px;
        border-bottom: 1px solid #e9ecef;
        text-align: center;
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
    .rec-grid {
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
    }
    .rec-card {
        border: 1px solid #e9ecef; 
        border-radius: 8px; 
        background: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden; 
        display: flex; 
        flex-direction: column;
        transition: all 0.2s ease;
    }
    .rec-card:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .rec-card-header {
        padding: 20px; 
        border-bottom: 1px solid #e9ecef; 
        background: #f8f9fa;
    }
    .rec-title { 
        font-size: 1.2rem; 
        font-weight: 600; 
        margin: 0 0 8px 0; 
        color: #333; 
        line-height: 1.4;
    }
    .rec-badge { 
        background: #007bff; 
        color: white; 
        padding: 4px 12px; 
        border-radius: 4px; 
        font-size: 0.85rem; 
        font-weight: 500;
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
        font-weight: 500;
        color: #555;
    }
    .rec-muted { 
        color: #666; 
        font-size: 0.95rem; 
    }
    .rec-actions { 
        padding: 20px; 
        border-top: 1px solid #e9ecef; 
        background: #f8f9fa;
        display: flex; 
        gap: 8px; 
        flex-wrap: wrap;
    }
    .btn-small { 
        padding: 8px 16px; 
        border-radius: 4px; 
        font-size: 0.9rem; 
        border: 1px solid #dee2e6; 
        background: #fff; 
        color: #495057; 
        text-decoration: none; 
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .btn-small.primary { 
        background: #007bff; 
        color: #fff; 
        border-color: #007bff;
    }
    .btn-small:hover { 
        background: #f8f9fa;
        border-color: #adb5bd;
    }
    .btn-small.primary:hover {
        background: #0056b3;
        border-color: #0056b3;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
    }
    .empty-icon {
        font-size: 3rem;
        color: #6c757d;
        margin-bottom: 20px;
    }
    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 10px;
    }
    .empty-description {
        color: #6c757d;
        font-size: 1rem;
        margin-bottom: 20px;
    }
    .hint-list { 
        margin: 0; 
        color: #6c757d;
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
        color: #007bff;
        font-weight: bold;
    }
    @media (max-width: 768px) {
        .rec-grid {
            grid-template-columns: 1fr;
        }
        .page-header {
            padding: 20px 0;
        }
        .page-title {
            font-size: 1.5rem;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title">🎯 Courses You Might Like</h1>
        <p class="page-subtitle">Discover courses tailored to your interests and academic goals</p>
    </div>
</div>

<div class="container">
    @if(empty($ranked))
        <div class="empty-state">
            <div class="empty-icon">🔍</div>
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
                            <span class="rec-field-label">🏫 Institution:</span>
                            <span class="rec-muted">{{ optional($detail->college)->name ?? 'N/A' }}</span>
                        </div>
                        <div class="rec-field">
                            <span class="rec-field-label">💰 Tuition Fee:</span>
                            <span class="rec-muted">{{ $detail->tuition_fee ?? 'N/A' }}</span>
                        </div>
                        <div class="rec-field">
                            <span class="rec-field-label">👥 Available Seats:</span>
                            <span class="rec-muted">{{ $detail->seats ?? 'N/A' }}</span>
                        </div>
                        @if(!empty($detail->application_deadline))
                            <div class="rec-field">
                                <span class="rec-field-label">📅 Application Deadline:</span>
                                <span class="rec-muted">{{ \Carbon\Carbon::parse($detail->application_deadline)->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="rec-actions">
                        <a class="btn-small primary" href="{{ route('coursedetail.getById', ['id' => $detail->id]) }}">
                            👁️ View Course Details
                        </a>
                        <a class="btn-small" href="{{ route('course.getCollegeByCourseId', ['id' => $detail->course_id]) }}">
                            🏫 Explore Institution
                        </a>
                        <a class="btn-small" href="{{ route('home.inquiry.form', ['coursedetail_id' => $detail->id]) }}">
                            💬 Ask Questions
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection