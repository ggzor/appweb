<?php

require_once "utils/query_builder.php";

const DEFAULT_DATABASE = "examenes";

class ExamenesDB extends Conexion
{
  function __construct()
  {
    parent::__construct("examenes", "localhost", "root", null);
  }

  function obtener_reactivos(
    int $id_usuario,
    string $busqueda,
    int $tema,
    string $nivel
  ) {
    return $this->procedure_idx(
      'id_reactivo',
      'hacer_query',
      $id_usuario,
      $busqueda,
      $tema,
      $nivel
    );
  }

  function obtener_temas()
  {
    return $this->tabla('tema')
      ->select('nombre')
      ->order_by('id_tema')
      ->get_idx();
  }

  function borrar_reactivo($id)
  {
    return $this->tabla('reactivo')
      ->where('id_reactivo', $id)
      ->delete();
  }
}

function crear_conexion($host = 'localhost', $user = 'root', $pass = null, $db = "examenes")
{
  $conn = new mysqli($host, $user, $pass, $db);

  # Inicializar utf8
  $conn->query("SET NAMES utf8");

  return $conn;
}

function crear_reactivo($conn, $id_creador, $id_tema, $nivel, $enunciado, $multiple)
{
  $stmt = null;

  try {
    $stmt = $conn->prepare("
INSERT INTO reactivo (publicado, id_creador, id_tema, nivel, enunciado, multiple)
VALUES (false, ?, ?, ?, ?, ?);
");
    $stmt->bind_param("iissi", $id_creador, $id_tema, $nivel, $enunciado, $multiple);
    if ($stmt->execute())
      return $stmt->insert_id;
    else
      return false;
  } catch (\Throwable $th) {
    throw $th;
  } finally {
    if ($stmt)
      $stmt->close();
  }
}

function crear_opcion($conn, $id_reactivo, $correcta, $contenido)
{
  $stmt = null;

  try {
    $stmt = $conn->prepare("
INSERT INTO opcion (id_reactivo, correcta, contenido)
VALUES (?, ?, ?);
");
    $stmt->bind_param("iis", $id_reactivo, $correcta, $contenido);
    if ($stmt->execute())
      return $stmt->insert_id;
    else
      return false;
  } catch (\Throwable $th) {
    throw $th;
  } finally {
    if ($stmt)
      $stmt->close();
  }
}

function obtener_informacion_reactivo($conn, $id_reactivo)
{
  $stmt = $conn->prepare("SELECT * FROM reactivo WHERE id_reactivo = ?");
  $stmt->bind_param("i", $id_reactivo);
  $stmt->execute();
  $result = $stmt->get_result();

  $reactivo = $result->fetch_assoc();

  $result->close();
  $stmt->close();

  return $reactivo;
}
function obtener_opciones_por_reactivo($conn, $id_reactivo)
{
  $stmt = $conn->prepare("SELECT * FROM opciones_por_reactivo WHERE id_reactivo = ?");
  $stmt->bind_param("i", $id_reactivo);
  $stmt->execute();
  $result = $stmt->get_result();
  $opciones = [];
  while ($row = $result->fetch_assoc())
    $opciones[] = $row;
  $result->close();
  $stmt->close();

  return $opciones;
}

function actualizar_reactivo($conn, $id_reactivo, $id_tema, $nivel, $enunciado, $multiple)
{
  $stmt = null;

  try {
    $stmt = $conn->prepare("
UPDATE reactivo
SET
  id_tema = ?,
  nivel = ?,
  enunciado = ?,
  multiple = ?
WHERE id_reactivo = ?;
");
    $stmt->bind_param("issii", $id_tema, $nivel, $enunciado, $multiple, $id_reactivo);
    return $stmt->execute();
  } catch (\Throwable $th) {
    throw $th;
  } finally {
    if ($stmt)
      $stmt->close();
  }
}

function actualizar_opcion($conn, $id_opcion, $correcta, $contenido)
{
  $stmt = null;

  try {
    $stmt = $conn->prepare("
UPDATE opcion
SET
  correcta = ?,
  contenido = ?
WHERE id_opcion = ?;
");
    $stmt->bind_param("isi", $correcta, $contenido, $id_opcion);
    return $stmt->execute();
  } catch (\Throwable $th) {
    throw $th;
  } finally {
    if ($stmt)
      $stmt->close();
  }
}
