<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeHasManyComments($query, $commentCount)
    {
        if ($commentCount == false) {
            return $query;
        }

        return $query->has('comments', '>', $commentCount);
    }

    public function scopeAuthorIdGreaterThan($query, $authorId)
    {
        if ($authorId == false) {
            return $query;
        }

        return $query->where('user_id', '>', $authorId);
    }
}
