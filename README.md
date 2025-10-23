# SiAPPMEN - Sistem Aplikasi Pengambilan dan Pendistribusian Instrumen

[![Laravel](https://img.shields.io/badge/Laravel-10.10-red.svg)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-3.6-green.svg)](https://livewire.laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)

SiAPPMEN adalah sistem aplikasi web berbasis Laravel yang dirancang khusus untuk mengelola pengambilan dan pendistribusian instrumen medis di rumah sakit, dengan fokus pada departemen CSSD (Central Sterile Supply Department).

## 🎯 Tujuan Aplikasi

Sistem ini bertujuan untuk:
- Mengelola inventaris instrumen medis secara efisien
- Melacak status sterilisasi dan distribusi instrumen
- Memfasilitasi peminjaman instrumen antar unit/departemen
- Mengurangi kesalahan dalam pengelolaan instrumen medis
- Meningkatkan traceability dan accountability dalam proses CSSD

## ✨ Fitur Utama

### 👥 Sistem Role-Based Access Control
- **Admin**: Akses penuh ke semua fitur, manajemen user, dan monitoring sistem
- **CSSD**: Pengelolaan instrumen, sterilisasi, dan distribusi
- **Unit**: Peminjaman instrumen untuk keperluan medis

### 🔧 Manajemen Master Data
- **Master Unit/Ruangan**: Pengelolaan data unit/departemen rumah sakit
- **Master Instrumen**: Katalog instrumen medis dengan kode unik
- **Manajemen Pouch**: Tracking pouch instrumen dengan QR code

### 📋 Sistem Peminjaman (Order System)
- Permintaan peminjaman instrumen oleh unit
- Approval workflow untuk CSSD
- Tracking status peminjaman (pending, approved, completed, cancelled)

### 🔄 Transaksi & Distribusi
- **Return Dirty**: Pengembalian instrumen kotor ke CSSD
- **Pickup**: Pengambilan instrumen kotor oleh CSSD
- **Borrow**: Peminjaman instrumen
- **Distribute**: Distribusi instrumen steril
- **Handover**: Serah terima antar petugas

### 📱 QR Code Integration
- Generate QR code untuk setiap pouch instrumen
- Scan QR untuk validasi dan tracking
- Export QR code dalam format PDF untuk label
- Verifikasi distribusi melalui scan QR

### 📊 Dashboard & Reporting
- Dashboard real-time dengan chart distribusi instrumen
- Activity logging untuk audit trail
- Export laporan aktivitas ke Excel
- Statistik penggunaan per unit

### 🔔 Real-time Notifications
- Notifikasi aktivitas menggunakan Laravel Broadcasting
- WebSocket integration dengan Laravel WebSockets
- Real-time updates untuk status instrumen

## 🏗️ Arsitektur & Teknologi

### Backend Framework
- **Laravel 10.10**: Framework PHP modern dengan fitur lengkap
- **Livewire 3.6**: Untuk komponen interaktif tanpa JavaScript kompleks
- **PHP 8.1+**: Versi PHP terbaru dengan performa optimal

### Database & Storage
- **MySQL 8.0+**: Database relasional untuk data terstruktur
- **Eloquent ORM**: Query builder yang powerful dan expressive

### Frontend & UI
- **Tailwind CSS**: Utility-first CSS framework
- **Alpine.js**: Reactive JavaScript framework (via Livewire)
- **Chart.js**: Library charting untuk dashboard

### Libraries & Packages
- **Laravel Sanctum**: API authentication
- **Laravel Breeze**: Authentication scaffolding
- **Maatwebsite Excel**: Export data ke Excel
- **Barryvdh DomPDF**: Generate PDF reports
- **SimpleSoftwareIO QR Code**: Generate QR codes
- **Laravel WebSockets**: Real-time communication

## 📁 Struktur Database

### Tabel Utama

#### Core Tables
- `users` - Data pengguna dengan role (admin, cssd, unit)
- `units` - Master data unit/ruangan rumah sakit
- `instruments` - Master data instrumen medis
- `pouches` - Data pouch instrumen dengan QR code

#### Transaction Tables
- `orders` - Header peminjaman instrumen
- `order_items` - Detail item dalam order
- `transactions` - Header transaksi (return, pickup, borrow, dll)
- `transaction_items` - Detail item dalam transaksi

#### Supporting Tables
- `activity_logs` - Log aktivitas untuk audit trail
- `qr_codes` - Data QR code yang di-generate
- `distributions` - Data distribusi steril

### Relasi Database
```
users (1) ──── (N) orders
users (1) ──── (N) transactions
units (1) ──── (N) orders
units (1) ──── (N) transactions
instruments (1) ──── (N) pouches
instruments (1) ──── (N) order_items
instruments (1) ──── (N) transaction_items
orders (1) ──── (N) order_items
transactions (1) ──── (N) transaction_items
```

## 🚀 Instalasi & Setup

### Prerequisites
- PHP 8.1 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL 8.0+
- Git

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/your-repo/siappmen.git
   cd siappmen
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   - Buat database MySQL baru
   - Konfigurasi `.env` dengan database credentials
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=siappmen
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Database Migration & Seeding**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build Assets**
   ```bash
   npm run build
   # atau untuk development
   npm run dev
   ```

7. **Storage Link (untuk file uploads)**
   ```bash
   php artisan storage:link
   ```

8. **WebSocket Setup (untuk real-time notifications)**
   ```bash
   php artisan websockets:serve
   ```

9. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```

### Default Users (dari Seeder)
- **Admin**: admin@siappmen.test / password
- **CSSD**: cssd@siappmen.test / password
- **Unit**: unit@siappmen.test / password

## 📖 Panduan Penggunaan

### Untuk CSSD Staff
1. **Login** dengan akun CSSD
2. **Master Data**: Kelola unit dan instrumen melalui menu Master Data
3. **Transaksi**: Proses return dirty, pickup, dan distribusi steril
4. **Verifikasi**: Scan QR untuk verifikasi distribusi
5. **Monitoring**: Pantau dashboard dan activity logs

### Untuk Unit Staff
1. **Login** dengan akun unit
2. **Order**: Buat permintaan peminjaman instrumen
3. **Tracking**: Monitor status order dan peminjaman
4. **Return**: Kembalikan instrumen yang sudah digunakan

### Untuk Admin
1. **User Management**: Kelola user dan role
2. **System Monitoring**: Pantau aktivitas sistem
3. **Reports**: Export laporan aktivitas
4. **QR Management**: Generate dan manage QR codes

## 🔧 Development Guidelines

### Code Standards
- Follow PSR-12 coding standards
- Gunakan meaningful variable dan method names
- Implement proper error handling
- Tulis komentar yang jelas untuk logic kompleks

### Database Migrations
```bash
php artisan make:migration create_new_table
php artisan make:migration add_column_to_table --table=table_name
```

### Livewire Components
```bash
php artisan make:livewire ComponentName
```

### Testing
```bash
php artisan test
# atau specific test
php artisan test --filter=TestClass
```

### Code Analysis
```bash
./vendor/bin/pint
composer run-script post-root-package-install
```

## 📈 Roadmap Pengembangan

### Fitur yang Akan Ditambahkan
- [ ] Mobile app companion
- [ ] Integration dengan HIS (Hospital Information System)
- [ ] Advanced reporting dengan grafik lebih detail
- [ ] Notification via email/SMS
- [ ] API untuk integrasi eksternal
- [ ] Multi-tenant support untuk multiple hospitals
- [ ] RFID integration sebagai alternatif QR
- [ ] Machine learning untuk predictive maintenance instrumen

### Improvements
- [ ] Performance optimization untuk large datasets
- [ ] Enhanced UI/UX dengan modern design system
- [ ] Multi-language support
- [ ] Automated backup system
- [ ] Real-time inventory alerts

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

### Development Workflow
- Gunakan conventional commits
- Pastikan semua tests pass
- Update documentation jika diperlukan
- Follow code review process

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Authors & Contributors

- **Developer Team** - Initial development
- **CSSD Department** - Domain expertise and requirements
- **IT Department** - Technical infrastructure support

## 🙏 Acknowledgments

- Laravel Framework community
- Open source contributors
- CSSD professionals untuk domain knowledge
- Hospital management untuk support

## 📞 Support

Untuk support teknis atau pertanyaan:
- Email: support@siappmen.com
- Documentation: [Wiki](https://github.com/your-repo/siappmen/wiki)
- Issues: [GitHub Issues](https://github.com/your-repo/siappmen/issues)

---

**SiAPPMEN** - Meningkatkan efisiensi dan keamanan pengelolaan instrumen medis di rumah sakit.
