@extends('layouts.admin')
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CourseDetailshow</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        ol, ul {
            padding-left: 0 !important; 
        }
    </style>
</head>     
<body>
<div class="container">
        <!-- create button here-->
        <!-- <a href="/course/add"><button type="button" class="btn btn-success">+ Create</button></a> -->
    <div class="container">
        <table class="table table-bordered shadow text-center table-stripped">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Image</th>
                <th>Passed Year</th>
                <th>Previous school/College</th>
                <th>GPA</th>
                <th>Interest</th>
                <th>Goal</th>
                <th>Delete</th>
                <th>Edit</th>
            </tr>
            @foreach($student as $student)
            <tr>
                <td>{{$student->name}}</td>
                <td>{{$student->email}}</td>
                <td>{{$student->contact}}</td>
                <td>{{$student->image}}</td>
                <td>{{$student->passedyear}}</td>
                <td>{{$student->previousschool}}</td>
                <td>{{$student->gpa}}</td>
                <td>{{$student->interest}}</td>

                <td><a href="/student/delete/{{$student->id}}" class="btn btn-danger">DELETE</a></td>
                <td><a href="/student/edit/{{$studnet->id}}" class="btn btn-success">EDIT</a></td>

            </tr>
            @endforeach
        </table>
    </div>   
</div>
@endsection
