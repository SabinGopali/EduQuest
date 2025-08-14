@extends('layouts.admin')

@section('content')
<!-- Font Awesome CDN (if you want to use icons) -->
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

    /* Responsive */
    @media (max-width: 768px) {
        .header-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }

        .custom-table th,
        .custom-table td {
            font-size: 0.85rem;
        }
    }
</style>

<div class="custom-container">
    <div class="header-row">
        <h2>🏫 College Details</h2>
        {{-- Optional: Add a button to add new college if needed --}}
        {{-- <a href="/admin/college/create" class="add-btn">+ Add College</a> --}}
    </div>

    @if(session('success'))
        <div style="margin-bottom: 20px; color: #16a34a; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <table class="custom-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($college as $college)
            <tr>
                <td><b>{{ $loop->index + 1 }}</b></td>
                <td>{{ $college->name }}</td>
                <td>{{ $college->email }}</td>
                <td>{{ $college->address }}</td>
                <td>{{ $college->contact }}</td>
                <td>{{ $college->status }}</td>
                <td>
                    <div class="action-buttons">
                        <a href="/admin/college/detail/{{ $college->id }}" class="icon-btn icon-view" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($college->status === 'PENDING')
                            <a href="{{ route('admin.college.approve', $college->id) }}" class="icon-btn icon-view" title="Approve">
                                <i class="fas fa-check"></i>
                            </a>
                            <a href="{{ route('admin.college.reject', $college->id) }}" class="icon-btn icon-delete" title="Reject">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                        <a href="/college/delete/{{ $college->id }}" class="icon-btn icon-delete" title="Delete">
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
