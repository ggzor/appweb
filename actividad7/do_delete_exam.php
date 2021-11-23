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

$db->borrar_examen($id_examen);

header("Location: history.php");
exit();
