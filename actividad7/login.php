<?php
require 'database.php';

session_start();
solo_permitir([USUARIO_INTERNAUTA]);

require 'componentes.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $usuario = $_POST['usuario'];
  $pass = $_POST['pass'];

  $conn = new mysqli("localhost", "root", null, "examenes");
  $resultados = $conn->query("SELECT * FROM usuarios WHERE nombre = '$usuario'");

  if ($row = $resultados->fetch_assoc()) {
    if ($row['pass'] === $pass) {
      $tipo = intval($row['tipo']);

      $_SESSION['usuario'] = $usuario;
      $_SESSION['tipo'] = $tipo;

      $pagina = obtener_pagina_para($tipo);
      header("Location: $pagina");
      exit();
    } else {
      $error = 'La contraseña no es correcta';
    }
  } else {
    $error = 'No existe el usuario';
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Iniciar sesión</title>
  <link rel="stylesheet" href="compartido.css" />
  <link rel="stylesheet" href="generic_form.css" />
</head>

<body>
  <?php echo logo() ?>
  <h2>Iniciar sesión</h2>
  <form class="card" action="login.php" method="POST">
    <?php
    if ($error) {
      echo "<p class='error'><b> Error: </b> $error</p>";
    }
    ?>
    <input type="text" placeholder="Usuario" name="usuario" id="usuario" required autofocus /><br />
    <input type="password" placeholder="Contraseña" name="pass" id="pass" required /><br />
    <a href="olvidado.php">Olvidé mi contraseña</a>
    <section class="bottom">
      <p>¿No tienes cuenta? <a href="register.php">Regístrate</a></p>
      <input type="submit" value="Iniciar sesión" />
    </section>
  </form>
</body>

</html>