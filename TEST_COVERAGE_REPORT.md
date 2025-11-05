# Laporan Cakupan Tes Otomatis

Dokumen ini merangkum semua tes otomatis yang telah ditambahkan ke proyek untuk meningkatkan stabilitas, keamanan, dan keandalan.

---

### 1. `tests/Feature/ScanControllerTest.php`

- **Fitur yang Dicakup:** Proses Pengembalian Instrumen.
- **Tujuan Tes:**
    - **`test_it_successfully_returns_a_dirty_instrument`**: Memastikan pengguna dapat berhasil mengembalikan instrumen **jika** instrumen tersebut adalah bagian dari pesanan yang benar.
    - **`test_it_fails_to_return_an_instrument_not_in_the_order`**: Memastikan sistem **menolak** upaya pengembalian jika instrumen yang dikembalikan **tidak sesuai** dengan pesanan yang dipindai. Ini adalah tes kritis yang memvalidasi perbaikan bug utama.
- **Hasil yang Diharapkan:** Tes akan berhasil, mengonfirmasi bahwa logika pengembalian sekarang aman dan benar.

---

### 2. `tests/Feature/CssdOrderManagementTest.php`

- **Fitur yang Dicakup:** Manajemen Pesanan oleh CSSD.
- **Tujuan Tes:**
    - **`test_cssd_user_can_approve_an_order`**: Memverifikasi bahwa pengguna dengan peran `cssd` dapat mengubah status pesanan menjadi "disetujui".
    - **`test_cssd_user_can_reject_an_order`**: Memverifikasi bahwa pengguna dengan peran `cssd` dapat mengubah status pesanan menjadi "ditolak".
    - **`test_non_cssd_user_cannot_update_order_status`**: Memastikan bahwa pengguna tanpa peran yang benar (misalnya, `unit`) **tidak dapat** mengubah status pesanan, mengonfirmasi keamanan berbasis peran.
- **Hasil yang Diharapkan:** Tes akan berhasil, mengunci logika bisnis dan keamanan dari alur kerja inti ini.

---

### 3. `tests/Feature/MasterDataManagementTest.php`

- **Fitur yang Dicakup:** Manajemen Data Master (Unit dan Instrumen).
- **Tujuan Tes:**
    - **`cssd_user_can_create_and_delete_a_unit`**: Memastikan pengguna `cssd` dapat melakukan operasi tulis (buat, hapus) pada data Unit.
    - **`cssd_user_can_create_and_delete_an_instrument`**: Memastikan pengguna `cssd` dapat melakukan operasi tulis pada data Instrumen.
    - **`unauthorized_user_cannot_access_master_data_pages`**: Memastikan pengguna tanpa peran yang benar **diblokir** bahkan untuk mengakses halaman-halaman ini.
- **Hasil yang Diharapkan:** Tes akan berhasil, menjamin bahwa fitur CRUD dan keamanannya berfungsi seperti yang diharapkan.

---

### 4. `tests/Feature/QrCodeGenerationTest.php`

- **Fitur yang Dicakup:** Generasi QR Code.
- **Tujuan Tes:**
    - **`qr_code_content_is_generated_with_correct_secure_format`**: Memverifikasi bahwa konten yang dimasukkan ke dalam QR code adalah format yang aman (`type|orderNo|hash`) dan bukan data mentah. Ini adalah tes kritis yang memvalidasi perbaikan bug keamanan dan kompatibilitas.
- **Hasil yang Diharapkan:** Tes akan berhasil, memastikan QR code yang dibuat aman dan akan berfungsi dengan benar saat dipindai.
