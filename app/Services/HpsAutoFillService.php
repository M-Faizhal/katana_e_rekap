<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class HpsAutoFillService
{
    /**
     * Auto-fill kalkulasi data dengan data permintaan klien berdasarkan urutan
     */
    public static function autoFillFromClientRequests($kalkulasiData, $proyek)
    {
        return $kalkulasiData->map(function($item, $index) use ($proyek) {
            $clientRequest = $proyek->proyekBarang[$index] ?? null;
            
            if ($clientRequest) {
                // Auto-fill qty jika masih default atau kosong
                if (!$item['qty'] || $item['qty'] == 1) {
                    $item['qty'] = $clientRequest->jumlah;
                }
                
                // Auto-fill harga_pagu_dinas_per_pcs jika masih kosong
                if (!isset($item['harga_pagu_dinas_per_pcs']) || $item['harga_pagu_dinas_per_pcs'] == 0) {
                    $item['harga_pagu_dinas_per_pcs'] = $clientRequest->harga_satuan;
                }
                
                // Auto-fill satuan jika masih default
                if (!$item['satuan'] || $item['satuan'] == 'pcs' || $item['satuan'] == 'Unit') {
                    $item['satuan'] = $clientRequest->satuan;
                }
                
                // Log auto-fill untuk debugging
                Log::info("Auto-filled item {$index}:", [
                    'qty' => $item['qty'],
                    'harga_pagu_dinas_per_pcs' => $item['harga_pagu_dinas_per_pcs'],
                    'satuan' => $item['satuan'],
                    'from_client' => [
                        'nama_barang' => $clientRequest->nama_barang,
                        'jumlah' => $clientRequest->jumlah,
                        'satuan' => $clientRequest->satuan,
                        'harga_satuan' => $clientRequest->harga_satuan
                    ]
                ]);
            }
            
            return $item;
        });
    }
    
    /**
     * Prepare new item dengan auto-fill dari client request berdasarkan index
     */
    public static function prepareNewItemWithClientData($index, $clientRequests)
    {
        $matchingClientRequest = $clientRequests[$index] ?? null;
        
        $newItem = [
            'id' => time() + $index,
            'id_barang' => '',
            'nama_barang' => '',
            'id_vendor' => '',
            'nama_vendor' => '',
            'jenis_vendor' => '',
            'satuan' => $matchingClientRequest ? $matchingClientRequest->satuan : 'Unit',
            'qty' => $matchingClientRequest ? $matchingClientRequest->jumlah : 1,
            'harga_vendor' => 0,
            'harga_diskon' => 0,
            'nilai_diskon' => 0,
            'total_diskon' => 0,
            'harga_akhir' => 0,
            'total_harga' => 0,
            'jumlah_volume' => 0,
            'harga_yang_diharapkan' => 0,
            'persen_kenaikan' => 0,
            'proyeksi_kenaikan' => 0,
            'ppn_dinas' => 0,
            'pph_dinas' => 0,
            'hps' => 0,
            'nilai_hps' => 0,
            'harga_per_pcs' => 0,
            'harga_pagu_dinas_per_pcs' => $matchingClientRequest ? $matchingClientRequest->harga_satuan : 0,
            'pagu_total' => 0,
            // ... rest of default fields
        ];
        
        if ($matchingClientRequest) {
            Log::info("Created new item with client mapping:", [
                'index' => $index,
                'client_data' => [
                    'nama_barang' => $matchingClientRequest->nama_barang,
                    'jumlah' => $matchingClientRequest->jumlah,
                    'satuan' => $matchingClientRequest->satuan,
                    'harga_satuan' => $matchingClientRequest->harga_satuan
                ],
                'auto_filled' => [
                    'qty' => $newItem['qty'],
                    'satuan' => $newItem['satuan'],
                    'harga_pagu_dinas_per_pcs' => $newItem['harga_pagu_dinas_per_pcs']
                ]
            ]);
        }
        
        return $newItem;
    }
}
