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

  function obtener_reactivos_por_examen(int $id_examen)
  {
    return $this->tabla('reactivos_por_examen')
      ->where('id_examen', $id_examen)
      ->select();
  }

  function obtener_elegidas_por_reactivo(int $id_examen, int $id_reactivo)
  {
    return $this->tabla('elegidas_por_reactivo')
      ->where('id_examen', $id_examen)
      ->where('id_reactivo', $id_reactivo)
      ->select();
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

  function obtener_maximos_por_tema()
  {
    $temas = $this->tabla('maximos_por_tema')
      ->select();

    $temas_dict = [];
    foreach ($temas as $t) {
      $temas_dict[$t['id_tema']][$t['nivel']] = $t['cantidad'];
    }

    return $temas_dict;
  }

  function crear_examen(int $id_usuario, int $id_tema, string $nivel, int $cantidad)
  {
    return $this->run_function('crear_examen', $id_usuario, $id_tema, $nivel, $cantidad);
  }

  function obtener_examenes(int $id_usuario)
  {
    return $this->tabla('examen')
      ->where('id_usuario', $id_usuario)
      ->order_by('fecha', 'DESC')
      ->select();
  }

  function obtener_examen(int $id_examen)
  {
    return $this->tabla('examen')
      ->where('id_examen', $id_examen)
      ->single();
  }

  function borrar_opciones_elegidas($id_opciones_elegidas)
  {
    return $this->tabla('opcion_elegida')
      ->where('id_opcion_elegida', 'IN', $id_opciones_elegidas)
      ->delete();
  }

  function elegir_opcion(int $id_ref_reactivo, int $id_opcion)
  {
    $params = get_defined_vars();

    return $this->tabla('opcion_elegida')
      ->insert($params);
  }

  function asignar_calificacion(int $id_examen, float $calificacion)
  {
    return $this->tabla('examen')
      ->where('id_examen', $id_examen)
      ->update(['calificacion' => $calificacion]);
  }

  function publicar_reactivo(int $id_reactivo)
  {
    return $this->tabla('reactivo')
      ->where('id_reactivo', $id_reactivo)
      ->update(['publicado' => true]);
  }

  function borrar_examen($id_examen)
  {
    return $this->tabla('examen')
      ->where('id_examen', $id_examen)
      ->delete();
  }
}
