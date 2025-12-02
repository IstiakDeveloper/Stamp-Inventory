
    document.getElementById('toggleButton').addEventListener('click', function() {
        var mobileNav = document.getElementById('mobileNav');
        mobileNav.classList.toggle('hidden');
    });



    const slideContainer = document.getElementById('slideContainer');
    const indicatorContainer = document.getElementById('indicatorContainer');
    const slides = slideContainer.children;
    const indicators = indicatorContainer.children;

    let index = 0;
    let startX = 0;
    let isDragging = false;

    function updateSlide() {
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.transform = `translateX(-${index * 100}%)`;
        }

        for (let i = 0; i < indicators.length; i++) {
            indicators[i].classList.remove('bg-orange-500');
        }
        indicators[index].classList.add('bg-orange-500');
    }

    function nextSlide() {
        index = (index + 1) % slides.length;
        updateSlide();
    }

    function prevSlide() {
        index = (index - 1 + slides.length) % slides.length;
        updateSlide();
    }

    function handleIndicatorClick(indicatorIndex) {
        index = indicatorIndex;
        updateSlide();
    }

    function handleTouchStart(event) {
        startX = event.touches[0].clientX;
        isDragging = true;
    }

    function handleTouchMove(event) {
        if (!isDragging) return;
        const currentX = event.touches[0].clientX;
        const diff = startX - currentX;
        if (diff > 0) {
            nextSlide();
        } else if (diff < 0) {
            prevSlide();
        }
        isDragging = false;
    }

    function handleTouchEnd() {
        isDragging = false;
    }

    for (let i = 0; i < indicators.length; i++) {
        indicators[i].addEventListener('click', function() {
            handleIndicatorClick(i);
        });
    }

    slideContainer.addEventListener('touchstart', handleTouchStart);
    slideContainer.addEventListener('touchmove', handleTouchMove);
    slideContainer.addEventListener('touchend', handleTouchEnd);

    setInterval(nextSlide, 3000);

    updateSlide();



