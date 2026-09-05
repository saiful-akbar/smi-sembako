# POS UMKM - Point of Sale untuk Usaha Mikro Kecil Menengah

[![Laravel](https://img.shields.io/badge/Laravel-12-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.0+-blue.svg)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-green.svg)](https://alpinejs.dev)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![GitHub issues](https://img.shields.io/github/issues/muris11/Pos-UMKM-Laravel-12)](https://github.com/muris11/Pos-UMKM-Laravel-12/issues)
[![GitHub stars](https://img.shields.io/github/stars/muris11/Pos-UMKM-Laravel-12)](https://github.com/muris11/Pos-UMKM-Laravel-12/stargazers)

<br>

Sistem Point of Sale (POS) modern yang dirancang khusus untuk membantu Usaha Mikro Kecil Menengah (UMKM) mengelola bisnis mereka dengan lebih efisien. Dibangun dengan Laravel 12, Tailwind CSS, dan Alpine.js untuk pengalaman pengguna yang luar biasa.

## 📸 Screenshots

_Screenshots akan ditambahkan setelah deployment_

## ✨ Fitur Utama

### 🏪 **Manajemen Toko**

-   ✅ Multi-store support dengan pengaturan terpisah
-   ✅ Pengaturan toko individual (nama, alamat, kontak)
-   ✅ Manajemen inventori per toko
-   ✅ Transfer stok antar toko
-   ✅ Laporan penjualan per toko
-   ✅ Pengaturan jam operasional

### 👥 **Manajemen Pelanggan**

-   ✅ Database pelanggan lengkap dengan informasi detail
-   ✅ Sistem poin loyalitas otomatis
-   ✅ Riwayat pembelian lengkap
-   ✅ Segmentasi pelanggan (VIP, Regular, New)
-   ✅ Program diskon berdasarkan poin
-   ✅ Notifikasi ulang tahun pelanggan
-   ✅ Export data pelanggan

### 📦 **Manajemen Produk**

-   ✅ Katalog produk lengkap dengan foto
-   ✅ Sistem kategori produk hierarki
-   ✅ Manajemen stok real-time dengan alert
-   ✅ Barcode support (generate & scan)
-   ✅ Variasi produk (ukuran, warna, dll)
-   ✅ Harga grosir dan retail
-   ✅ Produk bundling/paket
-   ✅ Manajemen expired date

### 🛒 **Point of Sale (POS)**

-   ✅ Interface kasir yang intuitif dan cepat
-   ✅ Pencarian produk dengan autocomplete
-   ✅ Kalkulasi otomatis pajak & diskon
-   ✅ Multiple payment methods (Cash, Card, Transfer)
-   ✅ Split payment (gabungan metode bayar)
-   ✅ Hold transaction untuk antrian
-   ✅ Receipt printing thermal & PDF
-   ✅ Refund & return management

### 👨‍💼 **Manajemen Pengguna**

-   ✅ Role-based access control (RBAC)
-   ✅ Tiga level: Owner, Manager, Cashier
-   ✅ User activity logging & audit trail
-   ✅ Secure authentication dengan rate limiting
-   ✅ Password policy & reset
-   ✅ Session management
-   ✅ Two-factor authentication (opsional)

### 📊 **Analytics & Reporting**

-   ✅ Dashboard real-time dengan metrics utama
-   ✅ Laporan penjualan harian/mingguan/bulanan/tahunan
-   ✅ Analitik produk terlaris & tidak laku
-   ✅ Laporan keuangan (P&L, Cash Flow)
-   ✅ Customer analytics & behavior
-   ✅ Inventory turnover analysis
-   ✅ Export laporan ke PDF/Excel/CSV
-   ✅ Scheduled reports via email

### 🎨 **UI/UX Modern**

-   ✅ Dark/Light mode dengan auto-detection
-   ✅ Fully responsive design (Mobile, Tablet, Desktop)
-   ✅ Animasi smooth & micro-interactions
-   ✅ Theme customization dengan preset colors
-   ✅ Mobile-optimized untuk POS tablet
-   ✅ Accessibility compliance (WCAG 2.1)
-   ✅ Multi-language support (ID/EN)
-   ✅ PWA (Progressive Web App) ready

### 🔧 **Fitur Tambahan**

-   ✅ Backup & restore database otomatis
-   ✅ API RESTful untuk integrasi
-   ✅ Webhook support untuk external services
-   ✅ Queue system untuk performance
-   ✅ Cache optimization
-   ✅ Error logging & monitoring
-   ✅ Maintenance mode
-   ✅ Database optimization tools

## 🛠️ Tech Stack

### Backend

-   **Laravel 12** - PHP Framework terdepan untuk web applications
-   **MySQL 8.0+** - Database relational dengan performance tinggi
-   **PHP 8.2+** - Server-side scripting dengan JIT compiler
-   **Redis** - Cache & session storage (optional)

### Frontend

-   **Tailwind CSS 3.0+** - Utility-first CSS framework
-   **Alpine.js 3.x** - Reactive JavaScript framework
-   **Material Icons** - Icon library dari Google
-   **Chart.js** - Library charting untuk analytics

### Tools & Libraries

-   **Composer** - PHP dependency manager
-   **NPM/Yarn** - Node.js package manager
-   **Vite** - Next-generation build tool
-   **DomPDF** - PDF generation library
-   **Midtrans** - Payment gateway Indonesia
-   **Laravel Sanctum** - API authentication
-   **Laravel Horizon** - Queue monitoring (optional)
-   **Laravel Telescope** - Debugging & monitoring (dev)

## 📋 Prerequisites

Sebelum menjalankan aplikasi ini, pastikan Anda memiliki:

-   ✅ **PHP 8.2 atau lebih tinggi** (8.3 recommended)
-   ✅ **Composer 2.x** - PHP dependency manager
-   ✅ **Node.js 18+ & NPM 9+** - JavaScript runtime
-   ✅ **MySQL 8.0+ atau MariaDB 10.6+** - Database server
-   ✅ **Git 2.30+** - Version control
-   ✅ **Web Server** - Apache/Nginx dengan mod_rewrite

## 🚀 Instalasi & Setup

### 1. Clone Repository

```bash
git clone https://github.com/muris11/Pos-UMKM-Laravel-12.git
cd Pos-UMKM-Laravel-12
```

### 2. Install PHP Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### 3. Install Node.js Dependencies

```bash
npm install --production=false
```

### 4. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT secret (jika menggunakan API)
php artisan jwt:secret
```

### 5. Database Setup

```bash
# Buat database di MySQL
mysql -u root -p
CREATE DATABASE pos_umkm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Update konfigurasi di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_umkm
DB_USERNAME=root
DB_PASSWORD=your_password

# Jalankan migrations
php artisan migrate

# Seed database dengan data dummy
php artisan db:seed
```

### 6. Storage Setup

```bash
# Buat symbolic link untuk file uploads
php artisan storage:link

# Set permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### 7. Build Assets

```bash
# Development
npm run dev

# Production (optimized)
npm run build

# Watch mode untuk development
npm run watch
```

### 8. Cache Optimization

```bash
# Cache konfigurasi untuk performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear cache jika ada perubahan
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 9. Jalankan Aplikasi

```bash
# Development server
php artisan serve

# Production dengan queue worker
php artisan queue:work

# Schedule runner (cron job)
php artisan schedule:run
```

Aplikasi akan berjalan di `http://localhost:8000`

## 👤 User Accounts Demo

Setelah seeding database, Anda dapat login dengan akun berikut:

| Role    | Email        | Password | Permissions                     |
| ------- | ------------ | -------- | ------------------------------- |
| Owner   | owner@demo   | demo123  | Full access                     |
| Manager | manager@demo | demo123  | Manage store, products, reports |
| Cashier | cashier@demo | demo123  | POS, customers, basic reports   |

### Membuat User Baru

```bash
# Via artisan command
php artisan tinker
>>> User::create(['name'=>'John Doe', 'email'=>'john@example.com', 'password'=>Hash::make('password'), 'role'=>'cashier']);
```

## 📁 Struktur Project

```
pos-umkm/
├── app/
│   ├── Console/
│   │   ├── Commands/          # Custom artisan commands
│   │   └── Kernel.php
│   ├── Exceptions/
│   │   └── Handler.php
│   ├── Http/
│   │   ├── Controllers/       # All controllers
│   │   │   ├── Auth/
│   │   │   ├── DashboardController.php
│   │   │   ├── POSController.php
│   │   │   └── ...
│   │   ├── Middleware/        # Custom middleware
│   │   └── Requests/          # Form request validation
│   ├── Models/                # Eloquent models
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Customer.php
│   │   ├── Sale.php
│   │   ├── Store.php
│   │   └── ...
│   ├── Providers/             # Service providers
│   ├── Services/              # Business logic services
│   │   ├── MidtransService.php
│   │   ├── PromotionEngine.php
│   │   └── StockService.php
│   └── Notifications/         # Email/SMS notifications
├── database/
│   ├── migrations/            # Database migrations
│   ├── seeders/               # Database seeders
│   └── factories/             # Model factories
├── public/
│   ├── css/                   # Compiled CSS
│   ├── js/                    # Compiled JS
│   ├── images/                # Static images
│   ├── storage/               # Symlinked storage
│   ├── favicon.ico
│   └── robots.txt
├── resources/
│   ├── css/                   # Source CSS
│   ├── js/                    # Source JS (Alpine.js)
│   ├── lang/                  # Language files
│   └── views/                 # Blade templates
│       ├── layouts/
│       ├── auth/
│       ├── dashboard/
│       └── ...
├── routes/
│   ├── web.php                # Web routes
│   ├── api.php                # API routes
│   └── console.php            # Console routes
├── storage/
│   ├── app/                   # File uploads
│   ├── framework/             # Laravel cache/logs
│   └── logs/                  # Application logs
├── tests/
│   ├── Feature/               # Feature tests
│   ├── Unit/                  # Unit tests
│   └── TestCase.php
├── vendor/                    # Composer dependencies
├── .env.example               # Environment template
├── artisan                    # Laravel CLI
├── composer.json              # PHP dependencies
├── package.json               # Node dependencies
├── tailwind.config.js         # Tailwind config
├── vite.config.js             # Vite config
├── phpunit.xml                # Test config
└── README.md
```

## 🔧 Konfigurasi

### Environment Variables (.env)

```env
# Application
APP_NAME="POS UMKM"
APP_ENV=local
APP_KEY=base64:your_app_key_here
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_umkm
DB_USERNAME=root
DB_PASSWORD=your_password

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Queue (untuk background jobs)
QUEUE_CONNECTION=database

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Midtrans Payment Gateway
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SANDBOX=true

# File Storage
FILESYSTEM_DISK=local

# Logging
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Vite (untuk asset building)
VITE_APP_NAME="${APP_NAME}"
```

## 📝 License

Distributed under the MIT License. See `LICENSE` for more information.

---

**Dibuat dengan ❤️ untuk membantu UMKM Indonesia berkembang lebih maju**

⭐ Jika project ini bermanfaat, jangan lupa untuk memberikan star! 
 
