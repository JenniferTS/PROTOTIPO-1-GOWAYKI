# Modelo Entidad-Relación (MER) — GoWayki

## Diagrama conceptual

```
┌──────────────┐       ┌──────────────────┐       ┌──────────────┐
│    users     │       │ user_profiles     │       │    rutas     │
│──────────────│       │──────────────────│       │──────────────│
│ PK: id       │◄──────│ FK: user_id (UQ)  │       │ PK: id       │
│ name         │ 1..1  │ bio              │       │ nombre       │
│ email        │       │ telefono          │       │ descripcion  │
│ password     │       │ direccion         │       │ origen       │
│ role         │       │ fecha_nacimiento  │       │ destino      │
│ avatar       │       │ puntaje_explorac. │       │ tiempo_est.  │
│ notif_activ. │       └──────────────────┘       │ costo        │
└──────┬───────┘                                   │ color_linea  │
       │                                           │ activa       │
       │ 1:N                                       └──────┬───────┘
       │                                                  │
       │                                                  │ 1:N
       ▼                                                  ▼
┌──────────────┐                                   ┌──────────────┐
│  recorridos  │                                   │  paraderos   │
│──────────────│                                   │──────────────│
│ PK: id       │                                   │ PK: id       │
│ FK: user_id  │                                   │ FK: ruta_id  │
│ FK: ruta_id  │                                   │ nombre       │
│ nombre       │                                   │ latitud      │
│ origen       │                                   │ longitud     │
│ destino      │                                   │ orden        │
│ notas        │                                   │ imagen       │
└──────────────┘                                   │ imagen_url   │
                                                   └──────────────┘
       ▲
       │
       │ 1:N
       │
┌──────┴───────────────┐       ┌──────────────┐
│   lugares_visitados  │       │   destinos   │
│──────────────────────│       │──────────────│
│ PK: id               │       │ PK: id       │
│ FK: user_id          │       │ nombre       │
│ FK: destino_id       │◄──────│ tagline      │
│ fecha_visita         │  N:1  │ descripcion  │
│ notas                │       │ categoria    │
│ UNIQUE(user_id,      │       │ distrito     │
│        destino_id)   │       │ latitud      │
└──────────────────────┘       │ longitud     │
                               │ imagen_url   │
                               │ calificacion │
                               │ activo       │
                               └──────────────┘
```

---

## Diccionario de relaciones

### Relación Uno a Uno (1:1)

| Entidad A | Entidad B | Detalle |
|---|---|---|
| `users` | `user_profiles` | Cada usuario tiene exactamente un perfil extendido. La FK `user_id` en `user_profiles` tiene constraint `UNIQUE`. |

**Implementación:**
- `User.php`: `$this->hasOne(UserProfile::class, 'user_id')`
- `UserProfile.php`: `$this->belongsTo(User::class)`

### Relación Uno a Muchos (1:N)

| Entidad A (1) | Entidad B (N) | Detalle |
|---|---|---|
| `users` | `recorridos` | Un usuario puede tener múltiples recorridos guardados. |
| `users` | `lugares_visitados` | Un usuario puede marcar múltiples destinos como visitados. |
| `rutas` | `paraderos` | Una ruta tiene múltiples paraderos ordenados. |
| `rutas` | `recorridos` | Una ruta puede aparecer en múltiples recorridos de usuarios. |
| `destinos` | `lugares_visitados` | Un destino puede ser visitado por múltiples usuarios. |

**Implementación en modelos:**

| Modelo | Relación | Método | Línea |
|---|---|---|---|
| `User` | HasMany → Recorrido | `$this->hasMany(Recorrido::class)` | User.php:48 |
| `User` | HasMany → LugarVisitado | `$this->hasMany(LugarVisitado::class)` | User.php:43 |
| `Ruta` | HasMany → Paradero | `$this->hasMany(Paradero::class)->orderBy('orden')` | Ruta.php:32 |
| `Ruta` | HasMany → Recorrido | `$this->hasMany(Recorrido::class)` | Ruta.php:37 |
| `Destino` | HasMany → LugarVisitado | `$this->hasMany(LugarVisitado::class)` | Destino.php:33 |

### Relación Muchos a Uno (N:1)

| Entidad N | Entidad 1 | Detalle |
|---|---|---|
| `paraderos` | `rutas` | Cada paradero pertenece a una única ruta. |
| `recorridos` | `users` | Cada recorrido pertenece a un usuario. |
| `recorridos` | `rutas` | Cada recorrido está asociado a una ruta. |
| `lugares_visitados` | `users` | Cada registro pertenece a un usuario. |
| `lugares_visitados` | `destinos` | Cada registro pertenece a un destino. |
| `user_profiles` | `users` | Cada perfil pertenece a un usuario. |

