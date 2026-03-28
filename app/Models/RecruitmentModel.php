<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentModel extends Model
{
    use HasFactory;

    protected $table = 'tblrecruitments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'position',
        'department',
        'location',
        'employment_type',
        'quantity',
        'salary_range',
        'requirements',
        'benefits',
        'deadline',
        'contact_email',
        'img',
        'status',
        'admin_id',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    protected $appends = ['img_url'];

    public function getImgUrlAttribute(): ?string
    {
        if (!$this->img) {
            return null;
        }
        if (str_starts_with($this->img, 'http')) {
            return $this->img;
        }
        return asset('storage/' . $this->img);
    }

    public function admin()
    {
        return $this->belongsTo(AdminModel::class, 'admin_id');
    }
}
