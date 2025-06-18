<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;


class Comment extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'comments';

    protected $fillable = [
        'user_id',
        'comment',
        'post_id',
    ];

   
    // Relationship with User (if you have a User model)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
} 