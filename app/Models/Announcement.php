<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'author_id',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id')->withTrashed();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'announcement_category', 'announcement_id', 'category_id');
    }

    public function scopeHasCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        });
    }

    public function scopeBelongToAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }
}
