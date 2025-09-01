<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wilayah;

class WilayahWithPejabatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data sample wilayah dengan multiple instansi
        $wilayahData = [
            // Jakarta - Multiple Instansi
            [
                'nama_wilayah' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
                'kode_wilayah' => 'JKT-PST-01',
                'instansi' => 'Dinas Pendidikan DKI Jakarta',
                'nama_pejabat' => 'Dr. Budi Santoso, M.Pd',
                'jabatan' => 'Kepala Dinas',
                'no_telp' => '021-3456-7890',
                'email' => 'budi.santoso@jakarta.go.id',
                'alamat' => 'Jl. Jenderal Gatot Subroto Kav. 40-41, Jakarta Selatan',
                'deskripsi' => 'Wilayah Jakarta Pusat dengan berbagai instansi pemerintahan',
                'is_active' => true
            ],
            [
                'nama_wilayah' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
                'kode_wilayah' => 'JKT-PST-02',
                'instansi' => 'RSUD Tarakan',
                'nama_pejabat' => 'dr. Sari Dewi, Sp.M',
                'jabatan' => 'Direktur RSUD',
                'no_telp' => '021-4567-8901',
                'email' => 'sari.dewi@rsudtarakan.go.id',
                'alamat' => 'Jl. Kyai Caringin No. 7, Jakarta Pusat',
                'deskripsi' => 'Rumah Sakit Umum Daerah Tarakan',
                'is_active' => true
            ],
            [
                'nama_wilayah' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
                'kode_wilayah' => 'JKT-PST-03',
                'instansi' => 'Badan Perencanaan Pembangunan Daerah',
                'nama_pejabat' => 'Ir. Ahmad Fauzi, M.T',
                'jabatan' => 'Kepala Badan',
                'no_telp' => '021-5678-9012',
                'email' => 'ahmad.fauzi@bappeda.jakarta.go.id',
                'alamat' => 'Jl. Kebon Sirih No. 34, Jakarta Pusat',
                'deskripsi' => 'Badan Perencanaan Pembangunan Daerah DKI Jakarta',
                'is_active' => true
            ],

            // Bogor - Multiple Instansi
            [
                'nama_wilayah' => 'Bogor',
                'provinsi' => 'Jawa Barat',
                'kode_wilayah' => 'BGR-01',
                'instansi' => 'Dinas Kesehatan Kota Bogor',
                'nama_pejabat' => 'dr. Maria Sari, M.Kes',
                'jabatan' => 'Kepala Dinas',
                'no_telp' => '0251-2345-6789',
                'email' => 'maria.sari@dinkes.kotabogor.go.id',
                'alamat' => 'Jl. Ir. H. Juanda No. 10, Bogor',
                'deskripsi' => 'Dinas Kesehatan Kota Bogor',
                'is_active' => true
            ],
            [
                'nama_wilayah' => 'Bogor',
                'provinsi' => 'Jawa Barat',
                'kode_wilayah' => 'BGR-02',
                'instansi' => 'Universitas Pakuan',
                'nama_pejabat' => 'Prof. Dr. Rudi Hartono, M.Sc',
                'jabatan' => 'Rektor',
                'no_telp' => '0251-3456-7890',
                'email' => 'rektor@unpak.ac.id',
                'alamat' => 'Jl. Pakuan No. 1, Bogor',
                'deskripsi' => 'Universitas Pakuan Bogor',
                'is_active' => true
            ],

            // Depok - Multiple Instansi
            [
                'nama_wilayah' => 'Depok',
                'provinsi' => 'Jawa Barat',
                'kode_wilayah' => 'DPK-01',
                'instansi' => 'Dinas Perhubungan Kota Depok',
                'nama_pejabat' => 'Andi Wijaya, S.T',
                'jabatan' => 'Kepala Dinas',
                'no_telp' => '021-7654-3210',
                'email' => 'andi.wijaya@dishub.depok.go.id',
                'alamat' => 'Jl. Margonda Raya No. 54, Depok',
                'deskripsi' => 'Dinas Perhubungan Kota Depok',
                'is_active' => true
            ],
            [
                'nama_wilayah' => 'Depok',
                'provinsi' => 'Jawa Barat',
                'kode_wilayah' => 'DPK-02',
                'instansi' => 'RSUD Kota Depok',
                'nama_pejabat' => 'dr. Lisa Permata, Sp.A',
                'jabatan' => 'Direktur RSUD',
                'no_telp' => '021-8765-4321',
                'email' => 'lisa.permata@rsud.depok.go.id',
                'alamat' => 'Jl. Pemuda Raya No. 20, Depok',
                'deskripsi' => 'Rumah Sakit Umum Daerah Kota Depok',
                'is_active' => true
            ],

            // Tangerang - Single Instansi
            [
                'nama_wilayah' => 'Tangerang',
                'provinsi' => 'Banten',
                'kode_wilayah' => 'TNG-01',
                'instansi' => 'Dinas Pendidikan Kota Tangerang',
                'nama_pejabat' => 'Drs. Agus Setiawan, M.Pd',
                'jabatan' => 'Kepala Dinas',
                'no_telp' => '021-5555-1234',
                'email' => 'agus.setiawan@disdik.tangerang.go.id',
                'alamat' => 'Jl. Satria Sudirman No. 1, Tangerang',
                'deskripsi' => 'Dinas Pendidikan Kota Tangerang',
                'is_active' => true
            ],

            // Bekasi - Single Instansi
            [
                'nama_wilayah' => 'Bekasi',
                'provinsi' => 'Jawa Barat',
                'kode_wilayah' => 'BKS-01',
                'instansi' => 'Badan Kesatuan Bangsa dan Politik',
                'nama_pejabat' => 'H. Bambang Supriyanto, S.Sos',
                'jabatan' => 'Kepala Badan',
                'no_telp' => '021-6666-5678',
                'email' => 'bambang.supriyanto@kesbangpol.bekasi.go.id',
                'alamat' => 'Jl. Ahmad Yani No. 1, Bekasi',
                'deskripsi' => 'Badan Kesatuan Bangsa dan Politik Kota Bekasi',
                'is_active' => true
            ]
        ];

        // Insert data
        foreach ($wilayahData as $data) {
            Wilayah::create($data);
        }
    }
}
