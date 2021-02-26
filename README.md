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

    http://localhost:8000/api/product/
    http://localhost:8000/api/product/1
    ...

    http://localhost:8000/api/category/
    http://localhost:8000/api/category/1
    ...
