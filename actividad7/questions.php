<?php
session_start();

require_once 'database.php';
solo_permitir([USUARIO_ADMIN]);

require_once 'dal.php';
$conn = crear_conexion();

if (array_key_exists('delete', $_GET)) {
  $id_delete = intval($_GET['delete']);
  var_dump("Delete: $id_delete");

  $result = borrar_reactivo($conn, $id_delete);
  var_dump($result);

  header("Location: questions.php?delete_ok=1");
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
    <?php
    links();
    $todos_temas = obtener_temas($conn);

    /* $todos_temas = [1 => "Todos los temas", 2 => "Matemáticas", 3 => "Español"]; */
    /* $todos_niveles = [1 => "Todos los niveles", 2 => "Básico", 3 => "Intermedio", 4 => "Avanzado"]; */
    ?>

    <main>
      <section class="filtros" style="display: none;">
        <input type="text" id="filtro_busqueda" name="filtro_busqueda" placeholder="Buscar...">
        <select class="bold" name="filtro_tema" id="filtro_tema">
          <?php
          foreach ($todos_temas as $id_tema => $nombre_tema) {
            $selected_str = $id_tema == $filtro_tema ? 'selected' : '';
            echo "<option value='$id_tema' $selected_str> $nombre_tema</option>";
          }
          ?>
        </select>
        <select class="bold" name="filtro_nivel" id="filtro_nivel">
          <?php
          foreach ($todos_niveles as $id_nivel => $nombre_nivel) {
            $selected_str = $id_nivel == $filtro_nivel ? 'selected' : '';
            echo "<option value='$id_nivel' $selected_str> $nombre_nivel</option>";
          }
          ?>
        </select>
      </section>

      <?php
      if (array_key_exists('delete_ok', $_GET)) {
        echo "<p class='message success'> Reactivo eliminado correctamente </p>";
      }
      ?>

      <a class="btn btn-nuevo small" href="edit.php?new=1">+ Crear nuevo</a>

      <section class="tabla-reactivos">
        <p class="titulo">Id</p>
        <p class="titulo">Fecha <?php echo up_arrow() ?></p>
        <p class="titulo">Tema</p>
        <p class="titulo">Nivel</p>
        <p class="titulo">Enunciado</p>
        <p class="titulo">Publicado</p>
        <p class="titulo"></p>


        <?php
        $reactivos = obtener_reactivos($conn, $_SESSION['id_usuario']);

        function obtener_fecha_legible(DateTime $fecha)
        {
          $ahora = new DateTime();
          $diff = $ahora->diff($fecha, true);

          $result = $fecha->format('Y-m-d');

          return $result;
        }

        $edit_icon = icono_edit();
        $delete_icon = icono_delete();

        foreach ($reactivos as $reactivo) {
          $id = $reactivo['id_reactivo'];
          $fecha = obtener_fecha_legible(DateTime::createFromFormat("Y-m-d H:i:s", $reactivo['fecha']));
          $tema = $todos_temas[$reactivo['id_tema']];
          $icono = icono_para_nivel($reactivo['nivel']);
          $enunciado = $reactivo['enunciado'];
          $publicado = $reactivo['publicado'] ? icono_checkmark() : '';


          echo <<<EOF
            <p class="id">$id</p>
            <p class="fecha">$fecha</p>
            <p class="tema">$tema</p>
            <p class="nivel">$icono</p>
            <p class="enunciado">$enunciado</p>
            <p class="publicado">$publicado</p>
            <div class="acciones">
              <a class="btn secondary tiny" href="edit.php?id_reactivo=$id">$edit_icon</a>
              <a class="btn secondary tiny" href="questions.php?delete=$id"
                 onclick="return confirm('¿Seguro que quieres eliminar este reactivo?')">
                 $delete_icon
              </a>
            </div>
          EOF;
        }

        if (count($reactivos) == 0) {
          echo <<<EOF
          <h4>No hay reactivos por aquí.</h4>
          EOF;
        }
        ?>
      </section>
    </main>
  </section>

</body>

</html>
