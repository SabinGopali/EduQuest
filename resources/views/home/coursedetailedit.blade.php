<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Course Details</h2>
        <form id="myForm" action="{{ route('coursedetail.update', ['id' => $courseDetail->id]) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="courseid">Course ID:</label>
                <input type="text" id="courseid" name="course_id" value="{{$courseDetail->course_id}}" required>
            </div>
            <div class="form-group">
                <label for="collegeid">College ID:</label>
                <input type="text" id="collegeid" name="college_id" value="{{$courseDetail->college_id}}" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required>{{$courseDetail->description}}</textarea>
            </div>
            <input type="submit" name="update" value="CourseDetailUpdate" class="btn btn-success">
        </form>
    </div>
</body>
</html>
