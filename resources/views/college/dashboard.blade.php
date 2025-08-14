@extends('layouts.college') 
@section('content') 
<!-- Chart.js CDN --> 
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
 
<style> 
    html, body { 
        overflow-x: hidden; 
        margin: 0; 
        padding: 0; 
        width: 100%; 
        background-color: #f0f2f5; 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        color: #2c3e50;
    } 
 
    .dashboard { 
        padding: 2rem 1.5rem 3rem 1.5rem; 
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
        font-weight: 700;
        color: black;
        margin-bottom: 0.3rem;
        font-weight: 800;
    }
    .dashboard-welcome p {
        font-size: 1rem;
        color: #555555;
        margin: 0;
    }
 
    /* Cards container - row wise */ 
    .hero-body { 
        display: flex; 
        flex-wrap: wrap; 
        justify-content: flex-start;  
        gap: 2rem; 
        padding-bottom: 2rem; 
    } 
 
    /* PROFESSIONAL DASHBOARD CARD DESIGN */ 
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
        position: relative; 
    } 
    .tile-link:hover, 
    .tile-link:focus { 
        box-shadow: 0 16px 40px rgb(13 110 253 / 0.25); 
        transform: translateY(-8px); 
        border-color: black; 
        outline: none; 
        color: black; 
    } 
 
    /* Icon circle container */ 
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
    .tile-link:hover img, 
    .tile-link:focus img { 
        transform: scale(1.15); 
        background: white; 
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
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
    } 
 
    /* Count */ 
    .tile-link p { 
        font-size: 3.4rem; 
        font-weight: 900; 
        color: black; 
        margin: 0; 
        text-align: center; 
        letter-spacing: 0.06em; 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        text-shadow: 0 1px 4px rgba(13, 110, 253, 0.3); 
    } 
 
    /* Graph container styling */ 
    .graphs-container { 
        background: white; 
        padding: 2rem 1.5rem; 
        border-radius: 14px; 
        box-shadow: 0 4px 16px rgb(0 0 0 / 0.1); 
        max-width: 1140px; 
        margin: 0 auto; 
    } 
 
    /* Responsive */ 
    @media (max-width: 576px) { 
        .tile-link { 
            width: 100%; 
            padding: 1.8rem 1.4rem 2rem 1.4rem; 
        } 
        .tile-link h5 { 
            font-size: 0.8rem; 
        } 
        .tile-link p { 
            font-size: 2.8rem; 
        }
        .dashboard-welcome h2 {
            font-size: 1.6rem;
        }
        .dashboard-welcome p {
            font-size: 0.9rem;
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
            <h2>Welcome to Your College Dashboard</h2>
            <p>Quick insights at a glance.</p>
        </div>

        <div class="hero-body"> 
            <a href="/college/course" class="tile-link" aria-label="Number of Courses"> 
                <img src="{{ asset('dashboard/images/book.png') }}" alt="Courses icon" /> 
                <h5>No. of Courses</h5> 
                <p>{{ $coursecount }}</p> 
            </a> 
 
            <a href="/college/course-detail" class="tile-link" aria-label="Number of Course Details"> 
                <img src="{{ asset('dashboard/images/coursedetail.png') }}" alt="Course details icon" /> 
                <h5>No. of Course Details</h5> 
                <p>{{ $coursedetailcount }}</p> 
            </a> 
 
            <a href="/college/view-inquiry" class="tile-link" aria-label="Number of Inquiries"> 
                <img src="{{ asset('dashboard/images/inquiry.png') }}" alt="Inquiry icon" /> 
                <h5>No. of Inquiries</h5> 
                <p>{{ $inquirycount }}</p> 
            </a> 
        </div> 
 
        <!-- Graphs Section --> 
        <section class="graphs-container" aria-label="Summary graphs"> 
            <canvas id="summaryChart" aria-describedby="summaryDescription"></canvas> 
            <div id="summaryDescription" class="visually-hidden"> 
                Bar chart showing number of Courses, Course Details, and Inquiries. 
            </div> 
        </section> 
    </div> 
</div> 
 
<script> 
    const ctxSummary = document.getElementById('summaryChart').getContext('2d'); 
 
    const dataCounts = { 
        courses: {{ $coursecount ?? 0 }}, 
        courseDetails: {{ $coursedetailcount ?? 0 }}, 
        inquiries: {{ $inquirycount ?? 0 }}, 
    }; 
 
    const summaryChart = new Chart(ctxSummary, { 
        type: 'bar', 
        data: { 
            labels: ['Courses', 'Course Details', 'Inquiries'], 
            datasets: [{ 
                label: 'Count', 
                data: [dataCounts.courses, dataCounts.courseDetails, dataCounts.inquiries], 
                backgroundColor: [ 
                    'rgba(0, 123, 255, 0.8)', 
                    'rgba(23, 162, 184, 0.8)', 
                    'rgba(255, 193, 7, 0.8)' 
                ], 
                borderColor: [ 
                    'rgba(0, 123, 255, 1)', 
                    'rgba(23, 162, 184, 1)', 
                    'rgba(255, 193, 7, 1)' 
                ], 
                borderWidth: 1, 
                borderRadius: 8, 
                maxBarThickness: 80, 
            }] 
        }, 
        options: { 
            responsive: true, 
            maintainAspectRatio: false, 
            aspectRatio: 3, 
            scales: { 
                y: { 
                    beginAtZero: true, 
                    ticks: { 
                        precision: 0, 
                        stepSize: 1, 
                        color: '#555', 
                        font: { size: 14, weight: '600' } 
                    }, 
                    grid: { 
                        color: '#eee' 
                    } 
                }, 
                x: { 
                    ticks: { 
                        color: '#333', 
                        font: { size: 15, weight: '700' } 
                    }, 
                    grid: { 
                        display: false 
                    } 
                } 
            }, 
            plugins: { 
                legend: { display: false }, 
                tooltip: { 
                    enabled: true, 
                    callbacks: { 
                        label: ctx => `Count: ${ctx.parsed.y}` 
                    } 
                } 
            } 
        } 
    }); 
</script> 
@endsection
