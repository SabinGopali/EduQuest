@extends('layouts.app')
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Form</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .inquiry-container {
            max-width: 1200px;
            background: #fff;
            margin: 50px auto;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
        }

        .inquiry-title {
            text-align: center;
            font-size: 28px;
            margin-bottom: 25px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            color: #555;
        }

        textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
            resize: vertical;
            transition: border-color 0.3s ease;
        }

        textarea:focus {
            border-color: #4a90e2;
            outline: none;
            box-shadow: 0 0 4px rgba(74, 144, 226, 0.3);
        }

        .btn-submit {
            display: inline-block;
            background: white;
            color: black;
            border: 2px solid black;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.1s ease;
        }

        .btn-submit:hover {
            background: black;
            color: white;
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0px);
        }
    </style>
</head>
<body>
    <div class="inquiry-container">
        <h1 class="inquiry-title">Inquiry Form</h1>
        <form action="{{ route('home.inquiry.form.store') }}" method="POST">
            @csrf
            <input type="hidden" id="studentid" name="student_id" value="{{ Auth::guard('student')->id() }}" readonly>
            <input type="hidden" name="college_id" value="{{ $college_id }}" readonly>
            <input type="hidden" name="coursedetail_id" value="{{ $coursedetail_id }}" readonly>

            <div class="form-group">
                <label for="message">Type your Message Inquiry here !!</label>
                <textarea name="message" id="message" cols="30" rows="8"></textarea>
            </div>

            <button type="submit" class="btn-submit">Submit Inquiry</button>
        </form>
    </div>
</body>
@endsection
