# KATANA E-Rekap Dashboard

Dashboard untuk PT. Kamil Trio Niaga (KATANA) - Sistem manajemen bisnis terintegrasi.

## Deskripsi

Dashboard KATANA E-Rekap adalah aplikasi web modern yang dibangun dengan Laravel dan Tailwind CSS untuk mengelola berbagai aspek bisnis PT. Kamil Trio Niaga. Dashboard ini menyediakan antarmuka yang intuitif dan responsif untuk monitoring dan pengelolaan operasional perusahaan.

## Fitur Utama

### 📊 Dashboard
- Overview statistik bisnis real-time
- Grafik penjualan dan analitik
- Sebaran geografis pelanggan dengan peta Indonesia interaktif
- Aktivitas terbaru sistem

### 📋 Laporan
- Laporan Penjualan
- Laporan Keuangan
- Laporan Inventory
- Laporan Customer
- Laporan Performa KPI
- Laporan Pajak
- Export ke PDF, Excel, CSV

### 📢 Marketing
- Campaign Management
- Analytics & ROI tracking
- Content Calendar
- Customer Insights
- Social Media integration

### 🛒 Purchasing
- Purchase Order Management
- Supplier Management
- Purchase Analytics
- Cost Savings tracking

### 💰 Keuangan
- Cash Flow monitoring
- Account Balances
- Transaction History
- Budget vs Actual analysis
- Financial Health metrics

### 📦 Produk
- Inventory Management
- Stock monitoring
- Product catalog
- Sales analytics
- Low stock alerts

### ⚙️ Pengaturan
- Company Information
- System Preferences
- User Management
- Security Settings
- Backup & Maintenance

## Teknologi

- **Backend**: Laravel 11
- **Frontend**: Tailwind CSS
- **Icons**: Font Awesome
- **Database**: MySQL (ready to configure)
- **Server**: PHP 8.1+

## Struktur File

```
katana_e_rekap/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php          # Layout utama
│   │   │   └── dashboard.blade.php    # Layout dashboard
│   │   ├── components/
│   │   │   ├── sidebar.blade.php      # Komponen sidebar
│   │   │   └── header.blade.php       # Komponen header
│   │   └── pages/
│   │       ├── dashboard.blade.php    # Halaman dashboard
│   │       ├── laporan.blade.php      # Halaman laporan
│   │       ├── marketing.blade.php    # Halaman marketing
│   │       ├── purchasing.blade.php   # Halaman purchasing
│   │       ├── keuangan.blade.php     # Halaman keuangan
│   │       ├── produk.blade.php       # Halaman produk
│   │       └── pengaturan.blade.php   # Halaman pengaturan
│   ├── css/
│   │   └── app.css                    # Styling custom
│   └── js/
│       └── app.js                     # JavaScript interaktivity
├── routes/
│   └── web.php                        # Route definitions
└── README.md                          # Dokumentasi ini
```

## Instalasi

1. **Clone atau download project**
2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup** (opsional):
   - Konfigurasi database di `.env`
   - Jalankan migrasi: `php artisan migrate`

5. **Build assets**:
   ```bash
   npm run build
   # atau untuk development
   npm run dev
   ```

6. **Jalankan server**:
   ```bash
   php artisan serve
   ```

7. **Akses aplikasi**: Buka `http://127.0.0.1:8000`

## Cara Penggunaan

### Navigasi
- **Sidebar kiri**: Menu utama dengan 7 module bisnis
- **Header atas**: Search bar, filter bulanan, dan user menu
- **Konten utama**: Berbeda untuk setiap halaman

### Fitur Search
- Gunakan search bar di header untuk mencari data
- Tekan Enter atau klik tombol "Cari"

### Filter Data
- Gunakan dropdown "Bulanan" untuk filter periode
- Tombol "Filter" untuk opsi filtering lanjutan

### Responsive Design
- Dashboard fully responsive untuk desktop, tablet, dan mobile
- Sidebar collapse otomatis pada layar kecil

## Kustomisasi

### Menambah Halaman Baru
1. Buat file view di `resources/views/pages/`
2. Extend layout `layouts.dashboard`
3. Tambahkan route di `routes/web.php`
4. Update sidebar di `components/sidebar.blade.php`

### Styling
- Edit `resources/css/app.css` untuk custom CSS
- Gunakan Tailwind classes untuk styling cepat
- Warna utama: Red (#DC143C) untuk brand KATANA

### JavaScript
- Tambahkan fungsi di `resources/js/app.js`
- Gunakan event listeners untuk interactivity

## Komponen Utama

### Sidebar
- Navigation menu dengan active state
- Logo perusahaan
- Menu collapse untuk mobile

### Header
- Search functionality
- Date/month filter
- User menu dengan dropdown
- Notifications

### Cards & Widgets
- Statistics cards dengan icons
- Chart placeholders (siap untuk Chart.js)
- Data tables dengan sorting & pagination
- Alert system

### Peta Indonesia
- SVG-based map untuk visualisasi geografis
- Interactive markers
- Responsive design

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Kontribusi

Untuk pengembangan lebih lanjut:
1. Fork repository
2. Buat feature branch
3. Commit changes
4. Push ke branch
5. Create Pull Request

## Roadmap

### Phase 1 (Complete)
- ✅ Basic dashboard layout
- ✅ All main pages structure
- ✅ Responsive design
- ✅ Navigation system

### Phase 2 (Future)
- 🔄 Database integration
- 🔄 Authentication system
- 🔄 Real charts with Chart.js
- 🔄 API integration

### Phase 3 (Future)
- 🔄 Advanced filters
- 🔄 Export functionality
- 🔄 Real-time notifications
- 🔄 Mobile app

## Lisensi

Proyek ini dibuat untuk PT. Kamil Trio Niaga (KATANA). All rights reserved.

## Kontak

Untuk pertanyaan atau dukungan teknis, hubungi:
- Email: developer@katana.co.id
- Website: https://katana.co.id

---

**KATANA E-Rekap Dashboard** - Memudahkan pengelolaan bisnis dengan teknologi modern.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
