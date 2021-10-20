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

      $tema = "Español";
      $nivel = NIVEL_AVANZADO;
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
          <p><strong><?php echo $tema ?></strong></p>
          <div>
            <?php
            echo icono_para_nivel($nivel, 6);
            echo "<span>";
            echo obtener_cadena_nivel($nivel);
            echo "</span>";
            ?>
          </div>
        </article>
      </section>

      <section class="edit-section">
        <h2>Enunciado</h2>
        <article>
          <p class="enunciado">
            Son algunos de los marcadores gráficos que se utilizan para organizar el contenido de un reglamento.
          </p>
        </article>
      </section>

      <section class="edit-section">
        <h2>Opciones</h2>
        <article>
          <ul class="opciones">
            <div>
              <input type="radio" name="opciones" id="opcion1" checked>
              <label for="opcion1">Incisos, viñetas, números romanos y negritas.</label>
            </div>
            <div>
              <input type="radio" name="opciones" id="opcion2">
              <label for="opcion2">Puntos, comas, signos de interrogación y signos de admiración.</label>
            </div>
            <div>
              <input type="radio" name="opciones" id="opcion3">
              <label for="opcion3">Guion largo, guion corto y paréntesis.</label>
            </div>
            <div>
              <input type="radio" name="opciones" id="opcion4">
              <label for="opcion4">Párrafos, versos y estrofas.</label>
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
