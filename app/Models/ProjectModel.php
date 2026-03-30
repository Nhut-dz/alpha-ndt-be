<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectModel extends Model
{
    use HasFactory;

    protected $table = 'tblprojects';

    protected $fillable = [
        'title', 'slug', 'description', 'content', 'client',
        'industry', 'location', 'year', 'duration', 'tag',
        'img', 'highlights', 'methods', 'standards',
        'status', 'sort_order',
    ];

    protected $casts = [
        'highlights' => 'array',
        'methods' => 'array',
        'standards' => 'array',
    ];
}
