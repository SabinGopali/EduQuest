<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Font Awesome CDN for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
  <!-- Bootstrap 5 CSS for components/modals -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* Reset & base */
    *, *::before, *::after {
      box-sizing: border-box;
    }
    body, html {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f4f8;
      color: #222;
      height: 100%;
      overflow-x: hidden;
    }
    a {
      text-decoration: none;
      color: inherit;
    }

    /* Dashboard container */
    .dashboard {
      display: flex;
      min-height: 100vh;
      width: 100%;
    }

    /* Sidebar */
    nav.sidebar {
      width: 280px;
      background: #fff;
      box-shadow: 4px 0 12px rgba(0,0,0,0.1);
      padding: 25px 20px;
      display: flex;
      flex-direction: column;
      user-select: none;
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      overflow-y: auto;
      border-radius: 0 20px 20px 0;
      font-weight: 600;
      z-index: 1000;
      transition: transform 0.3s ease;
    }

    nav.sidebar .logo {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 35px;
      color: #000;
      text-shadow: 0 1px 2px rgba(0,0,0,0.2);
      text-align: center;
      user-select: none;
    }

    nav.sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
      flex-grow: 1;
    }

    nav.sidebar ul li {
      margin-bottom: 15px;
    }

    nav.sidebar ul li a {
      display: flex;
      align-items: center;
      padding: 14px 18px;
      border-radius: 8px;
      color: #222;
      font-size: 1rem;
      transition: background-color 0.3s ease, color 0.3s ease;
      position: relative;
      outline-offset: 2px;
    }
    nav.sidebar ul li a i {
      margin-right: 16px;
      font-size: 1.2rem;
      min-width: 22px;
      text-align: center;
      color: #222;
      transition: color 0.3s ease;
    }

    /* Active / Hover state */
    nav.sidebar ul li a.active,
    nav.sidebar ul li a:hover,
    nav.sidebar ul li a:focus {
      background-color: #000;
      color: #fff;
      outline: none;
    }
    nav.sidebar ul li a.active i,
    nav.sidebar ul li a:hover i,
    nav.sidebar ul li a:focus i {
      color: #fff;
    }
    nav.sidebar ul li a.active::before,
    nav.sidebar ul li a:hover::before,
    nav.sidebar ul li a:focus::before {
      content: '';
      position: absolute;
      left: 0;
      top: 6px;
      bottom: 6px;
      width: 4px;
      background: red; /* golden accent */
      border-radius: 0 4px 4px 0;
    }

    nav.sidebar ul li a.logout {
      color: #e74c3c;
      font-weight: 700;
    }
    nav.sidebar ul li a.logout:hover,
    nav.sidebar ul li a.logout:focus {
      background: rgba(231, 76, 60, 0.15);
      color: #c0392b;
      outline: none;
    }

    /* Main content */
    main.main {
      margin-left: 280px;
      padding: 40px 40px;
      flex-grow: 1;
      background-color: #f0f4f8;
      min-height: 100vh;
      overflow-y: auto;
      transition: margin-left 0.3s ease, padding 0.3s ease;
      color: #222;
    }

    /* Mobile menu toggle button */
    button.toggle-btn {
      position: fixed;
      top: 15px;
      left: 15px;
      width: 48px;
      height: 48px;
      border-radius: 8px;
      border: none;
      background: #0059b3;
      color: #fff;
      font-size: 1.7rem;
      cursor: pointer;
      z-index: 1100;
      box-shadow: 0 3px 8px rgba(0,0,0,0.25);
      display: none;
      transition: background-color 0.3s ease;
    }
    button.toggle-btn:hover,
    button.toggle-btn:focus {
      background: #003d7a;
      outline: none;
    }

    /* Overlay behind sidebar on mobile */
    div.overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.4);
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease;
      z-index: 1050;
    }
    div.overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* Responsive */
    @media (max-width: 992px) {
      nav.sidebar {
        transform: translateX(-100%);
        position: fixed;
        z-index: 1100;
        border-radius: 0;
      }
      nav.sidebar.show {
        transform: translateX(0);
      }
      main.main {
        margin-left: 0;
        padding: 25px 20px;
      }
      button.toggle-btn {
        display: block;
      }
    }
  </style>
</head>
<body>

  <button class="toggle-btn" aria-label="Toggle sidebar" aria-expanded="false" aria-controls="sidebar" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  <div class="dashboard">
    <nav class="sidebar" id="sidebar" tabindex="-1" aria-label="Sidebar Navigation">
      <a href="/admin/dashboard" class="logo">
        Admin Dashboard
      </a>
      <ul>
        <li><a href="/admin/college/show"><i class="fas fa-building"></i> Manage College</a></li>
        <li><a href="/admin/student/show"><i class="fas fa-user-graduate"></i> Manage Students</a></li>
        <li><a href="/admin/course/show"><i class="fas fa-book"></i> View Courses</a></li>
        <li><a href="/admin/course-detail/show"><i class="fas fa-info-circle"></i> View Course Detail</a></li>
        <li><a href="/admin/contact/show"><i class="fas fa-envelope"></i> Manage Contact</a></li>
        <li><a href="/admin/inquiry/show"><i class="fas fa-question-circle"></i> View Inquiry</a></li>
         <li><a href="/admin/bookings"><i class="fas fa-calendar-check"></i> View Bookings</a></li>
        <li><a href="/admin/change-password"><i class="fas fa-key"></i> Change password</a></li>
        <li><a href="/admin/logout" class="logout"><i class="fas fa-sign-out-alt"></i> Log out</a></li>
      </ul>
    </nav>

    <main class="main" tabindex="0">
      @yield('content')
    </main>
  </div>

  <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

  <script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleBtn = document.querySelector('.toggle-btn');

    function toggleSidebar() {
      const isVisible = sidebar.classList.toggle('show');
      overlay.classList.toggle('active', isVisible);
      toggleBtn.setAttribute('aria-expanded', isVisible);
      if (isVisible) {
        sidebar.focus();
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
        toggleBtn.focus();
      }
    }

    function closeSidebar() {
      sidebar.classList.remove('show');
      overlay.classList.remove('active');
      toggleBtn.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
      toggleBtn.focus();
    }

    document.addEventListener('keydown', e => {
      if(e.key === "Escape" && sidebar.classList.contains('show')) {
        closeSidebar();
      }
    });

    // Highlight current sidebar link
    document.addEventListener('DOMContentLoaded', () => {
      const currentPath = window.location.pathname;
      document.querySelectorAll('nav.sidebar ul li a').forEach(link => {
        link.classList.remove('active');
        const linkPath = new URL(link.href, window.location.origin).pathname;
        if(linkPath === currentPath) {
          link.classList.add('active');
        }
      });
    });
  </script>
  <!-- Bootstrap 5 JS bundle (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>