@extends('layouts.app')
@section('content')

<div class="container" style="max-width:1000px;margin:40px auto;">
    <h2 style="margin-bottom:20px;">My Bookings</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
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
            <tr><td colspan="4">No bookings yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection

