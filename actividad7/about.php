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

    menu([
      'items' => $items,
      'selected' => $is_admin ? 1 : 2
    ]);
    ?>
  </section>

  <section>
    <?php links($is_admin) ?>

    <main>
    </main>
  </section>
</body>

</html>