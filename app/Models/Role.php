<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'role_name'
    ];

    public function bagian()
    {
        return $this->belongsTo('App\Models\Bagian', 'bagian_id');
    }
}
