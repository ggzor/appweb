<?php
require 'componentes.php';

$usuario = $_COOKIE['usuario'];

$is_admin = $usuario === 'admin';

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

    $items = $is_admin
      ? [$m_questions, $m_about]
      : [$m_history, $m_create, $m_about];

    echo menu([
      'items' => $items,
      'selected' => $is_admin ? 1 : 2
    ]);
    ?>
  </section>

  <section>
    <section class="links">
      <?php
      if ($is_admin) {
        echo '<p class="admin-indicator">Administrador</p>';
      }
      ?>
      <a href="index.php">Cerrar sesión</a>
    </section>
    <main>

    </main>
  </section>
</body>

</html>