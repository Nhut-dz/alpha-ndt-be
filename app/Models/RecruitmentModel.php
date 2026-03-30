<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentModel extends Model
{
    use HasFactory;

    protected $table = 'tblrecruitments';

    protected $fillable = [
        'title', 'slug', 'description', 'content', 'position',
        'department', 'location', 'employment_type', 'quantity',
        'salary_range', 'requirements', 'benefits', 'deadline',
        'contact_email', 'img', 'status',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];
}
