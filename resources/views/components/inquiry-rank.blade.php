<section id="inquiry_rank">
<style>
  .scr-container { 
    max-width: 1400px; 
    margin: {{ $showAll ? '200px auto 60px' : '40px auto' }}; 
    padding: 0 20px; 
  }
  .scr-page-title { 
    text-align: center; 
    font-size: {{ $showAll ? '2.2rem' : '2rem' }}; 
    font-weight: 800; 
    color: #222; 
    margin-bottom: 8px; 
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease;
  }
  .scr-page-title.animate {
    opacity: 1;
    transform: translateY(0);
  }
  .scr-subtitle { 
    text-align: center; 
    color: #555; 
    margin-bottom: 28px; 
    font-size: {{ $showAll ? '1.1rem' : '1rem' }};
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease 0.2s;
  }
  .scr-subtitle.animate {
    opacity: 1;
    transform: translateY(0);
  }
  .scr-grid { 
    display: flex; 
    flex-wrap: wrap; 
    gap: 20px; 
    justify-content: center; 
  }
  .scr-card { 
    width: 320px; 
    background: #fff; 
    border: 1px solid #eee; 
    border-radius: 10px; 
    padding: 16px; 
    box-shadow: 0 4px 10px rgb(0 0 0 / 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    opacity: 0;
    transform: translateY(50px) scale(0.9);
    animation: slideInUp 0.6s ease forwards;
  }
  .scr-card:nth-child(1) { animation-delay: 0.1s; }
  .scr-card:nth-child(2) { animation-delay: 0.2s; }
  .scr-card:nth-child(3) { animation-delay: 0.3s; }
  .scr-card:nth-child(4) { animation-delay: 0.4s; }
  .scr-card:nth-child(5) { animation-delay: 0.5s; }
  .scr-card:nth-child(6) { animation-delay: 0.6s; }
  .scr-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 8px 25px rgb(0 0 0 / 0.15);
  }
  @keyframes slideInUp {
    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }
  .scr-rank { 
    text-align: center; 
    font-weight: 700; 
    color: #0d6efd; 
    margin-bottom: 6px; 
    font-size: 1.1rem;
  }
  .scr-logo { 
    height: 80px; 
    width: 80px; 
    object-fit: contain; 
    border-radius: 50%; 
    border: 2px solid #0d6efd; 
    background: #fff; 
    padding: 5px; 
    display:block; 
    margin: 6px auto 12px; 
  }
  .scr-name { 
    text-align: center; 
    font-weight: 700; 
    color: #222; 
    margin-bottom: 6px; 
    font-size: 1.1rem;
  }
  .scr-meta { 
    text-align: center; 
    color: #666; 
    font-size: 14px; 
    margin-bottom: 8px; 
  }
  .scr-badge { 
    display: inline-block; 
    background: #f4f6f9; 
    padding: 6px 10px; 
    border-radius: 6px; 
    font-size: 12px; 
    color: #333; 
    margin-bottom: 10px;
  }
  .scr-actions { 
    display: flex; 
    justify-content: center; 
    margin-top: 10px; 
  }
  .scr-btn { 
    border-radius: 8px; 
    font-weight: 600; 
    padding: 10px 20px; 
    border: none; 
    cursor: pointer; 
    transition: all 0.2s ease; 
    text-decoration: none;
    display: inline-block;
  }
  .scr-btn-primary { 
    background-color: white; 
    color: black; 
    border: 1px solid black;
  }
  .scr-btn-primary:hover { 
    background-color: black; 
    color: white;
    
  }
  .scr-view-all {
    text-align: center;
    margin-top: 30px;
  }
  .scr-view-all-btn {
    background: transparent;
    color: #0d6efd;
    border: 2px solid #0d6efd;
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
  }
  .scr-view-all-btn:hover {
    background: #0d6efd;
    color: white;
    text-decoration: none;
  }
  .scr-view-all {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease 0.8s;
  }
  .scr-view-all.animate {
    opacity: 1;
    transform: translateY(0);
  }
  .scr-rank {
    opacity: 0;
    transform: scale(0.5);
    transition: all 0.5s ease;
  }
  .scr-card.animate .scr-rank {
    opacity: 1;
    transform: scale(1);
  }
  .scr-logo {
    opacity: 0;
    transform: rotate(-10deg) scale(0.8);
    transition: all 0.6s ease 0.2s;
  }
  .scr-card.animate .scr-logo {
    opacity: 1;
    transform: rotate(0deg) scale(1);
  }
  .scr-name {
    opacity: 0;
    transform: translateX(-20px);
    transition: all 0.5s ease 0.3s;
  }
  .scr-card.animate .scr-name {
    opacity: 1;
    transform: translateX(0);
  }
  .scr-meta {
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.5s ease 0.4s;
  }
  .scr-card.animate .scr-meta {
    opacity: 1;
    transform: translateX(0);
  }
  .scr-badge {
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.5s ease 0.5s;
  }
  .scr-card.animate .scr-badge {
    opacity: 1;
    transform: scale(1);
  }
  .scr-actions {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.5s ease 0.6s;
  }
  .scr-card.animate .scr-actions {
    opacity: 1;
    transform: translateY(0);
  }
  @media(max-width: 768px){ 
    .scr-card{ width: 100%; } 
    .scr-container{ margin-top: {{ $showAll ? '140px' : '20px' }}; } 
  }
