@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@php
    $totalBookings = $bookings->count();
    $pendingBookings = $bookings->where('status', 'booked')->count();
@endphp

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
         margin-top: -25px;
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
    .header-meta {
        color: #64748b;
        font-weight: 600;
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
    .status-chip {
        padding: 6px 14px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-booked {
        background: #fff7ed;
        color: #c2410c;
    }
    .status-approved {
        background: #dcfce7;
        color: #166534;
    }
    .status-rejected {
        background: #fee2e2;
        color: #b91c1c;
    }
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .btn-outline-dark {
        border: 2px solid #111827;
        border-radius: 10px;
        padding: 6px 14px;
        font-weight: 600;
        font-size: 0.85rem;
        background: transparent;
        color: #111827;
        transition: all 0.25s ease;
    }
    .btn-outline-dark:hover {
        background: #111827;
        color: #fff;
    }
    .btn-outline-danger {
        border-color: #b91c1c;
        color: #b91c1c;
    }
    .btn-outline-danger:hover {
        background: #b91c1c;
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
    @media (max-width: 768px) {
        .header-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .custom-table th,
        .custom-table td {
            font-size: 0.85rem;
        }
    }
</style>

<div class="custom-container">
    <div class="header-row">
        <h2>📅 Booking Requests</h2>
        <p class="header-meta">Total: {{ $totalBookings }} · Pending: {{ $pendingBookings }}</p>
    </div>

    @if($bookings->isEmpty())
        <p class="empty-state mb-0">No bookings have been created yet.</p>
    @else
        <table class="custom-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Course</th>
                    <th>College</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td><b>{{ $loop->iteration }}</b></td>
                    <td>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#studentModal{{ $booking->id }}">
                            {{ $booking->student->name ?? '-' }}
                        </a>
                        <div class="modal fade" id="studentModal{{ $booking->id }}" tabindex="-1" aria-labelledby="studentModalLabel{{ $booking->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="studentModalLabel{{ $booking->id }}">Student Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Name:</strong> {{ $booking->student->name }}</p>
                                        <p><strong>Email:</strong> {{ $booking->student->email }}</p>
                                        <p><strong>Contact:</strong> {{ $booking->student->contact }}</p>
                                        <p><strong>GPA:</strong> {{ $booking->student->gpa }}</p>
                                        <p><strong>Education Level:</strong> {{ $booking->student->educationLevel }}</p>
                                        <p><strong>Interest:</strong> {{ $booking->student->interest }}</p>
                                        <p><strong>Goal:</strong> {{ $booking->student->goal }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="{{ route('students.getByIdForAdmin', $booking->student->id) }}" class="btn btn-link">View full profile</a>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $booking->courseDetail->course->name ?? '-' }}</td>
                    <td>{{ $booking->courseDetail->college->name ?? '-' }}</td>
                    <td>
                        <span class="status-chip status-{{ strtolower($booking->status) }}">
                            <i class="fas fa-circle" style="font-size: 0.55rem;"></i>
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>{{ $booking->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        @if($booking->status === 'booked')
                            <div class="action-buttons">
                                <form action="{{ route('booking.admin.approve', $booking->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-outline-dark">Approve</button>
                                </form>
                                <form action="{{ route('booking.admin.reject', $booking->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-outline-dark btn-outline-danger">Reject</button>
                                </form>
                            </div>
                        @elseif($booking->status === 'approved')
                            <span class="status-chip status-approved">Approved</span>
                        @else
                            <span class="status-chip status-rejected">Rejected</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection