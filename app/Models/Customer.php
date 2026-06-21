<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id_pelanggan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pelanggan',
        'nama_pelanggan',
        'no_telepon',
        'alamat_pemasangan',
        'latitude',
        'longitude',
        'status_langganan',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'id_pelanggan', 'id_pelanggan');
    }
}
