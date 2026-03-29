<?php

namespace App\Notifications;

use App\Models\Pembayaran;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembayaranApprovedNotification extends Notification
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
        $verifikator = $this->pembayaran->verifikator;

        $kodeProyek = $proyek?->kode_proyek ?? '-';
        $namaVerifikator = $verifikator?->nama ?? 'Admin Keuangan';

        return [
            'event' => 'pembayaran_approved',
            'title' => 'Pembayaran Disetujui',
            'message' => 'Pembayaran untuk proyek ' . $kodeProyek . ' telah disetujui oleh ' . $namaVerifikator . '.',
            'pembayaran_id' => $this->pembayaran->id_pembayaran,
            'proyek_id' => $proyek?->id_proyek,
            'kode_proyek' => $proyek?->kode_proyek,
            'status_verifikasi' => $this->pembayaran->status_verifikasi,
            'diverifikasi_oleh' => $this->pembayaran->diverifikasi_oleh,
            'diverifikasi_oleh_nama' => $namaVerifikator,
            'url' => route('purchasing.pembayaran'),
        ];
    }
}
