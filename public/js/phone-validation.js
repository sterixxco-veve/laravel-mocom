document.addEventListener('DOMContentLoaded', function () {
    const phoneInputs = document.querySelectorAll('input[name="phone_number"]');
    phoneInputs.forEach(function (input) {
        input.addEventListener('input', function (e) {
            // Hapus semua karakter yang bukan angka (hanya menerima angka)
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
});
