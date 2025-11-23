@extends('layouts.app')
@section('content')

  <title>My-Profile</title>
  
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: white;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .profile_card_container {
      max-width: 1000px;
      margin: 3rem auto 5rem auto;
      padding: 40px 35px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
      transition: box-shadow 0.3s ease;
    }

    .profile_card_container:hover {
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .profile_header {
      text-align: center;
      padding: 30px 20px 20px;
      border-bottom: 1px solid #ddd;
      margin-bottom: 20px;
    }

    .profile_header img {
      width: 160px;
      height: 160px;
      object-fit: cover;
      border-radius: 50%;
      border: 3px solid black;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      margin-bottom: 15px;
      transition: transform 0.3s ease;
    }

    .profile_header img:hover {
      transform: scale(1.05);
    }

    .profile_header h2 {
      font-weight: 800;
      font-size: 28px;
      margin: 0 0 8px;
      color: #222;
      letter-spacing: 1px;
    }

    .profile_header p {
      font-size: 16px;
      color: #666;
      margin: 4px 0;
      font-weight: 500;
    }

    .profile_section {
      padding: 25px 20px;
      border-bottom: 1px solid #eee;
      margin-bottom: 10px;
    }

    .profile_section:last-child {
      border-bottom: none;
      margin-bottom: 0;
    }

    .profile_section h3 {
      font-size: 22px;
      font-weight: 700;
      margin-bottom: 14px;
      color: red;
      border-bottom: 2px solid black;
      padding-bottom: 6px;
      letter-spacing: 0.05em;
    }

    .profile_section p {
      font-size: 16px;
      color: #444;
      margin: 6px 0;
      line-height: 1.5;
    }

    /* Back button */
    .back_button {
      display: inline-block;
      margin-bottom: 20px;
      padding: 10px 24px;
      background-color: white;
      border: 2px solid black;
      border-radius: 8px;
      color: black;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .back_button:hover,
    .back_button:focus {
      background-color: black;
      color: white;
      outline: none;
    }

    /* Edit button */
    .edit_profile_button {
      display: block;
      width: 100%;
      max-width: 300px;
      margin: 30px auto 0;
      padding: 14px 40px;
      background-color: white;
      border: 2px solid black;
      border-radius: 8px;
      color: black;
      font-weight: 800;
      font-size: 18px;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .edit_profile_button:hover,
    .edit_profile_button:focus {
      background-color: black;
      color: white;
      outline: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .profile_card_container {
        padding: 30px 25px;
        margin: 2rem 1.5rem 3rem 1.5rem;
        max-width: 100%;
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
        padding: 14px 0;
        font-size: 8px;
      }
    }
  </style>

<div class="profile_card_container">

  <a href="{{ route('home') }}" class="back_button">← Back</a>

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
