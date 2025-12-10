document.addEventListener("DOMContentLoaded", function () {
    const slideTrack = document.querySelector('.slider .slide-track');

    if (slideTrack) {
        const slideCount = slideTrack.children.length / 2;
        if (slideCount > 0) {
            const slideWidth = 400; // Lebar satu slide dari CSS
            const trackWidth = slideWidth * slideCount * 2;
            const animationDistance = -(slideWidth * slideCount);
            const animationDuration = slideCount * 5; // Durasi dinamis

            const styleElement = document.createElement('style');
            styleElement.innerHTML = `
                .slide-track {
                    width: ${trackWidth}px;
                    animation: scroll-dynamic ${animationDuration}s linear infinite;
                }
                @keyframes scroll-dynamic {
                    0% { transform: translateX(0); }
                    100% { transform: translateX(${animationDistance}px); }
                }
            `;
            document.head.appendChild(styleElement);
        }
    }
});
