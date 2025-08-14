
@section('content')

<div class="container">
    <h2 class="text-center">Contact</h2>
    <!-- <div class="container"> -->
        <!-- create button here-->
        <!-- <a href="/college/show"><button type="button" class="btn btn-success">+ Create</button></a> -->
    <!-- </div> -->
    <div class="container">
    <table class="table table-bordered shadow text-center table-stripped">
            <tr>
                <th>S.N.</th>
                <th>Email</th>
                <th>Message</th>
                <th>Status</th>
                <th>Delete</th>
                <th>Edit</th>

            </tr>
            @foreach($contacts as $contact)
            <tr>
                <td scope="row"><b>{{ $loop->index + 1 }}</b></td>
                <td>{{$contact->email}}</td>
                <td>{{$contact->message}}</td>
                <td>{{$contact->status}}</td>
                <td><a href="/contact/delete/{{$contact->id}}" class="btn btn-danger">DELETE</a></td>
                <td><a href="/contact/edit/{{$contact->id}}" class="btn btn-success">EDIT</a></td>

            </tr>
            @endforeach
        </table>
    </div>
    </div>
</div>

@endsection
