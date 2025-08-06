<?php
session_start();

$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "registro";

$conn = new mysqli($host, $usuario, $contrasena, $base_datos);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
  
// Manejo de operaciones CRUD
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   /* Crear nueva inscripción */
    if (isset($_POST['nombre_completo']) && !isset($_POST['id'])) {
        $nombre = $conn->real_escape_string($_POST["nombre_completo"]);
        $programa = $conn->real_escape_string($_POST["programa"]);
        $correo = $conn->real_escape_string($_POST["correo"]);
        $jornada = $conn->real_escape_string($_POST["jornada"]);
        $celular = $conn->real_escape_string($_POST["celular"]);
        $documento = $conn->real_escape_string($_POST["documento"]);
        $acepto = isset($_POST["acepto_terminos"]) ? 1 : 0;

        $sql = "INSERT INTO inscripciones (nombre_completo, programa, correo, jornada, celular, documento, acepto_terminos) 
                VALUES ('$nombre', '$programa', '$correo', '$jornada', '$celular', '$documento', $acepto)";

        if ($conn->query($sql)) {
            $mensaje = "Registro creado exitosamente.";
        } else {
            $error = "Error al crear registro: " . $conn->error;
        }
    }
    
    /* Actualizar inscripción */
    elseif (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = intval($_POST['id']);
        $nombre = $conn->real_escape_string($_POST["nombre_completo"]);
        $programa = $conn->real_escape_string($_POST["programa"]);
        $correo = $conn->real_escape_string($_POST["correo"]);
        $jornada = $conn->real_escape_string($_POST["jornada"]);
        $celular = $conn->real_escape_string($_POST["celular"]);
        $documento = $conn->real_escape_string($_POST["documento"]);

        $sql = "UPDATE inscripciones SET 
                nombre_completo='$nombre', 
                programa='$programa', 
                correo='$correo', 
                jornada='$jornada', 
                celular='$celular', 
                documento='$documento' 
                WHERE id=$id";

        if ($conn->query($sql)) {
            header("Location: admin.php");
            exit();
        } else {
            $error = "Error al actualizar registro: " . $conn->error;
        }
    }
    
    /* Eliminar inscripción */
    elseif (isset($_POST['delete_id'])) {
        $id = intval($_POST['delete_id']);
        $sql = "DELETE FROM inscripciones WHERE id = $id";
        
        if ($conn->query($sql)) {
            header("Location: admin.php");
            exit();
        } else {
            $error = "Error al eliminar registro: " . $conn->error;
        }
    }
}

/* Mostrar formulario de edición si se solicita */
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM inscripciones WHERE id = $id");
    
    if ($result->num_rows === 0) {
        die("Registro no encontrado.");
    }
    
    $fila = $result->fetch_assoc();
    mostrarFormularioEdicion($fila);
    exit();
}

/* Obtener datos para mostrar en la tabla */ 
$resultado = $conn->query("SELECT * FROM inscripciones");
$total_usuarios = $conn->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()['total'];


function mostrarFormularioEdicion($fila) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">   
         <link rel="icon" type="image/png" href="../img/principal/icono.png" />
        <title>Editar Inscripción</title>
        <style>
            /* Estilos para el formulario de edición */
            body {
                font-family: "Times New Roman", serif;
                background-color: rgb(214, 219, 216);
                padding: 20px;
                padding-top: 100px;
            }
            
            form {
                background: rgba(255, 255, 255, 0.9);
                padding: 25px;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                max-width: 600px;
                margin: 20px auto;
            }
            
            input[type="text"], input[type="email"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            
            button {
                padding: 10px 20px;
                background-color: #797b7e;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: all 0.3s;
            }
            
            button:hover {
                background-color: #6b6d70;
            }
            
            a {
                display: inline-block;
                margin-left: 15px;
                padding: 10px 20px;
                background-color: #ccc;
                color: #333;
                text-decoration: none;
                border-radius: 5px;
                transition: all 0.3s;
            }
            
            a:hover {
                background-color: #bbb;
            }
        </style>
    </head>
    <body>
        <center><h2>Editar Inscripción</h2></center>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $fila['id'] ?>">
            <input type="text" name="nombre_completo" value="<?= htmlspecialchars($fila['nombre_completo']) ?>" required>
            <input type="text" name="programa" value="<?= htmlspecialchars($fila['programa']) ?>" required>
            <input type="email" name="correo" value="<?= htmlspecialchars($fila['correo']) ?>" required>
            <input type="text" name="jornada" value="<?= htmlspecialchars($fila['jornada']) ?>" required>
            <input type="text" name="celular" value="<?= htmlspecialchars($fila['celular']) ?>" required>
            <input type="text" name="documento" value="<?= htmlspecialchars($fila['documento']) ?>" required>
            <button type="submit">Guardar cambios</button>
            <a href="admin.php.php">Cancelar</a>
        </form>
    </body>
    </html>
    <?php
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Admin</title>    
    <link rel="icon" type="image/png" href="../img/principal/icono.png" />
    <style>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Times New Roman", serif;
        }

       
        body {
            position: relative;
            min-height: 100vh;
            background-color: rgb(214, 219, 216);
            padding: 20px;
            display: flex;
            flex-direction: column;
            padding-top: 100px; /* Espacio para el header fijo */
        }

        /* Estilo  Barra superior */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 25px 150px;
            background-color: rgb(41, 42, 40);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 99;
            color: #ffffff;
            height: 80px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        
        .magnum {
            font-size: 2.2em;
            color: #ffffff;
            user-select: none;
            margin-left: 20px;
            letter-spacing: 1px;
        }

      
        .navigation a {
            text-decoration: none;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 4px;
            transition: all 0.3s;
            background-color: rgba(255, 255, 255, 0.1);
            font-size: 1.1em;
            margin-right: 20px;
        }

        .navigation a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            transform: translateY(-2px);
        }

        
        .main-container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Mensajes de éxito/error */
        .mensaje {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #f5c6cb;
        }

        /* Formulario de inscripción */
        .form-container {
            margin-bottom: 30px;
        }

        .form-container form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-container label.checkbox {
            grid-column: span 2;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-container button {
            grid-column: span 2;
            padding: 10px;
            background-color: #797b7e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .form-container button:hover {
            background-color: #6b6d70;
        }

        /* Tabla de inscripciones */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e9e9e9;
        }

        /* Acciones en la tabla */
        .acciones {
            display: flex;
            gap: 10px;
        }

        .acciones a {
            padding: 5px 10px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .acciones a:hover {
            background-color: #2980b9;
        }

        .acciones form {
            display: inline;
        }

        .acciones button {
            padding: 5px 10px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .acciones button:hover {
            background-color: #c0392b;
        }


    </style>
</head>
<body>
    <header>
        <div class="magnum">Panel de Admin</div>
        <nav class="navigation">
            <a href="Login.html">Cerrar Sesión</a>
        </nav>
    </header>

    <div class="main-container">
        <h2>Bienvenid@, Admin</h2>
       <div class="circular-progress">
  <div class="circle">
    <svg class="circle-chart" viewBox="0 0 36 36">
      <path class="circle-bg"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
      <path class="circle-fill"
        stroke-dasharray="<?php echo min(100, $total_usuarios); ?>, 100"
        d="M18 2.0845
          a 15.9155 15.9155 0 0 1 0 31.831
          a 15.9155 15.9155 0 0 1 0 -31.831"
      />
    </svg>
    <div class="circle-info">
      <span class="circle-number"><?php echo $total_usuarios; ?></span>
      <span class="circle-label">Usuarios</span>
    </div>
  </div>
  <h3>Total de Registros</h3>
</div>

<style>
.circular-progress {
  text-align: center;
  max-width: 200px;
  margin: 20px auto;
  font-family: 'Arial', sans-serif;
}

.circle {
  position: relative;
  margin: 0 auto 15px;
  width: 120px;
  height: 120px;
}

.circle-chart {
  width: 100%;
  height: 100%;
}

.circle-bg {
  fill: none;
  stroke: #eee;
  stroke-width: 2;
}

.circle-fill {
  fill: none;
  stroke: #4CAF50;
  stroke-width: 2;
  stroke-linecap: round;
  animation: circle-fill-animation 1.5s ease-in-out;
}

@keyframes circle-fill-animation {
  0% { stroke-dasharray: 0, 100; }
}

.circle-info {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
}

.circle-number {
  display: block;
  font-size: 1.8rem;
  font-weight: bold;
  color: #333;
}

.circle-label {
  font-size: 0.9rem;
  color: #666;
}

.circular-progress h3 {
  margin: 0;
  color: #444;
  font-size: 1.1rem;
}
</style>

        <?php if (isset($mensaje)): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="form-container">
            <h3>Registrar Nueva Inscripción</h3>
            <form method="POST">
                <input type="text" name="nombre_completo" placeholder="Nombre completo" required>
                <input type="text" name="programa" placeholder="Programa" required>
                <input type="email" name="correo" placeholder="Correo" required>
                <input type="text" name="jornada" placeholder="Jornada" required>
                <input type="text" name="celular" placeholder="Celular" required>
                <input type="text" name="documento" placeholder="Documento" required>
               <center> <label class="checkbox"><input type="checkbox" name="acepto_terminos" required> Acepta términos</label></center>
                <button type="submit">Crear</button>
            </form>
        </div>

        <h3>Lista de Inscripciones</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Programa</th>
                <th>Correo</th>
                <th>Jornada</th>
                <th>Celular</th>
                <th>Documento</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            <?php while($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nombre_completo']) ?></td>
                <td><?= htmlspecialchars($row['programa']) ?></td>
                <td><?= htmlspecialchars($row['correo']) ?></td>
                <td><?= htmlspecialchars($row['jornada']) ?></td>
                <td><?= htmlspecialchars($row['celular']) ?></td>
                <td><?= htmlspecialchars($row['documento']) ?></td>
                <td><?= $row['fecha_inscripcion'] ?></td>
                <td class="acciones">
                    <a href="?edit=<?= $row['id'] ?>">Editar</a>
                    <form method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este registro?');">
                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>  
</body>
</html>
<?php $conn->close(); ?>