@extends('layouts.app')
@section('content')

<style>
    .booking-container {
        max-width: 1000px;
        margin: 60px auto;
        background: #fff;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.08);
    }

    .booking-title {
        margin-bottom: 25px;
        font-size: 1.8rem;
        font-weight: 700;
        color: #222;
        text-align: center;
    }

    .table-custom {
        border-collapse: separate;
        border-spacing: 0 10px;
        width: 100%;
    }

    .table-custom thead {
        background: gray;
        color: #fff;
    }

    .table-custom th {
        padding: 12px;
        text-align: center;
        font-weight: 600;
        font-size: 15px;
        border: none;
    }

    .table-custom td {
        background: #f9fafb;
        text-align: center;
        padding: 14px 10px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        color: #333;
    }

    .table-custom tr td:first-child {
        font-weight: 600;
        color: #333;
    }

    .alert {
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 20px;
    }

    /* Mobile adjustments */
    @media (max-width: 768px) {
        .booking-container {
            padding: 20px;
        }
        .table-custom th, .table-custom td {
            font-size: 13px;
            padding: 10px;
        }
    }
</style>

<div class="booking-container">
    <h2 class="booking-title">My Bookings</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table-custom">
        <thead>
            <tr>
                <th>Course</th>
                <th>College</th>
                <th>Status</th>
                <th>Booked At</th>
            </tr>
        </thead>
        <tbody>
        @forelse($bookings as $booking)
            <tr>
                <td>{{ $booking->courseDetail->course->name ?? '-' }}</td>
                <td>{{ $booking->courseDetail->college->name ?? '-' }}</td>
                <td>{{ ucfirst($booking->status) }}</td>
                <td>{{ $booking->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align:center; padding:20px; background:#f4f6f9; border-radius:8px;">
                    No bookings yet.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
