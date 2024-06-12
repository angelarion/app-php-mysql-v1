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
