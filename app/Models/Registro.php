<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $perPage = 20;

    protected $fillable = ['nombre',
        'correo', 'contrasena',
        'edad'];

    public function comentariosEnviados()
    {
        return $this->hasMany(Comentario::class, 'registro_id_emisor', 'id');
    }


    public function comentariosRecibidos()
    {
        return $this->hasMany(Comentario::class, 'registro_id_receptor', 'id');
    }


}
