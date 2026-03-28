<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectModel extends Model
{
    //
    use HasFactory;
    protected $table = 'tblprojects';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'img',
        'view',
        'status',
        'admin_id',
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
