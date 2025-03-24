window.addEventListener('scroll', function() {
    const nav = document.querySelector('nav');
    if (window.scrollY > 50) { // Cambia 50 por la cantidad de scroll que desees
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
});