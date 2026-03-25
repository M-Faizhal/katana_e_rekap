<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceProyekItem extends Model
{
    protected $table = 'invoice_proyek_items';
    protected $primaryKey = 'id_invoice_item';

    protected $fillable = [
        'id_invoice',
        'id_penawaran_detail',
        'keterangan_html',
    ];

    public function invoice()
    {
        return $this->belongsTo(InvoiceProyek::class, 'id_invoice', 'id_invoice');
    }

    public function penawaranDetail()
    {
        return $this->belongsTo(PenawaranDetail::class, 'id_penawaran_detail', 'id_detail');
    }
}
