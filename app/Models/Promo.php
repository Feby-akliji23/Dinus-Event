<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kode',
        'tipe',
        'nilai',
        'scope',
        'event_id',
        'ticket_type_id',
        'tiket_id',
        'mulai',
        'akhir',
        'min_transaksi',
        'kuota',
        'digunakan',
        'aktif',
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
        'min_transaksi' => 'decimal:2',
        'mulai' => 'datetime',
        'akhir' => 'datetime',
        'aktif' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function tiket()
    {
        return $this->belongsTo(Tiket::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
