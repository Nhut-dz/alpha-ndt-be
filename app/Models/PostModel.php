<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostModel extends Model
{
    //
    use HasFactory;
    protected $table = 'tblposts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'slug',
        'content',
        'img',
        'view',
        'status',
        'admin_id',
        'post_category_id',
    ];
    public function admin()
    {
        return $this->belongsTo(AdminModel::class, 'admin_id');
    }
    public function category()
    {
        return $this->belongsTo(PostCategoryModel::class, 'post_category_id');
    }
}
