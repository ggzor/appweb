<?php
require 'database.php';

session_start();
solo_permitir([USUARIO_NORMAL]);

require 'componentes.php';
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
      'items' => [$m_history, $m_create, $m_about],
      'selected' => 1
    ]);
    ?>
  </section>

  <section>
    <?php links($is_admin = false) ?>

    <main>
    </main>
  </section>
</body>

</html>