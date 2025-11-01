# Prueba Técnica – Desarrollador/a de Software Jr (CRUD + SOLID + Consumo de API)

**Nombre del candidato:** Fernando Gil  
**ID de usuario para el API:** P01LAH  
**Framework:** Laravel 12  
**PHP:** 8.4.1  
**Base de datos:** MySQL/PostgreSQL/SQLite

---

## 📋 Tabla de Contenidos

-   [Descripción del Proyecto](#descripción-del-proyecto)
-   [Requisitos del Sistema](#requisitos-del-sistema)
-   [Instalación y Configuración](#instalación-y-configuración)
-   [Variables de Entorno](#variables-de-entorno)
-   [Uso de la Aplicación](#uso-de-la-aplicación)
-   [Endpoints de la API](#endpoints-de-la-api)
-   [Arquitectura y Principios SOLID](#arquitectura-y-principios-solid)
-   [Esquema de Base de Datos](#esquema-de-base-de-datos)
-   [Consultas SQL Utilizadas](#consultas-sql-utilizadas)
-   [Pruebas](#pruebas)
-   [Estructura del Proyecto](#estructura-del-proyecto)

---

## 📝 Descripción del Proyecto

Aplicación CRUD desarrollada con Laravel que consume una API externa para obtener resultados, los almacena en base de datos y ejecuta un proceso de "mejora" que convierte resultados con categoría "bad" a "medium" o "good" mediante reintentos controlados al API.

### Funcionalidades Principales

1. **Carga Inicial**: Consume el API hasta obtener 100 respuestas y las almacena en base de datos
2. **Barridos de Mejora**: Identifica registros "bad" y los reintenta hasta obtener "medium" o "good"
3. **CRUD Completo**: Endpoints para crear, leer, actualizar y eliminar resultados
4. **Reportes**: Genera reportes detallados con métricas y estadísticas

---

## 🔧 Requisitos del Sistema

-   PHP >= 8.4.1
-   Composer
-   MySQL/PostgreSQL/SQLite
-   Laravel 12
-   Extensiones PHP: PDO, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath

---

## 🚀 Instalación y Configuración

### 1. Clonar el Repositorio

```bash
git clone https://github.com/fer-gc05/PruebaTecnica3DM
cd PruebaTecnica3DM
```

### 2. Instalar Dependencias

```bash
composer install
npm install
```

### 3. Configurar Variables de Entorno

Copia el archivo `.env.example` a `.env`:

```bash
cp .env.example .env
```

Genera la clave de aplicación:

```bash
php artisan key:generate
```

### 4. Configurar Base de Datos

Edita el archivo `.env` con tus credenciales de base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prueba_tecnica_3dm
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

### 5. Ejecutar Migraciones

```bash
php artisan migrate
```

### 6. Iniciar el Servidor

```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

---

## 🔐 Variables de Entorno

Agrega las siguientes variables en tu archivo `.env`:

```env
# API Configuration
API_BASE_URL=https://4advance.co/testapi/get.php
API_USER_ID=P01LAH

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prueba_tecnica_3dm
DB_USERNAME=root
DB_PASSWORD=

# Application
APP_NAME="Prueba Técnica 3DM"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000
```

---

## 📖 Uso de la Aplicación

### 1. Realizar Carga Inicial (100 llamadas)

Consume el API hasta obtener 100 respuestas y las guarda en la base de datos:

```bash
# Usando cURL
curl http://localhost:8000/api/perform-init-load

# O usando Postman
GET http://localhost:8000/api/perform-init-load
```

**Respuesta esperada:**

```json
{
    "total_calls": 100,
    "metrics": {
        "total_results": 100,
        "bads": 35,
        "mediums": 40,
        "goods": 25
    }
}
```

### 2. Realizar Barridos de Mejora

Identifica registros con categoría "bad" y los reintenta hasta obtener "medium" o "good":

```bash
# Usando cURL
curl http://localhost:8000/api/perform-sweep

# O usando Postman
GET http://localhost:8000/api/perform-sweep
```

**Respuesta esperada:**

```json
{
    "sweeps": 5,
    "sweep_calls": 35,
    "remaining_bads": 0,
    "all_bads_eliminated": true,
    "metrics": {
        "total_results": 100,
        "bads": 0,
        "mediums": 65,
        "goods": 35
    }
}
```

**Nota:** El proceso se repite hasta que no existan registros "bad" o hasta alcanzar límites de seguridad.

### 3. Generar Reporte Final

Genera un reporte completo con métricas y estadísticas:

```bash
curl http://localhost:8000/api/generate-report
```

---

## 🌐 Endpoints de la API

### Endpoints de Procesos

| Método | Endpoint                 | Descripción                             |
| ------ | ------------------------ | --------------------------------------- |
| GET    | `/api/perform-init-load` | Realiza carga inicial de 100 resultados |
| GET    | `/api/perform-sweep`     | Ejecuta barridos de mejora              |
| GET    | `/api/generate-report`   | Genera reporte final con métricas       |

### Endpoints CRUD

| Método | Endpoint            | Descripción                      |
| ------ | ------------------- | -------------------------------- |
| GET    | `/api/results`      | Lista todos los resultados       |
| GET    | `/api/results/{id}` | Obtiene un resultado por ID      |
| POST   | `/api/results`      | Crea un nuevo resultado          |
| PUT    | `/api/results/{id}` | Actualiza un resultado existente |
| DELETE | `/api/results/{id}` | Elimina un resultado             |

### Ejemplos de Uso con cURL

#### Listar todos los resultados

```bash
curl http://localhost:8000/api/results
```

#### Obtener un resultado por ID

```bash
curl http://localhost:8000/api/results/1
```

#### Crear un nuevo resultado

```bash
curl -X POST http://localhost:8000/api/results \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "user_id": "P01LAH",
    "value": 75,
    "category": "medium",
    "timestamp": "2025-01-01 12:00:00",
    "ip_address": "192.168.1.1",
    "attempts": 1
  }'
```

#### Actualizar un resultado

```bash
curl -X PUT http://localhost:8000/api/results/1 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "value": 85,
    "category": "good",
    "attempts": 2
  }'
```

#### Eliminar un resultado

```bash
curl -X DELETE http://localhost:8000/api/results/1
```

### Ejemplos de Uso con Postman

Importa la colección de Postman incluida en el proyecto:

1. Abre Postman
2. Click en "Import"
3. Selecciona `PruebaTecnica3DM.postman_collection.json`
4. Selecciona `PruebaTecnica3DM.postman_environment.json` (opcional)

La colección incluye todos los endpoints preconfigurados con ejemplos.

---

## 🏗️ Arquitectura y Principios SOLID

### Arquitectura en Capas

La aplicación sigue una arquitectura en capas que separa responsabilidades:

```
┌─────────────────────────────────────────┐
│         Controllers (HTTP Layer)        │
│  - ApiController                        │
│  - ResultController                     │
└─────────────────┬───────────────────────┘
                  │
┌─────────────────▼───────────────────────┐
│         Services (Business Logic)       │
│  - ImprovementService                   │
│  - ResultServices                       │
│  - ReportService                        │
│  - ApiService                           │
└─────────────────┬───────────────────────┘
                  │
┌─────────────────▼───────────────────────┐
│      Repositories (Data Access)         │
│  - ResultRepository                     │
│  - Contracts (Interfaces)                │
└─────────────────┬───────────────────────┘
                  │
┌─────────────────▼───────────────────────┐
│         Models (Eloquent ORM)           │
│  - Result                                │
└─────────────────────────────────────────┘
```

### Aplicación de Principios SOLID

#### 1. Single Responsibility Principle (SRP)

Cada clase tiene una única responsabilidad:

-   **ApiService**: Única responsabilidad de consumir el API externo
-   **ImprovementService**: Única responsabilidad de orquestar los barridos de mejora
-   **ResultServices**: Única responsabilidad de gestionar la lógica de negocio de resultados
-   **ReportService**: Única responsabilidad de generar reportes y métricas
-   **ResultRepository**: Única responsabilidad de acceso a datos

#### 2. Open/Closed Principle (OCP)

-   Las interfaces (`ResultRepository`, `ApiClientRepository`) permiten extender funcionalidad sin modificar código existente
-   La inyección de dependencias permite cambiar implementaciones sin afectar consumidores

#### 3. Liskov Substitution Principle (LSP)

-   `ResultRepository` implementa `ResultRepository` interface de forma intercambiable
-   `ApiService` implementa `ApiClientRepository` permitiendo sustitución

#### 4. Interface Segregation Principle (ISP)

-   `ResultRepository` interface define métodos específicos y enfocados
-   `ApiClientRepository` interface minimalista con solo los métodos necesarios

#### 5. Dependency Inversion Principle (DIP)

-   Los servicios dependen de interfaces (Repository contracts) no de implementaciones concretas
-   Dependency Injection en constructores permite fácil testing y mantenimiento

### Ejemplo de Inyección de Dependencias

```php
class ImprovementService
{
    public function __construct(
        protected ResultServices $resultServices,
        protected ApiService $apiService
    ) {
    }
}
```

---

## 💾 Esquema de Base de Datos

### Tabla: `results`

| Columna      | Tipo                          | Descripción                                |
| ------------ | ----------------------------- | ------------------------------------------ |
| `id`         | BIGINT UNSIGNED               | Clave primaria autoincremental             |
| `user_id`    | VARCHAR(255)                  | ID del usuario para el API                 |
| `value`      | INTEGER                       | Valor del resultado (0-100)                |
| `category`   | ENUM('bad', 'medium', 'good') | Categoría del resultado                    |
| `timestamp`  | VARCHAR(255)                  | Timestamp del resultado original           |
| `ip_address` | VARCHAR(255)                  | IP address del resultado                   |
| `attempts`   | INTEGER                       | Número de intentos realizados (default: 1) |
| `created_at` | TIMESTAMP                     | Fecha de creación                          |
| `updated_at` | TIMESTAMP                     | Fecha de actualización                     |

### DDL Completo

```sql
CREATE TABLE results (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(255) NOT NULL,
    value INTEGER NOT NULL,
    category ENUM('bad', 'medium', 'good') NOT NULL,
    timestamp VARCHAR(255) NOT NULL,
    ip_address VARCHAR(255) NOT NULL,
    attempts INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Índices

-   `id`: Clave primaria (índice automático)
-   `category`: Índice implícito en ENUM para búsquedas rápidas
-   `attempts`: Permite filtrado eficiente por número de intentos

---

## 📊 Consultas SQL Utilizadas

### 1. Obtener Total de Resultados

```sql
SELECT COUNT(*) as total_results FROM results;
```

### 2. Distribución por Categoría

```sql
SELECT
    category,
    COUNT(*) as count,
    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM results)), 2) as percentage
FROM results
GROUP BY category;
```

### 3. Obtener Resultados Bad

```sql
SELECT * FROM results WHERE category = 'bad';
```

### 4. Métricas Detalladas con Estadísticas

```sql
SELECT
    COUNT(*) as total_results,
    SUM(CASE WHEN category = 'bad' THEN 1 ELSE 0 END) as bads,
    SUM(CASE WHEN category = 'medium' THEN 1 ELSE 0 END) as mediums,
    SUM(CASE WHEN category = 'good' THEN 1 ELSE 0 END) as goods,
    AVG(attempts) as avg_attempts,
    MAX(attempts) as max_attempts,
    MIN(attempts) as min_attempts,
    SUM(attempts) as total_attempts
FROM results;
```

### 5. Promedio de Intentos para Conversión

```sql
SELECT AVG(attempts) as avg_attempts
FROM results
WHERE category != 'bad' AND attempts > 1;
```

### 6. Estimar Llamadas Iniciales

```sql
SELECT COUNT(*) as initial_calls
FROM results
WHERE attempts = 1;
```

### 7. Estimar Llamadas de Mejora

```sql
SELECT SUM(attempts - 1) as improvement_calls
FROM results
WHERE attempts > 1;
```

### 8. Resultados Mejorados

```sql
SELECT COUNT(*) as results_improved
FROM results
WHERE attempts > 1;
```

---

## 🧪 Pruebas

### Ejecutar Tests

```bash
php artisan test
```

### Ejecutar Tests Específicos

```bash
# Ejecutar tests de un archivo específico
php artisan test tests/Feature/ExampleTest.php

# Ejecutar tests con filtro
php artisan test --filter=testName
```

---

## 📁 Estructura del Proyecto

```
PruebaTecnica3DM/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ApiController.php          # Endpoints de procesos
│   │   │   └── ResultController.php      # Endpoints CRUD
│   │   └── Requests/
│   │       └── Results/
│   │           ├── StoreResult.php        # Validación para crear
│   │           └── UpdateResult.php      # Validación para actualizar
│   ├── Models/
│   │   └── Result.php                     # Modelo Eloquent
│   ├── Repositories/
│   │   ├── Contracts/
│   │   │   ├── ApiClientRepository.php    # Interfaz cliente API
│   │   │   └── ResultRepository.php      # Interfaz repositorio
│   │   └── ResultRepository.php          # Implementación repositorio
│   └── Services/
│       ├── ApiService.php                 # Servicio de consumo API
│       ├── ImprovementService.php         # Servicio de barridos
│       ├── ReportService.php              # Servicio de reportes
│       └── ResultServices.php             # Servicio de lógica de negocio
├── database/
│   └── migrations/
│       └── 2025_10_31_192959_create_results_table.php
├── routes/
│   └── api.php                             # Rutas de la API
├── tests/
│   ├── Feature/                           # Tests de características
│   └── Unit/                               # Tests unitarios
├── .env.example                           # Ejemplo de variables de entorno
├── PruebaTecnica3DM.postman_collection.json  # Colección Postman
└── README.md                               # Este archivo
```

---

## 🔄 Proceso de Mejora

El proceso de mejora funciona de la siguiente manera:

1. **Identificación**: Se identifican todos los registros con `category = 'bad'`
2. **Reintento Individual**: Para cada registro "bad", se hace una llamada individual al API
3. **Validación**: Si el nuevo resultado es "medium" o "good", se actualiza el registro
4. **Seguimiento**: Se incrementa el contador de `attempts` en cada intento
5. **Repetición**: El proceso se repite hasta que no queden registros "bad"

### Manejo de Errores

-   **Timeouts**: Configurados en 5 segundos por llamada
-   **Reintentos**: Controlados para evitar ráfagas excesivas
-   **Pausas**: `usleep(100000)` (0.1 segundos) entre cada llamada individual
-   **Transacciones**: Operaciones de actualización masiva usan transacciones DB

---

## 📈 Reporte Final

El endpoint `/api/generate-report` genera un reporte completo con:

-   **Resumen de Ejecuciones**:

    -   Llamadas iniciales
    -   Llamadas de mejora
    -   Total de llamadas
    -   Barridos realizados

-   **Distribución Final**:

    -   Total de resultados
    -   Cantidad y porcentaje por categoría

-   **Estadísticas de Intentos**:
    -   Promedio de intentos
    -   Máximo y mínimo de intentos
    -   Total de intentos
    -   Promedio de intentos para convertir "bad"

---

## 🛠️ Tecnologías Utilizadas

-   **Framework**: Laravel 12
-   **PHP**: 8.4.1
-   **Base de Datos**: MySQL/PostgreSQL/SQLite
-   **ORM**: Eloquent
-   **HTTP Client**: Laravel HTTP Client (Guzzle)
-   **Testing**: Pest PHP
-   **Code Style**: Laravel Pint

---

## 👤 Autor

**Fernando Gil**  
ID de Usuario API: P01LAH

---
