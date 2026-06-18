# 🔧 Flowity.iq API - Backend Laravel

API RESTful completa para el sistema de gestión empresarial Flowity.iq, proporcionando servicios de autenticación, gestión de ventas, inventario, recursos y análisis de datos.

## 📋 Propósito Funcional

El backend de Flowity.iq es el núcleo de procesamiento de datos que:

- **🔐 Gestiona Autenticación**: Sistema de login con Laravel Sanctum para tokens seguros
- **💼 Procesa Ventas**: CRUD completo de ventas con soporte para múltiples tipos de items (productos, servicios, reservas, encargos)
- **📊 Genera Analíticas**: Dashboard con KPIs calculados dinámicamente, historial de ventas y estadísticas de stock
- **📦 Controla Inventario**: Gestión de productos con alertas de stock bajo/crítico
- **🗂️ Cataloga Recursos**: CRUD de productos, servicios, reservas y encargos
- **🚚 Gestiona Proveedores**: Registro y seguimiento de proveedores
- **👤 Administra Usuarios**: Sistema de usuarios con roles

## 🔧 Requisitos Previos

- **PHP** 8.2 o superior
- **Composer** 2.x o superior
- **Node.js** 20.x (para assets con Vite)
- **Base de Datos**: MySQL 8.0+ o PostgreSQL 14+
- **Extensiones PHP**: `pdo`, `mbstring`, `openssl`, `json`, `tokenizer`, `xml`

## 🚀 Instalación Paso a Paso

### 1. Clonar y navegar al proyecto
```powershell
cd c:\Users\rpard\FlowityIQ\flowity.iq_api
```

### 2. Instalar dependencias PHP
```powershell
composer install
```

### 3. Configurar entorno
```powershell
# Copiar archivo de ejemplo
copy .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

Editar `.env` con configuración de base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flowity_iq
DB_USERNAME=root
DB_PASSWORD=tu_password
```

### 4. Instalar dependencias Node (opcional, para assets)
```powershell
npm install
```

### 5. Ejecutar migraciones
```powershell
php artisan migrate
```

### 6. Ejecutar seeders (datos de prueba)
```powershell
php artisan db:seed
```

### 7. Iniciar servidor
```powershell
php artisan serve
```

La API estará disponible en: **http://localhost:8000**

## ⚡ Comandos Útiles

### Artisan (CLI de Laravel)
| Comando | Descripción |
|---------|-------------|
| `php artisan serve` | Inicia servidor de desarrollo |
| `php artisan migrate` | Ejecuta migraciones pendientes |
| `php artisan migrate:rollback` | Revierte últimas migraciones |
| `php artisan migrate:fresh --seed` | Recrea BD desde cero con seeders |
| `php artisan db:seed` | Ejecuta seeders |
| `php artisan make:model Nombre -m` | Crea modelo con migración |
| `php artisan make:controller NombreController` | Crea controlador |
| `php artisan route:list` | Lista todas las rutas |
| `php artisan cache:clear` | Limpia caché de aplicación |
| `php artisan config:clear` | Limpia caché de configuración |
| `php artisan optimize` | Optimiza la aplicación |

### Composer
```powershell
# Actualizar dependencias
composer update

# Autoload optimizado
composer dump-autoload -o

# Instalación de paquete
composer require vendor/paquete
```

### Testing
```powershell
# Ejecutar tests
php artisan test

# Con cobertura
php artisan test --coverage
```

### Base de Datos
```powershell
# Crear migración
php artisan make:migration create_nombre_tabla

# Resetear y re-migrar
php artisan migrate:fresh

# Seed específico
php artisan db:seed --class=NombreSeeder
```

## 📁 Estructura de Archivos

