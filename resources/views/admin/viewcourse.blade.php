@extends('layouts.admin')
@section('content')

<!-- Font Awesome CDN for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        background-color: #f5f7fa;
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .view-container {
        max-width: 1100px;
        margin: 40px auto;
        background: #ffffff;
        padding: 40px;
        border-radius: 18px;
        box-shadow: 0 12px 38px rgba(15, 23, 42, 0.08);
    }

    .view-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f2f5;
    }

    .view-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .action-buttons-header {
        display: flex;
        gap: 12px;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s ease;
        border: none;
        cursor: pointer;
    }

    .btn-edit {
        background: white;
        color: #10b981;
        border: 2px solid #10b981;
    }

    .btn-edit:hover {
        background: #10b981;
        color: white;
        transform: translateY(-1px);
    }

    .btn-back {
        background: #f3f4f6;
        color: #374151;
        border: 2px solid #e5e7eb;
    }

    .btn-back:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .info-card {
        background: #f9fafb;
        border-radius: 12px;
        padding: 20px;
        border-left: 4px solid #111827;
        transition: all 0.2s ease;
    }

    .info-card:hover {
        background: #f3f4f6;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .info-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #111827;
        word-break: break-word;
    }

    .description-section {
        margin-top: 32px;
        padding-top: 32px;
        border-top: 2px solid #f0f2f5;
    }

    .description-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    .description-content {
        background: #f9fafb;
        border-radius: 12px;
        padding: 24px;
        font-size: 1rem;
        line-height: 1.7;
        color: #374151;
        min-height: 120px;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    @media (max-width: 768px) {
        .view-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .action-buttons-header {
            width: 100%;
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="view-container">
    <div class="view-header">
        <h1>📚 Course Details</h1>
        <div class="action-buttons-header">
            <a href="{{ route('course.show') }}" class="btn-action btn-back">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
            <a href="{{ route('course.edit', $course->id) }}" class="btn-action btn-edit">
                <i class="fas fa-edit"></i>
                Edit Course
            </a>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <div class="info-label">Course Name</div>
            <div class="info-value">{{ $course->name ?? 'N/A' }}</div>
        </div>
        <div class="info-card">
            <div class="info-label">Stream</div>
            <div class="info-value">{{ $course->stream ?? 'N/A' }}</div>
        </div>
        <div class="info-card">
            <div class="info-label">Sub Stream</div>
            <div class="info-value">{{ $course->substream ?? 'N/A' }}</div>
        </div>
        <div class="info-card">
            <div class="info-label">Short Name</div>
            <div class="info-value">{{ $course->shortName ?? 'N/A' }}</div>
        </div>
        <div class="info-card">
            <div class="info-label">GPA Limit</div>
            <div class="info-value">{{ $course->gpa_limit ?? 'N/A' }}</div>
        </div>
        <div class="info-card">
            <div class="info-label">Duration</div>
            <div class="info-value">{{ $course->duration ?? 'N/A' }}</div>
        </div>
    </div>

    <div class="description-section">
        <div class="description-label">Description</div>
        <div class="description-content">{{ $course->description ?? 'No description available.' }}</div>
    </div>
</div>

@endsection

