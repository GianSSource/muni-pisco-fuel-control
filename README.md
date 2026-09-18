# Proyecto MUNIPISCO2 — Sistema de Control de Combustible

Sistema web para el control de ingresos y despachos (vales) de combustible de
una flota municipal. PHP puro con arquitectura MVC casera (sin framework) y
MySQL.

## Requisitos

- PHP 8.0 o superior (usa `declare(strict_types=1)`, `never`, propiedades
  tipadas)
- MySQL 5.7+ o MariaDB 10.3+ (usa `CHECK`, triggers, `ENUM`)
- Servidor web con `mod_rewrite` (Apache/XAMPP/Laragon) o el servidor
  embebido de PHP

## Instalación

### 1. Base de datos

Crea la base de datos ejecutando el script completo:

```bash
mysql -u root -p < database.sql
```

Esto crea la base `tecnosoluciones`, todas las tablas (`users`,
`system_catalogs`, `vehicles`, `fuel_incomes`, `fuel_deliveries`), los
triggers que mantienen `gallons_remaining` sincronizado automáticamente, y
algunos datos de catálogo y demostración.

> **¿Ya tienes una base de datos anterior con información real?** No vuelvas
> a correr `database.sql` completo (borraría/recrearía tablas). En vez de
> eso aplica solo los cambios nuevos:
> ```sql
> CREATE TABLE IF NOT EXISTS vehicles (
>     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
>     unit_code VARCHAR(40) NOT NULL UNIQUE,
>     plate VARCHAR(20) NOT NULL UNIQUE,
>     area_id INT UNSIGNED NULL,
>     status ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',
>     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
>     FOREIGN KEY (area_id) REFERENCES system_catalogs(id) ON DELETE SET NULL
> ) ENGINE=InnoDB;
>
> ALTER TABLE fuel_deliveries ADD COLUMN vehicle_id INT UNSIGNED NULL AFTER user_id;
> ALTER TABLE fuel_deliveries ADD CONSTRAINT fk_deliveries_vehicle
>     FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL;
>
> ALTER TABLE fuel_incomes ADD COLUMN invoice_number VARCHAR(40) NULL AFTER receipt_number;
> ```

### 2. Configuración

Edita `config/config.php` con tus credenciales de MySQL:

```php
const DB_HOST = 'localhost';
const DB_NAME = 'tecnosoluciones';
const DB_USER = 'root';
const DB_PASS = '';
```

### 3. Servidor web

**Con XAMPP/Laragon:** copia esta carpeta a `htdocs`/`www` y apunta el
document root a la subcarpeta `public/`.

**Con el servidor embebido de PHP** (rápido para probar):

```bash
cd public
php -S localhost:8000
```

Luego abre `http://localhost:8000`.

### 4. Primer usuario administrador

El registro público (`/registro`) **siempre crea cuentas con rol
`operador`**, a propósito — así nadie puede autoasignarse permisos de
administrador. Para tener el primer admin, insértalo directo en la base de
datos:

```sql
INSERT INTO users (name, email, password_hash, role, created_at)
VALUES (
    'Tu Nombre',
    'tuemail@muni.gob.pe',
    '$2b$10$EQ15uOuZs1KZJu8GIHYcjORiOfNsYV7/NmvSFiXYI1eKekXlYa33K',
    'admin',
    NOW()
);
```

Ese hash corresponde a la contraseña `CambiaEstaClave123!` — inicia sesión
con ella y **cámbiala cuanto antes**. Una vez dentro con ese primer admin,
ya puedes crear el resto de usuarios (y elegir su rol libremente) desde
**Usuarios** en el menú, sin volver a tocar SQL.

### 5. Registrar vehículos

Antes de poder emitir un vale de despacho, entra a **Catálogos → Registrar
Vehículo/Unidad** y da de alta al menos un vehículo (código de unidad,
placa, área). El formulario de despacho toma los vehículos de esta lista,
no acepta placas escritas a mano.

## Estructura del proyecto

```
app/
  Controllers/   Un controlador por sección (Auth, Dashboard, Fuel, Income,
                 Calendar, Admin, User, Report)
  Core/          Router, Database (PDO) y Controller base (auth, roles, CSRF)
  Models/        Acceso a datos: User, Fuel, Catalog, Vehicle
  Views/         Vistas PHP planas, organizadas por sección
  Services/      Generación de PDF de reportes
config/
  config.php     Constantes de conexión y app
public/
  index.php      Punto de entrada único, define todas las rutas
database.sql     Esquema completo + triggers + datos iniciales
```

## Roles de usuario

| Rol | Puede |
|---|---|
| `operador` | Ver dashboard, registrar despachos, ver ingresos y calendario |
| `supervisor` | Todo lo de operador, más lo que dependa de `requireSupervisor()` |
| `admin` | Todo lo anterior, más: registrar ingresos de stock, gestionar catálogos (tipos de combustible, áreas, proveedores, vehículos) y gestionar usuarios/roles |

## Reglas de negocio clave

- `fuel_incomes.gallons_remaining` se mantiene sincronizado por triggers de
  base de datos, no por código PHP — no dupliques esa lógica en un
  controlador o el saldo se descontará dos veces.
- Un vale (`fuel_deliveries`) puede repartirse entre **varios lotes**
  (`fuel_incomes`) cuando ninguno alcanza solo. El reparto lo calcula
  `Fuel::planFifoAllocation()` (siempre el lote más antiguo primero) y
  cada lote usado queda registrado como una fila en
  `fuel_delivery_lot_allocations`. Esa tabla es la fuente real de verdad
  del descuento de stock; `fuel_deliveries.fuel_income_id` solo se llena
  como referencia rápida cuando el vale usó un único lote (si usó 2+,
  queda en `NULL`).
- El stock total de un tipo de combustible sí es un límite real: si ni
  sumando todos los lotes alcanza, `store()` rechaza la operación antes
  de insertar nada.
- Anular un vale (`FuelController::anular()`) borra sus filas de
  `fuel_delivery_lot_allocations` (lo que repone el saldo lote por lote
  vía trigger) y luego marca el vale como `Anulado`.
- Un `fuel_income_id` que no existe ya no crea stock automáticamente — la
  operación se rechaza para no perder trazabilidad de origen.
