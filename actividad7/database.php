<?php

const USUARIO_INTERNAUTA = -1;
const USUARIO_ADMIN = 0;
const USUARIO_NORMAL = 1;

const NIVEL_BASICO = 'BASICO';
const NIVEL_INTERMEDIO = 'INTERMEDIO';
const NIVEL_AVANZADO = 'AVANZADO';
const TODOS_NIVELES = [NIVEL_BASICO, NIVEL_INTERMEDIO, NIVEL_AVANZADO];

function obtener_cadena_nivel($nivel)
{
  switch ($nivel) {
    case NIVEL_BASICO:
      return 'Básico';
    case NIVEL_INTERMEDIO:
      return 'Intermedio';
    case NIVEL_AVANZADO:
      return 'Avanzado';
    default:
      return 'Desconocido';
  }
}

function obtener_tipo_usuario()
{
  if (array_key_exists('usuario', $_SESSION)) {
    return $_SESSION['tipo'];
  } else {
    return USUARIO_INTERNAUTA;
  }
}

function obtener_pagina_para($tipo)
{
  switch ($tipo) {
    case USUARIO_INTERNAUTA:
      return 'index.php';
    case USUARIO_ADMIN:
      return 'questions.php';
    case USUARIO_NORMAL:
      return 'history.php';
    default:
      return 'index.php';
  }
}

function solo_permitir($usuarios_permitidos)
{
  $tipo = obtener_tipo_usuario();

  if (!in_array($tipo, $usuarios_permitidos)) {
    $pagina = obtener_pagina_para($tipo);
    header("Location: $pagina");
    exit();
  }
}
