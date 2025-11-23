@extends('layouts.app')
@section('content')

<style>
    body {
        background-color: white;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .booking-container {
        max-width: 1200px;
        margin: 3rem auto 5rem auto;
        background: #fff;
        padding: 40px 35px;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .booking-container:hover {
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .booking-title {
        margin-bottom: 30px;
        font-size: 28px;
        font-weight: 800;
        color: #222;
        text-align: center;
        letter-spacing: 1px;
    }

    .table-custom {
        border-collapse: separate;
        border-spacing: 0 12px;
        width: 100%;
    }

    .table-custom thead {
        background: black;
        color: #fff;
    }

    .table-custom th {
        padding: 14px 12px;
        text-align: center;
        font-weight: 700;
        font-size: 1rem;
        border: none;
    }

    .table-custom th:first-child {
        border-top-left-radius: 8px;
    }

    .table-custom th:last-child {
        border-top-right-radius: 8px;
    }

    .table-custom td {
        background: #fff;
        text-align: center;
        padding: 16px 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 0.95rem;
        color: #333;
    }

    .table-custom tr td:first-child {
        font-weight: 600;
        color: #222;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: capitalize;
    }

    .status-booked {
        background: #f0f8ff;
        color: #333;
        border: 1px solid #ddd;
    }

    .status-approved {
        background: #f0f8ff;
        color: #333;
        border: 1px solid #ddd;
    }

    .status-rejected {
        background: #fff5f5;
        color: #333;
        border: 1px solid #ddd;
    }

    .alert {
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 20px;
        padding: 12px 16px;
        border: 1px solid #ddd;
    }

    .alert-success {
        background: #f0f8ff;
        color: #333;
        border-color: #ddd;
    }

    .alert-danger {
        background: #fff5f5;
        color: #333;
        border-color: #ddd;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: #fff;
        border: 2px dashed #ddd;
        border-radius: 10px;
    }

    .empty-state p {
        font-size: 1.1rem;
        color: #666;
        margin: 0;
    }

    /* Mobile adjustments */
    @media (max-width: 768px) {
        .booking-container {
            padding: 30px 25px;
            margin: 2rem 1.5rem 3rem 1.5rem;
        }
        .booking-title {
            font-size: 24px;
        }
        .table-custom th, .table-custom td {
            font-size: 0.85rem;
            padding: 12px 8px;
        }
        .table-custom {
            border-spacing: 0 8px;
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

    @if($bookings->count() > 0)
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
            @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->courseDetail->course->name ?? '-' }}</td>
                    <td>{{ $booking->courseDetail->college->name ?? '-' }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($booking->status) }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>{{ $booking->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <p>No bookings yet. Start exploring courses to make your first booking!</p>
        </div>
    @endif
</div>
@endsection
