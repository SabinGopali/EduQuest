@extends('layouts.app')
@section('content')

<div class="container">
    <h2 class="text-center">College Details</h2>
    <!-- <div class="container"> -->
        <!-- create button here-->
        <!-- <a href="/college/show"><button type="button" class="btn btn-success">+ Create</button></a> -->
    <!-- </div> -->
    <div class="container">
    <table class="table table-bordered shadow text-center table-stripped">
            <tr>
                <th>S.N.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Password</th>
                <th>Contact</th>
                <th>Description</th>
                <th>Logo</th>
                <th>Gallery</th>
                <th>Delete</th>
                <th>Edit</th>

            </tr>
            @foreach($college as $college)
            <tr>
                <td scope="row"><b>{{ $loop->index + 1 }}</b></td>
                <td>{{$college->name}}</td>
                <td>{{$college->email}}</td>
                <td>{{$college->address}}</td>
                <td>{{$college->password}}</td>
                <td>{{$college->contact}}</td>
                <td>{{$college->description}}</td>
                <td>{{$college->logo}}</td>
                <td>{{$college->gallery}}</td>
                <td><a href="/admin/college/detail/{{$college->id}}" class="btn btn-primary">View</a></td>
                <td><a href="/college/delete/{{$college->id}}" class="btn btn-danger">DELETE</a></td>
                <td><a href="/college/edit/{{$college->id}}" class="btn btn-success">EDIT</a></td>

            </tr>
            @endforeach
        </table>
    </div>
    </div>
</div>

@endsection
