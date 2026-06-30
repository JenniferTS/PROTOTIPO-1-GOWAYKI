# CHANGELOG — Funcionalidades Avanzadas de Base de Datos

## 1. Triggers (Nuevos + Existente)

| # | Trigger | Tabla | Evento | Propósito |
|---|---------|-------|--------|-----------|
| 1 | `trg_incrementar_puntaje_exploracion` | `lugares_visitados` | AFTER INSERT | +10 al puntaje_exploracion del usuario |
| 2 | `trg_incrementar_total_visitas_destino` | `lugares_visitados` | AFTER INSERT | +1 al total_visitas en destinos |
| 3 | `trg_evitar_eliminar_ruta_con_paraderos` | `rutas` | BEFORE DELETE | Protege integridad referencial |
| 4a | `trg_auditoria_rutas_update` | `rutas` | AFTER UPDATE | Registra estado anterior en auditoria_rutas |
| 4b | `trg_auditoria_rutas_delete` | `rutas` | AFTER DELETE | Registra estado anterior en auditoria_rutas |

### Verificar triggers existentes:
```sql
SHOW TRIGGERS;
SELECT TRIGGER_NAME, EVENT_MANIPULATION, EVENT_OBJECT_TABLE, ACTION_TIMING
FROM information_schema.TRIGGERS
WHERE TRIGGER_SCHEMA = 'gowayki_db';
```

### Probar trg_evitar_eliminar_ruta_con_paraderos:
```sql
-- Debe fallar con error 45000
DELETE FROM rutas WHERE id = 1;
```

### Probar trg_auditoria_rutas_update y trg_auditoria_rutas_delete:
```sql
SET @current_user_id = 1;
UPDATE rutas SET nombre = 'Test Audit' WHERE id = 1;
DELETE FROM rutas WHERE id = 999; -- id inexistente (para no dañar datos reales)
-- Verificar registros de auditoría
SELECT * FROM auditoria_rutas;
```

### Probar trg_incrementar_total_visitas_destino:
```sql
-- Ver valor actual de total_visitas
SELECT id, nombre, total_visitas FROM destinos WHERE id = 1;
-- Insertar un lugar visitado
INSERT INTO lugares_visitados (user_id, destino_id, fecha_visita, created_at, updated_at)
VALUES (1, 1, CURDATE(), NOW(), NOW());
-- Verificar que total_visitas se incrementó
SELECT id, nombre, total_visitas FROM destinos WHERE id = 1;
```

---

## 2. Stored Procedures (Nuevos + Existente)

| # | Procedimiento | Parámetros | Retorna |
|---|--------------|-----------|---------|
| 1 | `sp_reporte_actividad_usuario` | p_user_id, p_fecha_inicio, p_fecha_fin | recorridos, lugares_visitados, puntaje |
| 2 | `sp_ruta_mas_visitada` | p_limite (opcional, default 5) | Top N rutas con total de recorridos |
| 3 | `sp_paradero_con_mayor_transito` | p_ruta_id | Paraderos ordenados por frecuencia |
| 4 | `sp_resumen_general_sistema` | (ninguno) | Métricas globales en 1 fila |

### Probar sp_ruta_mas_visitada:
```sql
CALL sp_ruta_mas_visitada(5);
```

### Probar sp_paradero_con_mayor_transito:
```sql
CALL sp_paradero_con_mayor_transito(1);
```

### Probar sp_resumen_general_sistema:
```sql
CALL sp_resumen_general_sistema();
```

### Probar sp_reporte_actividad_usuario (existente):
```sql
CALL sp_reporte_actividad_usuario(1, '2026-01-01', '2026-12-31');
```

---

## 3. Índices de Rendimiento

| Tabla | Índice | Columnas | Justificación |
|-------|--------|----------|---------------|
| `rutas` | `idx_rutas_origen` | origen | Búsquedas frecuentes por origen |
| `rutas` | `idx_rutas_destino` | destino | Búsquedas frecuentes por destino |
| `rutas` | `idx_rutas_activa` | activa | Filtro rápido de rutas activas/inactivas |
| `recorridos` | `idx_recorridos_created_at` | created_at | Ordenamiento por fecha |
| `recorridos` | `idx_recorridos_user_created` | user_id, created_at | Reporte de actividad por usuario |
| `lugares_visitados` | `idx_visitados_user_destino` | user_id, destino_id | Consulta de destinos visitados por usuario |
| `destinos` | `idx_destinos_activo` | activo | Filtro rápido de destinos activos |

### Verificar índices:
```sql
SHOW INDEXES FROM rutas;
SHOW INDEXES FROM recorridos;
SHOW INDEXES FROM lugares_visitados;
SHOW INDEXES FROM destinos;
```

