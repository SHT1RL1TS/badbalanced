// HERO_DETAIL_JS.js
document.addEventListener('DOMContentLoaded', function() {
    // Анимации для страницы героя
    const elements = document.querySelectorAll('.hero-detail-content, .hero-stats, .skill-card');
    
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = `opacity 0.5s ease ${index * 0.1}s, transform 0.5s ease ${index * 0.1}s`;
        
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 100);
    });
});