<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCodeScan extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_code_id',
        'endereco_ip',
        'agente_usuario',
    ];

    /**
     * Retorna o QR Code correspondente a esta leitura.
     */
    public function codigoQr()
    {
        return $this->belongsTo(QrCode::class, 'qr_code_id');
    }
}