### Demostración de mejora de rendimiento:
```sql
-- ANTES (sin índices): EXPLAIN muestra full table scan
EXPLAIN SELECT * FROM recorridos WHERE user_id = 1 ORDER BY created_at DESC;
-- DESPUÉS (con índice compuesto): EXPLAIN muestra ref + index
EXPLAIN SELECT * FROM recorridos WHERE user_id = 1 ORDER BY created_at DESC;
```

---

## 4. CHECK Constraints

| Tabla | Restricción | Regla |
|-------|------------|-------|
| `rutas` | `chk_costo_positivo` | costo_aproximado_soles >= 0 |
| `rutas` | `chk_tiempo_positivo` | tiempo_estimado_minutos > 0 |
| `destinos` | `chk_calificacion_rango` | calificacion >= 0 AND calificacion <= 5 |
| `paraderos` | `chk_orden_positivo` | orden >= 0 |
| `users` | `chk_email_formato` | email LIKE "%@%.%" |

### Verificar CHECK constraints:
```sql
SELECT CONSTRAINT_NAME, TABLE_NAME, CHECK_CLAUSE
FROM information_schema.TABLE_CONSTRAINTS tc
JOIN information_schema.CHECK_CONSTRAINTS cc ON tc.CONSTRAINT_NAME = cc.CONSTRAINT_NAME
WHERE tc.TABLE_SCHEMA = 'gowayki_db';
```

### Probar violación de constraint:
```sql
-- Debe fallar: costo negativo
INSERT INTO rutas (nombre, origen, destino, tiempo_estimado_minutos, costo_aproximado_soles)
VALUES ('Test', 'A', 'B', 30, -5);

-- Debe fallar: tiempo cero
INSERT INTO rutas (nombre, origen, destino, tiempo_estimado_minutos, costo_aproximado_soles)
VALUES ('Test', 'A', 'B', 0, 1.50);

-- Debe fallar: calificación fuera de rango
INSERT INTO destinos (nombre, categoria, latitud, longitud, calificacion)
VALUES ('Test', 'turistico', -16.4, -71.5, 6.5);
```

---

## 5. Transacciones Explícitas

| Archivo | Método | Operaciones atómicas |
|---------|--------|---------------------|
| `app/Services/RecorridoService.php` | `guardar()` | Crear recorrido |
| `app/Services/ProgresoService.php` | `marcarVisitado()` | Insertar + triggers (puntaje + total_visitas) |
| `app/Http/Controllers/Auth/RegisterController.php` | `register()` | Crear User + UserProfile |
| `app/Http/Controllers/Admin/RutaAdminController.php` | `update()` | Set @current_user_id para trigger auditoría |
| `app/Http/Controllers/Admin/RutaAdminController.php` | `destroy()` | Set @current_user_id para trigger auditoría |

---

## 6. Nueva Tabla

**`auditoria_rutas`**: Almacena el historial de cambios en la tabla `rutas`.

| Columna | Tipo | Descripción |
|---------|------|-------------|
| id | BIGINT UNSIGNED PK | Auto-increment |
| ruta_id | BIGINT UNSIGNED NOT NULL | Ruta modificada |
| accion | ENUM('update','delete') | Tipo de operación |
| datos_anteriores | JSON | Estado completo de la fila antes del cambio |
| usuario_id | BIGINT UNSIGNED NULL | Usuario que realizó el cambio |
| fecha_evento | TIMESTAMP | Momento del cambio |

---

## 7. Nueva Columna

**`destinos.total_visitas`** (INT NOT NULL DEFAULT 0): Contador desnormalizado que se actualiza automáticamente vía trigger `trg_incrementar_total_visitas_destino` cuando se inserta un registro en `lugares_visitados`.

---

## Comandos de Verificación Rápida

```bash
# 1. Migrar y sembrar
php artisan migrate:fresh --seed

# 2. Verificar triggers
php artisan tinker --execute="DB::select('SHOW TRIGGERS')"

# 3. Verificar SPs
php artisan tinker --execute="DB::select('SHOW PROCEDURE STATUS WHERE Db = DATABASE()')"

# 4. Verificar CHECK constraints
php artisan tinker --execute="DB::select(\"
  SELECT CONSTRAINT_NAME, TABLE_NAME
  FROM information_schema.TABLE_CONSTRAINTS
  WHERE TABLE_SCHEMA = DATABASE() AND CONSTRAINT_TYPE = 'CHECK'
\")"

# 5. Probar sp_resumen_general_sistema
php artisan tinker --execute="DB::select('CALL sp_resumen_general_sistema()')"

# 6. Ejecutar tests (SQLite - no prueba triggers/SPs)
php artisan test
```
