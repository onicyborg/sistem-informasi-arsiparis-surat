<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingLetter extends Model
{
    use HasFactory;

    protected $table = 'incoming_letters';

    protected $fillable = [
        'nomor',
        'pengirim',
        'perihal',
        'tanggal_diterima',
        'tanggal_dibuat',
        'deskripsi',
        'attachment'
    ];
}
