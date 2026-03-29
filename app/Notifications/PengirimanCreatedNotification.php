<?php

namespace App\Notifications;

use App\Models\Pengiriman;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengirimanCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Pengiriman $pengiriman
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $proyek = $this->pengiriman->penawaran?->proyek;

        return [
            'event' => 'pengiriman_created',
            'title' => 'Pengiriman Barang Dibuat',
            'message' => 'Pengiriman barang untuk proyek ' . ($proyek?->kode_proyek ?? '-') . ' telah dibuat.',
            'pengiriman_id' => $this->pengiriman->id_pengiriman,
            'proyek_id' => $proyek?->id_proyek,
            'kode_proyek' => $proyek?->kode_proyek,
            'vendor_id' => $this->pengiriman->id_vendor,
            'status_verifikasi' => $this->pengiriman->status_verifikasi,
            'url' => route('marketing.proyek'),
        ];
    }
}
