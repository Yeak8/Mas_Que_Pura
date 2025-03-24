const slides = document.querySelectorAll('.testimonio-slide');
        const indicadores = document.querySelectorAll('.indicador');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        let currentSlide = 0;

        function updateSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
            
            indicadores.forEach((indicador, i) => {
                indicador.classList.toggle('active', i === index);
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            updateSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            updateSlide(currentSlide);
        }

        // Event Listeners
        nextBtn.addEventListener('click', nextSlide);
        prevBtn.addEventListener('click', prevSlide);

        // Crear indicadores dinámicamente (agrega según número de slides)
        indicadores.forEach((indicador, index) => {
            indicador.addEventListener('click', () => {
                currentSlide = index;
                updateSlide(currentSlide);
            });
        });