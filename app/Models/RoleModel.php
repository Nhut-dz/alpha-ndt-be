<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;

    protected $table = 'tblroles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
    ];

    public function admins()
    {
        return $this->hasMany(AdminModel::class, 'role_id');
    }
}
