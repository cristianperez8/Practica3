<?php
// Conexión a la base de datos
$host = 'localhost';
$db = 'practica3';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $alias = $_POST['alias'];
        $password = $_POST['password'];
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];

        // Validar que el alias no exista
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE alias = ?");
        $stmt->execute([$alias]);
        if ($stmt->fetchColumn() > 0) {
            echo "El alias ya está en uso.";
        } else {
            // Insertar nuevo usuario
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO usuario (alias, password, nombre, apellidos, fecha_nacimiento) 
                                   VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$alias, $hashed_password, $nombre, $apellidos, $fecha_nacimiento]);
            echo "Usuario registrado exitosamente.";
        }
    }
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
