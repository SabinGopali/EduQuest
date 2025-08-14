@extends('layouts.app')
@section('content')

<head>
  <title>Contact</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Course Project">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" type="text/css" href="{{ asset('home/plugins/fontawesome-free-5.0.1/css/fontawesome-all.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('home/styles/contact_styles.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('home/styles/contact_responsive.css') }}">

  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      background-color: white;
    }

    .home {
      position: relative;
      height: 280px;
      background-color: #111;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      background-size: cover;
      background-position: center;
    }

    .home_background {
      position: absolute;
      width: 100%;
      height: 100%;
      background-size: cover;
      background-position: center;
      
      z-index: 1;
    }

    .home_content {
      position: relative;
      z-index: 2;
      text-align: center;
    }

    .home_content h1 {
      font-size: 48px;
      margin-bottom: 10px;
    }

    .contact {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 40px;
      max-width: 1200px;
      margin: 50px auto;
      padding: 20px;
      background-color: #e9f2eb;
      border-radius: 12px;
    }

    .contact_form {
      flex: 1 1 60%;
      background-color: #fff;
      padding: 30px;
      border-radius: 14px;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
    }

    .contact_title {
      display: none;
    }

    .contact_form_container input,
    .contact_form_container textarea {
      width: 100%;
      padding: 14px 20px;
      margin-bottom: 18px;
      border: none;
      border-bottom: 1px solid #ccc;
      font-size: 16px;
      background-color: transparent;
      outline: none;
      transition: border-color 0.3s;
    }

    .contact_form_container input:focus,
    .contact_form_container textarea:focus {
      border-color: #4c7c5c;
    }

    .contact_send_btn {
      background-color: white;
      color:black;
      padding: 12px 32px;
      border: 2px solid black;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .contact_send_btn:hover {
      background-color: black;
      color: white;
    }

    .about {
      flex: 1 1 35%;
      padding: 30px;
    }

    .about_title {
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 15px;
    }

    .about p {
      font-size: 16px;
      color: #333;
      margin-bottom: 15px;
      line-height: 1.6;
    }

    .contact_info {
      margin-top: 25px;
    }

    .contact_info h2 {
      font-size: 20px;
      margin-bottom: 10px;
    }

    .contact_info p {
      margin: 6px 0;
    }

    @media (max-width: 768px) {
      .contact {
        flex-direction: column;
        gap: 20px;
      }

      .contact_form,
      .about {
        flex: 1 1 100%;
      }
    }
  </style>
</head>

<!-- Hero Section -->
<div class="home">
  <div class="home_background" style="background-image: url('{{ asset('img/Contact_banner.jpg') }}')"></div>
  
</div>

<!-- Contact Section -->
<div class="contact">
  <!-- Contact Form -->
  <div class="contact_form">
    <div class="contact_title">Get in touch</div>
    <div class="contact_form_container">
      <form action="{{ route('contact.store') }}" method="POST">
        @csrf
        <input name="email" type="email" placeholder="E-mail" required>
        <textarea name="message" placeholder="Message" required></textarea>
        <button type="submit" class="contact_send_btn">Send Message</button>
      </form>
    </div>
  </div>

  <!-- About / Contact Info -->
  <div class="about">
    <div class="about_title">EduQuest</div>
    <p style="text-align: justify;">
  EduQuest is an educational web portal that aims to enhance higher education in Nepal. It's designed to serve as an interactive and user-friendly platform for students, academic institutions, scholars, parents, and others seeking information about higher education.
</p>
<p style="text-align: justify;">
  The key objectives of EduQuest are to connect students with academic institutions on a unified educational platform and to support them in making informed decisions that simplify their journey and save valuable time.
</p>

    <div class="contact_info">
      <h2>Contact Information</h2>
      <p>Address: Kalimati, Kathmandu</p>
      <p>Phone: 9715638026</p>
      <p>Email: eduquest@gmail.com</p>
    </div>
  </div>
</div>

@endsection
