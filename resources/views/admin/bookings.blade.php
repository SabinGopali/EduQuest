@extends('layouts.admin')
@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">All Bookings</h3>
          </div>
          <div class="card-body">
            <table id="bookings" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Student</th>
                  <th>Course</th>
                  <th>College</th>
                  <th>Status</th>
                  <th>Created</th>
                </tr>
              </thead>
              <tbody>
                @foreach($bookings as $b)
                <tr>
                  <td>{{ $b->student->name ?? '-' }}</td>
                  <td>{{ $b->courseDetail->course->name ?? '-' }}</td>
                  <td>{{ $b->courseDetail->college->name ?? '-' }}</td>
                  <td>{{ ucfirst($b->status) }}</td>
                  <td>{{ $b->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection