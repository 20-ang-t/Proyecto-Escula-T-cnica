<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $user_name = $_POST['user_name'];
        $password = $_POST['password'];

        if (!empty($user_name) && !empty($password)) {
            $query = $pdo->prepare("SELECT * FROM usuarios WHERE user_name = :user_name");
            $query->execute([':user_name' => $user_name]);
            $user = $query->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_name'] = $user['user_name'];

                /* Redirige según la contraseña (se debe conocer la contraseña específica) */
                $secretaria_password = 'Adm¡n$5'; /* Cambiar esto por tu contraseña */
                
                if (password_verify($secretaria_password, $user['password'])) {
                    header("Location: admin.php");
                } else {
                    header("Location: Pag_prin.html");
                }
                exit();
            } else {
                echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href = 'Login.html';</script>";
            }
        }
    }


    /* REGISTRO */
    if (isset($_POST['register'])) {
        $user_name = $_POST['user_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (!empty($user_name) && !empty($email) && !empty($password)) {
            try {
                $password_encriptada = password_hash($password, PASSWORD_DEFAULT);
                $query = $pdo->prepare("INSERT INTO usuarios (user_name, email, password) VALUES (:user_name, :email, :password)");
                $query->execute([
                    ':user_name' => $user_name,
                    ':email' => $email,
                    ':password' => $password_encriptada
                ]);

                header("Location: Login.html");
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    echo "<script>alert('El usuario o el email ya están registrados'); window.location.href = 'Login.html';</script>";
                } else {
                    echo "Error en el registro: " . $e->getMessage();
                }
            }
        }
    }
}
?>
