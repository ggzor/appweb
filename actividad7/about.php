<?php
session_start();

require_once 'database.php';
solo_permitir([USUARIO_NORMAL, USUARIO_ADMIN]);
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
    require_once 'componentes.php';

    $is_admin = obtener_tipo_usuario() === USUARIO_ADMIN;

    $items = $is_admin
      ? [$m_questions, $m_about]
      : [$m_history, $m_create, $m_about];

    echo logo();
    menu([
      'items' => $items,
      'selected' => $is_admin ? 1 : 2
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
