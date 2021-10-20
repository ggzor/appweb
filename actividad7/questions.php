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

    $todos_temas = [1 => "Todos los temas", 2 => "Matemáticas", 3 => "Español"];
    $todos_niveles = [1 => "Todos los niveles", 2 => "Básico", 3 => "Intermedio", 4 => "Avanzado"];
    ?>

    <main>

      <section class="filtros">

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
      <section class="tabla-reactivos">
        <p class="titulo">Id</p>
        <p class="titulo">Fecha <?php echo up_arrow() ?></p>
        <p class="titulo">Tema</p>
        <p class="titulo">Nivel</p>
        <p class="titulo">Enunciado</p>
        <p class="titulo">Publicado</p>
        <p class="titulo"></p>

        <p class="id">1</p>
        <p class="fecha">Hoy</p>
        <p class="tema">Matemáticas</p>
        <p class="nivel"><?php echo icono_para_nivel(NIVEL_BASICO) ?></p>
        <p class="enunciado">Francisco Chang</p>
        <p class="publicado"><?php echo icono_checkmark() ?></p>
        <div class="acciones">
          <?php echo icono_edit() ?>
          <?php echo icono_delete() ?>
        </div>


        <p class="id">2</p>
        <p class="fecha">02 Agosto</p>
        <p class="tema">Germany</p>
        <p class="nivel"><?php echo icono_para_nivel(NIVEL_AVANZADO) ?></p>
        <p class="enunciado">Lorem ipsum dolor sit acdsdf dss dfsfsh fdskhs fdsjkhmet consectetur adipisicing elit.
          Ea repellat facere voluptatum harum modi officia. </p>
        <p class="publicado"><?php echo icono_checkmark() ?></p>
        <div class="acciones">
          <?php echo icono_edit() ?>
          <?php echo icono_delete() ?>
        </div>

        <p class="id">3</p>
        <p class="fecha">08 de Otubre</p>
        <p class="tema">Germany</p>
        <p class="nivel"><?php echo icono_para_nivel(NIVEL_INTERMEDIO) ?></p>
        <p class="enunciado">Francisco Chang</p>
        <p class="publicado"><?php echo icono_checkmark() ?></p>
        <div class="acciones">
          <?php echo icono_edit() ?>
          <?php echo icono_delete() ?>
        </div>

      </section>
    </main>
  </section>

</body>

</html>