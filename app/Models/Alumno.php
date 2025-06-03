<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alumno extends Model
{
    use HasFactory;

    protected $fillable = ['matricula', 'nombre', 'apellido', 'apellido_materno', 'correo_electronico', 'sede_id'];

    public function sede() {
        return $this->belongsTo(Sede::class);
    }
}
