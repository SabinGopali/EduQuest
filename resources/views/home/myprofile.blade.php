@extends('layouts.app')
@section('content')

  <title>My-Profile</title>
  
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .profile_card_container {
      max-width: 700px;
      margin: 150px auto 60px;
      padding: 20px;
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgb(0 0 0 / 0.1);
    }

    .profile_header {
      text-align: center;
      padding: 30px 20px 10px;
      border-bottom: 1px solid #ddd;
    }

    .profile_header img {
      width: 160px;
      height: 160px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid #007bff;
      box-shadow: 0 0 10px rgb(0 123 255 / 0.3);
      margin-bottom: 15px;
      transition: transform 0.3s ease;
    }

    .profile_header img:hover {
      transform: scale(1.05);
    }

    .profile_header h2 {
      font-weight: 700;
      font-size: 28px;
      margin: 0 0 8px;
      color: #222;
    }

    .profile_header p {
      font-size: 16px;
      color: #555;
      margin: 4px 0;
      font-weight: 500;
    }

    .profile_section {
      padding: 25px 20px;
      border-bottom: 1px solid #eee;
    }

    .profile_section:last-child {
      border-bottom: none;
    }

    .profile_section h3 {
      font-size: 22px;
      font-weight: 700;
      margin-bottom: 14px;
      color: #007bff;
      border-left: 4px solid #007bff;
      padding-left: 12px;
    }

    .profile_section p {
      font-size: 16px;
      color: #444;
      margin: 6px 0;
      line-height: 1.5;
    }

    /* Edit button */
    .edit_profile_button {
      display: block;
      width: 160px;
      margin: 30px auto 0;
      padding: 12px 0;
      background-color: #007bff;
      border: none;
      border-radius: 30px;
      color: white;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
      box-shadow: 0 6px 12px rgb(0 123 255 / 0.3);
      transition: background-color 0.3s ease;
    }

    .edit_profile_button:hover,
    .edit_profile_button:focus {
      background-color: #0056b3;
      box-shadow: 0 8px 16px rgb(0 86 179 / 0.4);
      outline: none;
    }

    /* Responsive */
    @media (max-width: 480px) {
      .profile_card_container {
        margin: 100px 15px 40px;
        padding: 15px;
      }

      .profile_header img {
        width: 120px;
        height: 120px;
      }

      .profile_header h2 {
        font-size: 24px;
      }

      .profile_section h3 {
        font-size: 20px;
      }

      .edit_profile_button {
        width: 140px;
        font-size: 14px;
        padding: 10px 0;
      }
    }
  </style>

<div class="profile_card_container">

  <div class="profile_header">
    <img src="{{ asset('storage/uploads/' . $student->image) }}" alt="Student Image" />
    <h2>{{ $student->name }}</h2>
    <p>Email: {{ $student->email }}</p>
    <p>Contact: {{ $student->contact }}</p>
  </div>

  <div class="profile_section">
    <h3>Academic Information</h3>
    <p>Education Level: {{ $student->educationLevel }}</p>
    <p>Passed Year: {{ $student->passedyear }}</p>
    <p>Previous School/College: {{ $student->previousschool }}</p>
    <p>GPA: {{ $student->gpa }}</p>
  </div>

  <div class="profile_section">
    <h3>Interests</h3>
    <p>{{ $student->interest }}</p>
  </div>

  <div class="profile_section">
    <h3>Goals</h3>
    <p>{{ $student->goal }}</p>
  </div>

  <a href="/myprofile-edit" class="edit_profile_button">Edit Profile</a>

</div>

@endsection
