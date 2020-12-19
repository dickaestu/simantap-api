<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KopSurat extends Model
{
    protected $table = 'bagian';

    protected $fillable = [
        'path', 'status'
    ];
}
