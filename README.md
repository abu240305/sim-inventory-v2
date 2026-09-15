# Sistem Informasi Manajemen Inventaris Barang (SIM Inventaris)

Aplikasi berbasis web untuk mendigitalisasi proses pencatatan, pelacakan, dan pelaporan barang, yang dikembangkan menggunakan **Laravel 11**.

---

## 🚀 Panduan Instalasi (Menjalankan di Laptop Lain)

Jika Anda memindahkan proyek ini ke laptop/komputer lain, ikuti langkah-langkah di bawah ini:

### Persyaratan Sistem
Pastikan laptop tujuan sudah terinstal perangkat lunak berikut:
- **PHP** (Versi 8.2 atau lebih baru)
- **Composer**
- **Node.js** & **NPM**
- **MySQL / MariaDB** (melalui XAMPP/Laragon/dll)

### Langkah-langkah Menjalankan

1. **Unduh / Pindahkan Proyek**
   Salin folder proyek ini ke laptop tujuan (jika menggunakan flashdisk) atau *clone* dari Git/GitHub.

2. **Buka Terminal di Folder Proyek**
   Buka terminal/CMD/PowerShell, arahkan (cd) ke dalam folder proyek ini.

3. **Instal Dependensi PHP**
   Jalankan perintah ini untuk mengunduh semua paket backend:
   ```bash
   composer install
   ```

4. **Instal Dependensi Frontend**
   Jalankan perintah ini untuk mengunduh paket frontend:
   ```bash
   npm install
   ```

5. **Salin File Konfigurasi Lingkungan (.env)**
   Secara *default*, file `.env` tidak ikut disalin saat pindah perangkat. Gandakan file `.env.example` dan ubah namanya menjadi `.env`:
   - Di Windows (CMD): `copy .env.example .env`
   - Di Mac/Linux: `cp .env.example .env`

6. **Generate Application Key**
   Hasilkan kunci keamanan baru untuk Laravel:
   ```bash
   php artisan key:generate
   ```

7. **Konfigurasi Database**
   Buka aplikasi XAMPP/Laragon Anda dan pastikan MySQL sudah menyala.
   - Buat database kosong baru, misalnya dengan nama `sim_inventory`.
   - Buka file `.env` pada proyek ini, dan ubah pengaturan database agar sesuai, contoh:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=sim_inventory
     DB_USERNAME=root
     DB_PASSWORD=
     ```

8. **Migrasi dan Seed Database**
   Jalankan perintah ini untuk membuat struktur tabel dan mengisi data awal (akun Admin & dummy role):
   ```bash
   php artisan migrate:fresh --seed
   ```

9. **Jalankan Aplikasi**
   Untuk menyalakan *server* lokal, jalankan 2 perintah ini di terminal yang **berbeda**:
   
   Terminal 1 (Backend PHP):
   ```bash
   php artisan serve
   ```
   
   Terminal 2 (Frontend Assets - Vite):
   ```bash
   npm run dev
   ```

10. **Akses Aplikasi**
    Buka *browser* (Chrome/Edge/Firefox), lalu akses alamat: 
    👉 **http://localhost:8000**

---

### 🔑 Akun Default (Login Awal)
Setelah instalasi berhasil, Anda bisa login menggunakan akun bawaan berikut:

- **Email:** `superadmin@gmail.com` atau `admin@gmail.com`
- **Password:** `password`

*(Pastikan untuk mengganti password dari menu Profil setelah berhasil login).*
