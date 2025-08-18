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

class CompleteSystemSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate tables in reverse dependency order
        $tables = [
            'pengiriman',
            'pembayaran', 
            'penawaran_detail',
            'penawaran',
            'proyek',
            'barang',
            'vendor',
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
        $this->seedVendors();
        $this->seedBarang();
        $this->seedProyek();
        $this->seedPenawaran();
        $this->seedPenawaranDetail();
        $this->seedPembayaran();
        $this->seedPengiriman();

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

    private function seedProyek()
    {
        echo "🚀 Seeding proyek...\n";
        
        $proyeks = [
            // Proyek 1: Status "Selesai" - sudah selesai pengiriman
            [
                'tanggal' => Carbon::now()->subDays(30),
                'kota_kab' => 'Jakarta Pusat',
                'instansi' => 'Dinas Pendidikan DKI Jakarta',
                'nama_klien' => 'Budi Santoso',
                'kontak_klien' => '0812-3456-7890',
                'nama_barang' => 'Laptop dan Monitor untuk Lab Komputer',
                'jumlah' => 25,
                'satuan' => 'Set',
                'spesifikasi' => 'Laptop Dell Core i5 + Monitor LG 24 inch untuk lab komputer sekolah',
                'harga_satuan' => 14750000.00,
                'harga_total' => 368750000.00,
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
                'kota_kab' => 'Jakarta Selatan',
                'instansi' => 'PT Kreatif Teknologi',
                'nama_klien' => 'Sari Dewi',
                'kontak_klien' => '0813-9876-5432',
                'nama_barang' => 'Furniture Kantor Complete Set',
                'jumlah' => 15,
                'satuan' => 'Set',
                'spesifikasi' => 'Meja kerja executive, kursi ergonomis, lemari arsip untuk kantor baru',
                'harga_satuan' => 10125000.00,
                'harga_total' => 151875000.00,
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
                'kota_kab' => 'Bandung',
                'instansi' => 'CV Maju Bersama',
                'nama_klien' => 'Budi Santoso',
                'kontak_klien' => '0814-5678-9012',
                'nama_barang' => 'Generator Set dan Pompa Air Backup',
                'jumlah' => 2,
                'satuan' => 'Unit',
                'spesifikasi' => 'Generator 5 KVA + Pompa air centrifugal untuk backup power dan water supply',
                'harga_satuan' => 24375000.00,
                'harga_total' => 48750000.00,
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
                'kota_kab' => 'Jakarta Barat',
                'instansi' => 'Universitas Bina Nusantara',
                'nama_klien' => 'Prof. Maria Susanti',
                'kontak_klien' => '0815-2468-1357',
                'nama_barang' => 'Proyektor dan AC untuk Ruang Kuliah',
                'jumlah' => 10,
                'satuan' => 'Set',
                'spesifikasi' => 'Proyektor Epson 3300 lumens + AC split 1 PK untuk ruang kuliah baru',
                'harga_satuan' => 11250000.00,
                'harga_total' => 112500000.00,
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
                'kota_kab' => 'Jakarta Utara',
                'instansi' => 'PT Pelindo II',
                'nama_klien' => 'Ir. Bambang Wijaya',
                'kontak_klien' => '0816-1357-2468',
                'nama_barang' => 'Printer dan Scanner untuk Administrasi',
                'jumlah' => 20,
                'satuan' => 'Set',
                'spesifikasi' => 'Printer HP LaserJet + Scanner Canon untuk keperluan administrasi pelabuhan',
                'harga_satuan' => 4875000.00,
                'harga_total' => 97500000.00,
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
                'kota_kab' => 'Depok',
                'instansi' => 'Sekolah Tinggi Teknologi Depok',
                'nama_klien' => 'Dr. Agus Pramono',
                'kontak_klien' => '0817-8888-9999',
                'nama_barang' => 'Meja dan Kursi Kantor untuk Ruang Dosen',
                'jumlah' => 12,
                'satuan' => 'Set',
                'spesifikasi' => 'Meja executive kayu solid + Kursi direktur kulit untuk ruang dosen',
                'harga_satuan' => 8437500.00,
                'harga_total' => 101250000.00,
                'jenis_pengadaan' => 'Tender Terbatas',
                'deadline' => Carbon::now()->addDays(15),
                'id_admin_marketing' => 3,
                'id_admin_purchasing' => 5,
                'id_penawaran' => null,
                'catatan' => 'Proyek ini gagal karena barang tidak sesuai spesifikasi saat verifikasi',
                'status' => 'Gagal',
                'created_at' => Carbon::now()->subDays(45),
                'updated_at' => now()
            ]
        ];

        Proyek::insert($proyeks);
        echo "   ✓ Created " . count($proyeks) . " proyek\n";
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
                'status' => 'Dikirim',
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
                'harga_satuan' => 2700000.00, // Harga penawaran (markup 20%)
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
                'harga_satuan' => 4950000.00, // Harga penawaran (markup 10%)
                'subtotal' => 39600000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Penawaran 3 - Generator dan Pompa (Multi vendor: Vendor 4 & 2)
            [
                'id_penawaran' => 3,
                'id_barang' => 8, // Generator (Vendor 4)
                'nama_barang' => 'Generator Set 5 KVA',
                'spesifikasi' => 'Silent type, 5000 watt, portable, electric start',
                'qty' => 2,
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
                'harga_satuan' => 5625000.00,
                'subtotal' => 11250000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_penawaran' => 3,
                'id_barang' => 3, // Printer HP (Vendor 2) - Tambahan untuk kantor lapangan
                'nama_barang' => 'Printer HP LaserJet Pro M404n',
                'spesifikasi' => 'Laser Printer, Monochrome, Network Ready, Auto Duplex',
                'qty' => 1,
                'harga_satuan' => 3750000.00,
                'subtotal' => 3750000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Penawaran 4 - Complete Presentation Setup (Multi vendor: Vendor 5, 3, 1)
            [
                'id_penawaran' => 4,
                'id_barang' => 10, // Proyektor (Vendor 5)
                'nama_barang' => 'Proyektor Epson EB-S41',
                'spesifikasi' => '3300 lumens, SVGA, HDMI, VGA, USB',
                'qty' => 10,
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

            // Pembayaran untuk proyek 2 (Pengiriman) - Multi vendor
            // Vendor 3 (PT Furniture Nusantara) - Furniture
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
            // Vendor 5 (PT Alat Kantor Prima) - AC Split
            [
                'id_penawaran' => 2,
                'id_vendor' => 5,
                'jenis_bayar' => 'DP',
                'nominal_bayar' => 10000000.00, // DP kecil saja (dari total modal ~36,000,000)
                'tanggal_bayar' => Carbon::now()->subDays(18),
                'metode_bayar' => 'Transfer Bank',
                'bukti_bayar' => 'bukti_dp_vendor5_proyek2.pdf',
                'catatan' => 'DP 25% untuk AC Split dari PT Alat Kantor Prima',
                'status_verifikasi' => 'Approved',
                'created_at' => Carbon::now()->subDays(18),
                'updated_at' => now()
            ],

            // Pembayaran untuk proyek 3 (Pembayaran) - Multi vendor, ada yang partial payment
            // Vendor 4 (UD Mesin Industri Jaya) - Generator & Pompa
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
            // Vendor 2 (CV Elektronik Makmur) - Printer (Belum bayar sama sekali)
            // Tidak ada pembayaran untuk vendor 2 di proyek ini (untuk contoh vendor belum bayar)

            // Pembayaran untuk proyek 4 (Pembayaran) - Multi vendor, mixed status
            // Vendor 5 (PT Alat Kantor Prima) - Proyektor & AC (Pending)
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

            // Pembayaran untuk proyek 5 (Draft) - Partial payment dari beberapa vendor
            // Vendor 2 (CV Elektronik Makmur) - Printer & Scanner (Approved)
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
        
        $pengirimans = [
            // Pengiriman untuk proyek 1 (Selesai) - sudah diverifikasi oleh superadmin
            [
                'id_penawaran' => 1,
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
        echo "   ✓ Created " . count($pengirimans) . " pengiriman\n";
    }
}
