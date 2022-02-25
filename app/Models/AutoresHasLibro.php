<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoresHasLibro extends Model
{
    use HasFactory;
    protected $table = 'autores_has_libros'; // Nombre de la tabla
}
