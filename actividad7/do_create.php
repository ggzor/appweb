<?php
session_start();

require_once 'utils.php';

require_once 'database.php';
solo_permitir([USUARIO_NORMAL]);

require_once 'dal.php';
$db = new ExamenesDB();


$id_usuario = $_SESSION['id_usuario'];
$id_tema = intval($_REQUEST['id_tema']);
$nivel = $_REQUEST['nivel'];
$cantidad = intval($_REQUEST['cantidad']);

$id_examen = $db->crear_examen($id_usuario, $id_tema, $nivel, $cantidad);

header("Location: solve.php?id_examen=$id_examen");
exit();
