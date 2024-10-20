<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutcomingLetter extends Model
{
    use HasFactory;

    protected $table = 'outcoming_letters';

    protected $fillable = [
        'nomor',
        'penerima',
        'perihal',
        'tanggal_dikirim',
        'tanggal_dibuat',
        'deskripsi',
        'attachment'
    ];
}
