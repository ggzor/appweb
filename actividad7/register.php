<?php
require 'database.php';

session_start();
solo_permitir([USUARIO_INTERNAUTA]);

require 'componentes.php';
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
  <form class="card" action="register.html" method="POST">
    <input type="text" placeholder="Usuario" name="usuario" id="usuario" required autofocus /><br />
    <input type="password" placeholder="Contraseña" name="pass" id="pass" required /><br />
    <input type="password" placeholder="Confirmar contraseña" name="pass_conf" id="pass_conf" required /><br />
    <input type="text" placeholder="Pregunta de recuperación" name="pregunta" id="pregunta" required /><br />
    <input type="text" placeholder="Respuesta a la pregunta" name="respuesta" id="respuesta" required /><br />
    <section class="bottom">
      <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
      <input type="submit" value="Registrarse" />
    </section>
  </form>
</body>

</html>