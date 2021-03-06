<?php
require_once 'utils.php';

session_start();

require_once 'database.php';
solo_permitir([USUARIO_ADMIN]);

require_once 'dal.php';
$db = new ExamenesDB();

$todos_temas = $db->obtener_temas();

$tema = array_keys($todos_temas)[0];
$nivel = NIVEL_BASICO;
$multiple = false;
$enunciado = "En este espacio es donde va tu reactivo, da click para editar.";
$opciones = [
  [
    'id_opcion' => 'nueva_1',
    'correcta' => true,
    'contenido' => 'Esta es una opción seleccionada, haz click para editar.'
  ],
  [
    'id_opcion' => 'nueva_2',
    'correcta' => false,
    'contenido' => 'Esta es una opción no seleccionada, haz click para editar.'
  ]
];
$contador = 3;
$editable = true;
$id_reactivo = null;

$error = null;

const OPCION_TEXTO_PREFIJO = 'opciontexto_';
const OPCION_CHECK_PREFIJO = 'opcioncheck_';
const OPCION_NUEVA_PREFIJO = 'nueva_';
const OPCION_RADIO_KEY = 'opcionradio';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $crear_nuevo = array_key_exists('create', $_REQUEST);

  if (!$crear_nuevo) {
    $id_reactivo = intval($_REQUEST['update']);
  }

  $tema = intval($_REQUEST['tema']);
  $nivel = $_REQUEST['nivel'];
  $enunciado = $_REQUEST['enunciado'];
  $multiple = array_key_exists('multiple', $_REQUEST)
    ? boolval($_REQUEST['multiple'])
    : false;
  $opciones = [];

  foreach ($_REQUEST as $clave => $valor) {
    if (starts_with($clave, OPCION_TEXTO_PREFIJO)) {
      $nombre = remove_prefix($clave, OPCION_TEXTO_PREFIJO);
      $contenido = $valor;

      if (starts_with($nombre, OPCION_NUEVA_PREFIJO)) {
        $numero = intval(remove_prefix($nombre, OPCION_NUEVA_PREFIJO));

        if ($numero >= $contador) {
          $contador = $numero + 1;
        }
      }

      if ($multiple) {
        $nombre_check = OPCION_CHECK_PREFIJO . $nombre;
        $correcta = array_key_exists($nombre_check, $_REQUEST);
      } else {
        $correcta = $_REQUEST[OPCION_RADIO_KEY] == $nombre;
      }

      $opciones[] = [
        'id_opcion' => $nombre,
        'correcta' => $correcta,
        'contenido' => $contenido,
      ];
    }
  }

  if ($crear_nuevo) {
    $id_reactivo = $db->crear_reactivo(
      $_SESSION['id_usuario'],
      $tema,
      $nivel,
      $enunciado,
      $multiple
    );

    if ($id_reactivo !== false) {
      foreach ($opciones as $opcion)
        $db->crear_opcion(
          $id_reactivo,
          boolval($opcion['correcta']),
          $opcion['contenido']
        );

      header("Location: questions.php?create_ok=1");
    } else {
      header("Location: questions.php?create_error=1");
    }

    exit();
  } else {
    $db->actualizar_reactivo(
      $id_reactivo,
      $tema,
      $nivel,
      $enunciado,
      $multiple
    );

    $conservar = [];
    foreach ($opciones as $opcion) {
      if (is_numeric($opcion['id_opcion'])) {
        $id_opcion = intval($opcion['id_opcion']);
        $conservar[] = $id_opcion;

        $db->actualizar_opcion(
          $id_opcion,
          boolval($opcion['correcta']),
          $opcion['contenido']
        );
      } else {
        $nueva = $db->crear_opcion($id_reactivo, $opcion['correcta'], $opcion['contenido']);
        $conservar[] = $nueva;
      }
    }

    if (count($conservar) > 0) {
      $db->conservar_opciones($id_reactivo, $conservar);
    }

    header("Location: edit.php?id_reactivo=$id_reactivo&update_ok=1");
    exit();
  }

  exit();
} else if (array_key_exists('id_reactivo', $_REQUEST)) {
  $id_reactivo = intval($_REQUEST['id_reactivo']);
  $info_reactivo = $db->obtener_reactivo_unico($id_reactivo);

  $id_usuario = intval($_SESSION['id_usuario']);

  if ($info_reactivo['id_creador'] != $id_usuario) {
    header("Location: index.php");
    exit();
  }

  $opciones = $db->obtener_opciones_por_reactivo($id_reactivo);

  $multiple = boolval($info_reactivo['multiple']);
  if ($multiple) {
    foreach ($opciones as &$opcion) {
      $opcion['correcta'] = boolval($opcion['correcta']);
    }
  }

  $tema = $info_reactivo['id_tema'];
  $nivel = $info_reactivo['nivel'];
  $enunciado = $info_reactivo['enunciado'];
  $contador = 1;
  $editable = true;
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
  <link rel="stylesheet" href="edit.css">
</head>

<body>
  <section>
    <?php
    require_once 'componentes.php';

    echo logo();
    menu([
      'items' => [$m_edit, $m_questions, $m_about],
      'selected' => 0
    ]);
    ?>
  </section>

  <section>
    <?php links() ?>

    <?php
    const MEDIDA_ICONO = 6;

    $correcta = null;
    if (!$multiple) {
      foreach ($opciones as $opcion) {
        if ($opcion['correcta']) {
          $correcta = $opcion['id_opcion'];
          break;
        }
      }
    }

    $target_data = mb_ereg_replace(
      '"',
      "'",
      json_encode([
        'editable' => $editable,
        'multiple' => $multiple,
        'unica' => $correcta == null ? $opciones[0]['id_opcion'] : $correcta,
        'contador' => $contador,
        'opciones' => $opciones,
        'hide_messages' => false
      ], JSON_UNESCAPED_UNICODE)
    );

    $es_nueva = array_key_exists('new', $_GET);
    $target_url = $es_nueva
      ? "edit.php?create=1"
      : "edit.php?update=$id_reactivo";

    ?>

    <form action="<?php echo $target_url ?>" method="POST" class="main" x-data="<?php echo $target_data ?>" x-cloak>
      <?php
      if (array_key_exists('update_ok', $_REQUEST)) {
        echo "<p class='message success' x-show=\"!hide_messages\">Reactivo <b>actualizado</b> correctamente</p>";
      }
      ?>

      <section class="edit-section">
        <h2 class="title-2">General</h2>
        <article class="nivel">
          <div class="select">
            <select class="bold" name="tema" id="tema" :disabled="!editable">
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
            <select name="nivel" id="tema" onchange="this.parentElement.dataset.value = this.value" :disabled="!editable">
              <?php
              foreach (TODOS_NIVELES as $nivel_opcion) {
                $selected_str = $nivel_opcion == $nivel ? 'selected' : '';
                $opcion_str = obtener_cadena_nivel($nivel_opcion);
                echo "<option value='$nivel_opcion' $selected_str>$opcion_str</option>";
              }
              ?>
            </select>
          </div>
          <div class="icono basico">
            <?php echo icono_para_nivel(NIVEL_BASICO, MEDIDA_ICONO); ?>
          </div>
          <div class="icono intermedio">
            <?php echo icono_para_nivel(NIVEL_INTERMEDIO, MEDIDA_ICONO); ?>
          </div>
          <div class="icono avanzado">
            <?php echo icono_para_nivel(NIVEL_AVANZADO, MEDIDA_ICONO); ?>
          </div>
        </article>
      </section>

      <section class="edit-section">
        <h2 class="title-2">Enunciado</h2>
        <article class="textarea-inside">
          <?php
          $enunciado_seguro = xss_escape($enunciado);
          ?>

          <div class="input-sizer" data-value="<?php echo $enunciado_seguro ?>">
            <textarea oninput="this.parentNode.dataset.value = this.value" name="enunciado" id="enunciado" placeholder="Aquí va el enunciado..." required :readonly="!editable" onfocus="this.select()"><?php echo $enunciado_seguro ?></textarea>
          </div>
        </article>
      </section>

      <section class="edit-section">
        <h2 class="title-2">Opciones</h2>
        <article>
          <ul class="opciones">
            <template x-for="opcion in opciones" :key="opcion.id_opcion">
              <div class="opcion-reactivo">
                <input type="radio" name="opcionradio" :value="opcion.id_opcion" x-show="!multiple" :disabled="!editable" x-model="unica" tabindex="-1">
                <input type="checkbox" value="1" :name="`opcioncheck_${opcion.id_opcion}`" x-show="multiple" :disabled="!editable" x-model="opcion.correcta" tabindex="-1">
                <div class="input-sizer" :data-value="opcion.contenido">
                  <textarea oninput="this.parentNode.dataset.value = this.value" :name="`opciontexto_${opcion.id_opcion}`" placeholder="Aquí va el contenido de un reactivo..." required :readonly="!editable" x-text="opcion.contenido" onfocus="this.select()">
                  </textarea>
                </div>
                <div class="buttons">
                  <button class="tiny secondary" x-show="editable && opciones.length > 2" @click.prevent="
opciones.splice(opciones.findIndex(op => op.id_opcion == opcion.id_opcion), 1);
">×</button>
                </div>
              </div>
            </template>
          </ul>
        </article>
        <article class="option-buttons horizontal" x-cloak x-show="editable">
          <button class="subnormal secondary tiny upper" @click.prevent="
opciones.push({
  id_opcion: `nueva_${contador}`,
  contenido: `Nueva opción ${contador}`,
  correcta: false
});
contador += 1;
">+ Agregar opción</button>
          <div class="bar"></div>
          <input type="checkbox" value="1" class="subnormal" name="multiple" id="multiple" x-model="multiple">
          <label class="upper subnormal faded" for="multiple">Permitir más de una correcta</label>
        </article>
      </section>

      <section class="edit-section horizontal form-buttons" x-cloak x-show="!editable">
        <a href="questions.php" class="btn small secondary">Volver</a>
        <button class="small" @click.prevent="hide_messages = editable = true">Editar</button>
      </section>

      <section class="edit-section horizontal form-buttons" x-cloak x-show="editable">
        <a href="questions.php" class="btn small secondary">Cancelar</a>
        <input class="small" type="submit" value="<?php echo ($es_nueva ? "Crear" : "Guardar cambios") ?>">
      </section>
      </main>
  </section>

  <script src="public/alpine.js"></script>
  <script>
    /* setTimeout(() => window.location.reload(), 1000) */
  </script>
</body>

</html>