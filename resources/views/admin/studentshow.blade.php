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
        max-width: 1200px;
        margin: 40px auto;
        background: #ffffff;
        padding: 30px;
        border-radius: 14px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease-in-out;
    }

    .custom-container:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .header-row {
        margin-bottom: 25px;
        border-bottom: 2px solid #f0f2f5;
        padding-bottom: 12px;
    }

    .header-row h2 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        text-align: center;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
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
        font-size: 0.95rem;
        text-align: center;
        border-bottom: 2px solid #e5e7eb;
    }

    .custom-table td {
        padding: 14px 16px;
        text-align: center;
        font-size: 0.95rem;
        border-bottom: 1px solid #f0f2f5;
        vertical-align: middle;
        color: #4b5563;
    }

    .custom-table tbody tr:hover {
        background-color: #f9fafc;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .icon-btn {
        font-size: 1.1rem;
        padding: 8px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .icon-view {
        background-color: #3b82f6; /* blue */
        color: white;
    }

    .icon-view:hover {
        background-color: #2563eb;
        transform: translateY(-1px);
    }

    .icon-delete {
        background-color: #ef4444;
        color: white;
    }

    .icon-delete:hover {
        background-color: #dc2626;
        transform: translateY(-1px);
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
    <div class="header-row">
        <h2>🎓 Student Details</h2>
    </div>

    @if(session('success'))
        <div style="margin-bottom: 20px; color: #16a34a; font-weight: 600; text-align:center;">
            {{ session('success') }}
        </div>
    @endif

    <table class="custom-table">
        <thead>
            <tr>
                <th>S.N.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($student as $student)
            <tr>
                <td><b>{{ $loop->index + 1 }}</b></td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->contact }}</td>
                <td>
                    <div class="action-buttons">
                        <a href="/admin/student/detail/{{ $student->id }}" class="icon-btn icon-view" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/student/delete/{{ $student->id }}" class="icon-btn icon-delete" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
