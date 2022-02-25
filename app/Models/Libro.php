<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;
    protected $table = 'libros'; // Nombre de la tabla
    protected $primaryKey = 'isbn';
    protected $fillable = [
        'isbn', 'id_editorial', 'titulo', 'sinopsis', 'n_paginas'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function editorial()
    {
        return $this->belongsTo(Editorial::class, 'id_editorial');
    }

    public function autores()
    {
        return $this->belongsToMany(Autor::class, AutoresHasLibro::class, 'isbn', 'id_autor');
    }
}
