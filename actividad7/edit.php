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

    <main>
      <?php
      const MEDIDA_ICONO = 6;

      $todos_temas = [1 => "Matemáticas", 2 => "Español"];
      $todos_niveles = [NIVEL_BASICO, NIVEL_INTERMEDIO, NIVEL_AVANZADO];

      $tema = 1;
      $nivel = NIVEL_BASICO;
      $multiple = true;
      $enunciado = "Son algunos de los marcadores gráficos que se utilizan para organizar el contenido de un reglamento.";

      $opciones = [
        "Incisos, viñetas, números romanos y negritas.",
        "Puntos, comas, signos de interrogación y signos de admiración.",
        "Guion largo, guion corto y paréntesis.",
        "Párrafos, versos y estrofas."
      ];
      ?>

      <section class="edit-section">
        <h2>General</h2>
        <article class="nivel">
          <div class="select">
            <select class="bold" name="tema" id="tema">
              <?php
              foreach ($todos_temas as $id_tema => $nombre_tema) {
                $selected_str = $id_tema == $tema ? 'selected' : '';
                echo "<option value='$id_tema' $selected_str>$nombre_tema</option>";
              }
              ?>
            </select>
          </div>
          <div class="select" data-value="<?php echo $nivel ?>">
            <select name="nivel" id="tema" onchange="this.parentElement.dataset.value = this.value">
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
            <textarea oninput="this.parentNode.dataset.value = this.value" name="enunciado" id="enunciado" placeholder="<?php echo $enunciado ?>" required><?php echo $enunciado ?></textarea>
          </div>
        </article>
      </section>

      <section class="edit-section">
        <h2>Opciones</h2>
        <article>
          <ul class="opciones">
            <div class="opcion-reactivo">
              <input type="radio" name="opciones" id="opcion1" checked>
              <div class="input-sizer" data-value="<?php echo $opciones[0] ?>">
                <textarea oninput="this.parentNode.dataset.value = this.value" name="opcion1_texto" id="opcion1_texto" placeholder="<?php echo $opciones[0] ?>" required><?php echo $opciones[0] ?></textarea>
              </div>
            </div>
            <div class="opcion-reactivo">
              <input type="radio" name="opciones" id="opcion2" checked>
              <div class="input-sizer" data-value="<?php echo $opciones[1] ?>">
                <textarea oninput="this.parentNode.dataset.value = this.value" name="opcion2_texto" id="opcion2_texto" placeholder="<?php echo $opciones[1] ?>" required><?php echo $opciones[1] ?></textarea>
              </div>
            </div>
          </ul>
        </article>
        <article class="option-buttons horizontal">
          <button class="subnormal secondary tiny upper">+ Agregar opción</button>
          <div class="bar"></div>
          <input type="checkbox" class="subnormal" name="multiple" id="multiple">
          <label class="upper subnormal faded" for="multiple">Permitir más de una correcta</label>
        </article>
      </section>

      <section class="edit-section horizontal form-buttons">
        <a href="questions.php" class="btn small secondary">Cancelar</a>
        <input class="small" type="submit" value="Guardar cambios">
      </section>
    </main>
  </section>

  <script>
    /* setTimeout(() => window.location.reload(), 1000) */
  </script>
</body>

</html>
