<?php

/**
 * Obtener el tipo de parámetro para el string de un prepared statement.
 */
function get_param_type(&$var)
{
  if (is_int($var))
    return 'i';
  else
    return 's';
}

/**
 * Realiza la llamada a un prepared statement utilizando el indizador indicado
 *
 * @param ?string $indizador El atributo sobre el cual indizar los resultados
 *
 */
function get_results(
  mysqli $conn,
  ?string $indizador,
  string $query,
  string $params_str,
  &...$params
) {
  $stmt_info = print_r([
    'query' => $query,
    'params_str' => $params_str,
    'params' => $params
  ], true);

  /** @var mysqli_stmt */
  $stmt = null;

  /** @var mysqli_result */
  $result = null;

  try {
    $stmt = $conn->prepare($query);

    if ($stmt === false)
      throw new Exception("Statement incorrecto: \n$stmt_info");

    if (!empty($params)) {
      if ($stmt->bind_param($params_str, ...$params) === false) {
        throw new Exception("Número de parámetros incorrecto: \n$stmt_info");
      }
    }

    if ($stmt->execute() === false) {
      throw new Exception("
Error al ejecutar el statement, revisa que los tipos sean los que
espera el procedimiento en la base de datos:
$stmt_info
");
    }

    $result = $stmt->get_result();

    if ($result === false) {
      throw new Exception("Error al obtener los resultados: \n$stmt_info");
    }

    $entidades = [];
    while ($row = $result->fetch_assoc()) {
      if ($indizador !== null)
        $entidades[$row[$indizador]] = $row;
      else
        $entidades[] = $row;
    }

    return $entidades;
  } catch (\Throwable $th) {
    throw $th;
  } finally {
    if ($result !== null)
      $result->close();
    if ($stmt !== null)
      $stmt->close();
  }
}

class Conexion
{
  private mysqli $conn;

  function __construct(
    $db,
    $host = 'localhost',
    $user = 'root',
    $pass = null
  ) {
    $this->conn = new mysqli($host, $user, $pass, $db);

    # Inicializar utf8
    $this->conn->query("SET NAMES utf8");
  }

  function getConn(): mysqli
  {
    return $this->conn;
  }

  function tabla(string $tabla)
  {
    return new Entidad($this, $tabla);
  }

  function procedure_idx(?string $indizador, string $procedure, &...$params)
  {
    $params_str = "";
    $procedure_call_str = "CALL $procedure(";

    foreach ($params as &$param) {
      $params_str .= get_param_type($param);
      $procedure_call_str .= "?, ";
    }

    $procedure_call_str = trim($procedure_call_str, ", ");
    $procedure_call_str .= ")";

    return get_results(
      $this->getConn(),
      $indizador,
      $procedure_call_str,
      $params_str,
      ...$params
    );
  }

  function procedure(string $procedure, &...$args)
  {
    return $this->procedure_idx(null, $procedure, ...$args);
  }

  function __destruct()
  {
    $this->conn->close();
  }
}

class Entidad
{
  private Conexion $padre;
  private string $tabla;

  function __construct(Conexion $padre, string $tabla)
  {
    $this->padre = $padre;
    $this->tabla = $tabla;
    $this->wheres = [];
    $this->orders = [];
  }

  /**
   * Sanitizar $atributo, de preferencia utilizar constantes
   * */
  function where($atributo, $valor)
  {
    $this->wheres[] = ['=', $atributo, $valor];
    return $this;
  }

  /**
   * Sanitizar $atributo y $tipo, de preferencia utilizar constantes
   * */
  function order_by(string $atributo, $tipo = 'ASC')
  {
    $this->orders[] = [$atributo, $tipo];
    return $this;
  }


  function get($indizador = null)
  {
    $query = "SELECT * FROM $this->tabla";
    $params_str = "";
    $params = [];

    foreach ($this->wheres as [$op, $attr, $valor]) {
      $query .= " WHERE $attr $op ?";

      $params_str[] = get_param_type($valor);
      $params[] = $valor;
    }

    foreach ($this->orders as [$atributo, $tipo]) {
      $query .= " ORDER BY $atributo $tipo";
      $params_str = get_param_type($valor);
    }

    return get_results(
      $this->padre->getConn(),
      $indizador,
      $query,
      $params_str,
      ...$params
    );
  }

  function get_idx()
  {
    return $this->get('id_' . $this->tabla);
  }
}
