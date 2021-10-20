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

    $todos_temas = [1 => "Matemáticas", 2 => "Español"];
    $todos_niveles = [NIVEL_BASICO, NIVEL_INTERMEDIO, NIVEL_AVANZADO];

    $tema = 1;
    $nivel = NIVEL_BASICO;
    $multiple = false;
    $enunciado = "Son algunos de los marcadores gráficos que se utilizan para organizar el contenido de un reglamento.";

    $opciones = [
      [
        'id_opcion' => 1, 'correcta' => true,
        'contenido' => "Incisos, viñetas, números romanos y negritas."
      ],
      [
        'id_opcion' => 2, 'correcta' => false,
        'contenido' => "Puntos, comas, signos de interrogación y signos de admiración."
      ],
      [
        'id_opcion' => 3, 'correcta' => false,
        'contenido' => "Guion largo, guion corto y paréntesis."
      ],
      [
        'id_opcion' => 4, 'correcta' => false,
        'contenido' => "Párrafos, versos y estrofas."
      ],
    ];

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
        'editable' => false,
        'multiple' => $multiple,
        'unica' => $correcta == null ? "{$opciones[0]['id_opcion']}" : "$correcta",
        'contador' => 1,
        'opciones' => $opciones
      ], JSON_UNESCAPED_UNICODE)
    );

    ?>

    <main x-data="<?php echo $target_data ?>">
      <section class="edit-section">
        <h2>General</h2>
        <article class="nivel">
          <div class="select">
            <select class="bold" name="tema" id="tema" :disabled="!editable">
              <?php
              foreach ($todos_temas as $id_tema => $nombre_tema) {
                $selected_str = $id_tema == $tema ? 'selected' : '';
                echo "<option value='$id_tema' $selected_str>$nombre_tema</option>";
              }
              ?>
            </select>
          </div>
          <div class="select" data-value="<?php echo $nivel ?>">
            <select name="nivel" id="tema" onchange="this.parentElement.dataset.value = this.value" :disabled="!editable">
              <?php
              foreach ($todos_niveles as $nivel_opcion) {
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
        <h2>Enunciado</h2>
        <article class="textarea-inside">
          <div class="input-sizer" data-value="<?php echo $enunciado ?>">
            <textarea oninput="this.parentNode.dataset.value = this.value" name="enunciado" id="enunciado" placeholder="Aquí va el enunciado..." required :readonly="!editable"><?php echo $enunciado ?></textarea>
          </div>
        </article>
      </section>

      <section class="edit-section">
        <h2>Opciones</h2>
        <article>
          <ul class="opciones">
            <template x-for="opcion in opciones" :key="opcion.id_opcion">
              <div class="opcion-reactivo">
                <input type="radio" name="opciones_radio" id="opcion_radio_1" :value="opcion.id_opcion" x-model="unica" x-show="!multiple" :disabled="!editable">
                <input type="checkbox" name="opciones_check" id="opcion_check_1" x-model="opcion.correcta" x-show="multiple">
                <div class="input-sizer" :data-value="opcion.contenido">
                  <textarea oninput="this.parentNode.dataset.value = this.value" name="opcion1_texto" id="opcion1_texto" placeholder="Aquí va el contenido de un reactivo..." required :readonly="!editable" x-text="opcion.contenido">
                  </textarea>
                </div>
                <div class="buttons">
                  <button class="tiny secondary" x-show="editable && opciones.length > 2" @click="
opciones.splice(opciones.findIndex(op => op.id_opcion == opcion.id_opcion), 1);
">×</button>
                </div>
              </div>
            </template>
          </ul>
        </article>
        <article class="option-buttons horizontal" x-cloak x-show="editable">
          <button class="subnormal secondary tiny upper" @click="
opciones.push({
  id_opcion: `nueva_${contador}`,
  contenido: `Nueva opción ${contador}`,
  correcta: false
});
contador += 1;
">+ Agregar opción</button>
          <div class="bar"></div>
          <input type="checkbox" class="subnormal" name="multiple" id="multiple" x-model="multiple">
          <label class="upper subnormal faded" for="multiple">Permitir más de una correcta</label>
        </article>
      </section>

      <section class="edit-section horizontal form-buttons" x-cloak x-show="!editable">
        <a href="questions.php" class="btn small secondary">Volver</a>
        <button class="small" @click="editable = true">Editar</button>
      </section>

      <section class="edit-section horizontal form-buttons" x-cloak x-show="editable">
        <a href="questions.php" class="btn small secondary">Cancelar</a>
        <input class="small" type="submit" value="Guardar cambios">
      </section>
    </main>
  </section>

  <script src="public/alpine.js"></script>
  <script>
    /* setTimeout(() => window.location.reload(), 1000) */
  </script>
</body>

</html>
