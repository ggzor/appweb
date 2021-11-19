<?php
session_start();

require_once 'database.php';
solo_permitir([USUARIO_NORMAL, USUARIO_ADMIN]);
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
  <link rel="stylesheet" href="about.css">
</head>

<body>
  <section>
    <?php
    require_once 'componentes.php';

    $is_admin = obtener_tipo_usuario() === USUARIO_ADMIN;

    $items = $is_admin
      ? [$m_questions, $m_about]
      : [$m_history, $m_create, $m_about];

    echo logo();
    menu([
      'items' => $items,
      'selected' => $is_admin ? 1 : 2
    ]);
    ?>
  </section>

  <section>
    <?php links();
    ?>

    <?php
    $icono_php = icono_php();
    $icono_mysql = icono_mysql();
    $icono_html = icono_html();
    $icono_css = icono_css();
    $icono_js = icono_js();
    $icono_alpine = icono_alpine();

    $icono_github = icono_github();
    $icono_gmail = icono_gmail();

    echo <<<EOF
        <main>
          <h2 class="title-2">Acerca de</h2>
          <p>Proyecto final de la materia <b>Aplicaciones Web Otoño 2021.</b></p>

          <div class="iconos">
            <a href="https://www.php.net" target="_blank">$icono_php</a>
            <a href="https://www.mysql.com/" target="_blank">$icono_mysql</a>
            <a href="https://developer.mozilla.org/es/docs/Web/HTML" target="_blank">$icono_html </a>
            <a href="https://developer.mozilla.org/es/docs/Web/CSS" target="_blank">$icono_css</a>
            <a href="https://developer.mozilla.org/es/docs/Web/JavaScript" target="_blank">$icono_js</a>
            <a href="https://alpinejs.dev/" target="_blank">$icono_alpine</a>
          </div>

          <h2 class="title-2">Autores </h2>
          <section class="autores">
            <div class="autor">
              <div class="barra"></div>
              <div class="datos">
                <p class="nombre">Axel Suárez Polo</p>
                <a href="https://github.com/ggzor" class="github" target="_blank">$icono_github @ggzor</a>
                <a href="mailto:ggzorgg@outlook.com" class="gmail" target="_blank">$icono_gmail ggzorgg@outlook.com</a>
              </div>
            </div>

            <div class="autor">
              <div class="barra"></div>
              <div class="datos">
                <p class="nombre">Marisol Huitzil Juárez</p>
                <a href="https://github.com/Axol44" class="github" target="_blank">$icono_github @Axol44</a>
                <a href="mailto:k3ho45@gmail.com" class="gmail" target="_blank">$icono_gmail k3ho45@gmail.com</a>
              </div>
            </div>
          </section>
        </main>
        EOF;
    ?>
  </section>
</body>

</html>
