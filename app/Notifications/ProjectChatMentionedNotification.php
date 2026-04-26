<?php

namespace App\Notifications;

use App\Models\ProjectChat;
use App\Models\Proyek;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class ProjectChatMentionedNotification extends Notification
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
        $snippet = $this->chat->message
            ? Str::limit($this->chat->message, 80)
            : ($sender?->nama ?? 'Seseorang') . ' mengirim lampiran.';

        $url = route('chat.proyek', $this->proyek->id_proyek) . '#chat-' . $this->chat->id;

        return [
            'event'     => 'project_chat_mentioned',
            'title'     => 'Anda di-mention: ' . $this->proyek->kode_proyek,
            'message'   => ($sender?->nama ?? 'Seseorang') . ': ' . $snippet,
            'url'       => $url,
            'proyek_id' => $this->proyek->id_proyek,
            'chat_id'   => $this->chat->id,
        ];
    }
}