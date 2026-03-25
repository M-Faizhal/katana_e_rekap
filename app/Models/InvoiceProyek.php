<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\InvoiceProyekItem;

class InvoiceProyek extends Model
{
    use HasFactory;

    protected $table = 'invoice_proyek';
    protected $primaryKey = 'id_invoice';

    protected $fillable = [
        'id_proyek',
        'id_penawaran',
        'tanggal_surat',
        'nomor_surat',
        'bill_to_instansi',
        'bill_to_alamat',
        'ship_to_instansi',
        'ship_to_alamat',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class, 'id_penawaran', 'id_penawaran');
    }

    public function items()
    {
        return $this->hasMany(InvoiceProyekItem::class, 'id_invoice', 'id_invoice');
    }
}
