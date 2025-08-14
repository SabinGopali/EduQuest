<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Graphy Page</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      font-family: Arial, sans-serif;
      height: 100%;
    }

    .page-wrapper {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .content {
      flex: 1; /* empty spacer pushing footer down */
    }

    .footer {
      background-color: #fff;
      border-top: 1px solid #ddd;
      color: #333;
    }

    .footer-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 40px 20px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 40px;
    }

    .footer-section h4 {
      margin-bottom: 10px;
      font-weight: bold;
    }

    .footer-section ul {
      list-style: none;
      padding: 0;
    }

    .footer-section ul li {
      margin-bottom: 8px;
    }

    .footer-section ul li a {
      text-decoration: none;
      color: #555;
    }

    .footer-section ul li a:hover {
      text-decoration: underline;
    }

    .footer-branding .footer-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 15px;
    }

    .logo-icon {
      width: 28px;
      height: 28px;
    }

    .logo-text {
      font-size: 18px;
      font-weight: bold;
    }

    .footer-description {
      font-size: 14px;
      color: #666;
      line-height: 1.6;
      margin-bottom: 15px;
    }

    .footer-social a img {
      width: 20px;
      height: 20px;
      margin-right: 10px;
      filter: grayscale(100%);
      transition: filter 0.3s ease;
    }

    .footer-social a img:hover {
      filter: none;
    }

    .footer-bottom {
      border-top: 1px solid #eee;
      padding: 20px;
      font-size: 13px;
      color: #777;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .footer-links {
      margin-top: 10px;
    }

    .footer-links a {
      color: #777;
      margin: 0 10px;
      text-decoration: none;
    }

    .footer-links a:hover {
      text-decoration: underline;
    }

    @media(min-width: 768px) {
      .footer-bottom {
        flex-direction: row;
        justify-content: space-between;
        text-align: left;
      }
    }
  </style>
</head>
<body>
  <div class="page-wrapper">
    <div class="content"></div> <!-- empty flex spacer -->

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-container">
        <!-- Branding -->
        <div class="footer-section footer-branding">
          <div class="footer-logo">
            <img src="https://www.svgrepo.com/show/499963/logo.svg" alt="Logo" class="logo-icon">
            <span class="logo-text">EduQuest</span>
          </div>
          <p class="footer-description">
            EduQuest empowers teams to transform raw data into clear, compelling visuals — making insights easier to share, understand, and act on.
          </p>
          <div class="footer-social">
            <a href="#"><img src="https://www.svgrepo.com/show/448221/twitter-x.svg" alt="Twitter"></a>
            <a href="#"><img src="https://www.svgrepo.com/show/452229/instagram.svg" alt="Instagram"></a>
            <a href="#"><img src="https://www.svgrepo.com/show/448234/linkedin.svg" alt="LinkedIn"></a>
            <a href="#"><img src="https://www.svgrepo.com/show/452239/github.svg" alt="GitHub"></a>
          </div>
        </div>

        <!-- Product -->
        <div class="footer-section">
          <h4>Product</h4>
          <ul>
            <li><a href="#">Features</a></li>
            <li><a href="#">Pricing</a></li>
            <li><a href="#">Integrations</a></li>
            <li><a href="#">Changelog</a></li>
          </ul>
        </div>

        <!-- Resources -->
        <div class="footer-section">
          <h4>Resources</h4>
          <ul>
            <li><a href="#">Documentation</a></li>
            <li><a href="#">Tutorials</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">Support</a></li>
          </ul>
        </div>

        <!-- Company -->
        <div class="footer-section">
          <h4>Company</h4>
          <ul>
            <li><a href="#">About</a></li>
            <li><a href="#">Careers</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Partners</a></li>
          </ul>
        </div>
      </div>

      <!-- Footer Bottom -->
      <div class="footer-bottom">
        <p>© 2025 EduQuest. All rights reserved.</p>
        <div class="footer-links">
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Service</a>
          <a href="#">Cookies Settings</a>
        </div>
      </div>
    </footer>
  </div>
</body>
</html>
