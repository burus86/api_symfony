# api_symfony
API Rest realizada en Symfony 4.4 (LTS) para gestión de productos y categorías.

### 1.  Instalar dependencias

    composer install

### 2.  Base de datos
Importar el script SQL correspondiente al fichero "/migrations/db_api_symfony-full.sql" incluido dentro del proyecto, para crear la base de datos "db_api_symfony" con las tablas: category, product.

### 3.  Actualizar variables de entorno
Renombrar el fichero **`.env.dist`** con el nombre **`.env`** y editar los datos de conexión a la base de datos en MySQL.

### 4.  Iniciar servidor de symfony
Ejecutar por consola los comandos:

    cd api_symfony/
    symfony server:start

Para más información, consultar la guía oficial: https://symfony.com/doc/4.4/setup/symfony_server.html

### 5.  Probar métodos de la API
Acceder a la URL http://localhost:8000/ para ver todos los métodos definidos en la API. 

**Métodos de la entidad Category:**

    [GET]       /api/v1/categories/
    [POST]      /api/v1/categories/
    [GET]       /api/v1/categories/3
    [PUT]       /api/v1/categories/3
    [DELETE]    /api/v1/categories/3

**Métodos de la entidad Product:**

    [GET]       /api/v1/products/
    [POST]      /api/v1/products/
    [GET]       /api/v1/products/12
    [PUT]       /api/v1/products/12
    [DELETE]    /api/v1/products/12
    [GET]       /api/v1/products/12/delete
    [GET]       /api/v1/products/featured
    [GET]       /api/v1/products/featured?currency=EUR
    [GET]       /api/v1/products/featured?currency=USD

Descargar el programa **`Insomnia Core`** desde https://insomnia.rest/download/ y posteriormente importar el fichero JSON **`/examples_api.json`** que se incluye con el proyecto, que trae un ejemplo de cada petición añadida a la API.
