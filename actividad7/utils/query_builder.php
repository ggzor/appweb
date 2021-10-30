<?php

/**
 * Obtener el tipo de parámetro para el string de un prepared statement.
 */
function get_param_type(&$var)
{
  if (is_int($var) || is_bool($var))
    return 'i';
  else
    return 's';
}

class PreparedResult
{
  public int $affected_rows;
  public int $insert_id;
  /**
   * @var false|mysqli_result $result
   */
  public $result;

  function __construct($affected_rows, $insert_id, $result)
  {
    $this->affected_rows = $affected_rows;
    $this->insert_id = $insert_id;
    $this->result = $result;
  }

  function __destruct()
  {
    if ($this->result !== false)
      $this->result->close();
  }
}

function run_prepared(mysqli $conn, string $query, string $params_str, &...$params): PreparedResult
{
  $stmt_info = print_r([
    'query' => $query,
    'params_str' => $params_str,
    'params' => $params
  ], true);

  /** @var mysqli_stmt */
  $stmt = null;

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

    return new PreparedResult(
      $stmt->affected_rows,
      $stmt->insert_id,
      $stmt->get_result()
    );
  } finally {
    if ($stmt !== null)
      $stmt->close();
  }
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

  $prepared_result = run_prepared($conn, $query, $params_str, ...$params);

  if ($prepared_result->result === false) {
    throw new Exception("
Error al obtener los resultados, puede que sea un statement que no devuelve valores:
$stmt_info
");
  }

  $entidades = [];
  while ($row = $prepared_result->result->fetch_assoc()) {
    if ($indizador !== null)
      $entidades[$row[$indizador]] = $row;
    else
      $entidades[] = $row;
  }

  return $entidades;
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

    $this->indizador = false;
    $this->wheres = [];
    $this->orders = [];
  }

  /**
   * Sanitizar $by, de preferencia utilizar constantes.
   */
  function index($by = null)
  {
    if ($by === null)
      $this->indizador = "id_" . $this->tabla;
    else
      $this->indizador = $by;

    return $this;
  }

  /**
   * Sanitizar $atributo, de preferencia utilizar constantes.
   * */
  function where($atributo, $valor)
  {
    $this->wheres[] = ['=', $atributo, $valor];
    return $this;
  }

  /**
   * Sanitizar $atributo y $tipo, de preferencia utilizar constantes.
   * */
  function order_by(string $atributo, $tipo = 'ASC')
  {
    $this->orders[] = [$atributo, $tipo];
    return $this;
  }

  private function agregar_wheres(string &$query, string &$params_str, array &$params)
  {
    foreach ($this->wheres as [$op, $attr, $valor]) {
      $query .= " WHERE $attr $op ?";

      $params_str .= get_param_type($valor);
      $params[] = $valor;
    }
  }

  private function agregar_orderby(string &$query)
  {
    foreach ($this->orders as [$atributo, $tipo]) {
      $query .= " ORDER BY $atributo $tipo";
    }
  }

  function select($atributo = '*', ...$otros_atributos)
  {
    $todos_atributos = [$atributo, ...$otros_atributos];
    $selecciona_todos = count($todos_atributos) === 1 && $todos_atributos[0] === '*';

    $insertar_indizador =  !$selecciona_todos
      && $this->indizador !== false
      && !in_array($this->indizador, $todos_atributos);

    if ($insertar_indizador) {
      $todos_atributos[] = $this->indizador;
    }

    $select_str = implode(',', $todos_atributos);

    $query = "SELECT $select_str FROM $this->tabla";
    $params_str = "";
    $params = [];

    $this->agregar_wheres($query, $params_str, $params);
    $this->agregar_orderby($query);

    $resultados = get_results(
      $this->padre->getConn(),
      $this->indizador,
      $query,
      $params_str,
      ...$params
    );

    if ($insertar_indizador) {
      foreach ($resultados as &$value) {
        unset($value[$this->indizador]);
      }
    }

    if (!$selecciona_todos && empty($otros_atributos)) {
      return array_map(fn ($row) => array_values($row)[0], $resultados);
    } else {
      return $resultados;
    }
  }

  function delete(): int
  {
    if (empty($this->wheres)) {
      throw new Exception("
Debes especificar un where al menos o se borrará toda la tabla.
Si esto es lo que deseas, llama al metodo truncate()
");
    }

    $query = "DELETE FROM $this->tabla";
    $params_str = "";
    $params = [];

    $this->agregar_wheres($query, $params_str, $params);

    $result = run_prepared($this->padre->getConn(), $query, $params_str, ...$params);
    return $result->affected_rows;
  }

  function insert(array $keyValues): int
  {
    $query = "INSERT INTO $this->tabla (";
    $params_str = "";
    $params = [];

    foreach ($keyValues as $key => $value) {
      $query .= "$key, ";
    }

    $query = trim($query, ', ');
    $query .= ') VALUES (';

    foreach ($keyValues as $key => $value) {
      $params_str .= get_param_type($value);
      $params[] = $value;
      $query .= '?, ';
    }

    $query = trim($query, ', ');
    $query .= ')';

    $result = run_prepared($this->padre->getConn(), $query, $params_str, ...$params);
    return $result->insert_id;
  }
}
