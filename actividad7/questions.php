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
          <th>Fecha</th>
          <th>Nivel</th>
          <th>Enunciado</th>
          <th>Publicado</th>
        </tr>
        <tr>
          <td class="id">1</td>
          <td class="fecha">Hoy</td>
          <td class="tema">Matemáticas</td>
          <td class="nivel"><?php echo icono_para_nivel(NIVEL_BASICO) ?></td>
          <td class="enunciado">Francisco Chang</td>
          <td class="publicado"><?php echo icono_checkmark() ?></td>
        </tr>
        <tr>
          <td class="id">2</td>
          <td class="fecha">02 Agosto</td>
          <td class="tema">Germany</td>
          <td class="nivel"><?php echo icono_para_nivel(NIVEL_AVANZADO) ?></td>
          <td class="enunciado">Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Ea repellat facere voluptatum harum modi officia. </td>
          <td class="publicado"><?php echo icono_checkmark() ?></td>
        </tr>
        <tr>
          <td class="id">3</td>
          <td class="fecha">08 de Cotubre</td>
          <td class="tema">Germany</td>
          <td class="nivel"><?php echo icono_para_nivel(NIVEL_INTERMEDIO) ?></td>
          <td class="enunciado">Francisco Chang</td>
          <td class="publicado"><?php echo icono_checkmark() ?></td>
        </tr>
      </table>
    </main>
  </section>

</body>

</html>