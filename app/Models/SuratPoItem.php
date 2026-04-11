<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\SuratPo;
use App\Models\Barang;
use App\Models\KalkulasiHps;

class SuratPoItem extends Model
{
    protected $table = 'surat_po_items';
    protected $primaryKey = 'id_surat_po_item';

    protected $fillable = [
        'id_surat_po',
        'id_barang',
        'id_kalkulasi_hps',
        'qty',
        'unit_price',
        'spec_html',
    ];

    protected $casts = [
        'qty' => 'integer',
        'unit_price' => 'decimal:2',
    ];

    public function suratPo()
    {
        return $this->belongsTo(SuratPo::class, 'id_surat_po', 'id_surat_po');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function kalkulasiHps()
    {
        return $this->belongsTo(KalkulasiHps::class, 'id_kalkulasi_hps', 'id_kalkulasi');
    }

    public function getLineTotalAttribute(): float
    {
        return ((float) $this->qty) * ((float) $this->unit_price);
    }
}
