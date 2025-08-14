<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Description</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">College Description</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="field_name"><strong> Name:</strong></label>
                    <input type="text" class="form-control" id="field_name" value="{{ $college->name}}" readonly>
                </div>
                <div class="form-group">
                    <label for="email"><strong>Email:</strong></label>
                    <input type="email" class="form-control" id="email" value="{{ $college->email }}" readonly>
                </div>
                <div class="form-group">
                    <label for="phone"><strong>Phone:</strong></label>
                    <input type="tel" class="form-control" id="phone" value="{{ $college->phone }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contact_description"><strong>Contact Description:</strong></label>
                    <textarea class="form-control" id="contact_description" rows="4" readonly>{{ $college->description }}</textarea>
                </div>
                <div class="form-group">
                    <label for="logo"><strong>Logo:</strong></label>
                    <!-- Replace 'your-logo-url.jpg' with the actual URL of your college's logo -->
                    <img src="your-logo-url.jpg" class="img-fluid" alt="College Logo">
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and jQuery (optional) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
