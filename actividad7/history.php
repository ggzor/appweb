<?php
session_start();

require_once 'database.php';
solo_permitir([USUARIO_NORMAL]);
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
      'items' => [$m_history, $m_create, $m_about],
      'selected' => 0
    ]);
    ?>
  </section>

  <section>
    <?php links() ?>

    <main>
      <?php
      $id_usuario = $_SESSION['id_usuario'];

      $conn = new mysqli("localhost", "root", null, "examenes");
      $conn->query("SET NAMES utf8");

      $stmt = $conn->prepare("SELECT id_examen, fecha FROM examen WHERE id_usuario = ?");
      $stmt->bind_param("i", $id_usuario);
      $stmt->execute();
      $result = $stmt->get_result();
      $examenes = $result->fetch_all();
      $result->close();
      $stmt->close();

      foreach ($examenes as [$id_examen, $fecha]) {
        echo "<h3>Examen $id_examen - $fecha</h3>";

        $stmt = $conn->prepare("SELECT * FROM reactivos_por_examen WHERE id_examen = ?");
        $stmt->bind_param("i", $id_examen);
        $stmt->execute();
        $result = $stmt->get_result();
        $reactivos = [];
        while ($row = $result->fetch_assoc())
          $reactivos[$row['id_reactivo']] = $row;
        $result->close();
        $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM elegidas_por_reactivo WHERE id_examen = ?");
        $stmt->bind_param("i", $id_examen);
        $stmt->execute();
        $result = $stmt->get_result();
        $opciones = [];
        while ($row = $result->fetch_assoc()) {
          if (!array_key_exists($row['id_reactivo'], $opciones))
            $opciones[$row['id_reactivo']] = [];

          $opciones[$row['id_reactivo']][$row['id_opcion']] = $row;
        }
        $result->close();
        $stmt->close();

        echo "<ol>";
        foreach ($reactivos as $id_reactivo => $reactivo) {
          $reactivo_opciones = $opciones[$id_reactivo];
          $tipo_entrada = $reactivo['multiple'] ? "checkbox" : "radio";

          echo "<li>";

          echo "<p>$reactivo[enunciado]</p>";
          echo "<ol type='a'>";

          $primero = true;
          foreach ($reactivo_opciones as $id_opcion => $opcion) {
            if ($opcion['id_opcion_elegida'] == NULL) {
              $seleccionado = $primero && !$reactivo['multiple'];
            } else {
              $seleccionado = true;
            }
            $checked = $seleccionado ? 'checked' : '';

            $primero = false;

            echo "<li>";
            echo "
<input id='opcion_$id_opcion' name='reactivo_$id_reactivo' type='$tipo_entrada' $checked>
<label for='opcion_$id_opcion'>$opcion[contenido]</label>
<br>";
            echo "</li>";
          }
          echo "</ol>";
          echo "</li>";
        }
        echo "</ol>";
      }
      ?>
    </main>
  </section>
</body>

</html>