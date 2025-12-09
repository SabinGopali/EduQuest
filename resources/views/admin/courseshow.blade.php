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
        margin-top: -25px;

    }

    .custom-container:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        border-bottom: 2px solid #f0f2f5;
        padding-bottom: 12px;
    }

    .header-row h2 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .add-btn {
        background: white;
        color: black;
        padding: 10px 18px;
        border: 2px solid black;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
    }

    .add-btn:hover {
        background: black;
        color: white;
        transform: translateY(-1px);
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

    .icon-edit {
        background-color: #10b981; /* green */
        color: white;
    }

    .icon-edit:hover {
        background-color: #059669;
        transform: translateY(-1px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .custom-table th,
        .custom-table td {
            font-size: 0.85rem;
        }
    }
</style>

<div class="custom-container">
    <div class="header-row">
        <h2>📚 Course</h2>
        <a href="{{ route('admin.course.index') }}" class="add-btn">+ Add Course</a>
    </div>

    @if(session('success'))
        <div style="margin-bottom: 20px; color: #16a34a; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <table class="custom-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Stream</th>
                <th>Sub Stream</th>
                <th>Name</th>
                <th>Short Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($course as $courseItem)
            <tr>
                <td><b>{{ $loop->index + 1 }}</b></td>
                <td>{{ $courseItem->stream }}</td>
                <td>{{ $courseItem->subStream }}</td>
                <td>{{ $courseItem->name }}</td>
                <td>{{ $courseItem->shortName }}</td>
                <td>
                    <div class="action-buttons">
                        <a href="/admin/course/view/{{ $courseItem->id }}" class="icon-btn icon-view" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('course.edit', $courseItem->id) }}" class="icon-btn icon-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.course.destroy', $courseItem->id) }}" method="POST" onsubmit="return confirm('Delete this course? This will also remove its course details.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="icon-btn icon-delete" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
