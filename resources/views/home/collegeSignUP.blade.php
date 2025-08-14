@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 200px">
    <div class="container form-container" style="margin-top: 200px">
   
        <h2 class="text-center">College Forms</h2>
        <form id="Collegeform" method="POST" action="{{ route('college.store') }}" enctype="multipart/form-data">
        @csrf    
        <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="college_id"  name="name" placeholder="Enter Name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="tel" class="form-control" name="address" placeholder="Enter Address" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact</label>
                <input type="contact" class="form-control" name="contact" placeholder="Enter Contact" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description" rows="3" placeholder="Enter Description" required></textarea>
            </div>
            <div class="form-group">
                <label for="logo">Logo:</label>
                <input type="file" class="form-control-file" name="logo">
            </div>
            <div class="form-group gallery-container">
                <label for="gallery">Gallery:</label>
                <input type="file" class="form-control-file gallery-input" name="gallery[]">
                
            </div>
            <button type="button" class="add-gallery">Add More</button>
            <br/>
            <button type="submit">Submit</button>
        </form>
    </div>
@endsection