@extends('layouts.admin')

@section('content')
<!-- Font Awesome CDN for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        background-color: #f5f7fa;
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .custom-container {
        max-width: 1100px;
        margin: 40px auto;
        background: #ffffff;
        padding: 30px 40px;
        border-radius: 14px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        transition: all 0.3s ease-in-out;
    }

    .custom-container:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    .header {
        text-align: center;
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 1.7rem;
        color: #1f2937;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
    }

    .custom-table thead {
        background-color: #f9fafb;
    }

    .custom-table th {
        padding: 14px 16px;
        font-weight: 600;
        color: #374151;
        font-size: 1rem;
        text-align: center;
        border-bottom: 2px solid #e5e7eb;
    }

    .custom-table td {
        padding: 14px 16px;
        font-size: 0.95rem;
        text-align: center;
        border-bottom: 1px solid #f0f2f5;
        vertical-align: middle;
        color: #4b5563;
    }

    .custom-table tbody tr:hover {
        background-color: #f9fafc;
    }

    .message-cell div {
        max-height: 200px;
        overflow-y: auto;
        padding-right: 8px;
        color: #4b5563;
        text-align: left;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-delete {
        background-color: #ef4444;
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.25s ease;
        text-decoration: none;
        display: inline-block;
        width: 100px;
        text-align: center;
    }
    .btn-delete:hover {
        background-color: #dc2626;
    }

    .btn-read {
        background-color: #10b981;
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.25s ease;
        text-decoration: none;
        display: inline-block;
        width: 100px;
        text-align: center;
    }
    .btn-read:hover {
        background-color: #059669;
    }

    a {
        color: #3b82f6;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    a:hover {
        color: #2563eb;
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .custom-table th,
        .custom-table td {
            font-size: 0.85rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 8px;
        }

        .btn-delete, .btn-read {
            width: 100%;
        }
    }
</style>

<div class="custom-container">
    <h2 class="header">📩 Contact</h2>

    <table class="custom-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Message</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $contact)
            <tr>
                <td><b>{{ $loop->index + 1 }}</b></td>
                <td>
                    <a href="mailto:{{ $contact->email }}" target="_blank">
                        {{ $contact->email }}
                    </a>
                </td>
                <td class="message-cell">
                    <div>{{ $contact->message }}</div>
                </td>
                <td>{{ $contact->status }}</td>
                <td>
                    <div class="action-buttons">
                        <a href="/contact/delete/{{ $contact->id }}" class="btn-delete" title="Delete">DELETE</a>
                        @if ($contact->status == 'pending')
                            <a href="/contact/update-status/{{ $contact->id }}" class="btn-read" title="Mark as Read">READ</a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