**Implementación en modelos:**

| Modelo | Relación | Método | Línea |
|---|---|---|---|
| `Paradero` | BelongsTo → Ruta | `$this->belongsTo(Ruta::class)` | Paradero.php:28 |
| `Recorrido` | BelongsTo → User | `$this->belongsTo(User::class)` | Recorrido.php:21 |
| `Recorrido` | BelongsTo → Ruta | `$this->belongsTo(Ruta::class)` | Recorrido.php:26 |
| `LugarVisitado` | BelongsTo → User | `$this->belongsTo(User::class)` | LugarVisitado.php:26 |
| `LugarVisitado` | BelongsTo → Destino | `$this->belongsTo(Destino::class)` | LugarVisitado.php:31 |
| `UserProfile` | BelongsTo → User | `$this->belongsTo(User::class)` | UserProfile.php:17 |

### Relación Muchos a Muchos (N:M)

| Entidad A | Entidad B | Tabla pivote | Detalle |
|---|---|---|---|
| `users` | `destinos` | `lugares_visitados` | Un usuario puede visitar muchos destinos, y un destino puede ser visitado por muchos usuarios. La tabla pivote `lugares_visitados` incluye `fecha_visita` y `notas`. |

**Implementación:**
- `User.php` (línea 53): `$this->belongsToMany(Destino::class, 'lugares_visitados')->withPivot('fecha_visita', 'notas')->withTimestamps()`

---

## Tablas del sistema (10 tablas)

| # | Tabla | Propósito | Columnas |
|---|---|---|---|
| 1 | `users` | Usuarios del sistema (admin y regulares) | 11 |
| 2 | `password_reset_tokens` | Tokens de recuperación de contraseña | 3 |
| 3 | `sessions` | Sesiones activas de usuarios | 6 |
| 4 | `cache` | Caché de Laravel | 3 |
| 5 | `cache_locks` | Bloqueos de caché | 3 |
| 6 | `rutas` | Rutas de transporte público | 11 |
| 7 | `paraderos` | Paraderos con coordenadas geográficas | 10 |
| 8 | `destinos` | Destinos turísticos de Arequipa | 12 |
| 9 | `recorridos` | Recorridos planificados por usuarios | 8 |
| 10 | `lugares_visitados` | Registro de destinos visitados por usuario (N:M) | 7 |
| 11 | `user_profiles` | Perfiles extendidos de usuario (1:1) | 8 |

---

## Implementación avanzada

### Trigger: `trg_incrementar_puntaje_exploracion`

Se activa **AFTER INSERT** en `lugares_visitados`. Cada vez que un usuario marca un destino como visitado, se incrementa automáticamente su `puntaje_exploracion` en 10 puntos en la tabla `user_profiles`.

```sql
CREATE TRIGGER trg_incrementar_puntaje_exploracion
AFTER INSERT ON lugares_visitados
FOR EACH ROW
BEGIN
    UPDATE user_profiles
    SET puntaje_exploracion = COALESCE(puntaje_exploracion, 0) + 10
    WHERE user_id = NEW.user_id;
END;
```

### Stored Procedure: `sp_reporte_actividad_usuario`

Recibe `user_id`, `fecha_inicio` y `fecha_fin`. Retorna el total de recorridos creados, lugares visitados, destinos distintos y puntaje de exploración del usuario en ese período.

```sql
CREATE PROCEDURE sp_reporte_actividad_usuario(
    IN p_user_id INT,
    IN p_fecha_inicio DATE,
    IN p_fecha_fin DATE
)
BEGIN
    SELECT
        u.id AS user_id,
        u.name,
        COUNT(DISTINCT r.id) AS total_recorridos,
        COUNT(DISTINCT lv.id) AS total_lugares_visitados,
        COUNT(DISTINCT lv.destino_id) AS destinos_distintos,
        COALESCE(up.puntaje_exploracion, 0) AS puntaje_exploracion
    FROM users u
    LEFT JOIN recorridos r ON r.user_id = u.id
        AND r.created_at BETWEEN p_fecha_inicio AND p_fecha_fin
    LEFT JOIN lugares_visitados lv ON lv.user_id = u.id
        AND lv.created_at BETWEEN p_fecha_inicio AND p_fecha_fin
    LEFT JOIN user_profiles up ON up.user_id = u.id
    WHERE u.id = p_user_id
    GROUP BY u.id, u.name, up.puntaje_exploracion;
END;
```

**Uso:**
```sql
CALL sp_reporte_actividad_usuario(1, '2026-01-01', '2026-12-31');
```
