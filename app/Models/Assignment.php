<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    protected $table = 'assignments';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_pelanggan',
        'id_teknisi',
        'serial_number',
        'tipe_alur',
        'status_approval',
        'foto_bukti',
        'alasan_rusak',
        'keterangan',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_teknisi', 'id_user');
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'serial_number', 'serial_number');
    }
}
