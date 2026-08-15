/* =========================================================
   slider.js
   Simple auto-playing image slider (no external library).
   Topic 12: JavaScript
========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    var slides = document.querySelectorAll('.slide');
    var dots = document.querySelectorAll('.slider-dots span');
    var current = 0;
    var timer = null;

    if (slides.length === 0) return; // no slider on this page

    function showSlide(index) {
        // wrap around if index goes out of range
        if (index >= slides.length) index = 0;
        if (index < 0) index = slides.length - 1;

        slides.forEach(function (slide) { slide.classList.remove('active'); });
        dots.forEach(function (dot) { dot.classList.remove('active'); });

        slides[index].classList.add('active');
        if (dots[index]) dots[index].classList.add('active');

        current = index;
    }

    function nextSlide() { showSlide(current + 1); }
    function prevSlide() { showSlide(current - 1); }

    function startAutoPlay() {
        timer = setInterval(nextSlide, 4500); // change slide every 4.5 seconds
    }

    function stopAutoPlay() {
        clearInterval(timer);
    }

    // Buttons
    var nextBtn = document.querySelector('.slider-arrow.next');
    var prevBtn = document.querySelector('.slider-arrow.prev');
    if (nextBtn) nextBtn.addEventListener('click', function () { nextSlide(); stopAutoPlay(); startAutoPlay(); });
    if (prevBtn) prevBtn.addEventListener('click', function () { prevSlide(); stopAutoPlay(); startAutoPlay(); });

    // Dots
    dots.forEach(function (dot, index) {
        dot.addEventListener('click', function () {
            showSlide(index);
            stopAutoPlay();
            startAutoPlay();
        });
    });

    showSlide(0);
    startAutoPlay();
});
