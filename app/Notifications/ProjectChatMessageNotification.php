<?php

namespace App\Notifications;

use App\Models\ProjectChat;
use App\Models\Proyek;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class ProjectChatMessageNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected ProjectChat $chat,
        protected Proyek      $proyek,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $sender  = $this->chat->user;
        $snippet = $this->buildSnippet();
        $url     = route('chat.proyek', $this->proyek->id_proyek) . '#chat-' . $this->chat->id;

        return [
            'event'     => 'project_chat_message',
            'title'     => 'Pesan baru: ' . $this->proyek->kode_proyek,
            'message'   => ($sender?->nama ?? 'Seseorang') . ': ' . $snippet,
            'url'       => $url,
            'proyek_id' => $this->proyek->id_proyek,
            'chat_id'   => $this->chat->id,
        ];
    }

    private function buildSnippet(): string
    {
        if ($this->chat->message) {
            return Str::limit($this->chat->message, 80);
        }

        $fileCount = $this->chat->files->count();
        $sender    = $this->chat->user;

        return ($sender?->nama ?? 'Seseorang') . ' mengirim lampiran' .
               ($fileCount > 1 ? " ({$fileCount} file)" : '') . '.';
    }
}