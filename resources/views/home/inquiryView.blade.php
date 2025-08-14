@extends('layouts.app')
@section('content')

<head>
  <title>Inquiry</title>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="Course Project" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link href="{{ asset('home/plugins/fontawesome-free-5.0.1/css/fontawesome-all.css') }}" rel="stylesheet" type="text/css" />

  <style>
    /* Reset some default styles */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .super_container {
      max-width: 1300px;
      margin: 60px auto;
      padding: 0 15px;
    }

    .inquiry-container {
      background: #fff;
      padding: 30px 20px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
    }

    /* Alert */
    .alert {
      position: relative;
      padding: 15px 30px 15px 20px;
      border-radius: 6px;
      background-color: #d4edda;
      border: 1px solid #c3e6cb;
      color: #155724;
      margin-bottom: 20px;
      font-weight: 600;
      user-select: none;
      animation: fadein 0.3s ease forwards;
    }

    /* Close button for alert */
    .alert .close-btn {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      background: transparent;
      border: none;
      font-size: 18px;
      cursor: pointer;
      color: #155724;
      font-weight: bold;
      line-height: 1;
      padding: 0;
    }

    /* Table Styles */
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgb(0 0 0 / 0.05);
    }

    thead tr {
      background-color: #f1f1f1;
    }

    th, td {
      padding: 14px 16px;
      text-align: left;
      font-size: 16px;
      border-bottom: 1px solid #eee;
      vertical-align: middle;
      color: #333;
    }

    th {
      font-size: 18px;
      font-weight: 700;
      color: #222;
    }

    tbody tr:hover {
      background-color: #f9f9f9;
    }

    /* Links inside table */
    table a {
      color: #007bff;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    table a:hover {
      color: #0056b3;
      text-decoration: underline;
    }

    /* Button */
    .btn-danger {
      background-color: #dc3545;
      border: none;
      color: white;
      font-weight: 600;
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-size: 14px;
      user-select: none;
    }

    .btn-danger:hover,
    .btn-danger:focus {
      background-color: #c82333;
      outline: none;
    }

    /* No data message */
    .no-data {
      text-align: center;
      color: #666;
      font-size: 20px;
      margin: 60px 0;
    }

    /* Responsive for smaller devices */
    @media (max-width: 600px) {
      th, td {
        padding: 10px 8px;
        font-size: 14px;
      }

      .btn-danger {
        padding: 6px 12px;
        font-size: 13px;
      }
    }

    /* Simple fade-in animation for alert */
    @keyframes fadein {
      from {opacity: 0;}
      to {opacity: 1;}
    }
  </style>
</head>

<div class="super_container">
  <div class="container inquiry-container">
    @if(session('success'))
      <div class="alert" id="success-alert">
        {{ session('success') }}
        <button class="close-btn" aria-label="Close alert">&times;</button>
      </div>
    @endif

    @if($student && $student->inquiries->isNotEmpty())
      <div style="overflow-x:auto;">
        <table>
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Course Detail</th>
              <th scope="col">College Name</th>
              <th scope="col">Message</th>
              <th scope="col">Inquiry Date</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($student->inquiries as $inquiry)
              <tr>
                <td><strong>{{ $loop->index + 1 }}</strong></td>
                <td>
                  <a href="/college/detail/course/description/{{ $inquiry->courseDetail->id }}">
                    {{ $inquiry->courseDetail->course->name }}
                  </a>
                </td>
                <td>
                  <a href="/college/detail/{{ $inquiry->courseDetail->college->id }}">
                    {{ $inquiry->courseDetail->college->name }}
                  </a>
                </td>
                <td>{{ $inquiry->message ?? 'No message yet' }}</td>
                <td>{{ $inquiry->created_at }}</td>
                <td>
                  <a href="/inquiry/delete/{{ $inquiry->id }}">
                    <button class="btn-danger" type="button">Delete</button>
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p class="no-data">No data available.</p>
    @endif
  </div>
</div>

<script>
  // Auto-dismiss alert after 2 seconds and allow manual close
  document.addEventListener('DOMContentLoaded', function() {
    const alert = document.getElementById('success-alert');
    if (alert) {
      // Auto dismiss after 2 seconds
      setTimeout(() => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
      }, 2000);

      // Manual close button
      const closeBtn = alert.querySelector('.close-btn');
      if (closeBtn) {
        closeBtn.addEventListener('click', () => {
          alert.style.transition = 'opacity 0.3s ease';
          alert.style.opacity = '0';
          setTimeout(() => alert.remove(), 300);
        });
      }
    }
  });
</script>

@endsection
