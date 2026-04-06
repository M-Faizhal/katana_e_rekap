<?php

namespace App\Notifications;

use App\Models\Proyek;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProyekVerifiedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Proyek $proyek,
        public string $newStatus,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $kode = $this->proyek->kode_proyek ?? '-';
        $instansi = $this->proyek->instansi ?? '-';
        $kabKota = $this->proyek->kab_kota ?? '-';

        return [
            'event' => 'proyek_verified',
            'title' => 'Verifikasi Proyek',
            'message' => "Proyek {$kode} ({$instansi}- {$kabKota}) telah diverifikasi menjadi status {$this->newStatus}.",
            'proyek_id' => $this->proyek->id_proyek,
            'kode_proyek' => $this->proyek->kode_proyek,
            'new_status' => $this->newStatus,
            'url' => route('superadmin.verifikasi-proyek.history'),
        ];
    }
}
