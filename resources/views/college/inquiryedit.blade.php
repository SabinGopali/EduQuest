@extends('layouts.college')
@section('content')
<div class="container mt-5">
    <h2 class="text-center">Reply to Inquiry</h2>
    <form action="{{ route('inquiry.update', $inquiry->id) }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="message" class="form-label">Student Message</label>
        <textarea style="height: 150px;" class="form-control" id="message" name="message" readonly>{{ $inquiry->message }}</textarea>
    </div>
    <div class="mb-3">
        <label for="reply" class="form-label">Your Reply</label>
        <textarea style="height: 150px;" class="form-control" id="reply" name="reply">{{ $inquiry->reply }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Save Reply</button>
</form>

</div>
@endsection
