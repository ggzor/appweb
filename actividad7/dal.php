<?php

function crear_conexion($host = 'localhost', $user = 'root', $pass = null, $db = "examenes")
{
  $conn = new mysqli($host, $user, $pass, $db);

  # Inicializar utf8
  $conn->query("SET NAMES utf8");

  return $conn;
}

function obtener_reactivos($conn, $id_usuario)
{
  $stmt = $conn->prepare("SELECT * FROM reactivo WHERE id_creador = ? ORDER BY fecha DESC");
  $stmt->bind_param("i", $id_usuario);
  $stmt->execute();
  $result = $stmt->get_result();
  $reactivos = [];
  while ($row = $result->fetch_assoc())
    $reactivos[$row['id_reactivo']] = $row;
  $result->close();
  $stmt->close();

  return $reactivos;
}

function obtener_temas($conn)
{
  $stmt = $conn->prepare("SELECT * FROM tema ORDER BY id_tema");
  $stmt->execute();
  $result = $stmt->get_result();
  $temas = [];
  while ($row = $result->fetch_assoc())
    $temas[$row['id_tema']] = $row['nombre'];
  $result->close();
  $stmt->close();

  return $temas;
}

function borrar_reactivo($conn, $id)
{
  $stmt = null;

  try {
    $stmt = $conn->prepare("DELETE FROM reactivo WHERE id_reactivo = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  } catch (\Throwable $th) {
    throw $th;
  } finally {
    if ($stmt)
      $stmt->close();
  }
}

function crear_reactivo($conn, $id_creador, $id_tema, $nivel, $enunciado, $multiple)
{
  $stmt = null;

  try {
    $stmt = $conn->prepare("
INSERT INTO reactivo (publicado, id_creador, id_tema, fecha, nivel, enunciado, multiple)
VALUES (false, ?, ?, NOW(), ?, ?, ?);
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
  multiple = ?,
  fecha = NOW()
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
