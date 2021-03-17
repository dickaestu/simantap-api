<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'surat_id', 'status', 'sub_bagian_id', 'tipe_surat'
    ];

    public function historable()
    {
        return $this->morphTo();
    }
}
