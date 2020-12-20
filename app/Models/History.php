<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'surat_masuk_id', 'status', 'sub_bagian_id'
    ];

    public function historable(){
        return $this->morphTo();
    }
}
