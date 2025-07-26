<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $perPage = 20;
    protected $fillable = ['registro_id_emisor', 'registro_id_receptor', 'mensaje', 'imagen', 'audio'];

    public function emisor()
    {
        return $this->belongsTo(Registro::class, 'registro_id_emisor', 'id');
    }

    public function receptor()
    {
        return $this->belongsTo(Registro::class, 'registro_id_receptor', 'id');
    }

    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            return '/storage/' . $this->imagen;
        }
        return null;
    }

    public function getAudioUrlAttribute()
    {
        if ($this->audio) {
            return '/storage/' . $this->audio;
        }
        return null;
    }
}
