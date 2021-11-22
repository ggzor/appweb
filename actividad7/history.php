<?php
session_start();

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
      ?>

      <h1 class="titulo-1">Historial de exámenes</h1>

      <section class="examenes">
        <h2 class="titulo-2">Pendientes</h2>

        <section class="exam-list">

          <article class="pending">
            <p class="numero">#10</p>
            <p class="tiempo">Hace 2 horas</p>
            <section>
              <p class="titulo">Matemáticas<br>Intermedio</p>
              <p class="preguntas">10 preguntas</p>
            </section>
            <?php echo $clock ?>
            <div></div>
            <a class="btn small secondary" href="contestar.php?id_examen=10">Continuar</a>
          </article>

          <article class="pending">
            <p class="numero">#10</p>
            <p class="tiempo">Hace 2 horas</p>
            <section>
              <p class="titulo">Matemáticas<br>Intermedio</p>
              <p class="preguntas">10 preguntas</p>
            </section>
            <?php echo $clock ?>
            <div></div>
            <a class="btn small secondary" href="contestar.php?id_examen=10">Continuar</a>
          </article>

          <article class="pending">
            <p class="numero">#10</p>
            <p class="tiempo">Hace 2 horas</p>
            <section>
              <p class="titulo">Matemáticas<br>Intermedio</p>
              <p class="preguntas">10 preguntas</p>
            </section>
            <?php echo $clock ?>
            <div></div>
            <a class="btn small secondary" href="contestar.php?id_examen=10">Continuar</a>
          </article>

        </section>

        <h2 class="titulo-2">Completados</h2>

        <section class="exam-list">

          <article class="complete">
            <section class="numero-container">
              <p class="numero">#10</p>
              <?php echo $checkmark ?>
            </section>
            <p class="tiempo">Hace 2 horas</p>
            <section>
              <p class="titulo">Matemáticas<br>Intermedio</p>
              <p class="preguntas">10 preguntas</p>
            </section>
            <p class="calificacion">9.8</p>
            <div></div>
            <a class="btn small secondary accent" href="detalles.php?id_examen=10">Detalles</a>
          </article>

          <article class="complete">
            <section class="numero-container">
              <p class="numero">#10</p>
              <?php echo $checkmark ?>
            </section>
            <p class="tiempo">Hace 2 horas</p>
            <section>
              <p class="titulo">Matemáticas<br>Intermedio</p>
              <p class="preguntas">10 preguntas</p>
            </section>
            <p class="calificacion">9.8</p>
            <div></div>
            <a class="btn small secondary accent" href="detalles.php?id_examen=10">Detalles</a>
          </article>

          <article class="complete">
            <section class="numero-container">
              <p class="numero">#10</p>
              <?php echo $checkmark ?>
            </section>
            <p class="tiempo">Hace 2 horas</p>
            <section>
              <p class="titulo">Matemáticas<br>Intermedio</p>
              <p class="preguntas">10 preguntas</p>
            </section>
            <p class="calificacion">9.8</p>
            <div></div>
            <a class="btn small secondary accent" href="detalles.php?id_examen=10">Detalles</a>
          </article>

        </section>
      </section>

    </main>
  </section>
</body>

</html>
