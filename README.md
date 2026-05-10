# Sistema Gestión de Pastelería

Sistema para la gestión de pasteles e ingredientes. Aplicación CRUD con relación maestro-detalle, API REST y frontend interactivo.
---

## Tabla de Contenidos

- [Tecnologías](#-tecnologías)
- [Características](#-características)
- [Instalación](#-instalación)
- [Configuración](#-configuración)
- [Ejecución](#-ejecución)
- [Endpoints API](#-endpoints-api)
- [Autor](#-autor)

---

## Tecnologías

| Tecnología | Versión | Uso |
|------------|---------|-----|
| **PHP** | 7.4+ | API REST backend |
| **MySQL** | 5.7+ | Base de datos relacional |
| **Vue.js** | 2.x | Frontend interactivo |
| **Axios** | 1.x | Cliente HTTP |
| **HTML5** | - | Estructura web |
| **CSS3** | - | Estilos y diseño responsive |
| **PDO** | - | Conexión segura a base de datos |

---

## Características

### Funcionalidades Principales

- **CRUD completo de Ingredientes**
  - Crear, leer, editar y eliminar ingredientes
  - Control de fechas de ingreso y vencimiento
  - Validación de duplicados

- **CRUD completo de Pasteles**
  - Crear, leer, editar y eliminar pasteles
  - Registro de chef/preparador
  - Control de fechas de creación y vencimiento

- **Relación Maestro-Detalle**
  - Asignar múltiples ingredientes a un pastel
  - Quitar ingredientes de un pastel
  - Prevención de duplicados

- **Reporte Integrado**
  - Visualización de todos los pasteles
  - Listado detallado de ingredientes por pastel
  - Indicador visual de productos vencidos

### Características Técnicas

-  **Transacciones SQL** para integridad referencial
- **API RESTful** con endpoints bien definidos
- **Diseño responsive** adaptable a móviles
- **Interfaz moderna** con tabs y modales
- **Feedback visual** con mensajes de éxito/error
-  **Eliminación en cascada** con `ON DELETE CASCADE`

---

## Instalación

Requisitos Previos
XAMPP (recomendado) / WAMP / LAMP

PHP 7.4 o superior

MySQL 5.7 o superior

Navegador web moderno (Chrome, Firefox, Edge)

Paso a Paso
1. Clonar o descargar el proyecto
# Clonar desde GitHub
git clone https://github.com/Guille-X/Pasteleria.git

# O descargar y extraer en la carpeta correspondiente

2. Ubicar el proyecto
Windows (XAMPP):

C:\xampp\htdocs\pasteleria\

Mac (XAMPP):
/Applications/XAMPP/htdocs/pasteleria/

3. Crear la base de datos
Abrir phpMyAdmin: http://localhost/phpmyadmin

## Crear base de datos: pasteleria

Seleccionar la base de datos

Ir a la pestaña Importar

Seleccionar database/script.sql

Hacer clic en Importar

## Configuración

Configurar credenciales de base de datos
Editar el archivo config/database.php:

class Database {
    private $host = "localhost";
    private $db_name = "pasteleria";
    private $username = "root";      // Tu usuario de MySQL
    private $password = "";          // Tu contraseña de MySQL
    public $conn;
    // ...
}

## Ejecución

Opción A: Usando XAMPP (recomendado)
Iniciar XAMPP Control Panel

Iniciar Apache y MySQL

Abrir navegador y acceder a:

http://localhost/pasteleria/index.html

Opción B: Usando servidor PHP integrado

# Navegar a la carpeta del proyecto
cd C:\xampp\htdocs\pasteleria

# Iniciar servidor
php -S localhost:8000

# Abrir navegador en:
http://localhost:8000/index.html

## Endpoints API

# Ingredientes (api/ingredientes.php)

Método	Endpoint	Descripción
GET	/api/ingredientes.php	Obtener todos los ingredientes
GET	/api/ingredientes.php?id={id}	Obtener un ingrediente
POST	/api/ingredientes.php	Crear nuevo ingrediente
PUT	/api/ingredientes.php	Actualizar ingrediente
DELETE	/api/ingredientes.php	Eliminar ingrediente

# Pasteles (api/pasteles.php)

Método	Endpoint	Descripción
GET	/api/pasteles.php	Obtener todos los pasteles
GET	/api/pasteles.php?id={id}	Obtener pastel + ingredientes
POST	/api/pasteles.php	Crear pastel con ingredientes
PUT	/api/pasteles.php	Actualizar pastel
DELETE	/api/pasteles.php	Eliminar pastel

# Relaciones (api/pastel_ingredientes.php)

Método	Endpoint	Descripción
GET	/api/pastel_ingredientes.php?pastel_id={id}	Ingredientes de un pastel
GET	/api/pastel_ingredientes.php?ingrediente_id={id}	Pasteles con un ingrediente
POST	/api/pastel_ingredientes.php	Asignar ingrediente a pastel
DELETE	/api/pastel_ingredientes.php	Quitar ingrediente de pastel

# Reporte (api/reporte.php)

Método	Endpoint	Descripción
GET	/api/reporte.php	Reporte completo

## Autor

Desarrollador: Byron Guillermo Martin A

Fecha: 10 de mayo de 2026


