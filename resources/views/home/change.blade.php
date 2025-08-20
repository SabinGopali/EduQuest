@extends('layouts.app')
@section('content')

<style>
  .pw-container {
    max-width: 480px;
    margin: 150px auto 60px;
    background: #fff;
    padding: 30px 25px;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
  }

  .pw-title {
    font-weight: 700;
    font-size: 28px;
    margin-bottom: 30px;
    color: black;
    text-align: center;
  }

  .pw-form {
    display: flex;
    flex-direction: column;
  }

  .pw-label {
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 16px;
    color: #222;
  }

  .pw-input {
    padding: 12px 15px;
    font-size: 16px;
    border: 1.8px solid #ccc;
    border-radius: 8px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 12px;
    outline: none;
  }

  .pw-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 6px rgba(0, 123, 255, 0.4);
  }

  .pw-error {
    color: #dc3545;
    font-size: 14px;
    margin-top: -8px;
    margin-bottom: 12px;
  }

  .pw-btn {
    margin-top: 20px;
    padding: 14px;
    font-size: 18px;
    font-weight: 700;
    color: black;
    background-color: white;
    border: 2px solid black;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .pw-btn:hover,
  .pw-btn:focus {
    background-color: black;
    color: white;
    outline: none;
  }

  /* Responsive */
  @media (max-width: 520px) {
    .pw-container {
      margin: 100px 15px 40px;
      padding: 25px 20px;
      width: 90%;
    }

    .pw-title {
      font-size: 24px;
    }

    .pw-input {
      font-size: 14px;
      padding: 10px 12px;
    }

    .pw-btn {
      font-size: 16px;
      padding: 12px;
    }
  }
</style>

<div class="pw-container">
  <h2 class="pw-title">Password Change Form</h2>

  <form method="POST" action="{{ route('student.updatePassword') }}" class="pw-form">
    @csrf

    <label for="newPassword" class="pw-label">New Password:</label>
    <input type="password" id="newPassword" name="newPassword" class="pw-input" placeholder="Enter new password" />
    @error('newPassword')
      <div class="pw-error">{{ $message }}</div>
    @enderror

    <label for="confirmPassword" class="pw-label">Confirm Password:</label>
    <input type="password" id="confirmPassword" name="confirmPassword" class="pw-input" placeholder="Confirm new password" />
    @error('confirmPassword')
      <div class="pw-error">{{ $message }}</div>
    @enderror

    <button type="submit" class="pw-btn">Change Password</button>
  </form>
</div>

@endsection
