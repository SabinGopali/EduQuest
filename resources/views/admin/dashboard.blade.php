@extends('layouts.admin')
@section('content')

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .content-dashboard {
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

<div class="content-dashboard">
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(($pendingCollegeCount ?? 0) > 0 || ($pendingCourseDetailCount ?? 0) > 0)
        <div class="alert alert-warning" role="alert">
            <strong>Approvals pending:</strong>
            @if(($pendingCollegeCount ?? 0) > 0)
                {{ $pendingCollegeCount }} college(s)
                <a href="/admin/college/show" class="alert-link">review</a>
            @endif
            @if(($pendingCollegeCount ?? 0) > 0 && ($pendingCourseDetailCount ?? 0) > 0)
                &nbsp;|&nbsp;
            @endif
            @if(($pendingCourseDetailCount ?? 0) > 0)
                {{ $pendingCourseDetailCount }} course detail(s)
                <a href="/admin/course-detail/show" class="alert-link">review</a>
            @endif
        </div>
        <script>
            // Optional: draw admin's attention on first load
            window.addEventListener('load', function() {
                try {
                    const alertBox = document.querySelector('.alert.alert-warning');
                    if (alertBox) {
                        alertBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                } catch (e) {}
            });
        </script>
    @endif

    <div class="dashboard-header">
        <h2>Admin Dashboard</h2>
        <p>Overview of entities and activity</p>
    </div>

    <div class="stats-grid">
        <a href="/admin/course/show" class="stat-card" style="text-decoration:none; color:inherit;">
            <div class="stat-icon" style="background:#dbeafe; color:#2563eb;">📘</div>
            <div class="stat-content">
                <h5>No. of Courses</h5>
                <p>{{ $coursecount }}</p>
            </div>
        </a>
        <a href="/admin/college/show" class="stat-card" style="text-decoration:none; color:inherit;">
            <div class="stat-icon" style="background:#fde68a; color:#b45309;">🏫</div>
            <div class="stat-content">
                <h5>No. of Colleges</h5>
                <p>{{ $collegecount }}</p>
            </div>
        </a>
        <a href="/admin/student/show" class="stat-card" style="text-decoration:none; color:inherit;">
            <div class="stat-icon" style="background:#dcfce7; color:#16a34a;">🧑‍🎓</div>
            <div class="stat-content">
                <h5>No. of Students</h5>
                <p>{{ $studentscount }}</p>
            </div>
        </a>
        <a href="/admin/course-detail/show" class="stat-card" style="text-decoration:none; color:inherit;">
            <div class="stat-icon" style="background:#e0e7ff; color:#4f46e5;">📖</div>
            <div class="stat-content">
                <h5>No. of Course Details</h5>
                <p>{{ $coursedetailcount }}</p>
            </div>
        </a>
        <a href="/admin/contact/show" class="stat-card" style="text-decoration:none; color:inherit;">
            <div class="stat-icon" style="background:#fee2e2; color:#dc2626;">✉️</div>
            <div class="stat-content">
                <h5>No. of Contacts</h5>
                <p>{{ $contactcount }}</p>
            </div>
        </a>
        <a href="/admin/inquiry/show" class="stat-card" style="text-decoration:none; color:inherit;">
            <div class="stat-icon" style="background:#fef9c3; color:#ca8a04;">📨</div>
            <div class="stat-content">
                <h5>No. of Inquiries</h5>
                <p>{{ $inquirycount }}</p>
            </div>
        </a>
    </div>

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
        colleges: {{ $collegecount ?? 0 }},
        students: {{ $studentscount ?? 0 }},
        courseDetails: {{ $coursedetailcount ?? 0 }},
        contacts: {{ $contactcount ?? 0 }},
        inquiries: {{ $inquirycount ?? 0 }}
    };

    new Chart(document.getElementById('summaryChart'), {
        type: 'bar',
        data: {
            labels: ['Courses', 'Colleges', 'Students', 'Course Details', 'Contacts', 'Inquiries'],
            datasets: [{
                label: 'Count',
                data: [dataCounts.courses, dataCounts.colleges, dataCounts.students, dataCounts.courseDetails, dataCounts.contacts, dataCounts.inquiries],
                backgroundColor: ['#3b82f6', '#06b6d4', '#f59e0b', '#10b981', '#ef4444', '#6b7280'],
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: ['Courses', 'Colleges', 'Students', 'Course Details', 'Contacts', 'Inquiries'],
            datasets: [{
                data: [dataCounts.courses, dataCounts.colleges, dataCounts.students, dataCounts.courseDetails, dataCounts.contacts, dataCounts.inquiries],
                backgroundColor: ['#3b82f6', '#06b6d4', '#f59e0b', '#10b981', '#ef4444', '#6b7280']
            }]
        }
    });
</script>
@endsection