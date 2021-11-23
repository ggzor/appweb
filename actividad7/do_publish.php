<?php
session_start();

require_once 'utils.php';

require_once 'database.php';
solo_permitir([USUARIO_ADMIN]);

require_once 'dal.php';
$db = new ExamenesDB();

$id_usuario = $_SESSION['id_usuario'];
$id_reactivo = intval($_REQUEST['id_reactivo']);
$reactivo = $db->obtener_reactivo_unico($id_reactivo);

if ($reactivo['id_creador'] != $id_usuario) {
  header('Location: index.php');
  exit();
}

$db->publicar_reactivo($id_reactivo);

header('Location: questions.php');
exit();
