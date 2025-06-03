<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sede extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function alumnos() {
        return $this->hasMany(Alumno::class);
    }
}
