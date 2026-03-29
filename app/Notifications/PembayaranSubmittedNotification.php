<?php

namespace App\Notifications;

use App\Models\Pembayaran;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembayaranSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Pembayaran $pembayaran
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $proyek = $this->pembayaran->penawaran?->proyek;
        $purchasing = $proyek?->adminPurchasing;

        $kodeProyek = $proyek?->kode_proyek ?? '-';
        $namaPurchasing = $purchasing?->nama ?? 'Purchasing';
        $vendorId = $this->pembayaran->id_vendor ?? '-';

        return [
            'event' => 'pembayaran_submitted',
            'title' => 'Pembayaran Menunggu Approval',
            'message' => 'Ada pembayaran baru yang menunggu approval untuk vendor ' . $vendorId . ' dari Proyek ' . $kodeProyek . ' oleh ' . $namaPurchasing . '.',
            'pembayaran_id' => $this->pembayaran->id_pembayaran,
            'proyek_id' => $proyek?->id_proyek,
            'kode_proyek' => $proyek?->kode_proyek,
            'status_verifikasi' => $this->pembayaran->status_verifikasi,
            'nominal_bayar' => (string) $this->pembayaran->nominal_bayar,
            'id_vendor' => $this->pembayaran->id_vendor,
            'purchasing_id' => $purchasing?->id_user,
            'purchasing_nama' => $namaPurchasing,
            'url' => route('keuangan.approval'),
        ];
    }
}
