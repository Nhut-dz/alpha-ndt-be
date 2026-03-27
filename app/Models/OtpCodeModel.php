<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCodeModel extends Model
{
    protected $table = 'tblotp_codes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'email',
        'otp',
        'is_used',
    ];

    protected function casts(): array
    {
        return [
            'is_used' => 'boolean',
        ];
    }
}
