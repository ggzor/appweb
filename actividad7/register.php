<?php
require_once 'database.php';

session_start();
solo_permitir([USUARIO_INTERNAUTA]);

require_once 'componentes.php';

$error = null;

$nombre = '';
$usuario = '';
$pregunta = '';
$respuesta = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = trim($_REQUEST['nombre']);
  $usuario = trim($_REQUEST['usuario']);
  $pass = $_REQUEST['pass'];
  $pass_conf = $_REQUEST['pass_conf'];
  $pregunta = trim($_REQUEST['pregunta']);
  $respuesta = trim($_REQUEST['respuesta']);

  $conn = new mysqli("localhost", "root", null, "examenes");

  // Verificar usuario existente
  $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
  $stmt->bind_param("s", $usuario);
  $stmt->execute();

  $resultados = $stmt->get_result();
  if ($resultados->fetch_assoc()) {
    $error = "Ya existe un usuario con ese nombre";
  }
  $resultados->close();
  $stmt->close();

  // Verificar campos que sean espacios en blanco
  if ($error === null) {
    $campo_vacio = false;
    $validar_no_vacio = ['nombre', 'usuario', 'pregunta', 'respuesta'];
    foreach ($validar_no_vacio as $alt => $campo) {
      if (trim($_REQUEST[$campo]) === "") {
        if (!is_int($alt))
          $campo = $alt;
        $campo = ucfirst($campo);

        $error = "El campo '$campo' no puede estar vacío o tener sólo espacios en blanco.";
        break;
      }
    }
  }

  // Validar contraseña
  if ($error === null) {
    if (mb_strlen($pass) < 8)
      $error = "La contraseña debe tener al menos 8 caracteres.";
    else if (!preg_match('/\p{Lu}/u', $pass))
      $error = "La contraseña debe tener al menos una mayúscula.";
    else if (!preg_match('/\p{Ll}/u', $pass))
      $error = "La contraseña debe tener al menos una minúscula.";
    else if (!preg_match('/\p{N}/u', $pass))
      $error = "La contraseña debe tener al menos un dígito.";
    else if (!preg_match('/(\W|_)/u', $pass))
      $error = "La contraseña debe tener al menos un símbolo especial.";
  }

  // Validar que coinciden las contraseñas
  if ($error === null) {
    if ($pass !== $pass_conf) {
      $error = "Las contraseñas no coinciden";
    }
  }

  // Agregar usuario
  if ($error === null) {
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, usuario, pass,
                                                  pregunta, respuesta, tipo)
                            VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("sssss", $nombre, $usuario, $pass, $pregunta, $respuesta);
    $stmt->execute();
    $stmt->close();

    header("Location: login.php?registered=1");
    exit();
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrarse</title>
  <link rel="stylesheet" href="compartido.css" />
  <link rel="stylesheet" href="generic_form.css" />
</head>

<body>
  <?php echo logo() ?>
  <h2>Registrarse</h2>
  <form class="card" action="register.php" method="POST">
    <?php
    if ($error) {
      echo "<p class='error'><b> Error: </b> $error</p>";
    }
    ?>
    <input type="text" placeholder="Nombre" name="nombre" id="nombre" required autofocus value="<?php echo $nombre ?>" /><br />
    <input type="text" placeholder="Usuario" name="usuario" id="usuario" required value="<?php echo $usuario ?>" /><br />
    <input title="Test" type="password" placeholder="Contraseña" name="pass" id="pass" required /><br />
    <input type="password" placeholder="Confirmar contraseña" name="pass_conf" id="pass_conf" required /><br />
    <input type="text" placeholder="Pregunta de recuperación" name="pregunta" id="pregunta" required value="<?php echo $pregunta ?>" /><br />
    <input type="text" placeholder="Respuesta a la pregunta" name="respuesta" id="respuesta" required value="<?php echo $respuesta ?>" /><br />
    <div class="suggest">
      La contraseña debe contener:
      <ul>
        <li>Al menos 8 caracteres</li>
        <li>Mayúsculas y minúsculas</li>
        <li>Símbolos especiales</li>
        <li>Dígitos</li>
      </ul>
    </div>
    <br>
    <section class="bottom">
      <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
      <input type="submit" value="Registrarse" />
    </section>
  </form>
</body>

</html>
