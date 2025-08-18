@extends('layouts.college')
@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Course Bookings</h3>
          </div>
          <div class="card-body">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Student</th>
                  <th>Course</th>
                  <th>Status</th>
                  <th>Booked At</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
              @forelse($bookings as $b)
                <tr>
                  <td>
                    <a href="#" data-toggle="modal" data-target="#studentModal{{ $b->id }}">{{ $b->student->name ?? '-' }}</a>
                    <div class="modal fade" id="studentModal{{ $b->id }}" tabindex="-1" role="dialog" aria-labelledby="studentModalLabel{{ $b->id }}" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="studentModalLabel{{ $b->id }}">Student Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p><strong>Name:</strong> {{ $b->student->name }}</p>
                            <p><strong>Email:</strong> {{ $b->student->email }}</p>
                            <p><strong>Contact:</strong> {{ $b->student->contact }}</p>
                            <p><strong>GPA:</strong> {{ $b->student->gpa }}</p>
                            <p><strong>Education Level:</strong> {{ $b->student->educationLevel }}</p>
                            <p><strong>Interest:</strong> {{ $b->student->interest }}</p>
                            <p><strong>Goal:</strong> {{ $b->student->goal }}</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>{{ $b->courseDetail->course->name ?? '-' }}</td>
                  <td>{{ ucfirst($b->status) }}</td>
                  <td>{{ $b->created_at->format('Y-m-d H:i') }}</td>
                  <td>
                    @if($b->status !== 'approved')
                    <form action="{{ route('booking.college.approve', $b->id) }}" method="POST" style="display:inline-block;">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-success">Approve</button>
                    </form>
                    @else
                    <span class="badge badge-success">Approved</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr><td colspan="5">No bookings found.</td></tr>
              @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection