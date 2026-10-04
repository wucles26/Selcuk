<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'image_path',
        'seo_title',
        'seo_description',
        'description',
        'content',
    ];
}
