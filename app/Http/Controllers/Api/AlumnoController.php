<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAlumnoRequest;
use App\Models\Alumno;
use App\Models\Sede;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\BienvenidaAlumno;

class AlumnoController extends Controller
{
    public function store(StoreAlumnoRequest $request) {
        DB::beginTransaction();

        try {
            $datos = $request->validated();
            $año = now()->year;
            $ultimo = Alumno::where('matricula', 'like', "UNI{$año}%")->orderBy('matricula', 'desc')->first();
            $siguiente = $ultimo ? intval(substr($ultimo->matricula, 7)) + 1 : 1;
            $matricula = "UNI{$año}" . str_pad($siguiente, 3, '0', STR_PAD_LEFT);

            $alumno = Alumno::create([
                'matricula' => $matricula,
                'nombre' => $datos['nombre'],
                'apellido' => $datos['apellido'],
                'apellido_materno' => $datos['apellido_materno'] ?? null,
                'correo_electronico' => $datos['correo_electronico'],
                'sede_id' => $datos['sede_id'],
            ]);

            Mail::to($alumno->correo_electronico)->send(new BienvenidaAlumno($alumno));

            DB::commit();

            return response()->json(['message' => 'Alumno registrado exitosamente', 'data' => $alumno->load('sede'),], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Ocurrió un error al registrar el alumno', 'error' => $e->getMessage(),], 500);
        }
    }

    public function index() {
        $alumnos = Alumno::with('sede')->orderBy('created_at', 'desc')->get();

        return response()->json(['message' => 'Listado de alumnos', 'data' => $alumnos,]);
    }

    public function show($matricula) {
        $alumno = Alumno::with('sede')->where('matricula', $matricula)->first();

        if (!$alumno) {
            return response()->json([ 'message' => 'Alumno no encontrado',], 404);
        }

        return response()->json(['message' => 'Alumno encontrado', 'data' => $alumno,]);
    }

}
