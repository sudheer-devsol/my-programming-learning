/* ============ MOBILE NAV TOGGLE ============ */
var hamburgerBtn = document.getElementById('hamburger-btn');
var mobileMenu   = document.getElementById('mobile-menu');

hamburgerBtn.addEventListener('click', function () {
  var isOpen = mobileMenu.getAttribute('data-open') === 'true';
  mobileMenu.setAttribute('data-open', isOpen ? 'false' : 'true');
});

// Close the mobile menu whenever a nav link is tapped
var mobileLinks = mobileMenu.querySelectorAll('a');
mobileLinks.forEach(function (link) {
  link.addEventListener('click', function () {
    mobileMenu.setAttribute('data-open', 'false');
  });
});


/* ============ THEME TOGGLE (light / dark class on <html>) ============ */
var themeToggle = document.getElementById('theme-toggle');
themeToggle.addEventListener('click', function () {
  document.documentElement.classList.toggle('dark');
});


/* ============ FAQ ACCORDION ============ */
var faqItems = document.querySelectorAll('.faq-item');

faqItems.forEach(function (item) {
  var question = item.querySelector('.faq-question');
  var answer   = item.querySelector('.faq-answer');

  question.addEventListener('click', function () {
    var isOpen = item.getAttribute('data-open') === 'true';

    // Close every other open item (accordion behaviour — only one open at a time)
    faqItems.forEach(function (other) {
      other.setAttribute('data-open', 'false');
      other.querySelector('.faq-answer').style.maxHeight = null;
    });

    if (!isOpen) {
      item.setAttribute('data-open', 'true');
      answer.style.maxHeight = answer.scrollHeight + 'px';
    }
  });
});


/* ============ TESTIMONIAL CAROUSEL ============ */
var track    = document.getElementById('testimonial-track');
var cards    = document.querySelectorAll('.testimonial-card');
var dotsWrap = document.getElementById('testimonial-dots');
var prevBtn  = document.getElementById('testimonial-prev');
var nextBtn  = document.getElementById('testimonial-next');
var current  = 0;

// Build one dot per slide
cards.forEach(function (card, index) {
  var dot = document.createElement('button');
  dot.className = 'w-2 h-2 rounded-full bg-slate-300';
  dot.setAttribute('aria-label', 'Go to testimonial ' + (index + 1));
  dot.addEventListener('click', function () {
    goToSlide(index);
  });
  dotsWrap.appendChild(dot);
});

var dots = dotsWrap.querySelectorAll('button');

function updateDots() {
  dots.forEach(function (dot, index) {
    dot.className = index === current
      ? 'w-6 h-2 rounded-full bg-primary transition-all'
      : 'w-2 h-2 rounded-full bg-slate-300 transition-all';
  });
}

function goToSlide(index) {
  var total = cards.length;
  current = (index + total) % total;
  track.style.transform = 'translateX(-' + (current * 100) + '%)';
  updateDots();
}

prevBtn.addEventListener('click', function () { goToSlide(current - 1); });
nextBtn.addEventListener('click', function () { goToSlide(current + 1); });

// Auto-advance every 6 seconds
setInterval(function () { goToSlide(current + 1); }, 6000);

updateDots();


/* ============ CONTACT FORM SUBMIT (frontend-only demo) ============ */
var contactForm = document.getElementById('contact-form');
var formSuccess = document.getElementById('form-success');

contactForm.addEventListener('submit', function (e) {
  e.preventDefault();
  formSuccess.classList.remove('hidden');
  contactForm.reset();
});
