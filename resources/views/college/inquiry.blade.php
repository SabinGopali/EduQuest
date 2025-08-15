@extends('layouts.college')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry List</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .alert {
            padding: 12px 20px;
            background-color: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
            border-radius: 6px;
            margin-bottom: 20px;
            animation: fadeOut 2s ease-in-out 2s forwards;
        }

        @keyframes fadeOut {
            to { opacity: 0; visibility: hidden; height: 0; margin: 0; padding: 0; }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            margin-top: 20px;
        }

        table thead {
            background-color: #f9fafb;
        }

        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.95rem;
        }

        table th {
            color: #374151;
            font-weight: 600;
        }

        table td {
            color: #4b5563;
        }

        .action-btn {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.2s ease;
            display: inline-block;
        }

        .action-btn:hover {
            background-color: #dc2626;
        }

        .reply-btn {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.2s ease;
            display: inline-block;
            margin-right: 8px;
        }

        .reply-btn:hover {
            background-color: #2563eb;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            table th, table td {
                padding: 10px 6px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<div class="container">
    @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    <h2>Student Inquiries</h2>

    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Student Name</th>
                <th>Message</th>
                <th>Inquiry Date</th>
                <th>College Reply</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inquiries as $inquiry)
                <tr>
                    <td><b>{{ $loop->index + 1 }}</b></td>
                    <td>{{ $inquiry->student->name }}</td>
                    <td>{{ $inquiry->message }}</td>
                    <td>{{ $inquiry->created_at }}</td>
                    <td>{{ $inquiry->reply ?? 'No reply yet' }}</td>
                    <td>
                        <a href="/college/inquiry/edit/{{ $inquiry->id }}" class="reply-btn">REPLY</a>
                        <a href="/inquiry/delete/{{ $inquiry->id }}" class="action-btn">DELETE</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-data">No inquiries found for this college.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection