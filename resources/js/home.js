document.addEventListener('DOMContentLoaded', function () {
    // Flatpickr Logic
    if (typeof flatpickr !== "undefined") {
        let pricesCache = {};

        // Function to fetch prices
        async function getPricesForMonth(year, month) {
            const cacheKey = `${year}-${month}`;
            if (pricesCache[cacheKey]) return pricesCache[cacheKey];

            // Add loading state
            document.querySelectorAll('.datepicker').forEach(el => el.classList.add('is-loading'));

            try {
                const baseUrl = window.appRoutes?.roomPricesMonth || '/api/room-prices/month';
                const response = await fetch(`${baseUrl}?year=${year}&month=${month + 1}`);
                if (!response.ok) throw new Error('Network response was not ok');
                const data = await response.json();
                pricesCache[cacheKey] = data;
                return data;
            } catch (error) {
                console.error('Error fetching monthly prices:', error);
                return {};
            } finally {
                // Remove loading state
                document.querySelectorAll('.datepicker').forEach(el => el.classList.remove('is-loading'));
            }
        }

        const fpConfig = {
            dateFormat: "d-m-Y",
            minDate: "today",
            onReady: async function (selectedDates, dateStr, instance) {
                const prices = await getPricesForMonth(instance.currentYear, instance.currentMonth);
                instance.prices = prices;
                instance.redraw();
            },
            onMonthChange: async function (selectedDates, dateStr, instance) {
                const prices = await getPricesForMonth(instance.currentYear, instance.currentMonth);
                instance.prices = prices;
                instance.redraw();
            },
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                const date = dayElem.dateObj;
                const dateString = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
                if (fp.input.id === 'checkin' && fp.prices && fp.prices[dateString]) {
                    const priceInfo = fp.prices[dateString];
                    const priceElement = document.createElement('span');
                    priceElement.className = 'day-price';
                    priceElement.textContent = `${parseInt(priceInfo.price / 1000)}K`;
                    dayElem.appendChild(priceElement);
                }
            }
        };

        const datepickers = document.querySelectorAll(".datepicker");
        if (datepickers.length > 0) {
            flatpickr(".datepicker", fpConfig);
        }
    } else {
        console.warn("Flatpickr is not loaded.");
    }

    // Continuous Slider Logic
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
