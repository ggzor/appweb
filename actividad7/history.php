<?php
session_start();

require_once 'utils.php';

require_once 'database.php';
solo_permitir([USUARIO_NORMAL]);

require_once 'dal.php';
$db = new ExamenesDB();
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
  <link rel="stylesheet" href="history.css">
</head>

<body>
  <section>
    <?php
    require_once 'componentes.php';

    echo logo();
    menu([
      'items' => [$m_history, $m_create, $m_about],
      'selected' => 0
    ]);
    ?>
  </section>

  <section class="main-content">
    <?php links() ?>

    <main>
      <?php

      $clock = icono_timer();
      $checkmark = icono_checkmark();

      $id_usuario = $_SESSION['id_usuario'];
      $examenes = $db->obtener_examenes($id_usuario);
      $examenes_pendientes = array_filter($examenes, fn ($examen) => $examen['calificacion'] == null);
      $examenes_previos = array_filter($examenes, fn ($examen) => $examen['calificacion'] != null);

      $todos_temas = $db->obtener_temas();

      $calcular_extras = function ($examen) use (&$todos_temas) {
        return [
          'nombre_tema' => $todos_temas[$examen['id_tema']]['nombre'],
          'nivel_str' => obtener_cadena_nivel($examen['nivel']),
          'fecha' => obtener_fecha_legible(fecha_de_sql($examen['fecha']))
        ];
      }

      ?>

      <h1 class="titulo-1">Historial de exámenes</h1>

      <section class="examenes">
        <?php

        if (count($examenes_pendientes) > 0) {
        ?>
          <h2 class="titulo-2">Pendientes</h2>
          <section class="exam-list">

          <?php
          foreach ($examenes as $examen) {
            $extras = $calcular_extras($examen);

            if ($examen['calificacion'] == null) {
              echo <<<EOF
              <article class="pending">
                <p class="numero">#$examen[id_examen]</p>
                <p class="tiempo">$extras[fecha]</p>
                <section>
                  <p class="titulo">$extras[nombre_tema]<br>$extras[nivel_str]</p>
                  <p class="preguntas">$examen[cantidad_reactivos] preguntas</p>
                </section>
                $clock
                <div></div>
                <a class="btn small secondary" href="contestar.php?id_examen=10">Continuar</a>
              </article>
              EOF;
            }
          }
          echo '</section>';
        }
          ?>

          <?php

          if (count($examenes_previos) > 0) {
          ?>

            <h2 class="titulo-2">Completados</h2>
            <section class="exam-list">

            <?php

            foreach ($examenes_previos as $examen) {
              $extras = $calcular_extras($examen);

              echo <<<EOF
              <article class="complete">
                <section class="numero-container">
                  <p class="numero">#$examen[id_examen]</p>
                  $checkmark
                </section>
                <p class="tiempo">$extras[fecha]</p>
                <section>
                  <p class="titulo">$extras[nombre_tema]<br>$extras[nivel_str]</p>
                  <p class="preguntas">$examen[cantidad_reactivos] preguntas</p>
                </section>
                <p class="calificacion">$examen[calificacion]</p>
                <div></div>
                <a class="btn small secondary accent" href="detalles.php?id_examen=10">Detalles</a>
              </article>
              EOF;
            }

            echo '</section>';
          }
            ?>
            </section>
          </section>

    </main>
  </section>
</body>

</html>
