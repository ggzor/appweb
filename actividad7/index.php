<?php
require_once 'database.php';

session_start();
solo_permitir([USUARIO_INTERNAUTA]);

require_once 'componentes.php';
$reactivos = 100;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Examen</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="compartido.css">
  <link rel="stylesheet" href="index.css">
</head>

<body>
  <section id="userfirst" class="old-main">
    <section class="topbar">
      <?php echo logo() ?>
      <div></div>
      <section class="links">
        <a href="register.php">Registrarse</a>
        <div class="bar"></div>
        <a href="login.php">Iniciar sesión</a>
      </section>
    </section>

    <main>
      <section class="background">
        <svg class="hexagon top" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 409 344">
          <path fill="#E9EFFB" d="m122 111 80 60-11 99-92 40-80-59 11-100 92-40ZM309
             133l80 60-11 99-92 40-80-59 11-100 92-40ZM232-38l80
             60-11 99-92 40-80-59L140 2l92-40Z" />
        </svg>

        <svg class="hexagon bottom-left" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 222 222">
          <path fill="#E9EFFB" d="m122 11 80 60-11 99-92 40-80-59L30 51l92-40Z" />
        </svg>

        <svg class="hexagon bottom-right" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 586 301">
          <path fill="#BDCFF4" d="m305 34 80 60-11 99-92 40-80-59 11-100 92-40Z" />
          <path fill="#E9EFFB" d="m486 53 80 60-11 99-92 40-80-59 11-100 92-40ZM379 201l80
             60-11 99-92 40-80-59 11-100 92-40ZM195 179l80 60-11 99-92
             40-80-59 11-100 92-40ZM122 11l80 60-11 99-92 40-80-59L30 51l92-40Z" />
        </svg>

        <svg class="lapiz" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 472 472">
          <path style="fill: var(--color-accent)" d="M337 22c-30-14-66-1-80 29L111 359l-1 1v2l11 102v1a7
             7 0 0 0 2 2l2 2a7 7 0 0 0 6-1l86-55a6 6 0 0 0
             2-2v-1l146-307c15-30 2-66-28-81Zm-6 13c21 10 32 34 25
             56l-84-40c13-19 38-27 59-16Zm2 104-84-40 6-12 84 40-6
             12ZM144 429c-4-2-8 0-9 3l-3 7-8-74 27-13 8 34v2a7 7 0
             0 0 3 2 7 7 0 0 0 4 1h1l1-1 31-15 7 29-62 40 3-6c2-3
             0-7-3-9Zm67-63 90-190a7 7 0 1 0-12-6l-91 191-28 14-8-31
             91-191a7 7 0 1 0-12-6l-91 191-19 9 112-236 85 40-113
             236-4-21Zm134-251-85-40 6-12 85 40-6 12Z" />
        </svg>
      </section>

      <section class="foreground">
        <section>
          <b>+<?php echo ($reactivos - 1) ?></b> reactivos
        </section>
        <p>
          Comienza a <br>
          <b>crear</b> tus <br>
          <em>exámenes personalizados</em>
        </p>
        <a href="register.php" class="btn">Registrarse</a>
      </section>

      <div class="buttons-down">
        <a href="#details" class="btn secondary">Ver más <i class="bi bi-chevron-down"></i></a>
      </div>
    </main>
  </section>

  <section id="details" class="down">
    <section class="some-images">
      <section class="mucho-texto">
        <p>
          Varios <b>temas</b>, múltiples <b>niveles</b> de dificultad.
        </p>
        <p>
          Al registrarte, accedes a un banco de reactivos de diversos temas con
          múltiples dificultades. Tus respuestas serán evaluadas y se te
          asignará una calificación de acuerdo a tus aciertos.
        </p>
      </section>
      <section class="demo">
        <img src="imagenes/demo.png" alt="Demostración del funcionamiento">
      </section>
    </section>
    <section class="buttons-down">
      <a href="#userfirst" class="btn secondary">Regresar <i class="bi bi-chevron-up"></i></a>
    </section>
  </section>
</body>

</html>
