<?php
require 'componentes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $usuario = $_POST['usuario'];
  $pass = $_POST['pass'];

  setcookie('usuario', $usuario);

  if ($usuario === 'admin') {
    header('Location: questions.php');
  } else {
    header('Location: history.php');
  }

  exit();
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