// seat.js - Combined script with your seat generation and selection logic

// ===== KONFIGURASI =====
// Tetap gunakan seatLayout Anda
const seatLayouthc = [
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
const bookedSeatshc = [
    'A3', 'A4', 'A5', 'B7', 'B8', 'C1', 'C2',
    'D10', 'D11', 'E5', 'E6', 'F3', 'F4', 'G9',
    'H2', 'H3'];

// State aplikasi
let selectedSeats = [];

const seatPrice = 35000; // Harga per kursi
const serviceFee = 2500;
let totalPrice = 0;  // harga total seats yang dipilih


let modalMovieData = {
    title: 'Movie Title',
    date: 'Date: -',
    time: 'Time: -',
    studio: 'Studio: -'
};

// Inisialisasi
document.addEventListener('DOMContentLoaded', function () {
    // generateSeatLayout();
    updateSelectionSummary();

    // Event listeners untuk tombol
    document.getElementById('clearBtn').addEventListener('click', clearSelection);
    document.getElementById('continueBtn').addEventListener('click', showTransactionModal);

    setupModalEvents();
    loadPreviousSelection();
});

function convertSeatsToLayout(seatsFromDB) {
  // Cari semua row unik (urutkan dari H ke A supaya sama kayak contoh)
  const allRows = [...new Set(seatsFromDB.map(s => s.row))].sort().reverse();

  // Default total kursi per row
  const totalSeatsPerRow = 15;

  // Buat array hasil seatLayout
  const seatLayout = allRows.map(row => {
    // Filter semua kursi yang ada di row ini
    const seatsInRow = seatsFromDB.filter(s => s.row === row);

    // Hitung kursi kiri (col <= 7)
    const rightCount = 8;

    // Hitung kursi kanan
    const leftCount = seatsInRow.length - rightCount;

    // Kalau row tertentu mau kasih gapAfter (misal 'E'), bisa juga di-handle sini
    const gapAfter = (row === 'E'); // sesuaikan kalau mau yang lain

    return {
      row: row,
      left: leftCount,
      right: rightCount,
      ...(gapAfter ? { gapAfter: true } : {})
    };
  });

  return seatLayout;
}


function setupModalEvents() {
    // Close modal button
    document.getElementById('closeModalBtn').addEventListener('click', closeTransactionModal);

    // Cancel transaction button
    document.getElementById('cancelTransactionBtn').addEventListener('click', closeTransactionModal);

    // Confirm payment button
    // document.getElementById('confirmPaymentBtn').addEventListener('click', processPayment());

    // Close modal when clicking outside
    document.getElementById('transactionModalOverlay').addEventListener('click', function (e) {
        if (e.target === this) {
            closeTransactionModal();
        }
    });

    // Payment method selection
    document.querySelectorAll('.payment-option').forEach(option => {
        option.addEventListener('click', function () {
            document.querySelectorAll('.payment-option').forEach(opt => {
                opt.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
}

// Generate layout kursi
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

// Update summary seleksi - DARI CONTOH (disesuaikan dengan ID Anda)
function updateSelectionSummary() {
    const seatInfo = document.getElementById('seatInfo');
    const seatList = document.getElementById('seatList');
    const continueBtn = document.getElementById('continueBtn');
    const Price = document.getElementById('totalPrice');

    // Update info jumlah kursi
    const seatCount = selectedSeats.length;
    totalPrice = seatCount * seatPrice;
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
        seat.classList.add('available');
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

// Fungsi untuk show transaction modal
function showTransactionModal() {
    if (selectedSeats.length === 0) {
        alert('Please select at least one seat.');
        return;
    }

    // Update modal content
    updateModalContent();

    // Show modal
    document.getElementById('transactionModalOverlay').classList.add('active');
    document.body.style.overflow = 'hidden'; // Prevent scrolling
}

// Fungsi untuk update modal content
function updateModalContent() {
    const seatCount = selectedSeats.length;
    const ticketPrice = seatPrice * seatCount;
    totalPrice = ticketPrice + serviceFee;

    // Update movie info
    document.getElementById('modalMovieTitle').textContent = modalMovieData.title;
    document.getElementById('modalShowDate').textContent = `Date: ${modalMovieData.date}`;
    document.getElementById('modalShowTime').textContent = `Time: ${modalMovieData.time}`;
    document.getElementById('modalStudio').textContent = `Studio: ${modalMovieData.studio}`;

    // Update seats list
    const seatsContainer = document.getElementById('modalSeatsList');
    seatsContainer.innerHTML = '';

    selectedSeats.forEach(seatId => {
        const badge = document.createElement('div');
        badge.className = 'seat-badge-modal';
        badge.textContent = seatId;
        seatsContainer.appendChild(badge);
    });

    // Update ticket count and price
    document.getElementById('modalTicketCount').textContent = seatCount;
    document.getElementById('modalTicketPrice').textContent = `Rp ${ticketPrice.toLocaleString()},-`;
    document.getElementById('modalTotalPrice').innerHTML = `<strong>Rp ${totalPrice.toLocaleString()},-</strong>`;

    updateSelectionSummary();
}

// Fungsi untuk close modal
function closeTransactionModal() {
    document.getElementById('transactionModalOverlay').classList.remove('active');
    document.body.style.overflow = 'auto'; // Enable scrolling
}

function confirmPaymentBtn(href) {
    swal({
        title: "Confirm Payment? (Dummy)",
        text: "Make sure your selected seats are correct!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        confirmButtonText: "Pay Now",
        cancelButtonText: "Cancel",
        closeOnConfirm: false
    }, function (isConfirm) {
        if (isConfirm) {
            const data = {
                showtime_id: 1,
                seats: selectedSeats,
                total_price: totalPrice + serviceFee,
                tickets_qty: selectedSeats.length
            };

            fetch(href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                //   .then(response => response.json())
                .then(response => response.text())
                .then(text => {
                    console.log('Response text:', text); // cek dulu response
                    try {
                        const result = JSON.parse(text); // parse JSON manual
                        return result;
                    } catch (e) {
                        throw new Error('Invalid JSON response');
                    }
                })
                .then(result => {
                    console.log('Payment result:', result);
                    if (result.success) {
                        swal("Payment Successful!", "Your payment has been processed.", "success")
                        setTimeout(() => {
                            window.location.href = 'seats.php?payment=success';
                        }, 1500);
                    } else {
                        swal("Payment Failed!", result.message, "error");
                    }
                })
            // .catch(error => {
            //     // console.error('Error:', error);
            //     swal("Payment Failed!", "An error occurred during payment.", "error");
            // });
        }
    });
}
