# Panduan untuk Agen AI

Dokumen ini memberikan panduan untuk bekerja dengan repositori ini.

## Pengaturan Lingkungan

Aplikasi ini menggunakan Laravel Sail, yang membutuhkan Docker. Pastikan Docker sudah terinstal dan daemon-nya berjalan sebelum memulai.

1.  **Salin File Environment:**
    ```bash
    cp .env.example .env
    ```

2.  **Instal Dependensi Composer:**
    Gunakan Sail untuk menginstal dependensi PHP.
    ```bash
    bash sail composer install
    ```

3.  **Buat Kunci Aplikasi:**
    ```bash
    bash sail artisan key:generate
    ```

4.  **Jalankan Migrasi Database:**
    Pastikan kontainer Sail berjalan (`bash sail up -d`) sebelum menjalankan migrasi.
    ```bash
    bash sail artisan migrate --seed
    ```

## Menjalankan Tes

Selalu jalankan rangkaian tes untuk memverifikasi perubahan Anda. Gunakan perintah Sail berikut:

```bash
bash sail test
```

Ini akan menjalankan semua tes PHPUnit di dalam lingkungan Docker, memastikan konsistensi dan menghindari masalah "works on my machine".
