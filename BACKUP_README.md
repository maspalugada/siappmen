# Sistem Backup Otomatis SiAPPMEN

## 📋 Overview

Sistem backup otomatis SiAPPMEN dirancang untuk melindungi data penting aplikasi dengan melakukan backup database dan file storage secara terjadwal. Sistem ini menggunakan Laravel Artisan Commands yang powerful dan dapat dijalankan secara manual atau otomatis melalui Laravel Scheduler.

## 🛠️ Fitur Backup

### 1. **Backup Database** (`backup:database`)
- Backup database MySQL menggunakan `mysqldump`
- Mendukung cleanup otomatis backup lama (>30 hari)
- Logging aktivitas backup
- Verifikasi integritas file backup

### 2. **Backup Files** (`backup:files`)
- Backup file storage dalam format ZIP terkompresi
- Mendukung direktori: `storage/app/public`, `storage/logs`, `storage/backups`
- Cleanup otomatis backup lama
- Statistik kompresi dan jumlah file

### 3. **Backup Lengkap** (`backup:full`)
- Kombinasi backup database + files dalam satu command
- Konfirmasi interaktif (dapat di-skip dengan `--no-confirm`)
- Ringkasan hasil backup
- Logging terintegrasi

## 📅 Scheduling Otomatis

Backup dijalankan secara otomatis melalui Laravel Scheduler:

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule): void
{
    // Backup lengkap setiap hari pukul 02:00
    $schedule->command('backup:full --cleanup --no-confirm')
             ->dailyAt('02:00')
             ->withoutOverlapping()
             ->runInBackground();

    // Backup database setiap 6 jam
    $schedule->command('backup:database --cleanup')
             ->everySixHours()
             ->withoutOverlapping()
             ->runInBackground();

    // Backup files setiap 12 jam (06:00 & 18:00)
    $schedule->command('backup:files --cleanup')
             ->twiceDaily(6, 18)
             ->withoutOverlapping()
             ->runInBackground();
}
```

### Menjalankan Scheduler

Untuk menjalankan scheduler di production, tambahkan cron job:

```bash
# Edit crontab
crontab -e

# Tambahkan baris berikut
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## 🚀 Penggunaan Manual

### Backup Database
```bash
# Backup database saja
php artisan backup:database

# Backup database dengan cleanup
php artisan backup:database --cleanup
```

### Backup Files
```bash
# Backup files saja
php artisan backup:files

# Backup files dengan cleanup
php artisan backup:files --cleanup
```

### Backup Lengkap
```bash
# Backup lengkap dengan konfirmasi
php artisan backup:full

# Backup lengkap tanpa konfirmasi
php artisan backup:full --no-confirm

# Backup lengkap dengan cleanup
php artisan backup:full --cleanup --no-confirm
```

## 📁 Struktur File Backup

```
storage/
├── backups/
│   ├── siappmen_backup_2024-01-15_14-30-25.sql
│   ├── siappmen_backup_2024-01-16_02-00-01.sql
│   ├── siappmen_files_backup_2024-01-15_14-30-30.zip
│   └── siappmen_files_backup_2024-01-16_02-00-05.zip
```

## 🔧 Konfigurasi

### Environment Variables
Pastikan konfigurasi database di `.env` sudah benar:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siappmen
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Permissions
Pastikan direktori storage memiliki permission yang tepat:

```bash
chmod -R 755 storage/
chown -R www-data:www-data storage/
```

## 📊 Monitoring & Logging

### Log Files
- Backup activities dicatat di `storage/logs/laravel.log`
- Error dan success messages tersedia di log files

### Monitoring Commands
```bash
# Lihat scheduled commands
php artisan schedule:list

# Test backup manual
php artisan backup:full --no-confirm

# Lihat daftar backup files
ls -la storage/backups/
```

## 🧹 Cleanup Policy

- **Database backups**: Dihapus otomatis jika >30 hari
- **File backups**: Dihapus otomatis jika >30 hari
- **Manual cleanup**: Gunakan flag `--cleanup` pada command backup

## 🔒 Keamanan

### Best Practices
1. **Enkripsi**: Backup files dapat dienkripsi menggunakan tools tambahan
2. **Offsite Backup**: Upload backup ke cloud storage (AWS S3, Google Cloud, dll)
3. **Access Control**: Batasi akses ke direktori backup
4. **Regular Testing**: Test restore backup secara berkala

### Contoh Integrasi Cloud Storage

```php
// Contoh upload ke S3 setelah backup
use Illuminate\Support\Facades\Storage;

$backupPath = storage_path('backups/siappmen_backup_*.sql');
Storage::disk('s3')->put('backups/', $backupPath);
```

## 🆘 Troubleshooting

### Common Issues

#### 1. Mysqldump tidak ditemukan
```bash
# Install mysqldump
sudo apt-get install mysql-client

# Atau untuk macOS
brew install mysql
```

#### 2. Permission denied
```bash
# Perbaiki permission storage
chmod -R 755 storage/
chown -R $USER:www-data storage/
```

#### 3. Disk space penuh
```bash
# Cek disk usage
df -h

# Hapus backup lama manual
find storage/backups/ -name "*.sql" -mtime +30 -delete
find storage/backups/ -name "*.zip" -mtime +30 -delete
```

#### 4. Scheduler tidak berjalan
```bash
# Cek cron jobs
crontab -l

# Test scheduler manual
php artisan schedule:run
```

## 📈 Performance & Optimization

### Tips Optimasi
1. **Kompresi**: Backup files menggunakan ZIP untuk menghemat space
2. **Incremental Backup**: Pertimbangkan backup incremental untuk database besar
3. **Parallel Processing**: Backup database dan files dapat berjalan paralel
4. **Retention Policy**: Sesuaikan periode retention berdasarkan kebutuhan

### Monitoring Performance
```bash
# Monitor backup execution time
time php artisan backup:full --no-confirm

# Monitor disk usage
du -sh storage/backups/
```

## 🔄 Restore Procedures

### Restore Database
```bash
# Restore dari backup SQL
mysql -u username -p database_name < storage/backups/siappmen_backup_2024-01-15_14-30-25.sql
```

### Restore Files
```bash
# Extract ZIP backup
unzip storage/backups/siappmen_files_backup_2024-01-15_14-30-30.zip -d /tmp/restore/

# Copy ke storage
cp -r /tmp/restore/storage/* storage/
```

## 📞 Support & Maintenance

### Regular Maintenance Tasks
- [ ] Monitor disk space usage
- [ ] Test backup integrity bulanan
- [ ] Review dan update retention policy
- [ ] Backup konfigurasi server
- [ ] Dokumentasi perubahan sistem

### Emergency Contacts
- IT Administrator: [contact]
- Database Administrator: [contact]
- System Administrator: [contact]

---

**Sistem Backup SiAPPMEN** - Melindungi data kesehatan dengan backup otomatis yang reliable dan dapat diandalkan.
