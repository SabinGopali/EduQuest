@extends('layouts.college')

@section('content')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    body {
        background-color: #f9fafb;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .dashboard {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        padding: 2rem 1rem;
    }

    .dashboard-header {
        text-align: left;
        margin-bottom: 2rem;
    }

    .dashboard-header h2 {
        font-size: 1.8rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 0.4rem;
    }

    .dashboard-header p {
        font-size: 0.95rem;
        color: #6b7280;
    }

    /* Stats grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        transform: translateY(-3px);
    }

    .stat-icon {
        font-size: 2rem;
        padding: 0.8rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-content h5 {
        font-size: 0.9rem;
        color: #6b7280;
        margin: 0;
        font-weight: 500;
    }

    .stat-content p {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0;
        color: #111827;
    }

    /* Graphs container */
    .graphs-container {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .graph-block {
        width: 100%;
        height: 350px;
    }

    @media(max-width: 768px) {
        .graphs-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard">
    <div class="dashboard-header">
        <h2>College Dashboard</h2>
        <p>Overview of courses, details, and inquiries</p>
    </div>

    <!-- Stats cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe; color:#2563eb;">
                📘
            </div>
            <div class="stat-content">
                <h5>No. of Courses</h5>
                <p>{{ $coursecount }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7; color:#16a34a;">
                📖
            </div>
            <div class="stat-content">
                <h5>No. of Course Details</h5>
                <p>{{ $coursedetailcount }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#fef9c3; color:#ca8a04;">
                📨
            </div>
            <div class="stat-content">
                <h5>No. of Inquiries</h5>
                <p>{{ $inquirycount }}</p>
            </div>
        </div>
    </div>

    <!-- Graphs -->
    <div class="graphs-container">
        <div class="graph-block">
            <canvas id="summaryChart"></canvas>
        </div>
        <div class="graph-block">
            <canvas id="pieChart"></canvas>
        </div>
    </div>
</div>

<script>
    const dataCounts = {
        courses: {{ $coursecount ?? 0 }},
        courseDetails: {{ $coursedetailcount ?? 0 }},
        inquiries: {{ $inquirycount ?? 0 }},
    };

    // Bar Chart
    new Chart(document.getElementById('summaryChart'), {
        type: 'bar',
        data: {
            labels: ['Courses', 'Course Details', 'Inquiries'],
            datasets: [{
                label: 'Count',
                data: [dataCounts.courses, dataCounts.courseDetails, dataCounts.inquiries],
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b'],
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Pie Chart
    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: ['Courses', 'Course Details', 'Inquiries'],
            datasets: [{
                data: [dataCounts.courses, dataCounts.courseDetails, dataCounts.inquiries],
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b']
            }]
        }
    });
</script>
@endsection
