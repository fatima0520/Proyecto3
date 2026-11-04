<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Noticia extends Model
{
    // Nombre de la tabla en la base de datos
    protected $table = 'noticia';

    // Campos que se pueden rellenar mediante create() o update()
    protected $fillable = [
        'titulo',
        'texto'
    ];

    // Una noticia puede tener muchos comentarios
    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'noticia_id');
    }

    // Comprueba si todavía se puede editar la noticia (20 minutos)
    public function sePuedeEditar(): bool
    {
        return $this->created_at->diffInMinutes(now()) < 20;
    }

    // Comprueba si se puede borrar (no debe tener comentarios)
    public function sePuedeBorrar(): bool
    {
        return $this->comentarios()->doesntExist();
    }

    // Calcula los minutos que quedan para poder editar
    public function minutosRestantesEdicion(): int
    {
        return max(0, 20 - (int) $this->created_at->diffInMinutes(now()));
    }
}
