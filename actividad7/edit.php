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
      <section>
        <h2>General</h2>
        <article>
          <p><strong>Español</strong></p>
          <p class="nivel">Básico</p>
        </article>
      </section>
      <section>
        <h2>Enunciado</h2>
        <article>
          <p class="enunciado">
            Son algunos de los marcadores gráficos que se utilizan para organizar el contenido de un reglamento.
          </p>
        </article>
      </section>
      <section>
        <h2>Opciones</h2>
        <article>
          <input type="radio" name="opciones" id="opcion1">
          <label for="opcion1">Incisos, viñetas, números romanos y negritas.</label>
        </article>
        <article>
          <button class="secondary small upper">+ Agregar opción</button>
          <div class="bar"></div>
          <input type="checkbox" name="multiple" id="multiple">
          <label for="multiple">Permitir más de una correcta</label>
        </article>
      </section>
      <section>
        <a href="questions.php" class="button small secondary">Cancelar</a>
        <input class="small" type="submit" value="Guardar cambios">
      </section>
    </main>
  </section>
</body>

</html>
