<?php
session_start();

require_once 'utils.php';

require_once 'database.php';
solo_permitir([USUARIO_NORMAL]);

require_once 'dal.php';
$db = new ExamenesDB();

$id_usuario = intval($_SESSION['id_usuario']);
$id_examen = intval($_GET['id_examen']);
$examen = $db->obtener_examen($id_examen);

if ($examen['id_usuario'] != $id_usuario) {
  header('Location: index.php');
  exit();
}
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
  <link rel="stylesheet" href="details.css">
</head>

<body>
  <section>
    <?php
    require_once 'componentes.php';

    echo logo();
    menu([
      'items' => [$m_history, $m_create, $m_about, $m_details],
      'selected' => 3
    ]);
    ?>
  </section>

  <section class="main-content">
    <?php links() ?>

    <main>
      <?php

      $todos_temas = $db->obtener_temas();

      $nombre_tema = $todos_temas[$examen['id_tema']]['nombre'];
      $nivel_str = obtener_cadena_nivel($examen['nivel']);

      $calificacion_str = number_format($examen['calificacion'], 1);
      ?>

      <section class="buttons">
        <h1 class="titulo-1">Detalles del examen #<?php echo $id_examen ?></h1>
        <a href="do_delete_exam.php?id_examen=<?php echo $id_examen ?>" class="btn small secondary" onclick="return confirm('¿Seguro que quieres eliminar este examen?')">Borrar examen</a>
      </section>

      <section class="contenido">
        <h2 class="title-2">Tema</h2>
        <p class="bold"><?php echo $nombre_tema ?></p>

        <h2 class="title-2">Nivel</h2>
        <p class="bold"><?php echo $nivel_str ?></p>

        <h2 class="title-2">Cantidad de reactivos</h2>
        <p class="bold"><?php echo $examen['cantidad_reactivos'] ?></p>

        <h2 class="title-2">Calificación</h2>
        <p class="bold"><?php echo $calificacion_str ?></p>

        <h2 class="title-2">Fecha de creación</h2>
        <p class="bold"><?php echo $examen['fecha'] ?></p>

        <h2 class="title-2">Reactivos</h2>

        <ol>

          <?php
          foreach ($db->obtener_reactivos_por_examen($id_examen) as $reactivo) {
            $opciones = $db->obtener_elegidas_por_reactivo($id_examen, $reactivo['id_reactivo']);

            $total = count($opciones);
            $correctas = 0;
          ?>
            <li>
              <section class="reactivo">
                <section class="principal">
                  <p><?php echo $reactivo['enunciado'] ?></p>
                  <ul>
                    <?php foreach ($opciones as $opcion) {
                      $es_seleccionada = $opcion['id_opcion_elegida'] != NULL;
                      $checked_str = $es_seleccionada ? 'checked' : '';

                      $es_correcta = $opcion['correcta'] == $es_seleccionada;

                      if ($es_correcta) {
                        $correctas += 1;
                      }

                      echo '<li class="reactivo">';

                      if ($reactivo['multiple']) {
                        echo <<<EOF
                        <input id="opcion_$opcion[id_opcion]" type="checkbox" $checked_str onclick="event.preventDefault()">
                        EOF;
                      } else {
                        echo <<<EOF
                        <input name="reactivo_$reactivo[id_reactivo]" id="opcion_$opcion[id_opcion]" type="radio" $checked_str onclick="event.preventDefault()">
                        EOF;
                      }

                      echo <<<EOF
                        <label for="opcion_$opcion[id_opcion]">$opcion[contenido]</label>
                      EOF;

                      echo '</li>';
                    } ?>
                  </ul>
                </section>
                <?php
                if (!$reactivo['multiple']) {
                  if ($total == $correctas) {
                    $correctas = 1;
                  } else {
                    $correctas = 0;
                  }

                  $total = 1;
                }

                $label = "Parcial";
                if ($correctas == 0) {
                  $label = "Incorrecta";
                } else if ($correctas == $total) {
                  $label = "Correcta";
                }

                $clase_calificacion = strtolower($label);

                echo <<<EOF
                <section class="calificacion $clase_calificacion">
                  <p>$label</p>
                  <p>$correctas / $total</p>
                </section>
                EOF;
                ?>
              </section>
            </li>
          <?php } ?>

        </ol>
      </section>
    </main>
  </section>

  <script>
    function handle_event() {
      console.log(window.event)
    }
  </script>
</body>

</html>
