// buat header
window.addEventListener('scroll', function() {
  const header = document.getElementById('main-header');
  
  if (window.scrollY > 50) {
    header.classList.add('scrolled');
  } else {
    header.classList.remove('scrolled');
  }
});

// buat swal ALERT
(function($) {
  window.showSwal = function(type, title, text, callback) {
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
    }, function() {
        window.location.href = href;
    });
}

