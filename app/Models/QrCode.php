<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'titulo',
        'slug',
        'url_destino',
        'total_leituras',
    ];

    /**
     * Retorna o usuário dono deste QR Code.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Retorna as leituras (scans) registradas deste QR Code.
     */
    public function leituras()
    {
        return $this->hasMany(QrCodeScan::class, 'qr_code_id');
    }
}

