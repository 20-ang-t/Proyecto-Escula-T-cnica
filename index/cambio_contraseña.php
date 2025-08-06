<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registro";


$conn = new mysqli($servername, $username, $password, $dbname);

/* Verificar si hay algún error en la conexión */
if ($conn->connect_error) {
    die("CONEXIÓN FALLIDA: " . $conn->connect_error);
}

// Verificar si los datos se enviaron desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password_actual = $_POST["password_actual"];
    $password_nueva = password_hash($_POST["password_nueva"], PASSWORD_DEFAULT);

    $sql = "SELECT password FROM usuarios WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        /* Verificar si la contraseña actual coincide con la de la base de datos */
        if (password_verify($password_actual, $row["password"])) {
            $sql = "UPDATE usuarios SET password='$password_nueva' WHERE email='$email'";

            if ($conn->query($sql) === TRUE) {
               
                      echo "<script>alert('Contraseña Actualizada Correctamente');window.location.href = 'Login.html'; </script>";
                   
            } else {
                
                      echo "<script>alert('ERROR al actualizar la contraseña');window.location.href = 'Login.html'; </script>";
            }
        } else {
            
            echo "<script>
                    alert('Contraseña INCORRECTA.');window.location.href = 'Login.html'; </script>";        
        }
    } else {
      
        echo "<script>
                alert('No se ha encontrado un usuario con este correo electrónico.');window.location.href = 'Login.html'; </script>";
    }
}


$conn->close();
?>
