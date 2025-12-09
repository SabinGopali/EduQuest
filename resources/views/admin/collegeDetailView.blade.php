@extends('layouts.admin')

@section('content')
@php
    $statusColorMap = [
        'APPROVED' => 'status-approved',
        'PENDING' => 'status-pending',
        'REJECTED' => 'status-rejected',
    ];
    $statusClass = $statusColorMap[$college->status] ?? 'status-default';
@endphp

<style>
    .detail-layout {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 28px;
    }
    .section-card {
        background: #fff;
        border-radius: 18px;
        padding: 28px;
        box-shadow: 0 12px 38px rgba(15, 23, 42, 0.08);
    }
    .hero-card {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        align-items: center;
    }
    .logo-ring {
        width: 120px;
        height: 120px;
        border-radius: 18px;
        border: 2px dashed #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
    }
    .hero-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .logo-placeholder {
        font-size: 0.85rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .hero-actions {
        margin-left: auto;
        text-align: right;
    }
    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 999px;
        font-weight: 600;
        letter-spacing: 0.02em;
        font-size: 0.95rem;
    }
    .status-approved { background: #dcfce7; color: #166534; }
    .status-pending { background: #fef9c3; color: #92400e; }
    .status-rejected { background: #fee2e2; color: #b91c1c; }
    .status-default { background: #e0e7ff; color: #3730a3; }
    .meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 18px;
        margin-top: 18px;
    }
    .meta-label {
        text-transform: uppercase;
        font-size: 0.78rem;
        color: #94a3b8;
        letter-spacing: 0.08em;
    }
    .meta-value {
        font-size: 1.05rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }
    .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .section-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .section-body {
        color: #475569;
        line-height: 1.7;
        font-size: 1rem;
    }
    .course-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 18px;
    }
    .course-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        background: #fafafa;
        min-height: 220px;
    }
    .course-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .course-stream {
        font-size: 0.95rem;
        color: #475569;
    }
    .course-actions {
        margin-top: auto;
    }
    .btn-outline-dark {
        border: 2px solid #111827;
        border-radius: 10px;
        padding: 8px 16px;
        font-weight: 600;
    }
    .btn-outline-dark:hover {
        background: #111827;
        color: #fff;
    }
    .empty-state {
        text-align: center;
        padding: 30px;
        border: 2px dashed #cbd5f5;
        border-radius: 16px;
        color: #94a3b8;
        font-weight: 600;
        background: #f8fafc;
    }
    .gallery-wrapper {
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .gallery-wrapper img {
        width: 100%;
        height: 420px;
        object-fit: cover;
    }
    @media (max-width: 768px) {
        .hero-card {
            text-align: center;
        }
        .hero-actions {
            width: 100%;
            text-align: center;
            margin-left: 0;
        }
        .meta-grid {
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        }
        .course-card {
            min-height: unset;
        }
        .gallery-wrapper img {
            height: 260px;
        }
    }
</style>

<div class="detail-layout">
    <div class="section-card hero-card">
        <div class="logo-ring mx-auto mx-md-0">
            @if($college->logo)
                <img
                    src="{{ asset('storage/' . $college->logo) }}"
                    alt="{{ $college->name }} logo"
                    style="width: 90px; height: 90px; object-fit: contain;">
            @else
                <span class="logo-placeholder">No Logo</span>
            @endif
        </div>
        <div class="flex-grow-1">
            <h1 class="hero-title">{{ $college->name }}</h1>
            <p class="text-muted mb-2">{{ $college->address ?? 'Address not available' }}</p>
            <div class="meta-grid">
                <div>
                    <p class="meta-label">Contact</p>
                    <p class="meta-value">{{ $college->contact ?? '—' }}</p>
                </div>
                <div>
                    <p class="meta-label">Email</p>
                    <p class="meta-value">{{ $college->email ?? '—' }}</p>
                </div>
                <div>
                    <p class="meta-label">Status</p>
                    <p class="meta-value">{{ ucfirst(strtolower($college->status)) }}</p>
                </div>
            </div>
        </div>
        <div class="hero-actions">
            <span class="status-chip {{ $statusClass }}">
                <i class="fas fa-circle" style="font-size: 0.55rem;"></i>
                {{ ucfirst(strtolower($college->status)) }}
            </span>
            @if($college->status === 'PENDING')
                <div class="mt-3 d-flex gap-2 flex-wrap justify-content-md-end">
                    <a href="{{ route('admin.college.approve', $college->id) }}" class="btn btn-dark">Approve</a>
                    <a href="{{ route('admin.college.reject', $college->id) }}" class="btn btn-outline-danger">Reject</a>
                </div>
            @endif
        </div>
    </div>

    <div class="section-card">
        <div class="section-heading">
            <h2 class="section-title">About the College</h2>
        </div>
        <div class="section-body">
            {!! $college->description ? nl2br(e($college->description)) : '<p class="text-muted mb-0">No description available.</p>' !!}
        </div>
    </div>

    <div class="section-card">
        <div class="section-heading">
            <h2 class="section-title">Courses Offered</h2>
        </div>
        @if($college->courseDetails->isEmpty())
            <p class="empty-state">No courses have been added for this college yet.</p>
        @else
            <div class="course-grid">
                @foreach ($college->courseDetails as $courseDetail)
                    <div class="course-card">
                        <h3 class="course-name">{{ $courseDetail->course->name }}</h3>
                        <p class="course-stream">{{ $courseDetail->course->stream }}</p>
                        <p class="course-stream text-muted mb-2">{{ $courseDetail->course->subStream }}</p>
                        <div class="course-actions">
                            <a href="/college/detail/course/description/{{ $courseDetail->id }}" class="btn btn-outline-dark w-100">
                                View Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="section-card">
        <div class="section-heading">
            <h2 class="section-title">Gallery</h2>
        </div>
        @if($college->images->isEmpty())
            <p class="empty-state mb-0">No gallery images uploaded yet.</p>
        @else
            <div id="collegeGallery" class="carousel slide gallery-wrapper" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">
                    @foreach($college->images as $gallery)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            <img src="{{ asset('storage/'. $gallery->path) }}" alt="Gallery image {{ $loop->iteration }}">
                        </div>
                    @endforeach
                </div>
                @if($college->images->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#collegeGallery" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#collegeGallery" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection