<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Barang;
use App\Models\Proyek;
use App\Models\Penawaran;
use App\Models\PenawaranDetail;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\Wilayah;
use App\Models\PenagihanDinas;
use App\Models\BuktiPembayaran;

class CompleteSystemSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables in reverse dependency order
        $tables = [
            'bukti_pembayaran',
            'penagihan_dinas',
            'pengiriman',
            'pembayaran',
            'penawaran_detail',
            'penawaran',
            'proyek',
            'barang',
            'vendor',
            'wilayah',
            'users'
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "🗑️  Database cleaned successfully!\n";
        echo "📊 Starting complete system seeding...\n\n";

        // Seed data in proper order
        $this->seedUsers();
        $this->seedWilayah();
        $this->seedVendors();
        $this->seedBarang();
        $this->seedProyek();
        $this->seedPenawaran();
        $this->seedPenawaranDetail();
        $this->seedPembayaran();
        $this->seedPengiriman();
        $this->seedPenagihanDinas();
        $this->seedBuktiPembayaran();

        echo "\n✅ Complete system seeding finished successfully!\n";
        echo "🔐 Login credentials:\n";
        echo "   - Super Admin: superadmin@katana.com / password123\n";
        echo "   - Marketing: marketing@katana.com / password123\n";
        echo "   - Purchasing: purchasing@katana.com / password123\n";
        echo "   - Keuangan: keuangan@katana.com / password123\n";
        echo "   - Demo: demo@katana.com / password123\n\n";
    }

    private function seedUsers()
    {
        echo "👥 Seeding users...\n";

        $users = [
            [
                'nama' => 'Super Administrator',
                'username' => 'superadmin',
                'email' => 'superadmin@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'superadmin',
                'no_telepon' => '081234567890',
                'alamat' => 'Jakarta Pusat, DKI Jakarta',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Admin Marketing',
                'username' => 'admin_marketing',
                'email' => 'marketing@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_marketing',
                'no_telepon' => '081234567891',
                'alamat' => 'Jakarta Selatan, DKI Jakarta',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Manager Marketing',
                'username' => 'manager_marketing',
                'email' => 'manager.marketing@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_marketing',
                'no_telepon' => '081234567892',
                'alamat' => 'Jakarta Selatan, DKI Jakarta',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Admin Purchasing',
                'username' => 'admin_purchasing',
                'email' => 'purchasing@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_purchasing',
                'no_telepon' => '081234567893',
                'alamat' => 'Jakarta Timur, DKI Jakarta',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Manager Purchasing',
                'username' => 'manager_purchasing',
                'email' => 'manager.purchasing@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_purchasing',
                'no_telepon' => '081234567894',
                'alamat' => 'Jakarta Timur, DKI Jakarta',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Admin Keuangan',
                'username' => 'admin_keuangan',
                'email' => 'keuangan@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_keuangan',
                'no_telepon' => '081234567896',
                'alamat' => 'Jakarta Barat, DKI Jakarta',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Manager Keuangan',
                'username' => 'manager_keuangan',
                'email' => 'manager.keuangan@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_keuangan',
                'no_telepon' => '081234567897',
                'alamat' => 'Jakarta Barat, DKI Jakarta',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Demo User',
                'username' => 'demo',
                'email' => 'demo@katana.com',
                'password' => Hash::make('password123'),
                'role' => 'superadmin',
                'no_telepon' => '081234567899',
                'alamat' => 'Jakarta, DKI Jakarta',
                'foto' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        User::insert($users);
        echo "   ✓ Created " . count($users) . " users\n";
    }

    private function seedVendors()
    {
        echo "🏪 Seeding vendors...\n";

        $vendors = [
            [
                'nama_vendor' => 'PT Teknologi Canggih Indonesia',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'kontak' => '021-12345678',
                'jenis_perusahaan' => 'Principle',
                'email' => 'info@teknologicanggih.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_vendor' => 'CV Elektronik Makmur',
                'alamat' => 'Jl. Thamrin No. 456, Jakarta Pusat',
                'kontak' => '021-87654321',
                'jenis_perusahaan' => 'Distributor',
                'email' => 'sales@elektronikmakmur.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_vendor' => 'PT Furniture Nusantara',
                'alamat' => 'Jl. Gatot Subroto No. 789, Jakarta Selatan',
                'kontak' => '021-11223344',
                'jenis_perusahaan' => 'Principle',
                'email' => 'order@furniturenusantara.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_vendor' => 'UD Mesin Industri Jaya',
                'alamat' => 'Jl. Rasuna Said No. 101, Jakarta Selatan',
                'kontak' => '021-55667788',
                'jenis_perusahaan' => 'Retail',
                'email' => 'contact@mesinindustri.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_vendor' => 'PT Alat Kantor Prima',
                'alamat' => 'Jl. HR Rasuna Said No. 202, Jakarta Selatan',
                'kontak' => '021-99887766',
                'jenis_perusahaan' => 'Distributor',
                'email' => 'sales@alatkantor.com',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        Vendor::insert($vendors);
        echo "   ✓ Created " . count($vendors) . " vendors\n";
    }

    private function seedBarang()
    {
        echo "📦 Seeding barang...\n";

        $barang = [
            // Elektronik dari vendor 1
            [
                'id_vendor' => 1,
                'nama_barang' => 'Laptop Dell Latitude 5420',
                'foto_barang' => null,
                'brand' => 'Dell',
                'spesifikasi' => 'Intel Core i5-1135G7, 8GB RAM, 256GB SSD, 14" FHD, Windows 11',
                'kategori' => 'Elektronik',
                'satuan' => 'Unit',
                'harga_vendor' => 12500000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_vendor' => 1,
                'nama_barang' => 'Monitor LG 24" IPS',
                'foto_barang' => null,
                'brand' => 'LG',
                'spesifikasi' => '24 inch, IPS Panel, 1920x1080, HDMI, VGA',
                'kategori' => 'Elektronik',
                'satuan' => 'Unit',
                'harga_vendor' => 2250000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_vendor' => 2,
                'nama_barang' => 'Printer HP LaserJet Pro M404n',
                'foto_barang' => null,
                'brand' => 'HP',
                'spesifikasi' => 'Laser Printer, Monochrome, Network Ready, Auto Duplex',
                'kategori' => 'Elektronik',
                'satuan' => 'Unit',
                'harga_vendor' => 3750000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_vendor' => 2,
                'nama_barang' => 'Scanner Canon CanoScan LiDE 300',
                'foto_barang' => null,
                'brand' => 'Canon',
                'spesifikasi' => 'Flatbed Scanner, 2400x4800 dpi, USB powered',
                'kategori' => 'Elektronik',
                'satuan' => 'Unit',
                'harga_vendor' => 1125000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Meubel dari vendor 3
            [
                'id_vendor' => 3,
                'nama_barang' => 'Meja Kerja Executive',
                'foto_barang' => null,
                'brand' => 'Olympic',
                'spesifikasi' => 'Kayu mahoni, ukuran 160x80x75 cm, dengan laci',
                'kategori' => 'Meubel',
                'satuan' => 'Unit',
                'harga_vendor' => 4500000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_vendor' => 3,
                'nama_barang' => 'Kursi Kantor Ergonomis',
                'foto_barang' => null,
                'brand' => 'Chitose',
                'spesifikasi' => 'Bahan mesh, adjustable height, lumbar support',
                'kategori' => 'Meubel',
                'satuan' => 'Unit',
                'harga_vendor' => 2250000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_vendor' => 3,
                'nama_barang' => 'Lemari Arsip 4 Pintu',
                'foto_barang' => null,
                'brand' => 'VIP',
                'spesifikasi' => 'Bahan besi, 4 pintu, ukuran 180x90x40 cm',
                'kategori' => 'Meubel',
                'satuan' => 'Unit',
                'harga_vendor' => 3375000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Mesin dari vendor 4
            [
                'id_vendor' => 4,
                'nama_barang' => 'Generator Set 5 KVA',
                'foto_barang' => null,
                'brand' => 'Yamaha',
                'spesifikasi' => 'Silent type, 5000 watt, portable, electric start',
                'kategori' => 'Mesin',
                'satuan' => 'Unit',
                'harga_vendor' => 18750000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_vendor' => 4,
                'nama_barang' => 'Pompa Air Centrifugal',
                'foto_barang' => null,
                'brand' => 'Grundfos',
                'spesifikasi' => '1 HP, head 35 meter, kapasitas 40 liter/menit',
                'kategori' => 'Mesin',
                'satuan' => 'Unit',
                'harga_vendor' => 5625000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Lain-lain dari vendor 5
            [
                'id_vendor' => 5,
                'nama_barang' => 'Proyektor Epson EB-S41',
                'foto_barang' => null,
                'brand' => 'Epson',
                'spesifikasi' => '3300 lumens, SVGA, HDMI, VGA, USB',
                'kategori' => 'Elektronik',
                'satuan' => 'Unit',
                'harga_vendor' => 6750000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_vendor' => 5,
                'nama_barang' => 'AC Split 1 PK',
                'foto_barang' => null,
                'brand' => 'Daikin',
                'spesifikasi' => '1 PK, R32, inverter, low watt',
                'kategori' => 'Elektronik',
                'satuan' => 'Unit',
                'harga_vendor' => 4500000.00,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        Barang::insert($barang);
        echo "   ✓ Created " . count($barang) . " barang\n";
    }

    private function seedWilayah()
    {
        echo "🗺️  Seeding wilayah...\n";

        $wilayahData = [
            [
                'nama_wilayah' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
                'kode_wilayah' => 'JKT-PST',
                'deskripsi' => 'Wilayah pusat pemerintahan dan bisnis Jakarta',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_wilayah' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'kode_wilayah' => 'JKT-SEL',
                'deskripsi' => 'Wilayah Jakarta Selatan yang strategis',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_wilayah' => 'Jakarta Timur',
                'provinsi' => 'DKI Jakarta',
                'kode_wilayah' => 'JKT-TIM',
                'deskripsi' => 'Wilayah Jakarta Timur',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_wilayah' => 'Jakarta Barat',
                'provinsi' => 'DKI Jakarta',
                'kode_wilayah' => 'JKT-BAR',
                'deskripsi' => 'Wilayah Jakarta Barat',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_wilayah' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kode_wilayah' => 'BDG',
                'deskripsi' => 'Kota Bandung, Jawa Barat',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_wilayah' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'kode_wilayah' => 'SBY',
                'deskripsi' => 'Kota Surabaya, Jawa Timur',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        Wilayah::insert($wilayahData);
        echo "   ✓ Created " . count($wilayahData) . " wilayah\n";
    }

    private function seedProyek()
    {
        echo "🚀 Seeding proyek...\n";

        $proyeks = [
            // Proyek 1: Status "Selesai" - sudah selesai pengiriman
            [
                'tanggal' => Carbon::now()->subDays(30),
                'id_wilayah' => 1, // Jakarta Pusat
                'kab_kota' => 'Jakarta Pusat',
                'instansi' => 'Dinas Pendidikan DKI Jakarta',
                'nama_klien' => 'Budi Santoso',
                'kontak_klien' => '0812-3456-7890',
                'jenis_pengadaan' => 'Pengadaan Langsung',
                'deadline' => Carbon::now()->addDays(30),
                'id_admin_marketing' => 2, // Admin Marketing
                'id_admin_purchasing' => 4, // Admin Purchasing
                'id_penawaran' => null, // Will be updated after penawaran created
                'catatan' => 'Pengadaan untuk 25 set lab komputer SMA Negeri 1 Jakarta',
                'status' => 'Selesai',
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => now()
            ],

            // Proyek 2: Status "Pengiriman" - sedang dalam proses pengiriman
            [
                'tanggal' => Carbon::now()->subDays(25),
                'id_wilayah' => 2, // Jakarta Selatan
                'kab_kota' => 'Jakarta Selatan',
                'instansi' => 'PT Kreatif Teknologi',
                'nama_klien' => 'Sari Dewi',
                'kontak_klien' => '0813-9876-5432',
                'jenis_pengadaan' => 'Tender Terbatas',
                'deadline' => Carbon::now()->addDays(20),
                'id_admin_marketing' => 3, // Manager Marketing
                'id_admin_purchasing' => 5, // Manager Purchasing
                'id_penawaran' => null,
                'catatan' => 'Pengadaan furniture untuk kantor cabang baru PT Kreatif Teknologi',
                'status' => 'Pengiriman',
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => now()
            ],

            // Proyek 3: Status "Pengiriman" - pembayaran sudah diverifikasi, sedang pengiriman
            [
                'tanggal' => Carbon::now()->subDays(20),
                'id_wilayah' => 5, // Bandung
                'kab_kota' => 'Bandung',
                'instansi' => 'CV Maju Bersama',
                'nama_klien' => 'Budi Santoso',
                'kontak_klien' => '0814-5678-9012',
                'jenis_pengadaan' => 'Penunjukan Langsung',
                'deadline' => Carbon::now()->addDays(25),
                'id_admin_marketing' => 2,
                'id_admin_purchasing' => 4,
                'id_penawaran' => null,
                'catatan' => 'Urgent procurement untuk backup power',
                'status' => 'Pengiriman',
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => now()
            ],

            // Proyek 4: Status "Pembayaran" - belum ada pembayaran yang diverifikasi
            [
                'tanggal' => Carbon::now()->subDays(15),
                'id_wilayah' => 4, // Jakarta Barat
                'kab_kota' => 'Jakarta Barat',
                'instansi' => 'Universitas Bina Nusantara',
                'nama_klien' => 'Prof. Maria Susanti',
                'kontak_klien' => '0815-2468-1357',
                'jenis_pengadaan' => 'Tender Terbuka',
                'deadline' => Carbon::now()->addDays(35),
                'id_admin_marketing' => 3,
                'id_admin_purchasing' => 5,
                'id_penawaran' => null,
                'catatan' => 'Pengadaan untuk 10 ruang kuliah baru kampus BINUS Alam Sutera',
                'status' => 'Pembayaran',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => now()
            ],

            // Proyek 5: Status "Penawaran" - belum masuk ke pembayaran
            [
                'tanggal' => Carbon::now()->subDays(10),
                'id_wilayah' => 3, // Jakarta Timur (ganti dari Jakarta Utara)
                'kab_kota' => 'Jakarta Timur',
                'instansi' => 'PT Pelindo II',
                'nama_klien' => 'Ir. Bambang Wijaya',
                'kontak_klien' => '0816-1357-2468',
                'jenis_pengadaan' => 'Pengadaan Langsung',
                'deadline' => Carbon::now()->addDays(40),
                'id_admin_marketing' => 2,
                'id_admin_purchasing' => 4,
                'id_penawaran' => null,
                'catatan' => 'Replacement peralatan administrasi yang sudah lama',
                'status' => 'Penawaran',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => now()
            ],

            // Proyek 6: Status "Gagal" - proyek gagal verifikasi pengiriman
            [
                'tanggal' => Carbon::now()->subDays(45),
                'id_wilayah' => 6, // Surabaya (ganti dari Depok)
                'kab_kota' => 'Surabaya',
                'instansi' => 'Sekolah Tinggi Teknologi Surabaya',
                'nama_klien' => 'Dr. Agus Pramono',
                'kontak_klien' => '0817-8888-9999',
                'jenis_pengadaan' => 'Tender Terbatas',
                'deadline' => Carbon::now()->addDays(15),
                'id_admin_marketing' => 3,
                'id_admin_purchasing' => 5,
                'id_penawaran' => null,
                'catatan' => 'Proyek ini gagal karena barang tidak sesuai spesifikasi saat verifikasi',
                'status' => 'Gagal',
                'created_at' => Carbon::now()->subDays(45),
                'updated_at' => now()
            ],

            // PROYEK TAMBAHAN DENGAN STATUS "MENUNGGU"
            
            // Proyek 7: Lab Multimedia SMP - Status "Menunggu"
            [
                'tanggal' => Carbon::now()->subDays(5),
                'id_wilayah' => 1, // Jakarta Pusat
                'kab_kota' => 'Jakarta Pusat',
                'instansi' => 'SMP Negeri 5 Jakarta',
                'nama_klien' => 'Dra. Siti Rahayu',
                'kontak_klien' => '0818-1111-2222',
                'jenis_pengadaan' => 'Tender Terbuka',
                'deadline' => Carbon::now()->addDays(50),
                'id_admin_marketing' => 2,
                'id_admin_purchasing' => 4,
                'id_penawaran' => null,
                'catatan' => 'Pengadaan untuk upgrade lab multimedia SMP',
                'status' => 'Menunggu',
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => now()
            ],

            // Proyek 8: Lab Kimia Universitas - Status "Menunggu"
            [
                'tanggal' => Carbon::now()->subDays(3),
                'id_wilayah' => 2, // Jakarta Selatan
                'kab_kota' => 'Jakarta Selatan',
                'instansi' => 'Universitas Indonesia',
                'nama_klien' => 'Prof. Dr. Ahmad Ridwan',
                'kontak_klien' => '0819-3333-4444',
                'jenis_pengadaan' => 'Pengadaan Langsung',
                'deadline' => Carbon::now()->addDays(65),
                'id_admin_marketing' => 3,
                'id_admin_purchasing' => 5,
                'id_penawaran' => null,
                'catatan' => 'Pengadaan alat lab untuk penelitian mahasiswa S2/S3',
                'status' => 'Menunggu',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => now()
            ],

            // Proyek 9: Server IT Bank - Status "Menunggu"
            [
                'tanggal' => Carbon::now()->subDays(2),
                'id_wilayah' => 4, // Jakarta Barat
                'kab_kota' => 'Jakarta Barat',
                'instansi' => 'Bank Mandiri Cabang Kebon Jeruk',
                'nama_klien' => 'Ir. Bambang Susilo',
                'kontak_klien' => '0820-5555-6666',
                'jenis_pengadaan' => 'Tender Terbatas',
                'deadline' => Carbon::now()->addDays(95),
                'id_admin_marketing' => 2,
                'id_admin_purchasing' => 4,
                'id_penawaran' => null,
                'catatan' => 'Upgrade infrastructure IT untuk cabang baru',
                'status' => 'Menunggu',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => now()
            ],

            // Proyek 10: Furniture Sekolah Dasar - Status "Menunggu"
            [
                'tanggal' => Carbon::now()->subDays(1),
                'id_wilayah' => 3, // Jakarta Timur
                'kab_kota' => 'Jakarta Timur',
                'instansi' => 'SDN Cakung 01',
                'nama_klien' => 'Ibu Ratna Sari, S.Pd',
                'kontak_klien' => '0821-7777-8888',
                'jenis_pengadaan' => 'Pengadaan Langsung',
                'deadline' => Carbon::now()->addDays(30),
                'id_admin_marketing' => 3,
                'id_admin_purchasing' => 5,
                'id_penawaran' => null,
                'catatan' => 'Replacement furniture lama yang sudah rusak',
                'status' => 'Menunggu',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => now()
            ],

            // Proyek 11: Sound System Masjid - Status "Menunggu"
            [
                'tanggal' => Carbon::now(),
                'id_wilayah' => 5, // Bandung
                'kab_kota' => 'Bandung',
                'instansi' => 'Masjid Al-Ikhlas Bandung',
                'nama_klien' => 'Ustadz Ahmad Fauzi',
                'kontak_klien' => '0822-9999-0000',
                'jenis_pengadaan' => 'Penunjukan Langsung',
                'deadline' => Carbon::now()->addDays(20),
                'id_admin_marketing' => 2,
                'id_admin_purchasing' => 4,
                'id_penawaran' => null,
                'catatan' => 'Pengadaan untuk renovasi sound system masjid',
                'status' => 'Menunggu',
                'created_at' => Carbon::now(),
                'updated_at' => now()
            ],

            // Proyek 12: AC Ruang Operasi Rumah Sakit - Status "Menunggu"
            [
                'tanggal' => Carbon::now(),
                'id_wilayah' => 6, // Surabaya
                'kab_kota' => 'Surabaya',
                'instansi' => 'RSU Dr. Soetomo',
                'nama_klien' => 'Dr. Santi Wijayanti',
                'kontak_klien' => '0823-1111-2222',
                'jenis_pengadaan' => 'Tender Terbuka',
                'deadline' => Carbon::now()->addDays(120),
                'id_admin_marketing' => 3,
                'id_admin_purchasing' => 5,
                'id_penawaran' => null,
                'catatan' => 'Critical infrastructure untuk ruang operasi baru',
                'status' => 'Menunggu',
                'created_at' => Carbon::now(),
                'updated_at' => now()
            ]
        ];

        Proyek::insert($proyeks);
        echo "   ✓ Created " . count($proyeks) . " proyek\n";

        // Seed data proyek_barang untuk setiap proyek
        $this->seedProyekBarang();

        // Update harga_total untuk semua proyek setelah proyek_barang dibuat
        $this->updateHargaTotalProyek();
    }

    private function updateHargaTotalProyek()
    {
        echo "💰 Updating harga_total proyek...\n";
        
        $proyeks = \App\Models\Proyek::all();
        foreach ($proyeks as $proyek) {
            $proyek->calculateHargaTotal();
        }
        
        echo "   ✓ Updated harga_total for all proyek\n";
    }

    private function seedProyekBarang()
    {
        echo "📦 Seeding proyek barang...\n";

        $proyekBarangData = [
            // Proyek 1: 2 barang - Laptop dan Monitor (Selesai)
            ['id_proyek' => 1, 'nama_barang' => 'Laptop Dell Core i5', 'jumlah' => 25, 'satuan' => 'Unit', 'spesifikasi' => 'Laptop Dell Core i5, RAM 8GB, SSD 256GB untuk lab komputer', 'harga_satuan' => 12500000, 'harga_total' => 312500000],
            ['id_proyek' => 1, 'nama_barang' => 'Monitor LG 24 inch', 'jumlah' => 25, 'satuan' => 'Unit', 'spesifikasi' => 'Monitor LG 24 inch IPS full HD untuk lab komputer', 'harga_satuan' => 2250000, 'harga_total' => 56250000],
            
            // Proyek 2: 2 barang - Meja dan Kursi (Pengiriman)  
            ['id_proyek' => 2, 'nama_barang' => 'Meja Kerja Executive', 'jumlah' => 15, 'satuan' => 'Unit', 'spesifikasi' => 'Meja kerja executive kayu mahoni, ukuran 120x80cm', 'harga_satuan' => 4500000, 'harga_total' => 67500000],
            ['id_proyek' => 2, 'nama_barang' => 'Kursi Ergonomis', 'jumlah' => 15, 'satuan' => 'Unit', 'spesifikasi' => 'Kursi kantor ergonomis dengan sandaran lumbar, bahan kulit sintetis', 'harga_satuan' => 2250000, 'harga_total' => 33750000],
            
            // Proyek 3: 2 barang - Generator dan Pompa (Pengiriman)
            ['id_proyek' => 3, 'nama_barang' => 'Generator Set 5 KVA', 'jumlah' => 2, 'satuan' => 'Unit', 'spesifikasi' => 'Generator set 5 KVA, bahan bakar solar, panel kontrol otomatis', 'harga_satuan' => 18750000, 'harga_total' => 37500000],
            ['id_proyek' => 3, 'nama_barang' => 'Pompa Air Centrifugal', 'jumlah' => 2, 'satuan' => 'Unit', 'spesifikasi' => 'Pompa air centrifugal 2 HP, kapasitas 100 L/menit', 'harga_satuan' => 5625000, 'harga_total' => 11250000],
            
            // Proyek 4: 2 barang - Proyektor dan AC (Pembayaran)
            ['id_proyek' => 4, 'nama_barang' => 'Proyektor Epson 3300 lumens', 'jumlah' => 10, 'satuan' => 'Unit', 'spesifikasi' => 'Proyektor Epson EB-S41, 3300 lumens, SVGA, HDMI, VGA, USB', 'harga_satuan' => 6750000, 'harga_total' => 67500000],
            ['id_proyek' => 4, 'nama_barang' => 'AC Split 1 PK Daikin', 'jumlah' => 10, 'satuan' => 'Unit', 'spesifikasi' => 'AC Split Daikin 1 PK, R32, inverter, low watt', 'harga_satuan' => 4500000, 'harga_total' => 45000000],
            
            // Proyek 5: 2 barang - Printer dan Scanner (Penawaran)
            ['id_proyek' => 5, 'nama_barang' => 'Printer HP LaserJet', 'jumlah' => 20, 'satuan' => 'Unit', 'spesifikasi' => 'Printer HP LaserJet P1102, monochrome, USB, A4', 'harga_satuan' => 3750000, 'harga_total' => 75000000],
            ['id_proyek' => 5, 'nama_barang' => 'Scanner Canon LiDE', 'jumlah' => 20, 'satuan' => 'Unit', 'spesifikasi' => 'Scanner Canon LiDE 400, flatbed, 4800x4800 dpi, USB', 'harga_satuan' => 1125000, 'harga_total' => 22500000],
            
            // Proyek 6: 2 barang - Meja dan Kursi (Gagal)
            ['id_proyek' => 6, 'nama_barang' => 'Meja Executive Mahoni', 'jumlah' => 12, 'satuan' => 'Unit', 'spesifikasi' => 'Meja executive kayu mahoni solid, ukuran 150x90cm, finishing glossy', 'harga_satuan' => 4500000, 'harga_total' => 54000000],
            ['id_proyek' => 6, 'nama_barang' => 'Kursi Direktur Kulit', 'jumlah' => 12, 'satuan' => 'Unit', 'spesifikasi' => 'Kursi direktur kulit asli, reclining, massage, premium quality', 'harga_satuan' => 3375000, 'harga_total' => 40500000],
            
            // PROYEK MENUNGGU - TAMBAHAN DENGAN BANYAK JENIS BARANG
            
            // Proyek 7: Lab Multimedia - 4 jenis barang (Menunggu)
            ['id_proyek' => 7, 'nama_barang' => 'Komputer All-in-One', 'jumlah' => 30, 'satuan' => 'Unit', 'spesifikasi' => 'Komputer All-in-One 21.5 inch, Core i3, 8GB RAM, 256GB SSD', 'harga_satuan' => 8500000, 'harga_total' => 255000000],
            ['id_proyek' => 7, 'nama_barang' => 'Headset Gaming', 'jumlah' => 30, 'satuan' => 'Unit', 'spesifikasi' => 'Headset gaming stereo dengan microphone, anti-noise', 'harga_satuan' => 750000, 'harga_total' => 22500000],
            ['id_proyek' => 7, 'nama_barang' => 'Webcam HD', 'jumlah' => 30, 'satuan' => 'Unit', 'spesifikasi' => 'Webcam HD 1080p dengan auto focus dan built-in microphone', 'harga_satuan' => 850000, 'harga_total' => 25500000],
            ['id_proyek' => 7, 'nama_barang' => 'Speaker Multimedia', 'jumlah' => 15, 'satuan' => 'Set', 'spesifikasi' => 'Speaker multimedia 2.1 dengan subwoofer, 50W RMS', 'harga_satuan' => 1250000, 'harga_total' => 18750000],
            
            // Proyek 8: Lab Kimia - 5 jenis barang (Menunggu)
            ['id_proyek' => 8, 'nama_barang' => 'Mikroskop Digital', 'jumlah' => 1, 'satuan' => 'Unit', 'spesifikasi' => 'Mikroskop digital dengan kamera 5MP, perbesaran 40x-2000x', 'harga_satuan' => 125000000, 'harga_total' => 125000000],
            ['id_proyek' => 8, 'nama_barang' => 'pH Meter Digital', 'jumlah' => 1, 'satuan' => 'Unit', 'spesifikasi' => 'pH meter digital dengan akurasi ±0.01, auto calibration', 'harga_satuan' => 45000000, 'harga_total' => 45000000],
            ['id_proyek' => 8, 'nama_barang' => 'Centrifuge', 'jumlah' => 1, 'satuan' => 'Unit', 'spesifikasi' => 'Centrifuge kecepatan tinggi 12000 rpm, 24 tube capacity', 'harga_satuan' => 75000000, 'harga_total' => 75000000],
            ['id_proyek' => 8, 'nama_barang' => 'Spektrofotometer', 'jumlah' => 1, 'satuan' => 'Unit', 'spesifikasi' => 'Spektrofotometer UV-Vis dengan wavelength 190-1100nm', 'harga_satuan' => 150000000, 'harga_total' => 150000000],
            ['id_proyek' => 8, 'nama_barang' => 'Timbangan Analitik', 'jumlah' => 1, 'satuan' => 'Unit', 'spesifikasi' => 'Timbangan analitik presisi 0.1mg, kapasitas 220g', 'harga_satuan' => 55000000, 'harga_total' => 55000000],
            
            // Proyek 9: Server & Network - 6 jenis barang (Menunggu)
            ['id_proyek' => 9, 'nama_barang' => 'Server Dell PowerEdge', 'jumlah' => 1, 'satuan' => 'Unit', 'spesifikasi' => 'Server Dell PowerEdge R750, Xeon Silver, 32GB RAM, 2TB SSD', 'harga_satuan' => 250000000, 'harga_total' => 250000000],
            ['id_proyek' => 9, 'nama_barang' => 'Switch Managed 48 Port', 'jumlah' => 1, 'satuan' => 'Unit', 'spesifikasi' => 'Switch managed 48 port gigabit dengan 4 SFP+ uplink', 'harga_satuan' => 75000000, 'harga_total' => 75000000],
            ['id_proyek' => 9, 'nama_barang' => 'Firewall Enterprise', 'jumlah' => 1, 'satuan' => 'Unit', 'spesifikasi' => 'Firewall enterprise dengan throughput 10Gbps, VPN support', 'harga_satuan' => 125000000, 'harga_total' => 125000000],
            ['id_proyek' => 9, 'nama_barang' => 'UPS 10KVA', 'jumlah' => 1, 'satuan' => 'Unit', 'spesifikasi' => 'UPS online 10KVA dengan battery backup 30 menit', 'harga_satuan' => 85000000, 'harga_total' => 85000000],
            ['id_proyek' => 9, 'nama_barang' => 'Rack Server 42U', 'jumlah' => 1, 'satuan' => 'Unit', 'spesifikasi' => 'Rack server 42U dengan cooling system dan cable management', 'harga_satuan' => 45000000, 'harga_total' => 45000000],
            ['id_proyek' => 9, 'nama_barang' => 'Access Point WiFi 6', 'jumlah' => 10, 'satuan' => 'Unit', 'spesifikasi' => 'Access Point WiFi 6 indoor dengan PoE+, dual band', 'harga_satuan' => 8500000, 'harga_total' => 85000000],
            
            // Proyek 10: Furniture Sekolah - 3 jenis barang (Menunggu)
            ['id_proyek' => 10, 'nama_barang' => 'Meja Belajar Siswa', 'jumlah' => 120, 'satuan' => 'Set', 'spesifikasi' => 'Meja belajar siswa plastik ergonomis, tinggi adjustable', 'harga_satuan' => 650000, 'harga_total' => 78000000],
            ['id_proyek' => 10, 'nama_barang' => 'Kursi Belajar Siswa', 'jumlah' => 120, 'satuan' => 'Unit', 'spesifikasi' => 'Kursi belajar siswa plastik ergonomis, tinggi adjustable', 'harga_satuan' => 350000, 'harga_total' => 42000000],
            ['id_proyek' => 10, 'nama_barang' => 'Loker Siswa', 'jumlah' => 60, 'satuan' => 'Unit', 'spesifikasi' => 'Loker siswa besi 2 pintu dengan kunci, anti karat', 'harga_satuan' => 1750000, 'harga_total' => 105000000],
            
            // Proyek 11: Sound System Masjid - 4 jenis barang (Menunggu) 
            ['id_proyek' => 11, 'nama_barang' => 'Mixer Audio 16 Channel', 'jumlah' => 1, 'satuan' => 'Unit', 'spesifikasi' => 'Mixer audio 16 channel dengan USB recording dan Bluetooth', 'harga_satuan' => 15000000, 'harga_total' => 15000000],
            ['id_proyek' => 11, 'nama_barang' => 'Speaker Aktif 15 inch', 'jumlah' => 4, 'satuan' => 'Unit', 'spesifikasi' => 'Speaker aktif 15 inch 500W dengan tweeter horn', 'harga_satuan' => 8500000, 'harga_total' => 34000000],
            ['id_proyek' => 11, 'nama_barang' => 'Microphone Wireless', 'jumlah' => 6, 'satuan' => 'Unit', 'spesifikasi' => 'Microphone wireless handheld dengan receiver diversity', 'harga_satuan' => 2750000, 'harga_total' => 16500000],
            ['id_proyek' => 11, 'nama_barang' => 'Power Amplifier 1000W', 'jumlah' => 2, 'satuan' => 'Unit', 'spesifikasi' => 'Power amplifier 1000W stereo dengan protection circuit', 'harga_satuan' => 9750000, 'harga_total' => 19500000],
            
            // Proyek 12: AC Ruang Operasi - 3 jenis barang (Menunggu)
            ['id_proyek' => 12, 'nama_barang' => 'AC Presisi Medical', 'jumlah' => 3, 'satuan' => 'Unit', 'spesifikasi' => 'AC presisi medical grade dengan HEPA filter dan humidity control', 'harga_satuan' => 125000000, 'harga_total' => 375000000],
            ['id_proyek' => 12, 'nama_barang' => 'Exhaust Fan Medical', 'jumlah' => 6, 'satuan' => 'Unit', 'spesifikasi' => 'Exhaust fan medical grade anti-bacterial dengan speed control', 'harga_satuan' => 15000000, 'harga_total' => 90000000],
            ['id_proyek' => 12, 'nama_barang' => 'Sistem Monitoring Udara', 'jumlah' => 3, 'satuan' => 'Set', 'spesifikasi' => 'Sistem monitoring suhu, kelembaban, dan tekanan udara ruang operasi', 'harga_satuan' => 25000000, 'harga_total' => 75000000],
        ];

        \App\Models\ProyekBarang::insert($proyekBarangData);
        echo "   ✓ Created " . count($proyekBarangData) . " proyek barang\n";
    }

    private function seedPenawaran()
    {
        echo "📋 Seeding penawaran...\n";

        $penawarans = [
            [
                'id_proyek' => 1,
                'no_penawaran' => 'PNW/2024/08/001',
                'tanggal_penawaran' => Carbon::now()->subDays(28),
                'masa_berlaku' => Carbon::now()->addDays(30),
                'surat_pesanan' => 'SP-DIN-DIKDKI-2024-001.pdf',
                'surat_penawaran' => 'SPN-KATANA-2024-001.pdf',
                'total_penawaran' => 442500000.00, // Updated: 375,000,000 + 67,500,000
                'status' => 'ACC',
                'created_at' => Carbon::now()->subDays(28),
                'updated_at' => now()
            ],
            [
                'id_proyek' => 2,
                'no_penawaran' => 'PNW/2024/08/002',
                'tanggal_penawaran' => Carbon::now()->subDays(23),
                'masa_berlaku' => Carbon::now()->addDays(25),
                'surat_pesanan' => 'SP-KT-2024-002.pdf',
                'surat_penawaran' => 'SPN-KATANA-2024-002.pdf',
                'total_penawaran' => 214256250.00, // Updated: 77,625,000 + 38,812,500 + 58,218,750 + 39,600,000
                'status' => 'ACC',
                'created_at' => Carbon::now()->subDays(23),
                'updated_at' => now()
            ],
            [
                'id_proyek' => 3,
                'no_penawaran' => 'PNW/2024/08/003',
                'tanggal_penawaran' => Carbon::now()->subDays(18),
                'masa_berlaku' => Carbon::now()->addDays(30),
                'surat_pesanan' => 'SP-RSU-CP-2024-003.pdf',
                'surat_penawaran' => 'SPN-KATANA-2024-003.pdf',
                'total_penawaran' => 52500000.00, // Diperbaiki: 37.5M + 11.25M + 3.75M
                'status' => 'ACC',
                'created_at' => Carbon::now()->subDays(18),
                'updated_at' => now()
            ],
            [
                'id_proyek' => 4,
                'no_penawaran' => 'PNW/2024/08/004',
                'tanggal_penawaran' => Carbon::now()->subDays(13),
                'masa_berlaku' => Carbon::now()->addDays(35),
                'surat_pesanan' => 'SP-BINUS-2024-004.pdf',
                'surat_penawaran' => 'SPN-KATANA-2024-004.pdf',
                'total_penawaran' => 112500000.00,
                'status' => 'ACC',
                'created_at' => Carbon::now()->subDays(13),
                'updated_at' => now()
            ],
            [
                'id_proyek' => 5,
                'no_penawaran' => 'PNW/2024/08/005',
                'tanggal_penawaran' => Carbon::now()->subDays(8),
                'masa_berlaku' => Carbon::now()->addDays(35),
                'surat_pesanan' => 'SP-PELINDO-2024-005.pdf',
                'surat_penawaran' => 'SPN-KATANA-2024-005.pdf',
                'total_penawaran' => 97500000.00,
                'status' => 'Menunggu',
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => now()
            ],
            [
                'id_proyek' => 6,
                'no_penawaran' => 'PNW/2024/07/006',
                'tanggal_penawaran' => Carbon::now()->subDays(43),
                'masa_berlaku' => Carbon::now()->addDays(20),
                'surat_pesanan' => 'SP-STT-2024-006.pdf',
                'surat_penawaran' => 'SPN-KATANA-2024-006.pdf',
                'total_penawaran' => 101250000.00,
                'status' => 'ACC',
                'created_at' => Carbon::now()->subDays(43),
                'updated_at' => now()
            ]
        ];

        Penawaran::insert($penawarans);

        // Update proyek with id_penawaran
        Proyek::where('id_proyek', 1)->update(['id_penawaran' => 1]);
        Proyek::where('id_proyek', 2)->update(['id_penawaran' => 2]);
        Proyek::where('id_proyek', 3)->update(['id_penawaran' => 3]);
        Proyek::where('id_proyek', 4)->update(['id_penawaran' => 4]);
        Proyek::where('id_proyek', 5)->update(['id_penawaran' => 5]);
        Proyek::where('id_proyek', 6)->update(['id_penawaran' => 6]);

        echo "   ✓ Created " . count($penawarans) . " penawaran\n";
    }

    private function seedPenawaranDetail()
    {
        echo "📝 Seeding penawaran detail...\n";

        $details = [
            // Penawaran 1 - Laptop dan Monitor (Multi vendor: Vendor 1 & 2)
            [
                'id_penawaran' => 1,
                'id_barang' => 1, // Laptop Dell (Vendor 1) - Harga modal: 12,500,000, Margin: 20%
                'nama_barang' => 'Laptop Dell Latitude 5420',
                'spesifikasi' => 'Intel Core i5-1135G7, 8GB RAM, 256GB SSD, 14" FHD, Windows 11',
                'qty' => 25,
                'satuan' => 'Unit',
                'harga_satuan' => 15000000.00, // Harga penawaran (markup 20%)
                'subtotal' => 375000000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 1,
                'id_barang' => 2, // Monitor LG (Vendor 1) - Harga modal: 2,250,000, Margin: 20%
                'nama_barang' => 'Monitor LG 24" IPS',
                'spesifikasi' => '24 inch, IPS Panel, 1920x1080, HDMI, VGA',
                'qty' => 25,
                'satuan' => 'Unit',
                'harga_satuan' => 2250000.00,
                'subtotal' => 56250000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Penawaran 2 - Furniture
            [
                'id_penawaran' => 2,
                'id_barang' => 5, // Meja Kerja
                'nama_barang' => 'Meja Kerja Executive',
                'spesifikasi' => 'Kayu mahoni, ukuran 160x80x75 cm, dengan laci',
                'qty' => 15,
                'satuan' => 'Set',
                'harga_satuan' => 4500000.00,
                'subtotal' => 67500000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Penawaran 2 - Furniture Complete Office Setup (Multi vendor: Vendor 3 & 5)
            [
                'id_penawaran' => 2,
                'id_barang' => 5, // Meja Kerja (Vendor 3) - Harga modal: 4,500,000, Margin: 15%
                'nama_barang' => 'Meja Kerja Executive',
                'spesifikasi' => 'Kayu mahoni, ukuran 160x80x75 cm, dengan laci',
                'qty' => 15,
                'satuan' => 'Set',
                'harga_satuan' => 5175000.00, // Harga penawaran (markup 15%)
                'subtotal' => 77625000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 2,
                'id_barang' => 6, // Kursi Kantor (Vendor 3) - Harga modal: 2,250,000, Margin: 15%
                'nama_barang' => 'Kursi Kantor Ergonomis',
                'spesifikasi' => 'Bahan mesh, adjustable height, lumbar support',
                'qty' => 15,
                'satuan' => 'Unit',
                'harga_satuan' => 2587500.00, // Harga penawaran (markup 15%)
                'subtotal' => 38812500.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 2,
                'id_barang' => 7, // Lemari Arsip (Vendor 3) - Harga modal: 3,375,000, Margin: 15%
                'nama_barang' => 'Lemari Arsip 4 Pintu',
                'spesifikasi' => 'Bahan besi, 4 pintu, ukuran 180x90x40 cm',
                'qty' => 15,
                'satuan' => 'Unit',
                'harga_satuan' => 3881250.00, // Harga penawaran (markup 15%)
                'subtotal' => 58218750.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 2,
                'id_barang' => 11, // AC Split (Vendor 5) - Harga modal: 4,500,000, Margin: 10%
                'nama_barang' => 'AC Split 1 PK',
                'spesifikasi' => '1 PK, R32, inverter, low watt',
                'qty' => 8,
                'satuan' => 'Unit',
                'harga_satuan' => 4950000.00, // Harga penawaran (markup 10%)
                'subtotal' => 39600000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Penawaran 3 - Generator dan Pompa
            [
                'id_penawaran' => 3,
                'id_barang' => 8, // Generator (Vendor 4)
                'nama_barang' => 'Generator Set 5 KVA',
                'spesifikasi' => 'Silent type, 5000 watt, portable, electric start',
                'qty' => 2,
                'satuan' => 'Unit',
                'harga_satuan' => 18750000.00,
                'subtotal' => 37500000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 3,
                'id_barang' => 9, // Pompa Air (Vendor 4)
                'nama_barang' => 'Pompa Air Centrifugal',
                'spesifikasi' => '1 HP, head 35 meter, kapasitas 40 liter/menit',
                'qty' => 2,
                'satuan' => 'Unit',
                'harga_satuan' => 5625000.00,
                'subtotal' => 11250000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Penawaran 4 - Proyektor dan AC
            [
                'id_penawaran' => 4,
                'id_barang' => 10, // Proyektor (Vendor 5)
                'nama_barang' => 'Proyektor Epson EB-S41',
                'spesifikasi' => '3300 lumens, SVGA, HDMI, VGA, USB',
                'qty' => 10,
                'satuan' => 'Unit',
                'harga_satuan' => 6750000.00,
                'subtotal' => 67500000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 4,
                'id_barang' => 11, // AC Split (Vendor 5)
                'nama_barang' => 'AC Split 1 PK',
                'spesifikasi' => '1 PK, R32, inverter, low watt',
                'qty' => 10,
                'satuan' => 'Unit',
                'harga_satuan' => 4500000.00,
                'subtotal' => 45000000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 4,
                'id_barang' => 6, // Kursi Kantor (Vendor 3) - Untuk ruang presentasi
                'nama_barang' => 'Kursi Kantor Ergonomis',
                'spesifikasi' => 'Bahan mesh, adjustable height, lumbar support',
                'qty' => 20,
                'satuan' => 'Unit',
                'harga_satuan' => 2250000.00,
                'subtotal' => 45000000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 4,
                'id_barang' => 1, // Laptop Dell (Vendor 1) - Untuk presentasi
                'nama_barang' => 'Laptop Dell Latitude 5420',
                'spesifikasi' => 'Intel Core i5-1135G7, 8GB RAM, 256GB SSD, 14" FHD, Windows 11',
                'qty' => 5,
                'satuan' => 'Unit',
                'harga_satuan' => 12500000.00,
                'subtotal' => 62500000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Penawaran 5 - Complete IT Office Package (Multi vendor: Vendor 2, 1, 3)
            [
                'id_penawaran' => 5,
                'id_barang' => 3, // Printer HP (Vendor 2)
                'nama_barang' => 'Printer HP LaserJet Pro M404n',
                'spesifikasi' => 'Laser Printer, Monochrome, Network Ready, Auto Duplex',
                'qty' => 20,
                'satuan' => 'Unit',
                'harga_satuan' => 3750000.00,
                'subtotal' => 75000000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 5,
                'id_barang' => 4, // Scanner Canon (Vendor 2)
                'nama_barang' => 'Scanner Canon CanoScan LiDE 300',
                'spesifikasi' => 'Flatbed Scanner, 2400x4800 dpi, USB powered',
                'qty' => 20,
                'satuan' => 'Unit',
                'harga_satuan' => 1125000.00,
                'subtotal' => 22500000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 5,
                'id_barang' => 1, // Laptop Dell (Vendor 1)
                'nama_barang' => 'Laptop Dell Latitude 5420',
                'spesifikasi' => 'Intel Core i5-1135G7, 8GB RAM, 256GB SSD, 14" FHD, Windows 11',
                'qty' => 15,
                'satuan' => 'Unit',
                'harga_satuan' => 12500000.00,
                'subtotal' => 187500000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 5,
                'id_barang' => 2, // Monitor LG (Vendor 1)
                'nama_barang' => 'Monitor LG 24" IPS',
                'spesifikasi' => '24 inch, IPS Panel, 1920x1080, HDMI, VGA',
                'qty' => 15,
                'satuan' => 'Unit',
                'harga_satuan' => 2250000.00,
                'subtotal' => 33750000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 5,
                'id_barang' => 5, // Meja Kerja (Vendor 3)
                'nama_barang' => 'Meja Kerja Executive',
                'spesifikasi' => 'Kayu mahoni, ukuran 160x80x75 cm, dengan laci',
                'qty' => 15,
                'satuan' => 'Set',
                'harga_satuan' => 4500000.00,
                'subtotal' => 67500000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 5,
                'id_barang' => 6, // Kursi Kantor (Vendor 3)
                'nama_barang' => 'Kursi Kantor Ergonomis',
                'spesifikasi' => 'Bahan mesh, adjustable height, lumbar support',
                'qty' => 15,
                'satuan' => 'Unit',
                'harga_satuan' => 2250000.00,
                'subtotal' => 33750000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // Penawaran 6 - Infrastructure Package (Multi vendor: Vendor 4, 5, 2)
            [
                'id_penawaran' => 6,
                'id_barang' => 8, // Generator (Vendor 4)
                'nama_barang' => 'Generator Set 5 KVA',
                'spesifikasi' => 'Silent type, 5000 watt, portable, electric start',
                'qty' => 1,
                'satuan' => 'Unit',
                'harga_satuan' => 18750000.00,
                'subtotal' => 18750000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 6,
                'id_barang' => 9, // Pompa Air (Vendor 4)
                'nama_barang' => 'Pompa Air Centrifugal',
                'spesifikasi' => '1 HP, head 35 meter, kapasitas 40 liter/menit',
                'qty' => 3,
                'satuan' => 'Unit',
                'harga_satuan' => 5625000.00,
                'subtotal' => 16875000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 6,
                'id_barang' => 11, // AC Split (Vendor 5)
                'nama_barang' => 'AC Split 1 PK',
                'spesifikasi' => '1 PK, R32, inverter, low watt',
                'qty' => 5,
                'satuan' => 'Unit',
                'harga_satuan' => 4500000.00,
                'subtotal' => 22500000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 6,
                'id_barang' => 10, // Proyektor (Vendor 5)
                'nama_barang' => 'Proyektor Epson EB-S41',
                'spesifikasi' => '3300 lumens, SVGA, HDMI, VGA, USB',
                'qty' => 2,
                'satuan' => 'Unit',
                'harga_satuan' => 6750000.00,
                'subtotal' => 13500000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 6,
                'id_barang' => 3, // Printer HP (Vendor 2)
                'nama_barang' => 'Printer HP LaserJet Pro M404n',
                'spesifikasi' => 'Laser Printer, Monochrome, Network Ready, Auto Duplex',
                'qty' => 2,
                'satuan' => 'Unit',
                'harga_satuan' => 3750000.00,
                'subtotal' => 7500000.00,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        PenawaranDetail::insert($details);
        echo "   ✓ Created " . count($details) . " penawaran detail items\n";
        echo "   ✓ Multi-vendor penawaran created successfully!\n";
    }

    private function seedPembayaran()
    {
        echo "💰 Seeding pembayaran...\n";

        $pembayarans = [
            // Pembayaran untuk proyek 1 (Selesai) - DP dan Pelunasan per vendor (Approved)
            // Vendor 1 (PT Teknologi Canggih Indonesia) - Laptop & Monitor
            [
                'id_penawaran' => 1,
                'id_vendor' => 1,
                'jenis_bayar' => 'DP',
                'nominal_bayar' => 184375000.00, // 50% dari total vendor 1 (312,500,000 + 56,250,000)
                'tanggal_bayar' => Carbon::now()->subDays(25),
                'metode_bayar' => 'Transfer Bank',
                'bukti_bayar' => 'bukti_dp_vendor1_proyek1.pdf',
                'catatan' => 'DP 50% untuk laptop dan monitor dari PT Teknologi Canggih Indonesia',
                'status_verifikasi' => 'Approved',
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 1,
                'id_vendor' => 1,
                'jenis_bayar' => 'Pelunasan',
                'nominal_bayar' => 184375000.00, // 50% Pelunasan vendor 1
                'tanggal_bayar' => Carbon::now()->subDays(10),
                'metode_bayar' => 'Transfer Bank',
                'bukti_bayar' => 'bukti_pelunasan_vendor1_proyek1.pdf',
                'catatan' => 'Pelunasan untuk laptop dan monitor dari PT Teknologi Canggih Indonesia',
                'status_verifikasi' => 'Approved',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => now()
            ],

            // Pembayaran untuk proyek 2 (Pengiriman) - DP saja (Approved)
            [
                'id_penawaran' => 2,
                'id_vendor' => 3,
                'jenis_bayar' => 'DP',
                'nominal_bayar' => 75937500.00, // 50% dari total vendor 3 (67,500,000 + 33,750,000 + 50,625,000)
                'tanggal_bayar' => Carbon::now()->subDays(20),
                'metode_bayar' => 'Transfer Bank',
                'bukti_bayar' => 'bukti_dp_vendor3_proyek2.pdf',
                'catatan' => 'DP 50% untuk furniture dari PT Furniture Nusantara',
                'status_verifikasi' => 'Approved',
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => now()
            ],

            // Pembayaran untuk proyek 3 (Pembayaran) - DP sudah Approved, ready untuk kirim
            [
                'id_penawaran' => 3,
                'id_vendor' => 4,
                'jenis_bayar' => 'DP',
                'nominal_bayar' => 15000000.00, // DP 30% saja (dari total modal ~48,750,000)
                'tanggal_bayar' => Carbon::now()->subDays(15),
                'metode_bayar' => 'Transfer Bank',
                'bukti_bayar' => 'bukti_dp_vendor4_proyek3.pdf',
                'catatan' => 'DP 30% untuk generator dan pompa dari UD Mesin Industri Jaya',
                'status_verifikasi' => 'Approved',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => now()
            ],

            // Pembayaran untuk proyek 4 (Pembayaran) - masih Pending
            [
                'id_penawaran' => 4,
                'id_vendor' => 5,
                'jenis_bayar' => 'DP',
                'nominal_bayar' => 30000000.00, // DP kecil saja (dari total modal ~112,500,000)
                'tanggal_bayar' => Carbon::now()->subDays(12),
                'metode_bayar' => 'Transfer Bank',
                'bukti_bayar' => 'bukti_dp_vendor5_proyek4.pdf',
                'catatan' => 'DP 50% untuk proyektor dan AC dari PT Alat Kantor Prima',
                'status_verifikasi' => 'Pending',
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => now()
            ],
            // Vendor 3 (PT Furniture Nusantara) - Kursi (Approved)
            [
                'id_penawaran' => 4,
                'id_vendor' => 3,
                'jenis_bayar' => 'Lunas',
                'nominal_bayar' => 45000000.00, // Full payment vendor 3
                'tanggal_bayar' => Carbon::now()->subDays(10),
                'metode_bayar' => 'Transfer Bank',
                'bukti_bayar' => 'bukti_lunas_vendor3_proyek4.pdf',
                'catatan' => 'Pembayaran lunas untuk kursi kantor dari PT Furniture Nusantara',
                'status_verifikasi' => 'Approved',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => now()
            ],
            // Vendor 1 (PT Teknologi Canggih Indonesia) - Laptop (Ditolak & Re-submit Pending)
            [
                'id_penawaran' => 4,
                'id_vendor' => 1,
                'jenis_bayar' => 'DP',
                'nominal_bayar' => 31250000.00, // 50% dari total vendor 1 (62,500,000)
                'tanggal_bayar' => Carbon::now()->subDays(14),
                'metode_bayar' => 'Transfer Bank',
                'bukti_bayar' => 'bukti_dp_vendor1_proyek4_ditolak.pdf',
                'catatan' => 'DP 50% untuk laptop (bukti transfer tidak jelas)',
                'status_verifikasi' => 'Ditolak',
                'created_at' => Carbon::now()->subDays(14),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 4,
                'id_vendor' => 1,
                'jenis_bayar' => 'DP',
                'nominal_bayar' => 31250000.00, // 50% dari total vendor 1 (re-submit)
                'tanggal_bayar' => Carbon::now()->subDays(8),
                'metode_bayar' => 'Transfer Bank',
                'bukti_bayar' => 'bukti_dp_vendor1_proyek4_revisi.pdf',
                'catatan' => 'DP 50% untuk laptop (revisi bukti transfer yang jelas)',
                'status_verifikasi' => 'Pending',
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => now()
            ],

            // Pembayaran untuk proyek 6 (Gagal) - DP sudah dibayar tapi proyek gagal
            [
                'id_penawaran' => 5,
                'id_vendor' => 2,
                'jenis_bayar' => 'DP',
                'nominal_bayar' => 48750000.00, // 50% dari total vendor 2 (97,500,000)
                'tanggal_bayar' => Carbon::now()->subDays(6),
                'metode_bayar' => 'Transfer Bank',
                'bukti_bayar' => 'bukti_dp_vendor2_proyek5.pdf',
                'catatan' => 'DP 50% untuk printer dan scanner dari CV Elektronik Makmur',
                'status_verifikasi' => 'Approved',
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => now()
            ]
        ];

        Pembayaran::insert($pembayarans);
        echo "   ✓ Created " . count($pembayarans) . " pembayaran per vendor\n";
        echo "   ✓ Multi-vendor payment system implemented!\n";
    }

    private function seedPengiriman()
    {
        echo "🚚 Seeding pengiriman...\n";

        // Ambil vendor dari penawaran detail untuk setiap proyek
        $proyek1Vendor = DB::table('penawaran_detail')
            ->join('barang', 'penawaran_detail.id_barang', '=', 'barang.id_barang')
            ->where('penawaran_detail.id_penawaran', 1)
            ->distinct()
            ->pluck('barang.id_vendor')
            ->first(); // Ambil vendor pertama untuk simplicity

        $proyek2Vendor = DB::table('penawaran_detail')
            ->join('barang', 'penawaran_detail.id_barang', '=', 'barang.id_barang')
            ->where('penawaran_detail.id_penawaran', 2)
            ->distinct()
            ->pluck('barang.id_vendor')
            ->first();

        $proyek3Vendor = DB::table('penawaran_detail')
            ->join('barang', 'penawaran_detail.id_barang', '=', 'barang.id_barang')
            ->where('penawaran_detail.id_penawaran', 3)
            ->distinct()
            ->pluck('barang.id_vendor')
            ->first();

        $proyek6Vendor = DB::table('penawaran_detail')
            ->join('barang', 'penawaran_detail.id_barang', '=', 'barang.id_barang')
            ->where('penawaran_detail.id_penawaran', 6)
            ->distinct()
            ->pluck('barang.id_vendor')
            ->first();

        $pengirimans = [
            // Pengiriman untuk proyek 1 (Selesai) - sudah diverifikasi oleh superadmin
            [
                'id_penawaran' => 1,
                'id_vendor' => $proyek1Vendor ?? 1, // Fallback ke vendor 1 jika tidak ada
                'no_surat_jalan' => 'SJ/KATANA/2024/001',
                'file_surat_jalan' => 'surat_jalan_001.pdf',
                'tanggal_kirim' => Carbon::now()->subDays(15),
                'alamat_kirim' => 'SMA Negeri 1 Jakarta, Jl. Budi Kemuliaan I No. 2, Jakarta Pusat',
                'foto_berangkat' => 'pengiriman_001_berangkat.jpg',
                'foto_perjalanan' => 'pengiriman_001_perjalanan.jpg',
                'foto_sampai' => 'pengiriman_001_sampai.jpg',
                'tanda_terima' => 'tanda_terima_001.pdf',
                'status_verifikasi' => 'Verified',
                'catatan_verifikasi' => 'Pengiriman telah sampai dengan baik dan dokumentasi lengkap. Diverifikasi oleh Superadmin.',
                'verified_by' => 1, // Superadmin
                'verified_at' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(5)
            ],

            // Pengiriman untuk proyek 2 (Sudah sampai, menunggu verifikasi)
            [
                'id_penawaran' => 2,
                'id_vendor' => $proyek2Vendor ?? 2,
                'no_surat_jalan' => 'SJ/KATANA/2024/002',
                'file_surat_jalan' => 'surat_jalan_002.pdf',
                'tanggal_kirim' => Carbon::now()->subDays(3),
                'alamat_kirim' => 'PT Kreatif Teknologi, Jl. Sudirman No. 456, Jakarta Selatan',
                'foto_berangkat' => 'pengiriman_002_berangkat.jpg',
                'foto_perjalanan' => 'pengiriman_002_perjalanan.jpg',
                'foto_sampai' => 'pengiriman_002_sampai.jpg',
                'tanda_terima' => 'tanda_terima_002.jpg',
                'status_verifikasi' => 'Sampai_Tujuan',
                'catatan_verifikasi' => null,
                'verified_by' => null,
                'verified_at' => null,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(1)
            ],

            // Pengiriman untuk proyek 3 (Baru sampai, menunggu verifikasi)
            [
                'id_penawaran' => 3,
                'id_vendor' => $proyek3Vendor ?? 3,
                'no_surat_jalan' => 'SJ/KATANA/2024/003',
                'file_surat_jalan' => 'surat_jalan_003.pdf',
                'tanggal_kirim' => Carbon::now()->subDays(2),
                'alamat_kirim' => 'CV Maju Bersama, Jl. Raya Bandung No. 789, Bandung',
                'foto_berangkat' => 'pengiriman_003_berangkat.jpg',
                'foto_perjalanan' => 'pengiriman_003_perjalanan.jpg',
                'foto_sampai' => 'pengiriman_003_sampai.jpg',
                'tanda_terima' => 'tanda_terima_003.jpg',
                'status_verifikasi' => 'Sampai_Tujuan',
                'catatan_verifikasi' => null,
                'verified_by' => null,
                'verified_at' => null,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()
            ],

            // Pengiriman untuk proyek 6 (Gagal) - barang tidak sesuai spesifikasi
            [
                'id_penawaran' => 6,
                'id_vendor' => $proyek6Vendor ?? 1,
                'no_surat_jalan' => 'SJ/KATANA/2024/006',
                'file_surat_jalan' => 'surat_jalan_006.pdf',
                'tanggal_kirim' => Carbon::now()->subDays(35),
                'alamat_kirim' => 'Sekolah Tinggi Teknologi Depok, Jl. Raya Depok No. 123, Depok',
                'foto_berangkat' => 'pengiriman_006_berangkat.jpg',
                'foto_perjalanan' => 'pengiriman_006_perjalanan.jpg',
                'foto_sampai' => 'pengiriman_006_sampai.jpg',
                'tanda_terima' => null, // Tidak ada tanda terima karena ditolak klien
                'status_verifikasi' => 'Gagal',
                'catatan_verifikasi' => 'Barang ditolak klien karena tidak sesuai spesifikasi. Kualitas kayu tidak solid seperti yang diminta dan kursi bukan kulit asli. Proyek dinyatakan gagal.',
                'verified_by' => 1, // Superadmin
                'verified_at' => Carbon::now()->subDays(30),
                'created_at' => Carbon::now()->subDays(35),
                'updated_at' => Carbon::now()->subDays(30)
            ]
        ];

        Pengiriman::insert($pengirimans);
        echo "   ✓ Created " . count($pengirimans) . " pengiriman per vendor\n";
        echo "   ✓ Multi-vendor shipping system implemented!\n";
    }

    private function seedPenagihanDinas()
    {
        echo "💳 Seeding penagihan dinas...\n";

        $penagihanDinas = [
            // Penagihan untuk proyek 1 (Selesai) - Pembayaran Lunas
            [
                'proyek_id' => 1,
                'penawaran_id' => 1,
                'nomor_invoice' => 'INV/KATANA/2024/001',
                'total_harga' => 431250000.00, // Total penawaran detail
                'status_pembayaran' => 'lunas',
                'persentase_dp' => 50.00,
                'jumlah_dp' => 215625000.00, // 50% dari total
                'tanggal_jatuh_tempo' => Carbon::now()->subDays(20),
                'berita_acara_serah_terima' => 'bast_001.pdf',
                'invoice' => 'invoice_001.pdf',
                'pnbp' => 'pnbp_001.pdf',
                'faktur_pajak' => 'faktur_pajak_001.pdf',
                'surat_lainnya' => 'surat_tambahan_001.pdf',
                'keterangan' => 'Pembayaran untuk pengadaan laptop dan monitor lab komputer SMA Negeri 1 Jakarta',
                'created_at' => Carbon::now()->subDays(28),
                'updated_at' => now()
            ],

            // Penagihan untuk proyek 2 (Pengiriman) - Status DP
            [
                'proyek_id' => 2,
                'penawaran_id' => 2,
                'nomor_invoice' => 'INV/KATANA/2024/002',
                'total_harga' => 214256250.00,
                'status_pembayaran' => 'dp',
                'persentase_dp' => 40.00,
                'jumlah_dp' => 85702500.00, // 40% dari total
                'tanggal_jatuh_tempo' => Carbon::now()->addDays(15),
                'berita_acara_serah_terima' => 'bast_002.pdf',
                'invoice' => 'invoice_002.pdf',
                'pnbp' => null,
                'faktur_pajak' => 'faktur_pajak_002.pdf',
                'surat_lainnya' => null,
                'keterangan' => 'Pembayaran untuk pengadaan furniture kantor PT Kreatif Teknologi',
                'created_at' => Carbon::now()->subDays(23),
                'updated_at' => now()
            ],

            // Penagihan untuk proyek 3 (Pengiriman) - Status Lunas (baru saja lunas)
            [
                'proyek_id' => 3,
                'penawaran_id' => 3,
                'nomor_invoice' => 'INV/KATANA/2024/003',
                'total_harga' => 48750000.00, // Sesuai dengan data asli proyek
                'status_pembayaran' => 'lunas',
                'persentase_dp' => 30.00,
                'jumlah_dp' => 14625000.00, // 30% dari total
                'tanggal_jatuh_tempo' => Carbon::now()->addDays(10),
                'berita_acara_serah_terima' => 'bast_003.pdf',
                'invoice' => 'invoice_003.pdf',
                'pnbp' => 'pnbp_003.pdf',
                'faktur_pajak' => null,
                'surat_lainnya' => 'kontrak_003.pdf',
                'keterangan' => 'Pembayaran untuk generator set dan pompa air CV Maju Bersama',
                'created_at' => Carbon::now()->subDays(18),
                'updated_at' => now()
            ]
        ];

        PenagihanDinas::insert($penagihanDinas);
        echo "   ✓ Created " . count($penagihanDinas) . " penagihan dinas\n";
    }

    private function seedBuktiPembayaran()
    {
        echo "📄 Seeding bukti pembayaran...\n";

        $buktiPembayarans = [
            // Bukti pembayaran untuk penagihan dinas 1 (Proyek 1 - Selesai)
            // DP
            [
                'penagihan_dinas_id' => 1,
                'jenis_pembayaran' => 'dp',
                'jumlah_bayar' => 215625000.00,
                'tanggal_bayar' => Carbon::now()->subDays(25),
                'bukti_pembayaran' => 'bukti_dp_penagihan_001.pdf',
                'keterangan' => 'Pembayaran DP 50% untuk pengadaan laptop dan monitor',
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => now()
            ],
            // Pelunasan
            [
                'penagihan_dinas_id' => 1,
                'jenis_pembayaran' => 'lunas',
                'jumlah_bayar' => 215625000.00,
                'tanggal_bayar' => Carbon::now()->subDays(8),
                'bukti_pembayaran' => 'bukti_pelunasan_penagihan_001.pdf',
                'keterangan' => 'Pelunasan pembayaran untuk pengadaan laptop dan monitor',
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => now()
            ],

            // Bukti pembayaran untuk penagihan dinas 2 (Proyek 2 - DP saja)
            [
                'penagihan_dinas_id' => 2,
                'jenis_pembayaran' => 'dp',
                'jumlah_bayar' => 85702500.00,
                'tanggal_bayar' => Carbon::now()->subDays(20),
                'bukti_pembayaran' => 'bukti_dp_penagihan_002.pdf',
                'keterangan' => 'Pembayaran DP 40% untuk pengadaan furniture kantor',
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => now()
            ],

            // Bukti pembayaran untuk penagihan dinas 3 (Proyek 3 - Baru saja lunas)
            // DP
            [
                'penagihan_dinas_id' => 3,
                'jenis_pembayaran' => 'dp',
                'jumlah_bayar' => 14625000.00,
                'tanggal_bayar' => Carbon::now()->subDays(15),
                'bukti_pembayaran' => 'bukti_dp_penagihan_003.pdf',
                'keterangan' => 'Pembayaran DP 30% untuk generator dan pompa air',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => now()
            ],
            // Pelunasan (baru saja lunas kemarin)
            [
                'penagihan_dinas_id' => 3,
                'jenis_pembayaran' => 'lunas',
                'jumlah_bayar' => 34125000.00, // Sisa pembayaran (48,750,000 - 14,625,000)
                'tanggal_bayar' => Carbon::now()->subDays(1),
                'bukti_pembayaran' => 'bukti_pelunasan_penagihan_003.pdf',
                'keterangan' => 'Pelunasan pembayaran untuk generator dan pompa air',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => now()
            ]
        ];

        BuktiPembayaran::insert($buktiPembayarans);
        echo "   ✓ Created " . count($buktiPembayarans) . " bukti pembayaran\n";
        echo "   ✓ Penagihan dinas system seeded successfully!\n";
    }
}
