<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectChat extends Model
{
    protected $table = 'project_chats';

    protected $fillable = [
        'id_proyek',
        'user_id',
        'message',
        'reply_to_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function replyTo()
    {
        return $this->belongsTo(self::class, 'reply_to_id')->with(['user', 'files']);
    }

    public function files()
    {
        return $this->hasMany(ProjectChatFile::class, 'chat_id');
    }
}
