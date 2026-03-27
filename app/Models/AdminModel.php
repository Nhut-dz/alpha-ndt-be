<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminModel extends Model
{
    //
    use HasFactory;

    protected $table = 'tbladmins';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];
    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id');
    }
}
