// buat header
window.addEventListener('scroll', function () {
  const header = document.getElementById('main-header');

  if (window.scrollY > 50) {
    header.classList.add('scrolled');
  } else {
    header.classList.remove('scrolled');
  }
});

// buat swal ALERT
(function ($) {
  window.showSwal = function (type, title, text, callback) {
    'use strict';

    if (type === 'basic') {
      swal({
        title: title,
        text: text,
        timer: 1500,
        showConfirmButton: false
      });
    }

    if (type === 'signupSuccess') {
      swal({
        title: title,
        text: text,
        type: "success",
        timer: 1500,
        showConfirmButton: false
      });
    }

    if (type === 'success') {
      swal({
        title: title,
        text: text,
        type: "success",
        confirmButtonText: "OK"
      }, function () {
        if (typeof callback === "function") {
          callback();
        }
      });
    }

    if (type === 'error') {
      swal({
        title: title,
        text: text,
        type: "error",
        timer: 1500,
        showConfirmButton: false
      });
    }

    if (type === 'info') {
      swal({
        title: title,
        text: text,
        type: "info",
        timer: 1500,
        showConfirmButton: false
      });
    }

    if (type === 'warning') {
      swal({
        title: title,
        text: text,
        type: "warning",
        timer: 1500,
        showConfirmButton: false
      });
    }
  };
})(jQuery);


function confirmLogout(href) {
  swal({
    title: "Yakin mau logout?",
    text: "Anda akan keluar dari akun Anda!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    confirmButtonText: "logout",
    cancelButtonText: "cancel",
    closeOnConfirm: false
  }, function () {
    window.location.href = href;
  });
}

// function confirmPaymentBtn(href) {
//   swal({
//     title: "Confirm Payment? (Dummy)",
//     text: "Make sure your selected seats are correct!",
//     type: "warning",
//     showCancelButton: true,
//     confirmButtonColor: "#28a745",
//     confirmButtonText: "Pay Now",
//     cancelButtonText: "Cancel",
//     closeOnConfirm: false
//   }).then(pay) => {
//     if (pay) {
//       const data = {
//         showtime_id: movieId,    // atau dari URL param
//         seats: selectedSeats,
//         total_price: totalPrice,
//         tickets_qty: selectedSeats.length
//       };

//       fetch(href, {
//         method: 'POST',
//         headers: {
//           'Content-Type': 'application/json'
//         },
//         body: JSON.stringify(data)
//       })
//       .then(response => response.json())
//       .then(result => {
//         if (result.success) {
//           swal("Payment Successful!", "Your payment has been processed.", "success")
//           .then(() => {
//             window.location.href = 'ticket_confirmation.php?transaction_id=' + result.transaction_id;
//           });
//         }
//       } else {
//         swal("Payment Failed!", result.message, "error");
//       }
//     })
//     .catch(error => {
//       console.error('Error:', error);
//       swal("Payment Failed!", "An error occurred during payment.", "error");
//     }
//   });
// }
