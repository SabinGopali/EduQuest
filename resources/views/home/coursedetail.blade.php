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
        <h2>Course Details</h2>
        <form id="myForm" action="{{ route('coursedetail.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="id">ID:</label>
                <input type="text" id="id" name="id" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="courseid">Course ID:</label>
                <input type="text" id="courseid" name="course_id" required>
            </div>
            <div class="form-group">
                <label for="collegeid">College ID:</label>
                <input type="text" id="collegeid" name="college_id" required>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>

    <!-- <script>
        function submitForm() {
            // You can add your JavaScript code here to handle form submission
            // For example, you can use JavaScript to collect form data and send it to a server.
            // Here's a simple alert for demonstration purposes:
            const formData = {
                id: document.getElementById('id').value,
                courseid: document.getElementById('courseid').value,
                collegeid: document.getElementById('collegeid').value,
                description: document.getElementById('description').value,
            };
            alert('Form data submitted: ' + JSON.stringify(formData));
            return true;
        }
    </script> -->
</body>
</html>
