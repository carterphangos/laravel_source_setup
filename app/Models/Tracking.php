<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tracking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'announcement_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function announcement()
    {
        return $this->belongsTo(Announcement::class, 'announcement_id');
    }
}
