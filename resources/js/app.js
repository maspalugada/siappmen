import './bootstrap';

import Alpine from 'alpinejs';
import QrScanner from 'qr-scanner';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const videoElem = document.getElementById('preview');
    const returnForm = document.getElementById('returnForm');

    // Hanya jalankan pemindai jika elemen video ada di halaman
    if (videoElem) {
        const scanner = new QrScanner(videoElem, result => {
            const qrContentInput = document.getElementById('qr_content');
            if (qrContentInput) {
                qrContentInput.value = result.data; // Benar: hasil scan adalah qr_content
            }
            const div = document.getElementById('result');
            div.textContent = "QR terdeteksi. Silakan lengkapi form."; // Pesan lebih jelas
            div.className = "bg-blue-100 text-blue-800 p-3 rounded";
            div.classList.remove("hidden");
        }, { highlightScanRegion: true });

        QrScanner.hasCamera().then(has => {
            if (has) {
                scanner.start();
            } else {
                alert("Kamera tidak tersedia!");
            }
        });
    }

    // Hanya jalankan logika form jika form ada di halaman
    if (returnForm) {
        returnForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const data = {
                pouch_code: document.getElementById('pouch_code').value,
                patient_name: document.getElementById('patient_name').value,
                qty: document.getElementById('qty').value,
                qr_content: document.getElementById('qr_content').value,
            };

            try {
                const res = await fetch(returnForm.action, {
                    method:'POST',
                    headers:{
                        'Content-Type':'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(data)
                });
                const json = await res.json();
                const div = document.getElementById('result');
                if (res.status >= 400) {
                    div.textContent = json.error || 'Terjadi kesalahan.';
                    div.className = "bg-red-100 text-red-700 p-3 rounded";
                } else {
                    div.textContent = json.message;
                    div.className = "bg-green-100 text-green-700 p-3 rounded";
                }
            } catch (error) {
                const div = document.getElementById('result');
                div.textContent = 'Gagal terhubung ke server.';
                div.className = "bg-red-100 text-red-700 p-3 rounded";
            }
        });
    }
});
