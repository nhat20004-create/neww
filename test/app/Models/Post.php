<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    public const CATEGORIES = ['Công nghệ', 'Học tập', 'Đời sống'];

    protected $table = 'posts';

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'category',
        'content',
        'file_path',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOwnedBy(?int $userId): bool
    {
        return $userId !== null && (int) $this->user_id === $userId;
    }
}
