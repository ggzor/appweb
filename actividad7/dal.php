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
      ->select();
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

  function actualizar_reactivo(
    int $id_reactivo,
    int $id_tema,
    string $nivel,
    string $enunciado,
    bool $multiple
  ) {
    $params = get_defined_vars();
    # Do not change the id
    unset($params['id_reactivo']);

    return $this->tabla('reactivo')
      ->where('id_reactivo', $id_reactivo)
      ->update($params);
  }

  function actualizar_opcion(int $id_opcion, bool $correcta, string $contenido)
  {
    $params = get_defined_vars();
    # Do not change the id
    unset($params['id_opcion']);

    return $this->tabla('opcion')
      ->where('id_opcion', $id_opcion)
      ->update($params);
  }

  function conservar_opciones(int $id_reactivo, array $mantener)
  {
    return $this->tabla('opcion')
      ->where('id_reactivo', $id_reactivo)
      ->where('id_opcion', 'NOT IN', $mantener)
      ->delete();
  }
}
