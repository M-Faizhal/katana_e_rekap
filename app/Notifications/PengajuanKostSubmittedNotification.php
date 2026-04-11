<?php

namespace App\Notifications;

use App\Models\PengajuanKost;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengajuanKostSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public PengajuanKost $pengajuan
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $picMarketing = $this->pengajuan->picMarketing;
        $namaPic = $picMarketing?->nama ?? ('User #' . ($this->pengajuan->pic_marketing_id ?? '-'));

        return [
            'event' => 'pengajuan_kost_submitted',
            'title' => 'Pengajuan Kost Baru',
            'message' => 'Ada pengajuan Kost baru: ' . ($this->pengajuan->kode_pengajuan ?? '-') . '. Oleh: ' . $namaPic . '.',
            'pengajuan_kost_id' => $this->pengajuan->id,
            'kode_pengajuan' => $this->pengajuan->kode_pengajuan,
            'status' => $this->pengajuan->status,
            'nominal' => (string) $this->pengajuan->nominal,
            'pic_marketing_id' => $this->pengajuan->pic_marketing_id,
            'pic_marketing_nama' => $namaPic,
            'url' => route('keuangan.verifikasi-kost'),
        ];
    }
}
