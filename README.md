# Proyecto Integrador GoWayki — Transporte inteligente en Arequipa

Plataforma web de movilidad urbana para Arequipa, Perú. Permite consultar rutas de transporte público, explorar destinos turísticos, planificar recorridos personalizados y gestionar el contenido desde un panel administrativo.

## Stack tecnológico

- **Backend**: Laravel 11 + PHP 8.2+
- **Base de datos**: MySQL 8 (con soporte espacial SRID 4326)
- **Frontend**: Tailwind CSS 4 + Vite + Leaflet.js + React (componentes aislados)
- **Mapas**: OpenStreetMap + OSRM (enrutamiento por calles reales)
- **Tests**: PHPUnit (27 tests de aceptación)

## Lo que hicimos desde la clonación

Este repositorio se clonó de [`fabriciorojas-10/PROTOTIPO-1-GOWAYKI-FABRICIO`](https://github.com/fabriciorojas-10/PROTOTIPO-1-GOWAYKI-FABRICIO). A partir de ahí se realizaron los siguientes cambios:

### Panel administrativo (`/admin`)
- Se creó un panel de administración completo con Dashboard, CRUD de rutas, paraderos, usuarios y recorridos.
- Layout con sidebar fijo y topbar siempre visible (flexbox, sin `position: sticky`).
- Dashboard con KPIs (rutas activas, paraderos, usuarios, nuevos en 7 días) y gráficos Chart.js.
- Roles de usuario (`admin`/`usuario`) con gate de autorización. Solo los administradores pueden acceder al panel.
- Migración `add_role_to_users_table` que agrega la columna `role` a la tabla `users`.

### Paleta de colores
- Se unificó la paleta del sitio público y el admin con el rojo corporativo (`#F83A34`) definido en CSS variables.
- Navbar principal del sitio público ahora es `position: fixed` con padding compensatorio en `<main>`.

### Correcciones y mejoras
- Se corrigió la navegación del sitio: enlaces a rutas, destinos, planificar y perfil de usuario.
- Se integraron las vistas del admin con el sistema de roles de Laravel.
- Se limpiaron archivos heredados que no pertenecían al proyecto (migraciones de podcasts, jobs, backups, referencias PHP puras, archivos temporales).
- Se eliminó el archivo SQLITE (`database/gowayki.sqlite`) que se usó durante desarrollo — el proyecto corre sobre MySQL.
- Se migró la configuración de `.env` de SQLite a MySQL: `DB_CONNECTION=mysql`, `DB_DATABASE=gowayki_db`.
- Se cambiaron drivers que dependían de tablas de base de datos: `SESSION_DRIVER=file`, `QUEUE_CONNECTION=sync`, `CACHE_STORE=file`.

### Implementaciones para proyecto final de Base de Datos
- **HasOne**: Relación 1:1 entre `users` y `user_profiles` (perfil extendido con bio, teléfono, puntaje de exploración).
- **Factories**: Creadas para todos los modelos (Ruta, Paradero, Destino, Recorrido, LugarVisitado, UserProfile).
- **Massive seeders**: 50 usuarios, 20 rutas adicionales (inactivas), 30 destinos adicionales (inactivos), 100 recorridos, +100 lugares visitados usando factories.
- **Trigger** `trg_incrementar_puntaje_exploracion`: AFTER INSERT en `lugares_visitados`, suma 10 puntos al `puntaje_exploracion` del usuario.
- **Stored Procedure** `sp_reporte_actividad_usuario`: Recibe user_id + rango de fechas, retorna total de recorridos, lugares visitados y puntaje.
- **MER y diccionario de relaciones**: Documentados en `docs/MER.md`.

## Estructura del proyecto

```
PROTOTIPO-1-GOWAYKI-FABRICIO/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # Controladores del panel admin
│   │   │   ├── RutasController.php
│   │   │   ├── DestinosController.php
│   │   │   └── RecorridosController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Ruta.php
│   │   ├── Paradero.php
│   │   ├── Destino.php
│   │   └── Recorrido.php
│   └── Providers/
├── database/
│   ├── migrations/              # Migraciones de la BD
│   ├── seeders/                 # Datos de prueba
│   └── factories/
├── resources/
│   ├── css/app.css              # Estilos globales + navbar fixed
│   ├── js/                      # Componentes React y JS
│   └── views/
│       ├── admin/               # Vistas del panel admin
│       │   ├── layouts/master.blade.php
│       │   ├── dashboard/
│       │   ├── rutas/
│       │   ├── paraderos/
│       │   ├── usuarios/
│       │   └── recorridos/
│       ├── layouts/app.blade.php # Layout público con navbar fixed
│       ├── rutas/
│       ├── destinos/
│       ├── recorridos/
│       └── auth/
├── routes/
│   ├── web.php                  # Rutas públicas y admin
│   └── api.php
├── docs/
│   └── MATRIZ_QA.md             # Matriz de pruebas QA
├── public/
│   └── images/
├── .env
├── composer.json
├── package.json
└── vite.config.js
```

## Instalación

### 1. Clonar e instalar dependencias

```bash
git clone <repo-url>
cd PROTOTIPO-1-GOWAYKI-FABRICIO
composer install
npm install
```

### 2. Configurar entorno

```bash
cp .env.example .env
php artisan key:generate
```

Editar `.env` con las credenciales de MySQL:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gowayki_db
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Crear BD y migrar

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS gowayki_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
php artisan migrate --seed
```

### 4. Compilar assets y servir

```bash
npm run build
php artisan serve
```

Ir a `http://127.0.0.1:8000/`.

## Usuarios de prueba

| Email | Contraseña | Rol |
|---|---|---|
| admin@gowayki.com | GoWayki2025! | admin |
| test@test.com | password | usuario |

El panel admin está en `/admin`. Solo usuarios con `role = admin` pueden acceder.

## Rutas del sistema

### Públicas
| Ruta | Descripción |
|---|---|
| `/` | Home con hero slider |
| `/rutas` | Buscador de rutas de transporte |
| `/rutas/{id}` | Detalle de ruta con mapa interactivo |
| `/destinos` | Explorador de destinos turísticos |
| `/recorridos/planificar` | Planificador de recorridos |
| `/recorridos/mi-ruta` | Recorridos guardados del usuario |

### Admin (requiere rol admin)
| Ruta | Descripción |
|---|---|
| `/admin` | Dashboard con KPIs y gráficos |
| `/admin/rutas` | CRUD de rutas |
| `/admin/paraderos` | CRUD de paraderos |
| `/admin/usuarios` | Listado de usuarios |
| `/admin/recorridos` | Listado de recorridos |

## Pruebas

```bash
php artisan test
```

27 tests de aceptación que cubren consulta de rutas, destinos, planificación, autenticación, y roles de administrador.
