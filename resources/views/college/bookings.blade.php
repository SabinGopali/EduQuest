@extends('layouts.college')

@section('content')
@php
    $totalBookings = $bookings->count();
    $pendingBookings = $bookings->where('status', 'booked')->count();
@endphp

<style>
    .booking-wrapper {
        max-width: 1100px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    .section-card {
        background: #fff;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
        margin-top: -25px;
    }
    .section-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
    }
    .section-header h2 {
        margin: 0;
        font-size: 1.7rem;
        font-weight: 700;
        color: #111827;
    }
    .section-header p {
        margin: 0;
        color: #94a3b8;
        font-weight: 600;
    }
    .booking-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
        border-radius: 12px;
        overflow: hidden;
    }
    .booking-table thead {
        background: #f1f5f9;
    }
    .booking-table th,
    .booking-table td {
        padding: 14px 16px;
        text-align: center;
        font-size: 0.95rem;
        border-bottom: 1px solid #e2e8f0;
        color: #475569;
    }
    .booking-table tbody tr:hover {
        background: #f9fafb;
    }
    .status-chip {
        padding: 6px 14px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .status-booked {
        background: #fff7ed;
        color: #c2410c;
    }
    .status-approved {
        background: #dcfce7;
        color: #15803d;
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
        padding: 28px;
        border: 2px dashed #cbd5f5;
        border-radius: 16px;
        color: #94a3b8;
        font-weight: 600;
        background: #f8fafc;
    }
    @media (max-width: 768px) {
        .booking-table th,
        .booking-table td {
            font-size: 0.85rem;
        }
        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="booking-wrapper">
    <div class="section-card">
        <div class="section-header">
            <h2>📚 Course Bookings</h2>
            <p>Total: {{ $totalBookings }} · Pending: {{ $pendingBookings }}</p>
        </div>
        @if($bookings->isEmpty())
            <p class="empty-state mb-0">No bookings found for your college yet.</p>
        @else
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Requested At</th>
                        <th>Action</th>
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
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $booking->courseDetail->course->name ?? '-' }}</td>
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
                                    <form action="{{ route('booking.college.approve', $booking->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-outline-dark">Approve</button>
                                    </form>
                                    <form action="{{ route('booking.college.reject', $booking->id) }}" method="POST">
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
</div>
@endsection