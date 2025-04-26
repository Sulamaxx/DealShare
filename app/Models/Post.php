<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'link',
        'upvotes',
        'downvotes',
        'posted_at',
        'comment_count',
        'category',
        'verified_member',
        'image',
        'discount_text',
        'price_saving',
        'post_by',
    ];

    protected $casts = [
        'verified_member' => 'boolean',
        'posted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'post_by');
    }

}
