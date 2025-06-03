# Sistema de Registro de Alumnos – Laravel 12

Este proyecto es una API REST desarrollada con Laravel 12 que permite registrar, listar y consultar alumnos asociados a diferentes sedes. Incluye validaciones, generación automática de matrícula, envío de correos de bienvenida y pruebas automatizadas.

---

## Requisitos

- PHP >= 8.2
- Composer
- MySQL
- Laravel 12
- Cualquier servicio SMTP válido (para envio de correo)

---

## Instalación

```bash
git clone https://github.com/Ernesto611/alumnos_santander.git
cd alumnos_santander
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed


---

##  Endpoints disponibles

| Método | Endpoint                  | Descripción                    |
|--------|---------------------------|--------------------------------|
| POST   | `/api/alumnos`           | Registrar un nuevo alumno      |
| GET    | `/api/alumnos`           | Listar todos los alumnos       |
| GET    | `/api/alumnos/{matricula}` | Consultar un alumno por matrícula |