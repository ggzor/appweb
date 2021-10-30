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
      ->order_by('id_tema')
      ->index()
      ->select('nombre');
  }

  function borrar_reactivo($id)
  {
    return $this->tabla('reactivo')
      ->where('id_reactivo', $id)
      ->delete();
  }

  function crear_reactivo(string $id_creador, int $id_tema, string $nivel, string $enunciado, bool $multiple)
  {
    # Trick to pass parameters for free
    $params = get_defined_vars();
    $params['publicado'] = false;

    return $this->tabla('reactivo')
      ->insert($params);
  }

  function crear_opcion(int $id_reactivo, bool $correcta, string $contenido)
  {
    $params = get_defined_vars();
    return $this->tabla('opcion')
      ->insert($params);
  }

  function obtener_reactivo_unico(int $id_reactivo)
  {
    return $this->tabla('reactivo')
      ->where('id_reactivo', $id_reactivo)
      ->single();
  }

  function obtener_opciones_por_reactivo(int $id_reactivo)
  {
    return $this->tabla('opciones_por_reactivo')
      ->where('id_reactivo', $id_reactivo)
      ->select();
  }

  function actualizar_reactivo($id_reactivo, $id_tema, $nivel, $enunciado, $multiple)
  {
    $params = get_defined_vars();
    # Do not change the id
    unset($params['id_reactivo']);

    return $this->tabla('reactivo')
      ->where('id_reactivo', $id_reactivo)
      ->update($params);
  }
}

function crear_conexion($host = 'localhost', $user = 'root', $pass = null, $db = "examenes")
{
  $conn = new mysqli($host, $user, $pass, $db);

  # Inicializar utf8
  $conn->query("SET NAMES utf8");

  return $conn;
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
