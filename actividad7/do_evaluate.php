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

# FIXME: Check if the exam is already answered

# Construir a partir de la información existente
$agregar = [];
$borrar = [];

foreach ($db->obtener_reactivos_por_examen($id_examen) as $reactivo) {
  $borrar[$reactivo['id_reactivo']] = [];
  $agregar[$reactivo['id_reactivo']] = [
    'id_ref_reactivo' => $reactivo['id_ref_reactivo'],
    'agregar' => []
  ];

  foreach ($db->obtener_elegidas_por_reactivo($id_examen, $reactivo['id_reactivo']) as $opcion) {
    $borrar[$reactivo['id_reactivo']][$opcion['id_opcion']] = $opcion['id_opcion_elegida'];
  }
}

const REACTIVO_RADIO_PREFIJO = 'reactivo_';
const REACTIVO_CHECK_PREFIJO = 'opcioncheck_';
const REACTIVO_OPCION_CHECK_PREFIJO = '_opcion_';

$handle_reactivo = function (int $id_reactivo, int $id_opcion) use (&$agregar, &$borrar) {
  if ($borrar[$id_reactivo][$id_opcion] != null) {
    $borrar[$id_reactivo][$id_opcion] = null;
  } else {
    $agregar[$id_reactivo]['agregar'][] = $id_opcion;
  }
};

# Actualizar con la información de POST
foreach ($_REQUEST as $key => $value) {
  if (starts_with($key, REACTIVO_RADIO_PREFIJO)) {
    $id_reactivo = intval(remove_prefix($key, REACTIVO_RADIO_PREFIJO));
    $id_opcion = intval($value);

    $handle_reactivo($id_reactivo, $id_opcion);
  } elseif (starts_with($key, REACTIVO_CHECK_PREFIJO)) {
    [$id_reactivo, $_, $id_opcion] = explode('_', remove_prefix($key, REACTIVO_CHECK_PREFIJO));
    $id_reactivo = intval($id_reactivo);
    $id_opcion = intval($id_opcion);

    $handle_reactivo($id_reactivo, $id_opcion);
  }
}

# Procesar información cruda
$borrar_ids = array_values(array_filter(array_merge([], ...array_values($borrar)), fn ($v) => $v != null));
$agregar_params = [];

foreach ($agregar as ['id_ref_reactivo' => $id_ref_reactivo, 'agregar' => $ids]) {
  foreach ($ids as $id_opcion) {
    $agregar_params[] = [$id_ref_reactivo, $id_opcion];
  }
}

# Ejecutar acciones
if (count($borrar_ids) > 0) {
  $db->borrar_opciones_elegidas($borrar_ids);
}
foreach ($agregar_params as [$id_ref_reactivo, $id_opcion]) {
  $db->elegir_opcion($id_ref_reactivo, $id_opcion);
}

header('Location: history.php');
exit();
