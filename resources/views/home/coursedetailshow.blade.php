@extends('layouts.admin')

@section('content')
<!-- Font Awesome CDN for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        background-color: #f5f7fa;
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .custom-container {
        max-width: 1100px;
        margin: 40px auto;
        background: #ffffff;
        padding: 30px 40px;
        border-radius: 14px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        transition: all 0.3s ease-in-out;
    }

    .custom-container:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    .header {
        text-align: center;
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 1.7rem;
        color: #1f2937;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
    }

    .custom-table thead {
        background-color: #f9fafb;
    }

    .custom-table th {
        padding: 14px 16px;
        font-weight: 600;
        color: #374151;
        font-size: 1rem;
        text-align: center;
        border-bottom: 2px solid #e5e7eb;
    }

    .custom-table td {
        padding: 14px 16px;
        font-size: 0.95rem;
        text-align: center;
        border-bottom: 1px solid #f0f2f5;
        vertical-align: middle;
        color: #4b5563;
    }

    .custom-table tbody tr:hover {
        background-color: #f9fafc;
    }

    .description-cell div {
        max-height: 200px;
        overflow-y: auto;
        padding-right: 8px;
        color: #4b5563;
        text-align: left;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .custom-table th,
        .custom-table td {
            font-size: 0.85rem;
        }
    }
</style>

<div class="custom-container">
    <h2 class="header">📚 Course Detail</h2>
    <table class="custom-table">
        <thead>
            <tr>
                <th>S.N.</th>
                <th>Course Name</th>
                <th>College Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courseDetails as $courseDetail)
            <tr>
                <td><b>{{ $loop->index + 1 }}</b></td>
                <td>{{ $courseDetail->course->name }}</td>
                <td>{{ $courseDetail->college->name }}</td>
                <td class="description-cell">
                    <div>
                        {{ $courseDetail->description }}
                    </div>
                </td>
                <td>{{ $courseDetail->status ?? 'PENDING' }}</td>
                <td>
                    @if(($courseDetail->status ?? 'PENDING') === 'PENDING')
                        <a href="{{ route('admin.coursedetail.approve', $courseDetail->id) }}" class="btn btn-success btn-sm">Approve</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
