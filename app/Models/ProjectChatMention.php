<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectChatMention extends Model
{
    protected $table = 'project_chat_mentions';

    protected $fillable = [
        'chat_id',
        'mentioned_user_id',
    ];

    public function chat()
    {
        return $this->belongsTo(ProjectChat::class, 'chat_id');
    }

    public function mentionedUser()
    {
        return $this->belongsTo(User::class, 'mentioned_user_id', 'id_user');
    }
}