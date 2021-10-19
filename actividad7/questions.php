<?php
session_start();

require_once 'database.php';
solo_permitir([USUARIO_ADMIN]);
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
  <link rel="stylesheet" href="questions.css">
</head>

<body>
  <section>
    <?php
    require_once 'componentes.php';

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
      <table class="tabla-reactivos">
        <tr>
          <th>Id</th>
          <th>Tema</th>
          <th>Nivel</th>
          <th>Enunciado</th>
          <th>Publicado</th>
        </tr>
        <tr>
          <td class="id">Maria Anders</td>
          <td class="tema">Germany</td>
          <td class="nivel"><?php echo icono_para_nivel(NIVEL_BASICO) ?></td>
          <td class="enunciado">Francisco Chang</td>
          <td class="publicado">Mexico</td>
        </tr>
        <tr>
          <td class="id">Maria Anders</td>
          <td class="tema">Germany</td>
          <td class="nivel">Centro comercial Moctezuma</td>
          <td class="enunciado">Francisco Chang</td>
          <td class="publicado">Mexico</td>
        </tr>
      </table>
    </main>
  </section>

</body>

</html>