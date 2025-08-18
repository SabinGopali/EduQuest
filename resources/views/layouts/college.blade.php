<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>College Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f0f4f8;
      overflow-x: hidden;
    }

    .dashboard {
      display: flex;
      min-height: 100vh;
      width: 100%;
      position: relative;
    }

    /* Sidebar */
    .sidebar {
      width: 280px;
      background: white;
      color: white;
      border: none;
      border-radius: 20px;
      padding: 25px 20px;
      position: relative;
      transition: transform 0.3s ease;
      overflow-y: auto;
      box-shadow: 4px 0 12px rgba(0, 0, 0, 0.15);
      font-weight: 600;
    }

    .sidebar .logo {
      text-align: center;
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 35px;
      color: black;
      text-shadow: 0 1px 2px rgba(0,0,0,0.3);
      user-select: none;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar ul li {
      margin-bottom: 15px;
      position: relative;
    }

    .sidebar ul a {
      text-decoration: none;
      color: black;
      display: flex;
      align-items: center;
      padding: 14px 18px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 1rem;
      transition: background-color 0.3s ease, color 0.3s ease;
      user-select: none;
      position: relative;
    }

    .sidebar ul a i {
      margin-right: 16px;
      color: black;
      min-width: 22px;
      text-align: center;
      font-size: 1.2rem;
      transition: color 0.3s ease;
    }

    /* Vertical active indicator */
    .sidebar ul a.active,
    .sidebar ul a:hover,
    .sidebar ul a:focus {
      background-color: black;
      color: white;
      outline: none;
    }
    .sidebar ul a.active i,
    .sidebar ul a:hover i,
    .sidebar ul a:focus i {
      color: white;
    }
    .sidebar ul a.active::before,
    .sidebar ul a:hover::before,
    .sidebar ul a:focus::before {
      content: '';
      position: absolute;
      left: 0;
      top: 6px;
      bottom: 6px;
      width: 4px;
      background: red; /* yellow accent */
      border-radius: 0 4px 4px 0;
    }

    .sidebar ul a.logout {
      color: #ff6f61 !important;
      font-weight: 700;
    }

    .sidebar ul a.logout:hover,
    .sidebar ul a.logout:focus {
      background: rgba(255, 111, 97, 0.25);
      color: #ff3b2e !important;
      outline: none;
    }

    /* Main content */
    .main {
      flex-grow: 1;
      padding: 40px 40px;
      background-color: #f0f4f8;
      overflow-y: auto;
      min-height: 100vh;
      transition: padding 0.3s ease;
      color: black;
    }

    /* Toggle Button */
    .toggle-btn {
      display: none;
      position: fixed;
      top: 15px;
      left: 15px;
      font-size: 1.7rem;
      background: #0059b3;
      border: none;
      color: white;
      width: 48px;
      height: 48px;
      border-radius: 8px;
      cursor: pointer;
      z-index: 1200;
      box-shadow: 0 3px 8px rgba(0,0,0,0.25);
      transition: background-color 0.3s ease;
    }
    .toggle-btn:hover,
    .toggle-btn:focus {
      background: #003d7a;
      outline: none;
    }

    /* Overlay for mobile when sidebar open */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0,0,0,0.4);
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease;
      z-index: 1050;
    }
    .overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* Responsive */
    @media (max-width: 992px) {
      .sidebar {
        width: 280px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        transform: translateX(-100%);
        z-index: 1100;
        box-shadow: 4px 0 12px rgba(0,0,0,0.25);
      }

      .sidebar.show {
        transform: translateX(0);
      }

      .main {
        padding: 25px 20px;
      }

      .toggle-btn {
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
      <a href="/college/dashboard" class="logo">
        @auth('college')
          <p>{{ Auth::guard('college')->user()->name }}</p>
        @endauth
      </a>
      <ul>
        <li>
          <a href="/college/edit-profile">
            <i class="fas fa-user"></i> Edit Profile
          </a>
        </li>
        <li>
          <a href="/college/course-detail">
            <i class="fas fa-book-open"></i> Manage Course Detail
          </a>
        </li>
        <li>
          <a href="/college/view-inquiry">
            <i class="fas fa-question-circle"></i> Manage Inquiry
          </a>
        </li>
         <li>
          <a href="/college/bookings">
            <i class="fas fa-calendar-check"></i> Manage Bookings
          </a>
        </li>
        <li>
          <a href="/college/change-password">
            <i class="fas fa-lock"></i> Edit Password
          </a>
        </li>
        <li>
          <a href="/college/logout" class="logout">
            <i class="fas fa-sign-out-alt"></i> Log out
          </a>
        </li>
      </ul>
    </nav>

    <main class="main" tabindex="0">
      @yield('content')
    </main>
  </div>

  <!-- Overlay for closing sidebar on mobile -->
  <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

  <script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleBtn = document.querySelector('.toggle-btn');

    function toggleSidebar() {
      const isShown = sidebar.classList.toggle('show');
      overlay.classList.toggle('active', isShown);
      toggleBtn.setAttribute('aria-expanded', isShown);
      if (isShown) {
        sidebar.focus();
        document.body.style.overflow = 'hidden'; // prevent body scroll when sidebar open
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

    // Close sidebar on ESC key when open (mobile)
    document.addEventListener('keydown', (e) => {
      if (e.key === "Escape" && sidebar.classList.contains('show')) {
        closeSidebar();
      }
    });

    // Add 'active' class to the sidebar link matching current URL path
    document.addEventListener('DOMContentLoaded', () => {
      const currentUrl = window.location.pathname;
      const sidebarLinks = document.querySelectorAll('.sidebar ul a');

      sidebarLinks.forEach(link => {
        link.classList.remove('active');
        const linkUrl = new URL(link.href, window.location.origin);
        if (linkUrl.pathname === currentUrl) {
          link.classList.add('active');
        }
      });
    });
  </script>

</body>
</html>
