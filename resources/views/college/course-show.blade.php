@extends('layouts.college')
@section('content')
<div class="container">
<h2 class="text-center">Course</h2>
<div class="row">
    <a href="{{ route('college.course.index') }}">
    <button class="btn btn-primary">Add Course</button>
    </a>
</div>
<div class="container">
        <table class="table table-bordered shadow text-center table-stripped">
            <tr>
                <th>Id</th>
                <th>Stream</th>
                <th>Sub Stream</th>
                <th>Name</th>
                <th>Short Name</th>
            </tr>
            @foreach($course as $course)
            <tr>
                <td scope="row"><b>{{ $loop->index + 1 }}</b></td>
                <td>{{$course->stream}}</td>
                <td>{{$course->subStream}}</td>
                <td>{{$course->name}}</td>
                <td>{{$course->shortName}}</td>
                <td> <a href="/college/course/view/{{$course->id}}"><button type="button" class="btn btn-primary">View</button></a></td>
            </tr>


            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection
