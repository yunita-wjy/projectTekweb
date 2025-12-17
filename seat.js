// seat.js - Combined script with your seat generation and selection logic

// ===== KONFIGURASI =====
// Tetap gunakan seatLayout Anda
const seatLayout = [
    { row: 'H', left: 7, right: 8 }, // total 15
    { row: 'G', left: 7, right: 8 },
    { row: 'F', left: 7, right: 8 },
    { row: 'E', left: 7, right: 8, gapAfter: true },
    { row: 'D', left: 5, right: 8 },
    { row: 'C', left: 5, right: 8 }, // total 10
    { row: 'B', left: 5, right: 8 },
    { row: 'A', left: 5, right: 8 }
];

// Tetap gunakan bookedSeats dari contoh
const bookedSeats = [
    'A3', 'A4', 'A5', 'B7', 'B8', 'C1', 'C2',
    'D10', 'D11', 'E5', 'E6', 'F3', 'F4', 'G9',
    'H2', 'H3'];

// State aplikasi
let selectedSeats = [];

const seatPrice = 35000; // Harga per kursi

// Inisialisasi
document.addEventListener('DOMContentLoaded', function () {
    generateSeatLayout();
    updateSelectionSummary();

    // Event listeners untuk tombol
    document.getElementById('clearBtn').addEventListener('click', clearSelection);
    document.getElementById('continueBtn').addEventListener('click', proceedToCheckout);
});

// Generate layout kursi - DENGAN LOGIC ANDA
function generateSeatLayout() {
    const seating = document.getElementById('seating');
    if (!seating) {
        console.error('Seating element not found!');
        return;
    }

    seating.innerHTML = '';

    seatLayout.forEach(r => {
        const rowDiv = document.createElement('div');
        rowDiv.className = 'row';

        let seatNum = 1;

        const missing = (15 - (r.left + r.right));
        const leftSpacer = Math.floor(missing / 2);
        const rightSpacer = missing - leftSpacer;

        // Tambah spacer kiri
        for (let i = 0; i < leftSpacer; i++) {
            rowDiv.innerHTML += `<div class="seat spacer"></div>`;
        }
        for (let i = 0; i < rightSpacer; i++) {
            rowDiv.innerHTML += `<div class="seat spacer"></div>`;
        }

        // Kursi kiri
        for (let i = 0; i < r.left; i++) {
            const seatId = `${r.row}${seatNum}`;
            const isBooked = bookedSeats.includes(seatId);
            const seatClass = isBooked ? 'seat booked' : 'seat available';
            rowDiv.innerHTML += `<div class="${seatClass}" data-seat-id="${seatId}">${seatId}</div>`;
            seatNum++;
        }

        // Gap lorong
        rowDiv.innerHTML += `<div class="gap"></div>`;

        // Kursi kanan
        for (let i = 0; i < r.right; i++) {
            const seatId = `${r.row}${seatNum}`;
            const isBooked = bookedSeats.includes(seatId);
            const seatClass = isBooked ? 'seat booked' : 'seat available';
            rowDiv.innerHTML += `<div class="${seatClass}" data-seat-id="${seatId}">${seatId}</div>`;
            seatNum++;
        }
        seating.appendChild(rowDiv);

        if (r.gapAfter) {
            const spacer = document.createElement('div');
            spacer.className = 'row-gap';
            seating.appendChild(spacer);
        }
    });

    // Tambah event listeners untuk kursi yang available
    document.querySelectorAll('.seat.available').forEach(seat => {
        seat.addEventListener('click', handleSeatClick);
    });
}

function handleSeatClick(event) {
    const seat = event.currentTarget;
    const seatId = seat.getAttribute('data-seat-id');

    // Cek apakah kursi sudah dipilih
    const seatIndex = selectedSeats.indexOf(seatId);

    if (seatIndex === -1) {
        // Tambah ke seleksi (maksimal 6 kursi)
        if (selectedSeats.length < 6) {
            selectedSeats.push(seatId);
            seat.classList.add('selected');
            seat.classList.remove('available');
        } else {
            alert('You can only select up to 6 seats at a time.');
            return;
        }
    } else {
        // Hapus dari seleksi
        selectedSeats.splice(seatIndex, 1);
        seat.classList.remove('selected');
        seat.classList.add('available');
    }

    updateSelectionSummary();
    saveSelectionToSession();
}

// Tambah event listeners untuk semua kursi available
function addSeatEventListeners() {
    document.querySelectorAll('.seat.available').forEach(seat => {
        seat.addEventListener('click', toggleSeatSelection);
    });
}

// Toggle seleksi kursi - DARI CONTOH
function toggleSeatSelection(event) {
    const seat = event.currentTarget;
    const seatId = seat.dataset.seatId;

    // Cek apakah kursi sudah dipilih
    const seatIndex = selectedSeats.indexOf(seatId);

    if (seatIndex === -1) {
        // Tambah ke seleksi
        selectedSeats.push(seatId);
        seat.classList.add('selected');
    } else {
        // Hapus dari seleksi
        selectedSeats.splice(seatIndex, 1);
        seat.classList.remove('selected');
    }

    updateSelectionSummary();
    saveSelectionToSession();
}

// Update summary seleksi - DARI CONTOH (disesuaikan dengan ID Anda)
function updateSelectionSummary() {
    const seatInfo = document.getElementById('seatInfo');
    const seatList = document.getElementById('seatList');
    const continueBtn = document.getElementById('continueBtn');
    const Price = document.getElementById('totalPrice');

    // Update info jumlah kursi
    const seatCount = selectedSeats.length;
    const totalPrice = seatCount * seatPrice;
    if (Price) {
        if (seatCount > 0) {
            Price.textContent = `Total: Rp.${totalPrice.toLocaleString('id-ID')}`;
        } else {
            Price.textContent = `Total: Rp.0`;
        }
    }
    if (seatInfo) {
        seatInfo.textContent = `${seatCount} seat${seatCount !== 1 ? 's' : ''} selected`;
    }

    // Update daftar kursi terpilih
    if (seatList) {
        seatList.innerHTML = '';
        selectedSeats.forEach(seatId => {
            const badge = document.createElement('span');
            badge.className = 'selected-seat-badge';
            badge.textContent = seatId;
            seatList.appendChild(badge);
        });
    }

    // Enable/disable tombol continue
    if (continueBtn) {
        continueBtn.disabled = seatCount === 0;
    }
}

// Clear semua seleksi - DARI CONTOH
function clearSelection() {
    // Hapus class selected dari semua kursi
    document.querySelectorAll('.seat.selected').forEach(seat => {
        seat.classList.remove('selected');
    });

    // Reset array selectedSeats
    selectedSeats = [];

    // Update summary
    updateSelectionSummary();
    saveSelectionToSession();
}

function saveSelectionToSession() {
    // Simpan ke sessionStorage untuk persistensi sementara
    sessionStorage.setItem('selectedSeats', JSON.stringify(selectedSeats));
}

function loadPreviousSelection() {
    // Load dari sessionStorage
    const savedSeats = sessionStorage.getItem('selectedSeats');
    if (savedSeats) {
        try {
            const parsedSeats = JSON.parse(savedSeats);
            // Validasi dan apply seleksi
            parsedSeats.forEach(seatId => {
                const seatElement = document.querySelector(`[data-seat-id="${seatId}"]`);
                if (seatElement && !seatElement.classList.contains('booked')) {
                    seatElement.classList.add('selected');
                    seatElement.classList.remove('available');
                    selectedSeats.push(seatId);
                }
            });
            updateSelectionSummary();
        } catch (e) {
            console.error('Error loading saved seats:', e);
        }
    }
}

// Proceed to checkout (simulasi) - DARI CONTOH
function proceedToCheckout() {
    if (selectedSeats.length === 0) return;

    const seatCount = selectedSeats.length;
    const totalPrice = seatCount * seatPrice;

    // Simpan ke localStorage atau session untuk proses checkout
    localStorage.setItem('selectedSeats', JSON.stringify(selectedSeats));

    // Ambil data movie dan showtime dari URL (jika ada)
    const urlParams = new URLSearchParams(window.location.search);
    const movieId = urlParams.get('movie_id') || '1';
    const showtime = urlParams.get('showtime') || '18:00';

    // Tampilkan alert konfirmasi
    alert(`You have selected ${selectedSeats.length} seat(s): ${selectedSeats.join(', ')}\nMovie ID: ${movieId}\nShowtime: ${showtime}\n\nProceeding to checkout...`);

    // Simpan data booking lengkap
    const bookingData = {
        seats: selectedSeats,
        movieId: movieId,
        showtime: showtime,
        totalPrice: totalPrice, // Rp 35,000 per kursi
        timestamp: new Date().toISOString()
    };

    // Simpan ke sessionStorage juga
    sessionStorage.setItem('bookingData', JSON.stringify(bookingData));

    // Untuk demo, tampilkan di console
    console.log('Selected seats:', selectedSeats);
    console.log('Booking data:', bookingData);
    console.log('Proceeding to checkout...');

    // Dalam implementasi nyata, redirect ke halaman checkout
    // window.location.href = `checkout.php?movie_id=${movieId}&showtime=${showtime}&seats=${selectedSeats.join(',')}`;
}