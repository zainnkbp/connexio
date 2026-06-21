<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    protected $table = 'devices';
    protected $primaryKey = 'serial_number';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'serial_number',
        'jenis_merek',
        'tipe_perangkat',
        'status_kondisi',
        'alasan_rusak',
        'tanggal_pasang_awal',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'serial_number', 'serial_number');
    }
}
