<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;
    protected $table = 'autores'; // Nombre de la tabla
    protected $appends = ['nombre_completo'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getNombreCompletoAttribute() 
{ 
  return $this->nombre . ' ' . $this->apellidos; 
}

    public function libros()
    {
        return $this->belongsToMany(Libro::class, AutoresHasLibro::class, 'id_autor', 'isbn');
    }
}
