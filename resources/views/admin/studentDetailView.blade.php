@extends('layouts.admin')

@section('content')
<style>
    .detail-layout {
        max-width: 1100px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 24px;
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
    .avatar-frame {
        width: 140px;
        height: 140px;
        border-radius: 20px;
        border: 2px dashed #d1d5db;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f8fafc;
    }
    .avatar-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-placeholder {
        font-size: 0.85rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .hero-body {
        flex: 1;
        min-width: 240px;
    }
    .hero-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .hero-subtitle {
        color: #64748b;
        margin-bottom: 16px;
    }
    .badge-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 12px;
    }
    .pill {
        padding: 6px 14px;
        border-radius: 999px;
        background: #f1f5f9;
        font-weight: 600;
        font-size: 0.85rem;
        color: #0f172a;
    }
    .meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
    }
    .meta-label {
        text-transform: uppercase;
        font-size: 0.75rem;
        color: #94a3b8;
        letter-spacing: 0.08em;
        margin-bottom: 4px;
    }
    .meta-value {
        font-size: 1.05rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }
    .section-title {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: #0f172a;
    }
    .section-body {
        color: #475569;
        line-height: 1.7;
        font-size: 1rem;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
    }
    .info-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        background: #f9fafb;
    }
    .info-card h4 {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94a3b8;
        margin-bottom: 6px;
    }
    .info-card p {
        margin: 0;
        font-weight: 600;
        color: #111827;
    }
    .empty-state {
        padding: 18px;
        border: 2px dashed #cbd5f5;
        border-radius: 16px;
        text-align: center;
        color: #94a3b8;
        font-weight: 600;
        background: #f8fafc;
    }
    @media (max-width: 768px) {
        .hero-card {
            text-align: center;
        }
        .badge-row {
            justify-content: center;
        }
    }
</style>

<div class="detail-layout">
    <div class="section-card hero-card">
        <div class="avatar-frame">
            @if($student->image)
                <img src="{{ asset('storage/uploads/' . $student->image) }}" alt="{{ $student->name }} photo">
            @else
                <span class="avatar-placeholder">No Image</span>
            @endif
        </div>
        <div class="hero-body">
            <h1 class="hero-title">{{ $student->name }}</h1>
            <p class="hero-subtitle">{{ $student->email ?? 'Email not provided' }}</p>
            <div class="meta-grid">
                <div>
                    <p class="meta-label">Contact</p>
                    <p class="meta-value">{{ $student->contact ?? '—' }}</p>
                </div>
                <div>
                    <p class="meta-label">Education Level</p>
                    <p class="meta-value">{{ $student->educationLevel ?? '—' }}</p>
                </div>
                <div>
                    <p class="meta-label">Passed Year</p>
                    <p class="meta-value">{{ $student->passedyear ?? '—' }}</p>
                </div>
            </div>
            <div class="badge-row">
                <span class="pill">GPA: {{ $student->gpa ?? '—' }}</span>
                @if($student->previousschool)
                    <span class="pill">From {{ $student->previousschool }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="section-card">
        <h2 class="section-title">Personal Overview</h2>
        <div class="info-grid">
            <div class="info-card">
                <h4>Name</h4>
                <p>{{ $student->name }}</p>
            </div>
            <div class="info-card">
                <h4>Email</h4>
                <p>{{ $student->email ?? '—' }}</p>
            </div>
            <div class="info-card">
                <h4>Contact</h4>
                <p>{{ $student->contact ?? '—' }}</p>
            </div>
        </div>
    </div>

    <div class="section-card">
        <h2 class="section-title">Academic Detail</h2>
        <div class="info-grid">
            <div class="info-card">
                <h4>Education Level</h4>
                <p>{{ $student->educationLevel ?? '—' }}</p>
            </div>
            <div class="info-card">
                <h4>Previous School / College</h4>
                <p>{{ $student->previousschool ?? '—' }}</p>
            </div>
            <div class="info-card">
                <h4>Passed Year</h4>
                <p>{{ $student->passedyear ?? '—' }}</p>
            </div>
            <div class="info-card">
                <h4>GPA</h4>
                <p>{{ $student->gpa ?? '—' }}</p>
            </div>
        </div>
    </div>

    <div class="section-card">
        <h2 class="section-title">Interest</h2>
        <div class="section-body">
            {!! $student->interest ? nl2br(e($student->interest)) : '<p class="empty-state mb-0">Interest details not provided.</p>' !!}
        </div>
    </div>

    <div class="section-card">
        <h2 class="section-title">Goal</h2>
        <div class="section-body">
            {!! $student->goal ? nl2br(e($student->goal)) : '<p class="empty-state mb-0">Goal details not provided.</p>' !!}
        </div>
    </div>
</div>
@endsection

