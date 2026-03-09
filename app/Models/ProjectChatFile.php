<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectChatFile extends Model
{
    protected $table = 'project_chat_files';

    protected $fillable = [
        'chat_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
    ];

    public function chat()
    {
        return $this->belongsTo(ProjectChat::class, 'chat_id');
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->file_type, 'image/');
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
