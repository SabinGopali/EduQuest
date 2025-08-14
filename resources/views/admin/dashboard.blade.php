@extends('layouts.admin')
@section('content')

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    html, body {
        overflow-x: hidden;
        margin-top: 0;
        padding: 0;
        width: 100%;
        background-color: #f0f2f5;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #2c3e50;
    }

    .dashboard {
        padding: 0 1.5rem 3rem 1.5rem;
        background: #f0f2f5;
        min-height: 100vh;
        box-sizing: border-box;
    }

    .content-wrapper {
        max-width: 1140px;
        margin: 0 auto;
        padding: 0 1rem;
        box-sizing: border-box;
    }

    /* Welcome header */
    .dashboard-welcome {
        text-align: center;
        margin-bottom: 2rem;
    }
    .dashboard-welcome h2 {
        font-size: 1.9rem;
        font-weight: 800;
        color: black;
        margin-bottom: 0.3rem;
    }
    .dashboard-welcome p {
        font-size: 1rem;
        color: #555555;
        margin: 0;
    }

    /* Cards container */
    .hero-body {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        gap: 2rem;
        padding-bottom: 2rem;
    }

    /* Dashboard cards */
    .tile-link {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgb(0 0 0 / 0.07);
        padding: 2rem 2rem 2.5rem 2rem;
        text-decoration: none !important;
        color: #222 !important;
        width: 320px;
        user-select: none;
        border: 1px solid #d4d9e2;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        transition: box-shadow 0.4s ease, transform 0.3s ease;
    }
    .tile-link:hover {
        box-shadow: 0 16px 40px rgb(13 110 253 / 0.25);
        transform: translateY(-8px);
        border-color: black;
        color: black !important;
    }

    /* Icon styles */
    .tile-link img {
        width: 60px;
        height: 60px;
        object-fit: contain;
        margin-bottom: 1.5rem;
        border-radius: 50%;
        padding: 12px;
        background: white;
        box-shadow: 3px 4px 10px rgb(0 0 0 / 0.05);
        transition: transform 0.3s ease;
    }
    .tile-link:hover img {
        transform: scale(1.15);
        box-shadow: 5px 6px 18px rgb(13 110 253 / 0.4);
    }

    /* Title */
    .tile-link h5 {
        font-size: 0.85rem;
        font-weight: 600;
        color: #4a5568;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin-bottom: 0.3rem;
        text-align: center;
    }

    /* Count */
    .tile-link p {
        font-size: 3.4rem;
        font-weight: 900;
        color: black;
        margin: 0;
        text-align: center;
        letter-spacing: 0.06em;
        text-shadow: 0 1px 4px rgba(13, 110, 253, 0.3);
    }

    /* Graph section */
    .graphs-container {
        background: white;
        padding: 2rem 1.5rem;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgb(0 0 0 / 0.1);
        max-width: 1140px;
        margin: 0 auto;
    }

    @media (max-width: 576px) {
        .tile-link {
            width: 100%;
            padding: 1.8rem 1.4rem 2rem 1.4rem;
        }
        .tile-link p {
            font-size: 2.8rem;
        }
        .dashboard-welcome h2 {
            font-size: 1.6rem;
        }
    }
</style>

<div class="dashboard">
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="content-wrapper">
        <div class="dashboard-welcome">
            <h2>Welcome to Your Admin Dashboard</h2>
            <p>Quick insights at a glance.</p>
        </div>

        <div class="hero-body">
            <a href="/admin/course/show" class="tile-link">
                <img src="{{ asset('dashboard/images/book.png') }}" alt="Course" />
                <h5>No. of Course</h5>
                <p>{{ $coursecount }}</p>
            </a>

            <a href="/admin/college/show" class="tile-link">
                <img src="{{ asset('dashboard/images/college.png') }}" alt="College" />
                <h5>No. of College</h5>
                <p>{{ $collegecount }}</p>
            </a>

            <a href="/admin/student/show" class="tile-link">
                <img src="{{ asset('dashboard/images/student.png') }}" alt="Student" />
                <h5>No. of Student</h5>
                <p>{{ $studentscount }}</p>
            </a>

            <a href="/admin/course-detail/show" class="tile-link">
                <img src="{{ asset('dashboard/images/coursedetail.png') }}" alt="Course Detail" />
                <h5>No. of CourseDetail</h5>
                <p>{{ $coursedetailcount }}</p>
            </a>

            <a href="/admin/contact/show" class="tile-link">
                <img src="{{ asset('dashboard/images/message.png') }}" alt="Contact" />
                <h5>No. of Contact</h5>
                <p>{{ $contactcount }}</p>
            </a>

            <a href="/admin/inquiry/show" class="tile-link">
                <img src="{{ asset('dashboard/images/inquiry.png') }}" alt="Inquiry" />
                <h5>No. of Inquiry</h5>
                <p>{{ $inquirycount }}</p>
            </a>
        </div>

        <!-- Optional graph -->
        <section class="graphs-container">
            <canvas id="summaryChart"></canvas>
        </section>
    </div>
</div>

<script>
    const ctx = document.getElementById('summaryChart').getContext('2d');
    const chartData = {
        labels: ['Courses', 'Colleges', 'Students', 'Course Details', 'Contacts', 'Inquiries'],
        datasets: [{
            label: 'Count',
            data: [
                {{ $coursecount }},
                {{ $collegecount }},
                {{ $studentscount }},
                {{ $coursedetailcount }},
                {{ $contactcount }},
                {{ $inquirycount }}
            ],
            backgroundColor: [
                'rgba(0, 123, 255, 0.8)',
                'rgba(23, 162, 184, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(40, 167, 69, 0.8)',
                'rgba(220, 53, 69, 0.8)',
                'rgba(108, 117, 125, 0.8)'
            ],
            borderRadius: 5
        }]
    };
    new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
</script>
@endsection
