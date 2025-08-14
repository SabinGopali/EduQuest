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
        word-wrap: break-word;
    }

    .custom-table tbody tr:hover {
        background-color: #f9fafc;
    }

    a {
        color: #3b82f6;
        text-decoration: none;
        transition: color 0.2s ease;
        font-weight: 600;
    }
    a:hover {
        color: #2563eb;
        text-decoration: underline;
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
    <h2 class="header">📬 Inquiry</h2>

    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Course Detail</th>
                <th>Message</th>
                <th>Inquiry Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inquiry as $inquiry)
            <tr>
                <td><b>{{ $loop->index + 1 }}</b></td>
                <td>
                    <a href="/admin/student/detail/{{ $inquiry->student->id }}">
                        {{ $inquiry->student->name }}
                    </a>
                </td>
                <td>{{ $inquiry->courseDetail->course->name }}</td>
                <td>{{ $inquiry->message }}</td>
                <td>{{ $inquiry->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