```
flowity.iq_api/
├── 📁 app/                          # Código fuente principal
│   ├── 📁 Http/
│   │   └── 📁 Controllers/          # Controladores API
│   │       ├── 📄 DashboardController.php    # KPIs y estadísticas
│   │       ├── 📄 VentaController.php         # Gestión de ventas
│   │       ├── 📄 ProductoController.php      # CRUD productos
│   │       ├── 📄 ServicioController.php      # CRUD servicios
│   │       ├── 📄 ReservaController.php       # CRUD reservas
│   │       ├── 📄 EncargoController.php       # CRUD encargos
│   │       ├── 📄 ProveedorController.php     # CRUD proveedores
│   │       └── 📄 AuthController.php          # Autenticación
│   │
│   ├── 📁 Models/                   # Modelos Eloquent
│   │   ├── 📄 Venta.php             # Modelo principal de ventas
│   │   ├── 📄 Producto.php          # Productos con stock
│   │   ├── 📄 ProductoVenta.php     # Relación N:M ventas-productos
│   │   ├── 📄 Servicio.php          # Servicios
│   │   ├── 📄 ServicioVenta.php     # Relación N:M ventas-servicios
│   │   ├── 📄 Reserva.php           # Reservas
│   │   ├── 📄 ReservaVenta.php      # Relación N:M ventas-reservas
│   │   ├── 📄 Encargo.php           # Encargos recurrentes
│   │   ├── 📄 EncargoVenta.php      # Relación N:M ventas-encargos
│   │   ├── 📄 Proveedor.php         # Proveedores
│   │   ├── 📄 Suscripcion.php       # Suscripciones de clientes
│   │   └── 📄 User.php              # Usuarios con Sanctum
│   │
│   └── 📁 Providers/                # Proveedores de servicios
│
├── 📁 bootstrap/                    # Bootstrapping de la aplicación
├── 📁 config/                       # Archivos de configuración
│   ├── 📄 app.php                   # Configuración general
│   ├── 📄 auth.php                  # Configuración de autenticación
│   ├── 📄 database.php              # Configuración de BD
│   └── 📄 sanctum.php               # Configuración de API tokens
│
├── 📁 database/
│   ├── 📁 factories/                # Factories para datos de prueba
│   ├── 📁 migrations/               # Migraciones de esquema
│   │   ├── 📄 0001_01_01_000000_create_users_table.php
│   │   ├── 📄 2025_05_21_100000_create_proveedores_table.php
│   │   ├── 📄 2025_05_21_100001_create_productos_table.php
│   │   ├── 📄 2025_05_21_100002_create_ventas_table.php
│   │   ├── 📄 2026_05_27_201305_create_servicios_table.php
│   │   ├── 📄 2026_05_27_201322_create_reservas_table.php
│   │   ├── 📄 2026_05_27_201333_create_encargos_table.php
│   │   ├── 📄 2026_06_15_000004_create_producto_ventas_table.php
│   │   ├── 📄 2026_06_15_000005_create_servicio_ventas_table.php
│   │   ├── 📄 2026_06_15_000006_create_reserva_ventas_table.php
│   │   └── 📄 2026_06_15_000007_create_encargo_ventas_table.php
│   │
│   └── 📁 seeders/                  # Seeders para datos iniciales
│
├── 📁 routes/                       # Definición de rutas
│   ├── 📄 api.php                   # Rutas API (cargan controllers)
│   ├── 📄 web.php                   # Rutas web (no usadas en API)
│   └── 📄 console.php               # Comandos de consola
│
├── 📁 storage/                      # Almacenamiento (logs, caché, uploads)
├── 📁 tests/                        # Tests automatizados
├── 📁 vendor/                       # Dependencias Composer (no versionar)
├── 📄 .env                          # Variables de entorno (no versionar)
├── 📄 .env.example                  # Plantilla de variables
├── 📄 artisan                       # CLI de Laravel
├── 📄 composer.json                 # Dependencias PHP
└── 📄 phpunit.xml                   # Configuración de testing
```

## 🗄️ Modelo de Datos

### Entidades Principales

```
┌─────────────────┐     ┌──────────────────┐     ┌─────────────────┐
│    Ventas       │────<│  ProductoVenta   │>────│    Productos    │
├─────────────────┤     ├──────────────────┤     ├─────────────────┤
│ id              │     │ id               │     │ id              │
│ codigo          │     │ venta_id         │     │ nombre          │
│ cliente         │     │ producto_id     │     │ stock           │
│ total           │     │ cantidad         │     │ stock_minimo    │
│ estado          │     │ precio           │     │ precio          │
│ fecha           │     │ subtotal         │     │ proveedor_id    │
└─────────────────┘     └──────────────────┘     └─────────────────┘
         │
         │              ┌──────────────────┐     ┌─────────────────┐
         ├────────────<│  ServicioVenta     │>────│    Servicios    │
         │              ├──────────────────┤     ├─────────────────┤
         │              │ venta_id           │     │ id              │
         │              │ servicio_id       │     │ nombre          │
         │              │ precio            │     │ precio          │
         │              │ subtotal          │     │ descripcion     │
         │              └──────────────────┘     └─────────────────┘
         │
         │              ┌──────────────────┐     ┌─────────────────┐
         ├────────────<│  ReservaVenta      │>────│    Reservas     │
         │              ├──────────────────┤     ├─────────────────┤
         │              │ venta_id           │     │ id              │
         │              │ reserva_id        │     │ nombre          │
         │              │ precio            │     │ precio          │
         │              │ subtotal          │     │ descripcion     │
         │              └──────────────────┘     └─────────────────┘
         │
         │              ┌──────────────────┐     ┌─────────────────┐
         └────────────<│  EncargoVenta      │>────│    Encargos     │
                        ├──────────────────┤     ├─────────────────┤
                        │ venta_id           │     │ id              │
                        │ encargo_id        │     │ nombre          │
                        │ cantidad          │     │ producto_id    │
                        │ precio            │     │ dia_semana      │
                        │ subtotal          │     │ cantidad        │
                        └──────────────────┘     └─────────────────┘
```

## 🔌 Endpoints API Principales

### Autenticación
- `POST /api/login` - Iniciar sesión
- `POST /api/logout` - Cerrar sesión
- `GET /api/user` - Obtener usuario autenticado

### Ventas
- `GET /api/ventas` - Listar ventas (con filtros)
- `GET /api/ventas/{id}` - Detalle de venta con items
- `POST /api/ventas` - Crear venta
- `PUT /api/ventas/{id}` - Actualizar venta
- `DELETE /api/ventas/{id}` - Eliminar venta
- `GET /api/ventas/estadisticas` - Estadísticas de ventas

### Dashboard
- `GET /api/dashboard` - KPIs y datos del dashboard

### Recursos (Productos, Servicios, Reservas, Encargos)
- `GET /api/productos` / `/api/servicios` / `/api/reservas` / `/api/encargos`
- `POST`, `PUT`, `DELETE` - CRUD completo

### Proveedores
- `GET /api/proveedores` - Listar
- `POST`, `PUT`, `DELETE` - CRUD

## 🔐 Autenticación

La API usa **Laravel Sanctum** para autenticación con tokens:

```bash
# Login devuelve token
POST /api/login
{
  "email": "admin@flowity.iq",
  "password": "password"
}

# Respuesta
{
  "token": "1|laravel_sanctum_token...",
  "user": { ... }
}

# Usar token en headers
Authorization: Bearer {token}
```

## 🐛 Troubleshooting

### Error: "No such file or directory"
- Verificar que estás en el directorio correcto
- Ejecutar `composer install` si falta la carpeta `vendor`

### Error: "Access denied for user"
- Verificar credenciales en archivo `.env`
- Crear base de datos manualmente si no existe

### Error: "Class not found"
- Ejecutar `composer dump-autoload`
- Verificar namespace del archivo

### Problemas de caché
```powershell
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📄 Licencia

Proyecto privado - Flowity.iq © 2026