</style>

<div class="scr-container">
  <h1 class="scr-page-title">{{ $showAll ? 'Most Booked Colleges' : '🏆 Most Popular Colleges' }}</h1>
  <p class="scr-subtitle">
    {{ $showAll ? 'Ranked by how many course bookings each college has received.' : 'Discover the most booked colleges by students. These institutions are highly sought after for their quality education.' }}
  </p>

  <div class="scr-grid">
    @forelse($items as $index => $item)
      @php $college = $item['college']; $count = $item['bookings']; @endphp
      <div class="scr-card">
        {{-- <div class="scr-rank">#{{ $index + 1 }}</div> --}}
        <img class="scr-logo" src="{{ isset($college->logo) ? asset('storage/' . $college->logo) : asset('img/landing.jpg') }}" alt="Logo" />
        <div class="scr-name">{{ $college->name }}</div>
        <div class="scr-meta">{{ $college->address }}</div>
        {{-- <div class="scr-badge">{{ $count }} {{ $count == 1 ? 'Booking' : 'Bookings' }}</div> --}}
        <div class="scr-actions">
          <a class="scr-btn scr-btn-primary" href="/college/detail/{{ $college->id }}">View Details</a>
        </div>
      </div>
    @empty
      <div style="text-align: center; padding: 40px; color: #666;">
        <h3>No colleges available.</h3>
        <p>Check back later for college rankings.</p>
      </div>
    @endforelse
  </div>

  @if(!$showAll && $items->count() > 0)
    
  @endif
</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Create intersection observer for scroll animations
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate');
        
        // Add staggered animation to cards
        if (entry.target.classList.contains('scr-grid')) {
          const cards = entry.target.querySelectorAll('.scr-card');
          cards.forEach((card, index) => {
            setTimeout(() => {
              card.classList.add('animate');
            }, index * 100);
          });
        }
      }
    });
  }, observerOptions);

  // Observe elements for animation
  const elementsToAnimate = document.querySelectorAll('#inquiry_rank .scr-page-title, #inquiry_rank .scr-subtitle, #inquiry_rank .scr-grid, #inquiry_rank .scr-view-all');
  elementsToAnimate.forEach(el => {
    observer.observe(el);
  });

  // Add floating animation to cards on hover
  const cards = document.querySelectorAll('#inquiry_rank .scr-card');
  cards.forEach(card => {
    card.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-8px) scale(1.02)';
      this.style.boxShadow = '0 12px 30px rgba(0, 0, 0, 0.2)';
    });
    
    card.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0) scale(1)';
      this.style.boxShadow = '0 4px 10px rgba(0, 0, 0, 0.08)';
    });
  });

  // Add pulse animation to rank numbers
  const ranks = document.querySelectorAll('#inquiry_rank .scr-rank');
  ranks.forEach(rank => {
    rank.addEventListener('animationend', function() {
      this.style.animation = 'pulse 2s infinite';
    });
  });
});

// Add pulse keyframe animation
const style = document.createElement('style');
style.textContent = `
  @keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
  }
`;
document.head.appendChild(style);
</script>
