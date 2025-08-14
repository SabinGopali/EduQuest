@extends('layouts.admin')
@section('content')

<style>
  /* Main container */
  .form-container {
    max-width: 800px;
    margin: 60px auto;
    padding: 35px 30px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  /* Heading */
  h2 {
    text-align: center;
    margin-bottom: 28px;
    font-weight: 700;
    font-size: 1.7rem;
    color: #1f2937;
  }

  /* Form group spacing */
  .form-group {
    margin-bottom: 22px;
  }

  /* Labels */
  label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #374151;
    font-size: 0.95rem;
  }

  /* Input fields */
  input[type="password"] {
    width: 100%;
    padding: 12px 14px;
    font-size: 0.95rem;
    border: 1.6px solid #d1d5db;
    border-radius: 8px;
    background-color: #f9fafb;
    transition: border-color 0.25s ease, background-color 0.25s ease;
  }

  input[type="password"]:focus {
    border-color: #6366f1;
    background-color: #ffffff;
    outline: none;
  }

  /* Error message styling */
  .text-danger {
    color: #dc2626;
    font-size: 0.85rem;
    margin-top: 6px;
    font-weight: 500;
  }

  /* Submit button */
  button[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: white;
    color: black;
    font-weight: 600;
    font-size: 1rem;
    border: 2px solid black;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.25s ease;
  }

  button[type="submit"]:hover {
    background-color: black;
    color: white;
  }

  /* Responsive tweaks */
  @media (max-width: 500px) {
    .form-container {
      margin: 40px 15px;
      padding: 25px 20px;
    }
  }
</style>

<div class="form-container">
  <h2>Password Change Form</h2>
  <form method="POST" action="{{ route('admin.updatePassword') }}">
    @csrf
    <div class="form-group">
      <label for="newPassword">New Password:</label>
      <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password">
      @error('newPassword')
      <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>

    <div class="form-group">
      <label for="confirmPassword">Confirm Password:</label>
      <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password">
      @error('confirmPassword')
      <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>

    <button type="submit">Submit</button>
  </form>
</div>

@endsection
