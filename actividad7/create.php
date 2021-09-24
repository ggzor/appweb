<?php
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

    echo menu([
      'items' => [$m_history, $m_create, $m_about],
      'selected' => 1
    ]);
    ?>
  </section>

  <section>
    <section class="links">
      <a href="index.php">Cerrar sesión</a>
    </section>
    <main>

    </main>
  </section>
</body>

</html>