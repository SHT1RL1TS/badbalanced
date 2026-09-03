console.log('=== AOS Debug ===');
console.log('AOS available:', typeof AOS);
console.log('AOS init called:', !!window.aosInitialized);

// Принудительно обновим AOS
if (typeof AOS !== 'undefined') {
    setTimeout(() => {
        console.log('Refreshing AOS...');
        AOS.refresh();
    }, 1000);
}

// document.addEventListener('DOMContentLoaded', function() {
//     if (typeof AOS !== 'undefined') {
//         document.querySelectorAll('[data-aos]').forEach(el => {
//             el.classList.remove('aos-init', 'aos-animate');
//         });

//         AOS.init({
//             duration: 1200,
//             once: false,
//             mirror: true,
//             offset: 120,
//             // Добавьте эти настройки
//             disable: false,
//             startEvent: 'DOMContentLoaded'
//         });

//         console.log("AOS initialized");
//     }
// });
