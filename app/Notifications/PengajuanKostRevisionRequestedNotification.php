<?php

namespace App\Notifications;

use App\Models\PengajuanKost;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengajuanKostRevisionRequestedNotification extends Notification
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
        return [
            'event' => 'pengajuan_kost_revisi',
            'title' => 'Pengajuan Cost Perlu Revisi',
            'message' => 'Pengajuan cost ' . ($this->pengajuan->kode_pengajuan ?? '-') . ' perlu revisi: ' . ($this->pengajuan->catatan_keuangan ?? '-'),
            'pengajuan_kost_id' => $this->pengajuan->id,
            'kode_pengajuan' => $this->pengajuan->kode_pengajuan,
            'status' => $this->pengajuan->status,
            'catatan_keuangan' => $this->pengajuan->catatan_keuangan,
            'url' => route('marketing.pengajuan-kost'),
        ];
    }
}
