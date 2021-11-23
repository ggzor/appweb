<?php
require_once "utils.php";
session_start();

require_once 'database.php';
solo_permitir([USUARIO_ADMIN]);

require_once 'dal.php';
$db = new ExamenesDB();

if (array_key_exists('delete', $_GET)) {
  $id_delete = intval($_GET['delete']);
  var_dump("Delete: $id_delete");

  $result = $db->borrar_reactivo($id_delete);
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
    $todos_temas = $db->obtener_temas();
    array_unshift($todos_temas, ["nombre" => "Todos los temas"]);

    $todos_niveles = TODOS_NIVELES;
    array_unshift($todos_niveles, "TODOS");
    ?>

    <main>
      <?php
      if (array_key_exists('delete_ok', $_GET)) {
        echo "<p class='message success'> Reactivo <b>eliminado</b> correctamente </p>";
      } else if (array_key_exists('create_ok', $_GET)) {
        echo "<p class='message success'> Reactivo <b>creado</b> correctamente </p>";
      } else if (array_key_exists('create_error', $_GET)) {
        echo "<p class='message error'> No se pudo crear el reactivo </p>";
      }

      $icono_busqueda = icono_search();

      $busqueda = '';
      if (array_key_exists('filtro_busqueda', $_REQUEST)) {
        $busqueda = trim($_REQUEST['filtro_busqueda']);
      }

      $nivel = 'TODOS';
      if (array_key_exists('nivel', $_REQUEST)) {
        $nivel = $_REQUEST['nivel'];
      }

      $tema = 'Todos los temas';
      if (array_key_exists('tema', $_REQUEST)) {
        $tema = $_REQUEST['tema'];
      }
      ?>

      <form action="questions.php?search=10" method="GET" class="filtros">
        <input type="text" id="filtro_busqueda" name="filtro_busqueda" placeholder="Buscar..." value="<?php echo $busqueda ?>" autofocus onfocus="this.select()">
        <button type="submit" class="btn secondary tiny"><?php echo $icono_busqueda ?></button>

        <div class="spacer"></div>

        <div class="select">
          <select class="bold" name="tema" id="tema" :disabled="!editable" onchange="this.form.submit()">
            <?php
            foreach ($todos_temas as $id_tema => $tema_select) {
              $nombre_tema = $tema_select['nombre'];

              $selected_str = $id_tema == $tema ? 'selected' : '';
              $nombre_tema = xss_escape($nombre_tema);
              echo "<option value='$id_tema' $selected_str>$nombre_tema</option>";
            }
            ?>
          </select>

        </div>

        <div class="select" data-value="<?php echo $nivel ?>">
          <select name="nivel" id="tema" onchange="this.parentElement.dataset.value = this.value; this.form.submit()" :disabled="!editable">
            <?php
            foreach ($todos_niveles as $nivel_opcion) {
              $selected_str = $nivel_opcion == $nivel ? 'selected' : '';

              if (in_array($nivel_opcion, TODOS_NIVELES)) {
                $opcion_str = obtener_cadena_nivel($nivel_opcion);
              } else {
                $opcion_str = 'Todos los niveles';
              }

              echo "<option value='$nivel_opcion' $selected_str>$opcion_str</option>";
            }
            ?>
          </select>
        </div>

        <a class="btn btn-nuevo small" href="edit.php?new=1">+ Crear nuevo</a>
      </form>

      <section class="tabla-reactivos">
        <p class="titulo">Id</p>
        <p class="titulo">Fecha <?php echo up_arrow() ?></p>
        <p class="titulo">Tema</p>
        <p class="titulo">Nivel</p>
        <p class="titulo">Enunciado</p>
        <p class="titulo">Publicado</p>
        <p class="titulo"></p>


        <?php
        $reactivos = $db->obtener_reactivos(
          $_SESSION['id_usuario'],
          $busqueda,
          intval($tema),
          $nivel
        );

        $edit_icon = icono_edit();
        $delete_icon = icono_delete();

        foreach ($reactivos as $reactivo) {
          $id = $reactivo['id_reactivo'];
          $fecha = obtener_fecha_legible(fecha_de_sql($reactivo['fecha']));
          $tema = xss_escape($todos_temas[$reactivo['id_tema']]['nombre']);
          $icono = icono_para_nivel($reactivo['nivel']);
          $enunciado = xss_escape($reactivo['enunciado']);
          $publicado = $reactivo['publicado']
            ? icono_checkmark()
            : "<a class='btn small secondary' href='do_publish.php?id_reactivo=$id'
                onclick='return confirm(\"¿Seguro que quieres publicar este reactivo?\")'
                >Publicar</a>";


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