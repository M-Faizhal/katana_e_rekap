<?php

namespace App\Notifications;

use App\Models\Proyek;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProyekSiapVerifikasiBaruNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Proyek $proyek
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $kodeProyek = $this->proyek->kode_proyek ?? 'Proyek';
        $instansi   = $this->proyek->instansi ?? '-';
        $kabKota    = $this->proyek->kab_kota ?? '-';

        return [
            'event' => 'proyek_siapp_verifikasi_baru',
            'title' => 'Proyek Siap Diverifikasi',
            'message' => "Ada Proyek Baru yang siap diverifikasi: {$kodeProyek} untuk klien {$instansi}- {$kabKota}",
            'proyek_id' => $this->proyek->id_proyek,
            'kode_proyek' => $this->proyek->kode_proyek,
            'instansi' => $this->proyek->instansi,
            'kab_kota' => $this->proyek->kab_kota,
            'url' => route('superadmin.verifikasi-proyek.detail', ['id' => $this->proyek->id_proyek]),
        ];
    }
}
