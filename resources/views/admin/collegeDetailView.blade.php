@extends('layouts.admin')
@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College</title>

    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include Bootstrap JavaScript (Popper.js is required) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Include Custom CSS for Styling -->
    <link rel="stylesheet" href="styles.css">

    <!-- Custom CSS to add borders -->
    <style>
        .font-fix{
            color: black;
            font-size: 18px;
        }
        .bordered {
            border: 1px solid #000;
            padding: 10px;
            margin: 10px;
        }
    </style>
</head>


    <div class="container font-fix">
        <div class="row bordered">
            <div class="col-4 text-center">
            <img src="{{ asset('storage/' . $college->logo) }}" alt="College Logo" style="object-fit: contain; height: 100px; width:100px;">

            </div>
            <div class="col-4 text-center">
                <h2>{{$college->name}}</h2>
            </div>
            <div class="col-4 text-center">
                <b>Contact : </b> {{$college->contact}}
            </div>
        </div>

        <div class="row bordered">
            <div class="col-12">
                <div class="row">
				    <div class="col">
                        <div class="section_title text-center">
                            <h1>Description</h1>
                        </div>
				    </div>
			    </div>
                <p>{{$college->description}}</p>
            </div>
        </div>
        <div class="row bordered">
            <div class="col-12">
                <div class="row">
				    <div class="col">
                        <div class="section_title text-center">
                            <h1>Courses</h1>
                            @if($college->status === 'PENDING')
                                <div class="mt-2">
                                    <a href="{{ route('admin.college.approve', $college->id) }}" class="btn btn-success btn-sm">Approve College</a>
                                    <a href="{{ route('admin.college.reject', $college->id) }}" class="btn btn-danger btn-sm">Reject</a>
                                </div>
                            @endif
                        </div>
				    </div>
			    </div>
                <div class="row course_boxes">
                    @foreach ($college->courseDetails as $courseDetail)
                        <div class="col-lg-4 course_box">
                            <div class="card" style="height: 259px;">
                                <br/>
                                <div class="card-body text-center">
                                    <div class="card-title"><b>{{$courseDetail->course->name}}</b></div>
                                    <div class="card-text" style="font-size: 16px;">{{$courseDetail->course->stream}}, {{$courseDetail->course->subStream}}</div>
                                </div>
                                <br/>
                                <div class="d-flex justify-content-center">
                                    <a style="width:100px;" href="/college/detail/course/description/{{$courseDetail->id}}">
                                        <button class="btn btn-primary">View</button>
                                    </a>
                                </div>
                                <br/>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row bordered">
            <div class="row pt-1">
                <div class="col">
                    <div class="section_title text-center">
                        <h1>Gallery</h1>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center" style="height: 420px;">
                <div class="col-6" style="height: 100%">
                <div id="carouselExampleSlidesOnly" style="height: 100%" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" style="height: 100%" >
                        @foreach($college->images as $gallery)
                            <div class="carousel-item active" style="height: 100%">
                                <img src="{{ asset('storage/'. $gallery->path) }}" alt="Gallery Image" style=" object-fit:contain; height: 100%;">
                            </div>
                        @endforeach
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection
