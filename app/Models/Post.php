<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Carbon\Carbon;

class Post extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'posts';

    protected $fillable = [
        'user_id',
        'caption',
        'image_url',
        'image_path',
    ];

    /*protected $casts = [
        'likes_count' => 'integer',
        'comments_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];*/

    // Relationship with User (if you have a User model)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Comments (if you have a Comment model)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relationship with Likes (if you have a Like model)
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Scope to get recent posts
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Scope to get posts with images
    public function scopeWithImages($query)
    {
        return $query->whereNotNull('image_url');
    }

    // Method to increment likes count
    public function incrementLikes()
    {
        $this->increment('likes_count');
    }

    // Method to increment comments count
    public function incrementComments()
    {
        $this->increment('comments_count');
    }
} 