crear una app con docker de ABM en php y mysql 

ChatGPT
Para crear una aplicación de ABM (Alta, Baja, Modificación) en PHP con MySQL utilizando Docker, seguiremos estos pasos:
1.	Crear la estructura de directorios y archivos necesarios.
2.	Escribir el código de la aplicación PHP.
3.	Definir los servicios en el archivo docker-compose.yml.
4.	Construir y ejecutar los contenedores.
Paso 1: Crear la estructura de directorios y archivos
Primero, crea una estructura de directorios como la siguiente:
arduino
Copiar código
my_app/
├── docker-compose.yml
├── php/
│   ├── Dockerfile
│   ├── index.php
│   └── config.php
└── mysql/
    └── init.sql
Paso 2: Escribir el código de la aplicación PHP
php/index.php
Este archivo contendrá la lógica básica para el ABM en PHP.
php
Copiar código
<?php
$servername = "mysql";
$username = "example_user";
$password = "example_password";
$dbname = "example_db";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejar inserción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $sql = "INSERT INTO users (name) VALUES ('$name')";
    if ($conn->query($sql) === TRUE) {
        echo "Nuevo registro creado con éxito";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Manejar eliminación
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $sql = "DELETE FROM users WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Registro eliminado con éxito";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Consultar datos
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>ABM PHP</title>
</head>
<body>
    <h1>ABM PHP</h1>
    <form method="post" action="">
        <input type="text" name="name" placeholder="Nombre">
        <button type="submit">Agregar</button>
    </form>
    <h2>Usuarios</h2>
    <ul>
        <?php while($row = $result->fetch_assoc()): ?>
            <li>
                <?php echo $row["name"]; ?>
                <a href="?delete=<?php echo $row["id"]; ?>">Eliminar</a>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>

<?php $conn->close(); ?>
php/config.php
Archivo de configuración para la base de datos.
php
Copiar código
<?php
define('DB_SERVER', 'mysql');
define('DB_USERNAME', 'example_user');
define('DB_PASSWORD', 'example_password');
define('DB_NAME', 'example_db');
?>
Paso 3: Crear el archivo de inicialización SQL
mysql/init.sql
Este archivo creará la base de datos y la tabla users.
sql
Copiar código
CREATE DATABASE IF NOT EXISTS example_db;
USE example_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);
Paso 4: Definir los servicios en el archivo docker-compose.yml
docker-compose.yml
yaml
Copiar código
version: '3.8'

services:
  php:
    build: ./php
    ports:
      - "80:80"
    volumes:
      - ./php:/var/www/html
    depends_on:
      - mysql

  mysql:
    image: mysql:latest
    environment:
      MYSQL_ROOT_PASSWORD: example
      MYSQL_DATABASE: example_db
      MYSQL_USER: example_user
      MYSQL_PASSWORD: example_password
    volumes:
      - ./mysql:/docker-entrypoint-initdb.d
    ports:
      - "3306:3306"
Paso 5: Crear el Dockerfile para PHP
php/Dockerfile
dockerfile
Copiar código
FROM php:7.4-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli

# Copiar el contenido al contenedor
COPY . /var/www/html

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html
Paso 6: Construir y ejecutar los contenedores
Navega al directorio my_app en tu terminal y ejecuta los siguientes comandos:
sh
Copiar código
docker-compose up --build
Esto construirá las imágenes de Docker y levantará los contenedores definidos en el archivo docker-compose.yml.
Conclusión
Ahora deberías poder acceder a tu aplicación ABM en PHP en tu navegador web en http://localhost y gestionar usuarios con operaciones de alta, baja y modificación (a través de inserción y eliminación).
Este ejemplo es una base simple que puedes expandir según tus necesidades, incluyendo validación de entrada, manejo de errores más robusto, y funcionalidad de edición de registro
