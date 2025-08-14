@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 200px">
    <h2>SignUp Form</h2>
            <form id="userForm" method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
                @csrf
                <!-- User Information Section -->
                <div class="form-section">
                    <h3>User</h3>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Enter Name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="email" id="email" name="email" placeholder="Enter Email Address" required>
                    </div>
                    <div class="form-group">
                        <label for="contact">Contact Number:</label>
                        <input type="tel" id="contact" name="contact" placeholder="Enter Contact Number" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="file" id="image" name="image">
                    </div>
                </div>


                <!-- Academic Details Section -->
                <div class="form-section">
                    <h3>Academic Details</h3>
                    <div class="form-group">
                        <label for="passedYear">Passed Year:</label>
                        <input type="text" id="passedYear" name="passedyear" placeholder="Enter Passed Year" required>
                    </div>
                    <div class="form-group">
                        <label for="previousschool">Previous School/College:</label>
                        <input type="text" id="previousschool" name="previousschool" placeholder="Enter Previous School/College" required>
                    </div>
                    <div class="form-group">
                        <label for="gpa">GPA:</label>
                        <input type="text" id="gpa" name="gpa" placeholder="Enter GPA" required>
                    </div>
                </div>

                <!-- Interests Section -->
                <div class="form-group">
                    <label for="interests"><h3>Interests:</h3></label>
                    <textarea id="interests" name="interest" rows="3" placeholder="Enter Interests" required></textarea>
                </div>

                <!-- Goals Section -->
                <div class="form-group">
                    <label for="goals"><h3>Goals:</h3></label>
                    <textarea id="goals" name="goal" rows="3" placeholder="Enter Goals" required></textarea>
                </div>
                <button type="submit">Submit</button>
                <a  href="/college-signup">Sign up as College</a>

            </form>
        </div>
        

        <!-- <script>
            document.getElementById("userForm").addEventListener("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                // You can now access the form data using formData.get("fieldName")
                // Example: const name = formData.get("name");

                // For demonstration purposes, you can log the form data to the console
                for (var pair of formData.entries()) {
                    console.log(pair[0] + ": " + pair[1]);
                }
            });
        </script> -->

   


</div>

@endsection
