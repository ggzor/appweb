<?php
session_start();

require_once 'utils.php';

require_once 'database.php';
solo_permitir([USUARIO_NORMAL]);

require_once 'dal.php';
$db = new ExamenesDB();

$id_usuario = $_SESSION['id_usuario'];

$id_examen = intval($_GET['id_examen']);
$examen = $db->obtener_examen($id_examen);

$calificacion = 0.0;

$reactivos_examen = $db->obtener_reactivos_por_examen($id_examen);
$puntos_por_reactivo = 10.0 / count($reactivos_examen);

foreach ($reactivos_examen as $reactivo) {
  $opciones = $db->obtener_elegidas_por_reactivo($id_examen, $reactivo['id_reactivo']);

  if ($reactivo['multiple']) {
    $total = count($opciones);
  } else {
    $total = 1;
  }

  $correctas = 0;

  foreach ($opciones as $opcion) {
    $elegida = $opcion['id_opcion_elegida'] !== null;

    if ($reactivo['multiple']) {
      if ($opcion['correcta'] == $elegida) {
        $correctas += 1;
      }
    } else {
      if ($opcion['correcta'] && $elegida) {
        $correctas += 1;
      }
    }
  }

  $calificacion_reactivo = (floatval($correctas) / floatval($total)) * $puntos_por_reactivo;

  $calificacion += $calificacion_reactivo;
}

$db->asignar_calificacion($id_examen, $calificacion);

header("Location: details.php?id_examen=$id_examen");
exit();
