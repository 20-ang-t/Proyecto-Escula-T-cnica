<?php

$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "registro";
$conn = new mysqli($host, $usuario, $contrasena, $base_datos);
// Crear conexión


// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Recoger y sanitizar los datos del formulario
$tipo_mensaje = $conn->real_escape_string($_POST['tipo_mensaje']);
$nombre = $conn->real_escape_string($_POST['nombre']);
$email = $conn->real_escape_string($_POST['email']);
$telefono = isset($_POST['telefono']) ? $conn->real_escape_string($_POST['telefono']) : '';
$mensaje = $conn->real_escape_string($_POST['mensaje']);

// Preparar la consulta SQL
$sql = "INSERT INTO mensajes_contacto (tipo_mensaje, nombre, email, telefono, mensaje) 
        VALUES ('$tipo_mensaje', '$nombre', '$email', '$telefono', '$mensaje')";

// Ejecutar la consulta y verificar el resultado
if ($conn->query($sql) === TRUE) {
    // Envío exitoso
    echo json_encode(['success' => true, 'message' => 'Mensaje enviado con éxito.']);
} else {
    // Error en el envío
    echo json_encode(['success' => false, 'message' => 'Error al enviar el mensaje: ' . $conn->error]);
}

// Cerrar conexión
$conn->close();
?>