<?php

namespace App\Notifications;

use App\Models\Penawaran;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PenawaranAccNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Penawaran $penawaran
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $proyek = $this->penawaran->proyek;

        $kodeProyek = $proyek?->kode_proyek ?? '-';
        $tanggalAcc = now()->format('d M Y');

        return [
            'event' => 'penawaran_acc',

            'title' => 'Penawaran Disetujui',

            'message' =>
                "Potensi ($kodeProyek) telah berhasil menjadi Proyek pada tanggal $tanggalAcc. " .
                "Penawaran dengan nomor " . ($this->penawaran->no_penawaran ?? '-'),

            'penawaran_id' => $this->penawaran->id_penawaran,
            'proyek_id' => $this->penawaran->id_proyek,
            'kode_proyek' => $kodeProyek,
            'status' => $this->penawaran->status,

            'url' => $proyek ? route('marketing.proyek') : null,
        ];
    }
}