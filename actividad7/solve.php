<?php
session_start();

require_once 'utils.php';

require_once 'database.php';
solo_permitir([USUARIO_NORMAL]);

require_once 'dal.php';
$db = new ExamenesDB();

$id_usuario = intval($_SESSION['id_usuario']);
$nombre_usuario = xss_escape($_SESSION['nombre']);

$id_examen = intval($_GET['id_examen']);
$examen = $db->obtener_examen($id_examen);

if ($examen['id_usuario'] != $id_usuario || $examen['calificacion'] !== null) {
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
  <link rel="stylesheet" href="generic_form.css">
  <link rel="stylesheet" href="solve.css">
</head>

<body>
  <section>
    <?php
    require_once 'componentes.php';

    echo logo(false);
    ?>
  </section>

  <section class="main-content">
    <?php

    $todos_temas = $db->obtener_temas();

    $nombre_tema = $todos_temas[$examen['id_tema']]['nombre'];
    $nivel_str = obtener_cadena_nivel($examen['nivel']);
    ?>

    <form class="card" action="do_evaluate.php?id_examen=<?php echo $id_examen ?>" method="POST">
      <section class="header">
        <h1 class="titulo-1">Examen #<?php echo $id_examen ?></h1>

        <section class="subheader">
          <article class="sub-item">
            <h2 class="title-2">Usuario</h2>
            <p class="bold"><?php echo xss_escape($nombre_usuario) ?></p>
          </article>
          <article class="sub-item">
            <h2 class="title-2">Tema</h2>
            <p class="bold"><?php echo $nombre_tema ?></p>
          </article>
          <article class="sub-item">
            <h2 class="title-2">Nivel</h2>
            <p class="bold"><?php echo $nivel_str ?></p>
          </article>
        </section>
      </section>

      <section class="contenido">
        <p>Contesta cada una de las preguntas que se muestran a continuación:</p>


        <ol>
          <?php
          foreach ($db->obtener_reactivos_por_examen($id_examen) as $reactivo) {
            $opciones = $db->obtener_elegidas_por_reactivo($id_examen, $reactivo['id_reactivo']);

            $no_hay_seleccionadas =
              count(array_filter(
                $opciones,
                fn ($op) => $op['id_opcion_elegida'] != null
              )) == 0;

            $es_primera = true;
          ?>
            <li>
              <p><?php echo xss_escape($reactivo['enunciado']) ?></p>
              <ul>
                <?php foreach ($opciones as $opcion) {
                  $es_seleccionada =
                    $opcion['id_opcion_elegida'] != NULL
                    || ($no_hay_seleccionadas && $es_primera);
                  $checked_str = $es_seleccionada ? 'checked' : '';

                  echo '<li class="reactivo">';

                  if ($reactivo['multiple']) {
                    echo <<<EOF
                        <input type="checkbox" name="opcioncheck_$reactivo[id_reactivo]_opcion_$opcion[id_opcion]" value="1" id="opcion_$opcion[id_opcion]" $checked_str>
                        EOF;
                  } else {
                    echo <<<EOF
                        <input type="radio" name="reactivo_$reactivo[id_reactivo]" id="opcion_$opcion[id_opcion]" value="$opcion[id_opcion]" $checked_str required>
                        EOF;
                  }

                  $opcion_str = xss_escape($opcion['contenido']);

                  echo <<<EOF
                        <label for="opcion_$opcion[id_opcion]">$opcion_str</label>
                      EOF;

                  echo '</li>';

                  $es_primera = false;
                } ?>
              </ul>
            </li>
          <?php } ?>

        </ol>

      </section>

      <section class="buttons">
        <input class="small secondary" name="action" type="submit" value="Continuar después">
        <input class="small" name="action" type="submit" value="Finalizar" onclick="return confirm('¿Seguro que quieres finalizar este examen?')">
      </section>
    </form>
  </section>
</body>

</html>