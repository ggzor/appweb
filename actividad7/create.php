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

    <?php
    $todos_temas = $db->obtener_temas();
    $todos_niveles = TODOS_NIVELES;

    $target_data = mb_ereg_replace(
      '"',
      "'",
      json_encode([
        'tema' => 1,
        'nivel' => 'BASICO',
        'todosTemas' => $todos_temas,
        'maximos' => $db->obtener_maximos_por_tema()
      ], JSON_UNESCAPED_UNICODE)
    );
    ?>

    <script>
      document.addEventListener('alpine:init', () => {
        Alpine.store('data', {
          ...<?php echo $target_data ?>,
          get maximoActual() {
            return Math.min(this.maximos[this.tema]?. [this.nivel] ?? 0, 20)
          },
          get imagen() {
            return this.todosTemas[this.tema]['imagen_tema']
          },
          get descripcion() {
            return this.todosTemas[this.tema]['descripcion_tema']
          },
        })
      })
    </script>

    <main x-data="<?php echo $target_data ?>">
      <h1>Crear nuevo examen</h1>
      <section>
        <form action="do_create.php" method="POST">
          <h2>Tema</h2>
          <select name="id_tema" id="id_tema" required @change="$store.data.tema = $event.target.value" autocomplete="off">
            <?php
            foreach ($todos_temas as $id_tema => $tema) {
              echo <<<EOF
              <option value="$id_tema">$tema[nombre]</option>
            EOF;
            }
            ?>
          </select>

          <h2>Nivel</h2>
          <section class="linea-nivel">
            <div class="icono-nivel" :data-nivel="$store.data.nivel">
              <?php
              echo icono_para_nivel('BASICO', 10);
              echo icono_para_nivel('INTERMEDIO', 10);
              echo icono_para_nivel('AVANZADO', 10);
              ?>
            </div>

            <select id="nivel" name="nivel" required @change="$store.data.nivel = $event.target.value" autocomplete="off">
              <?php
              foreach ($todos_niveles as $nivel) {
                $nivel_str = obtener_cadena_nivel($nivel);

                echo <<<EOF
                  <option value="$nivel">$nivel_str</option>
                EOF;
              }
              ?>
            </select>
          </section>

          <h2>Reactivos</h2>
          <input x-ref="cantidad" type="number" x-effect="$refs.cantidad.value = Math.min($store.data.maximoActual, this.cantidad.value)" value="1" name="cantidad" id="cantidad" min="1" :max="$store.data.maximoActual" required>
          <p>Máximo: <span x-text="$store.data.maximoActual"></span></p>

          <br>
          <input class="small" type="submit" value="Crear" required>
        </form>

        <section class="descripcion">
          <img :src="$store.data.imagen" :alt="$store.data.nombre_tema" required>
          <p x-text="$store.data.descripcion" />
        </section>
      </section>
    </main>
  </section>

  <script src="public/alpine.js"></script>
</body>

</html>