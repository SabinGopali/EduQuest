<style>
  .site-footer {
    background: white;
    color: #333;
    margin-top: 80px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    border-top: 2px solid black;
  }
  .site-footer a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
  }
  .site-footer a:hover {
    color: #ff4d4d;
  }
  .site-footer__shell {
    max-width: 1200px;
    margin: 0 auto;
    padding: 50px 20px 30px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 36px;
  }
  .site-footer__brand {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }
  .site-footer__brand img {
    width: 140px;
    height: auto;
  }
  .site-footer__brand p {
    font-size: 0.95rem;
    line-height: 1.6;
    color: #666;
    margin: 0;
  }
  .site-footer__title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 14px;
    color: #333;
  }
  .site-footer__list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 10px;
  }
  .site-footer__contact li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    line-height: 1.5;
  }
  .site-footer__contact span {
    color: #666;
    font-size: 0.92rem;
  }
  .site-footer__social {
    display: flex;
    gap: 12px;
    margin-top: 6px;
  }
  .site-footer__social a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border: 1px solid #333;
    border-radius: 50%;
    color: #333;
    transition: all 0.3s ease;
  }
  .site-footer__social a:hover {
    border-color: #ff4d4d;
    color: #ff4d4d;
  }
  .site-footer__bottom {
    border-top: 1px solid rgba(0,0,0,0.1);
    padding: 18px 20px 28px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    font-size: 0.85rem;
    color: #666;
  }
  .site-footer__bottom nav {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    justify-content: center;
  }
  @media (max-width: 640px) {
    .site-footer__shell {
      padding: 40px 20px;
      gap: 28px;
    }
    .site-footer__brand img {
      width: 120px;
    }
  }
</style>

<footer class="site-footer" aria-label="EduQuest global footer">
  <div class="site-footer__shell">
    <section class="site-footer__brand">
      <img src="{{ asset('img/logo.png') }}" alt="EduQuest logo">
      <p>
        EduQuest is Nepal’s first adaptive guidance platform that connects students with verified colleges,
        curated courses, and location-aware recommendations. We help families make confident decisions
        about higher education within minutes.
      </p>
      <div class="site-footer__social" aria-label="Social links">
        <a href="https://www.facebook.com/EduQuestNepal" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a>
        <a href="https://www.instagram.com/eduquest.nepal" target="_blank" rel="noopener"><i class="bi bi-instagram"></i></a>
        <a href="https://www.linkedin.com/company/eduquest-nepal" target="_blank" rel="noopener"><i class="bi bi-linkedin"></i></a>
        <a href="mailto:support@eduquest.com.np" aria-label="Email EduQuest"><i class="bi bi-envelope"></i></a>
      </div>
    </section>

    <section>
      <h3 class="site-footer__title">Explore</h3>
      <ul class="site-footer__list">
        <li><a href="{{ url('/') }}">Home</a></li>
        <li><a href="{{ url('/courses') }}">Course Catalogue</a></li>
        <li><a href="{{ url('/college') }}">Approved Colleges</a></li>
        <li><a href="{{ route('home.nearest') }}">Find Nearby Colleges</a></li>
        <li><a href="{{ url('/recommend-content') }}">Recommended Courses</a></li>
      </ul>
    </section>

    <section>
      <h3 class="site-footer__title">Get Started</h3>
      <ul class="site-footer__list">
        <li><a href="{{ url('/register') }}">Student Sign Up</a></li>
        <li><a href="{{ url('/college-signup') }}">College Registration</a></li>
        <li><a href="{{ url('/login') }}">Student Login</a></li>
        <li><a href="{{ url('/college/login') }}">College Login</a></li>
        <li><a href="{{ url('/view/courses/colleges') }}">Book a Course Visit</a></li>
      </ul>
    </section>

    <section>
      <h3 class="site-footer__title">Contact</h3>
      <ul class="site-footer__list site-footer__contact">
        <li>
          <i class="bi bi-geo-alt"></i>
          <span>
            Kalimati,<br>
            Kathmandu 44600, Nepal
          </span>
        </li>
        <li>
          <i class="bi bi-telephone"></i>
          <a href="tel:+97714455667">+977 9854578214</a>
        </li>
        <li>
          <i class="bi bi-envelope"></i>
          <a href="mailto:support@eduquest.com.np">support@eduquest.com.np</a>
        </li>
        
      </ul>
    </section>
  </div>

  <div class="site-footer__bottom">
    <p>© {{ now()->year }} EduQuest Nepal Pvt. Ltd. All rights reserved.</p>
    <nav aria-label="Legal links">
      <a href="{{ url('/aboutus') }}">About EduQuest</a>
      <a href="{{ url('/contact') }}">Contact</a>
      <a href="{{ url('/college-signup') }}">Partner With Us</a>
    </nav>
  </div>
</footer>
