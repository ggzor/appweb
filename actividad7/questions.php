<?php
require_once 'database.php';

session_start();
solo_permitir([USUARIO_ADMIN]);

require_once 'componentes.php';

$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$tipo = $_SESSION['tipo'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Principal</title>
  <link rel="stylesheet" href="compartido.css">
  <link rel="stylesheet" href="generic_menu.css">
</head>

<body>
  <section>
    <?php
    echo logo();

    menu([
      'items' => [$m_questions, $m_about],
      'selected' => 0
    ]);
    ?>
  </section>

  <section>
    <?php links() ?>
    <main>
    </main>
  </section>
</body>

</html>
