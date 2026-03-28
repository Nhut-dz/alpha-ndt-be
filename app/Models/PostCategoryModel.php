<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCategoryModel extends Model
{
    //
    use HasFactory;
    protected $table = 'tblposts_category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];

    public function posts()
    {
        return $this->hasMany(PostModel::class, 'post_category_id');
    }
}
