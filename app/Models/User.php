<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nome', 'email', 'senha', 'google_id', 'avatar'])]
#[Hidden(['senha', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Retorna o nome da coluna de senha para autenticação do Laravel.
     */
    public function getAuthPasswordName(): string
    {
        return 'senha';
    }

    /**
     * Retorna o hash da senha do usuário.
     */
    public function getAuthPassword(): string
    {
        return (string) $this->senha;
    }

    /**
     * Retorna os atributos que devem ser convertidos (cast).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verificado_em' => 'datetime',
            'senha'               => 'hashed',
        ];
    }

    /**
     * Relação com os QR Codes pertencentes ao usuário.
     */
    public function codigosQr()
    {
        return $this->hasMany(QrCode::class, 'usuario_id');
    }
}

