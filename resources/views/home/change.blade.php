@extends('layouts.app')
@section('content')

<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 0;
    color: #333;
  }

  .super_container {
    max-width: 480px;
    margin: 150px auto 60px;
    background: white;
    padding: 30px 25px;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgb(0 0 0 / 0.12);
  }

  h2.text-center {
    font-weight: 700;
    font-size: 28px;
    margin-bottom: 30px;
    color: #007bff;
    text-align: center;
  }

  form {
    display: flex;
    flex-direction: column;
  }

  label {
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 16px;
    color: #222;
  }

  input[type="password"] {
    padding: 12px 15px;
    font-size: 16px;
    border: 1.8px solid #ccc;
    border-radius: 8px;
    transition: border-color 0.3s ease;
    outline-offset: 2px;
    outline-color: transparent;
    margin-bottom: 12px;
  }

  input[type="password"]:focus {
    border-color: #007bff;
    outline-color: #007bff;
    box-shadow: 0 0 6px rgba(0, 123, 255, 0.4);
  }

  .text-danger {
    color: #dc3545;
    font-size: 14px;
    margin-top: -8px;
    margin-bottom: 12px;
  }

  button[type="submit"] {
    margin-top: 20px;
    padding: 14px;
    font-size: 18px;
    font-weight: 700;
    color: white;
    background-color: #007bff;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    box-shadow: 0 6px 12px rgb(0 123 255 / 0.3);
  }

  button[type="submit"]:hover,
  button[type="submit"]:focus {
    background-color: #0056b3;
    box-shadow: 0 8px 16px rgb(0 86 179 / 0.4);
    outline: none;
  }

  /* Responsive */
  @media (max-width: 520px) {
    .super_container {
      margin: 100px 15px 40px;
      padding: 25px 20px;
      width: 90%;
    }

    h2.text-center {
      font-size: 24px;
    }

    input[type="password"] {
      font-size: 14px;
      padding: 10px 12px;
    }

    button[type="submit"] {
      font-size: 16px;
      padding: 12px;
    }
  }
</style>

<div class="super_container">

  <h2 class="text-center">Password Change Form</h2>

  <form method="POST" action="{{ route('student.updatePassword') }}">
    @csrf

    <label for="newPassword">New Password:</label>
    <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password" />
    @error('newPassword')
      <div class="text-danger">{{ $message }}</div>
    @enderror

    <label for="confirmPassword">Confirm Password:</label>
    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password" />
    @error('confirmPassword')
      <div class="text-danger">{{ $message }}</div>
    @enderror

    <button type="submit">Change Password</button>
  </form>

</div>

@endsection
