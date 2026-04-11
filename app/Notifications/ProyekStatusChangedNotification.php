<?php

namespace App\Notifications;

use App\Models\Proyek;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProyekStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Proyek $proyek,
        public string $oldStatus,
        public string $newStatus,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'event' => 'proyek_status_changed',
            'title' => 'Status Proyek Berubah',
            'message' => 'Status proyek ' . ($this->proyek->kode_proyek ?? '-') . ' berubah dari ' . $this->oldStatus . ' menjadi ' . $this->newStatus . '.',
            'proyek_id' => $this->proyek->id_proyek,
            'kode_proyek' => $this->proyek->kode_proyek,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'url' => route('marketing.proyek'),
        ];
    }
}
