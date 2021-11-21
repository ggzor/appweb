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
  <link rel="stylesheet" href="create.css">
</head>

<body>
  <section>
    <?php
    require_once 'componentes.php';

    echo logo();
    menu([
      'items' => [$m_history, $m_create, $m_about],
      'selected' => 1
    ]);
    ?>
  </section>

  <section>
    <?php links() ?>

    <main>
      <?php
      $todos_temas = $db->obtener_temas();
      $todos_niveles = TODOS_NIVELES;
      ?>
      <form action="do_create.php">
        <h1>Crear nuevo examen</h1>

        <h2>Tema</h2>
        <select name="tema" id="tema" required>
          <?php
          foreach ($todos_temas as $id_tema => $tema) {
            echo <<<EOF
              <option value="$id_tema">$tema[nombre]</option>
            EOF;
          }
          ?>
        </select>

        <h2>Nivel</h2>
        <select name="nivel" id="nivel" required>
          <?php
          foreach ($todos_niveles as $nivel) {
            $nivel_str = obtener_cadena_nivel($nivel);

            echo <<<EOF
              <option value="$nivel">$nivel_str</option>
            EOF;
          }
          ?>
        </select>

        <h2>Reactivos</h2>
        <input type="number" name="cantidad" value="10" id="cantidad" min="0" max="10" required>
        <p>Máximo: 30</p>

        <br>
        <input class="small" type="submit" value="Crear" required>
      </form>

      <section class="descripcion">
        <img src="imagenes/temas/matematicas.png" alt="Matemáticas" required>
        <p>
          Ejercicios que requieren de tu habilidad lógica y de calcular para resolverlos.
        </p>
      </section>
    </main>
  </section>
</body>

</html>