<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publicacione extends Model
{
    protected $perPage = 20;

    protected $fillable = [
        'registro_id',
        'titulo',
        'contenido',
        'imagen',
        'audio',
    ];

    public function autor()
    {
        return $this->belongsTo(Registro::class, 'registro_id', 'id');
    }

    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            return asset('storage/' . $this->imagen);
        }
        return null;
    }

    public function getAudioUrlAttribute()
    {
        if ($this->audio) {
            return asset('storage/' . $this->audio);
        }
        return null;
    }
}
