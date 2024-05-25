<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPostsLikes extends Model
{
    use HasFactory;

    protected $fillable  = ['user_id','blog_posts_id'];
}
