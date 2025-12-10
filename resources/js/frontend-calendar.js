console.log("Frontend Calendar Script Loaded");

function initCalendar() {
    console.log("Initializing Flatpickr Calendar...");
    if (typeof flatpickr === "undefined") {
        console.error("Flatpickr is not loaded.");
        return;
    }

    let pricesCache = {}; // Cache untuk menyimpan data harga
    let isLoading = false;

    // Helper untuk format mata uang IDR
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    };

    // Fungsi async untuk mengambil harga dari API
    async function getPricesForMonth(year, month) {
        // Cek apakah ada input room_id di halaman (biasanya di dalam modal booking)
        // Kita cari input dengan name="room_id"
        const roomInput = document.querySelector('input[name="room_id"]');
        const roomId = roomInput ? roomInput.value : null;

        const cacheKey = `${year}-${month}-${roomId || 'default'}`;
        if (pricesCache[cacheKey]) {
            console.log(`Using cached prices for ${cacheKey}`);
            return pricesCache[cacheKey]; // Ambil dari cache jika ada
        }

        if (isLoading) return {}; // Prevent multiple simultaneous calls
        isLoading = true;

        // Show loading indicator (optional UI enhancement)
        document.body.style.cursor = 'wait';

        try {
            // Menggunakan URL relative agar fleksibel
            let url = `/api/room-prices/month?year=${year}&month=${month + 1}`;
            if (roomId) {
                url += `&room_id=${roomId}`;
            }

            console.log(`Fetching API: ${url}`); // Debug URL

            const response = await fetch(url);
            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();
            console.log("Data received from API:", data); // Debug Data Masuk

            pricesCache[cacheKey] = data; // Simpan ke cache
            return data;
        } catch (error) {
            console.error('Error fetching monthly prices:', error);
            return {};
        } finally {
            isLoading = false;
            document.body.style.cursor = 'default';
        }
    }

    // Inject CSS untuk memperbaiki layout kalender
    const style = document.createElement('style');
    style.innerHTML = `
        /* Paksa tinggi sel kalender agar muat tanggal & harga */
        .flatpickr-day {
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important; /* Pisahkan atas dan bawah */
            align-items: center !important;
            height: auto !important;
            min-height: 60px !important;
            line-height: normal !important;
            padding: 4px 0 !important;
            margin-top: 5px !important;
        }
        
        /* Styling angka tanggal */
        .day-number {
            font-size: 14px !important;
            font-weight: bold !important;
            line-height: 1 !important;
            z-index: 1;
            margin-top: 2px;
        }

        /* Styling untuk harga */
        .day-price {
            font-size: 10px !important;
            font-weight: 600 !important;
            line-height: 1.2 !important;
            text-align: center !important;
            white-space: nowrap !important;
            z-index: 1;
            margin-bottom: 2px;
        }

        /* Perbaikan tampilan saat hover/selected */
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day:focus, .flatpickr-day:hover {
            background: #4f46e5 !important; /* Indigo-600 */
            color: white !important;
            border-color: #4f46e5 !important;
        }
        
        /* Pastikan text putih saat selected */
        .flatpickr-day.selected .day-number,
        .flatpickr-day.selected .day-price,
        .flatpickr-day.startRange .day-number,
        .flatpickr-day.startRange .day-price {
            color: white !important;
        }
    `;
    document.head.appendChild(style);

    // Konfigurasi Flatpickr
    const fpConfig = {
        dateFormat: "d-m-Y",
        minDate: "today",
        // Event yang berjalan saat kalender siap
        onReady: async function (selectedDates, dateStr, instance) {
            console.log("Flatpickr onReady triggered");
            const prices = await getPricesForMonth(instance.currentYear, instance.currentMonth);
            instance.prices = prices;
            instance.redraw();
        },
        // Event yang berjalan saat bulan diganti
        onMonthChange: async function (selectedDates, dateStr, instance) {
            console.log("Flatpickr onMonthChange triggered");
            const prices = await getPricesForMonth(instance.currentYear, instance.currentMonth);
            instance.prices = prices;
            instance.redraw();
        },
        // Event yang berjalan untuk setiap tanggal yang digambar
        onDayCreate: function (dObj, dStr, fp, dayElem) {
            const date = dayElem.dateObj;
            const dateString = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;

            // 1. Bersihkan konten lama dan bungkus angka tanggal
            const dayNumber = date.getDate();
            dayElem.innerHTML = `<span class="day-number">${dayNumber}</span>`;

            // 2. Cek harga
            // PERBAIKAN: Cek ID 'checkin' (Home) ATAU 'modal-checkin' (Booking Form)
            // Untuk debugging, kita set true dulu agar selalu mencoba render
            const isCheckinInput = true;

            if (isCheckinInput) {
                let priceText = '';
                let priceColor = '#ccc';

                if (fp.prices && fp.prices[dateString]) {
                    const priceInfo = fp.prices[dateString];
                    // Format harga: Bagi 1000 dan tambah 'k'. Contoh: 500000 -> 500k
                    priceText = parseInt(priceInfo.price / 1000) + 'k';
                    priceColor = priceInfo.is_special ? '#e11d48' : '#10b981'; // Merah jika special, Hijau jika biasa
                }

                if (priceText) {
                    const priceElement = document.createElement('span');
                    priceElement.className = 'day-price';
                    priceElement.textContent = priceText;
                    priceElement.style.color = priceColor;
                    dayElem.appendChild(priceElement);
                }
            }
        }
    };

    // Terapkan konfigurasi ke semua elemen dengan class .datepicker
    flatpickr(".datepicker", fpConfig);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCalendar);
} else {
    initCalendar();
}