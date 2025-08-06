<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "registro";
$conn = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $programa = $_POST["programa"];
    $correo = $_POST["correo"];
    $jornada = $_POST["jornada"];
    $celular = $_POST["celular"];
    $documento = $_POST["documento"];
    $acepto_terminos = isset($_POST["terminos"]) ? 1 : 0;

    $sql = "INSERT INTO inscripciones (nombre_completo, programa, correo, jornada, celular, documento, acepto_terminos)  
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $nombre, $programa, $correo, $jornada, $celular, $documento, $acepto_terminos);

    if ($stmt->execute()) {
        // Solo muestra un mensaje de éxito
            header("Location: Pag_prin.html");
            exit();
     
    } else {
        echo "Error al inscribirse: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>