<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comentario extends Model
{
    // Nombre de la tabla en la base de datos
    protected $table = 'comentario';

    // Campos que se pueden rellenar mediante create() o update()
    protected $fillable = [
        'noticia_id',
        'mensaje',
        'valoracion'
    ];

    // Un comentario pertenece a una noticia
    public function noticia(): BelongsTo
    {
        return $this->belongsTo(Noticia::class, 'noticia_id');
    }

    // Comprueba si todavía se puede modificar o borrar (5 minutos)
    public function sePuedeModificarOBorrar(): bool
    {
        return $this->created_at->diffInMinutes(now()) < 5;
    }

    // Calcula los minutos que quedan para modificar o borrar
    public function minutosRestantes(): int
    {
        return max(0, 5 - (int) $this->created_at->diffInMinutes(now()));
    }
}
