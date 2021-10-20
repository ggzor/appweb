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
