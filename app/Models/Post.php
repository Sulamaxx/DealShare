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
        'helpful_by_user',
        'reported_count',
        'expiration_date',
        'store',
    ];

    protected $casts = [
        'verified_member' => 'boolean',
        'posted_at' => 'datetime',
        'expiration_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'post_by');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        // If a specific field is explicitly requested (e.g., {post:id}), use that field.
        if ($field) {
            return $this->where($field, $value)->firstOrFail();
        }

        // Otherwise, try to resolve by ID first if the value is numeric
        if (is_numeric($value)) {
            return $this->where('id', $value)->firstOrFail();
        }

        // If not numeric, try to resolve by slug
        return $this->where('slug', $value)->firstOrFail();
    }
}
