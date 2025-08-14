@extends('layouts.college')
@section('content')
<div class="container mt-5">
    <h2 class="text-center">Update Inquiry</h2>
    <form action="{{ route('inquiry.update', $inquiry->id) }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="message" class="form-label">Message</label>
        <textarea style="height: 150px;" class="form-control" id="message" name="message" required>{{ $inquiry->message }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

</div>
@endsection
