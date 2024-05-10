<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function scopePostIdGreaterThan($query, $postId)
    {
        if ($postId == false) {
            return $query;
        }

        return $query->where('post_id', '>', $postId);
    }

    public function scopeAuthorIdGreaterThan($query, $authorId)
    {
        if ($authorId == false) {
            return $query;
        }

        return $query->where('user_id', '>', $authorId);
    }
}
