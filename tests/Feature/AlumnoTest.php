<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Sede;
use App\Models\Alumno;
use Illuminate\Support\Facades\Mail;
use App\Mail\BienvenidaAlumno;

class AlumnoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\SedesSeeder::class);
    }

    public function test_guardar_alumno_exitosamente()
    {
        Mail::fake();

        $sede = Sede::where('name', 'Zapopan')->first();

        $response = $this->postJson('/api/alumnos', [
            'nombre' => 'Ernesto',
            'apellido' => 'Cárdenas',
            'apellido_materno' => 'Hernández',
            'correo_electronico' => 'ernesto@ejemplo.com',
            'sede_id' => $sede->id,
        ]);

        $response->assertStatus(201)->assertJsonStructure(['message', 'data' => ['id', 'matricula', 'nombre', 'apellido', 'correo_electronico', 'sede']]);

        $this->assertDatabaseHas('alumnos', ['correo_electronico' => 'ernesto@ejemplo.com']);

        Mail::assertSent(BienvenidaAlumno::class);
    }

    public function test_falla_faltan_campos()
    {
        $response = $this->postJson('/api/alumnos', []);

        $response->assertStatus(422)->assertJsonValidationErrors(['nombre', 'apellido', 'correo_electronico', 'sede_id']);
    }

    public function test_falla_correo_existente()
    {
        $sede = Sede::where('name', 'Zapopan')->first();

        Alumno::create([
            'matricula' => 'UNI2025001',
            'nombre' => 'Carlos',
            'apellido' => 'Cárdenas',
            'correo_electronico' => 'carlos@ejemplo.com',
            'sede_id' => $sede->id,
        ]);

        $response = $this->postJson('/api/alumnos', [
            'nombre' => 'Carlos',
            'apellido' => 'Cárdenas',
            'correo_electronico' => 'carlos@ejemplo.com',
            'sede_id' => $sede->id,
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['correo_electronico']);
    }

    public function test_falla_correo_invalido()
    {
        $sede = Sede::where('name', 'Rectoria')->first();

        $response = $this->postJson('/api/alumnos', [
            'nombre' => 'Ernesto',
            'apellido' => 'Cárdenas',
            'correo_electronico' => 'correo',
            'sede_id' => $sede->id,
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['correo_electronico']);
    }

    public function test_falla_no_existe_sede()
    {
        $response = $this->postJson('/api/alumnos', [
            'nombre' => 'Carlos',
            'apellido' => 'Cárdenas',
            'correo_electronico' => 'carlos@ejemplo.com',
            'sede_id' => 9999,
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['sede_id']);
    }

    public function test_listar_alumnos()
    {
        $sede = Sede::where('name', 'Orizaba')->first();

        Alumno::create([
            'matricula' => 'UNI2025001',
            'nombre' => 'Ernesto',
            'apellido' => 'Cárdenas',
            'correo_electronico' => 'ernesto@ejemplo.com',
            'sede_id' => $sede->id,
        ]);

        $response = $this->getJson('/api/alumnos');

        $response->assertStatus(200)->assertJsonStructure(['message','data' => [['id', 'matricula', 'nombre', 'apellido', 'correo_electronico', 'sede']]]);
    }

    public function test_buscar_alumno_matricula()
    {
        $sede = Sede::where('name', 'Rectoria')->first();

        $alumno = Alumno::create([
            'matricula' => 'UNI2025002',
            'nombre' => 'Carlos',
            'apellido' => 'Cárdenas',
            'correo_electronico' => 'carlos@ejemplo.com',
            'sede_id' => $sede->id,
        ]);

        $response = $this->getJson('/api/alumnos/' . $alumno->matricula);

        $response->assertStatus(200)->assertJsonFragment(['matricula' => 'UNI2025002']);
    }

    public function test_falla_no_existe_matricula()
    {
        $response = $this->getJson('/api/alumnos/matricula');

        $response->assertStatus(404)->assertJsonFragment(['message' => 'Alumno no encontrado']);
    }
}
