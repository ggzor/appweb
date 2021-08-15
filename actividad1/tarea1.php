<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Actividad 1: Operaciones con matrices</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: sans-serif;
    }

    table {
      text-align: center;
      border-spacing: 1em 0.25em;

      border: 1px black;
      border-left-style: solid;
      border-right-style: solid;
      border-radius: 0.5em;

      padding: 0 0.25em;
    }
  </style>
</head>
<body>
  <?php
    function matriz_crear($filas, $columnas, $max = 10, $min = 0) {
      for($i = 0; $i < $filas; $i++)
        for($j = 0; $j < $columnas; $j++)
          $matriz[$i][$j] = rand($min, $max);

      return $matriz;
    }

    function matriz_dim($m) {
      return [count($m), count($m[0])];
    }

    function matriz_suma($a, $b) {
      [$ra, $ca] = matriz_dim($a);
      [$rb, $cb] = matriz_dim($b);

      if ($ra !== $rb || $ca !== $cb)
        throw new Exception("No se pueden sumar las matrices");

      for($i = 0; $i < $ra; $i++)
        for($j = 0; $j < $ca; $j++)
          $resultado[$i][$j] = $a[$i][$j] + $b[$i][$j];

      return $resultado;
    }

    function matriz_diferencia($a, $b) {
      [$ra, $ca] = matriz_dim($a);
      [$rb, $cb] = matriz_dim($b);

      if ($ra !== $rb || $ca !== $cb)
        throw new Exception("No se pueden restar las matrices");

      for($i = 0; $i < $ra; $i++)
        for($j = 0; $j < $ca; $j++)
          $resultado[$i][$j] = $a[$i][$j] - $b[$i][$j];

      return $resultado;
    }

    function matriz_producto($a, $b) {
      [$ra, $ca] = matriz_dim($a);
      [$rb, $cb] = matriz_dim($b);

      if ($ca !== $rb)
        throw new Exception("No se pueden multiplicar las matrices");

      for ($i = 0; $i < $ra; $i++) {
        for ($j = 0; $j < $cb; $j++) {
          $suma = 0;
          for ($k = 0; $k < $ca; $k++)
            $suma += $a[$i][$k] * $b[$k][$j];

          $resultado[$i][$j] = $suma;
        }
      }

      return $resultado;
    }

    function imprimir_matriz($nombre, $matriz) {
      [$r, $c] = matriz_dim($matriz);

      echo "
        <p>
          <strong>$nombre</strong> - $r x $c
        </p>";

      echo "<table>";
      for ($i = 0; $i < $r; $i++) {
        echo "<tr>";
        for ($j = 0; $j < $c; $j++) {
          echo "<td>{$matriz[$i][$j]}</td>";
        }
        echo "</tr>";
      }
      echo "</table>";
    }

    $ra = 3; $ca = 3;
    $rb = 3; $cb = 3;

    $ma = matriz_crear($ra, $ca);
    $mb = matriz_crear($rb, $cb);

    imprimir_matriz("Matriz 1", $ma);
    imprimir_matriz("Matriz 2", $mb);

    echo '<hr>';

    try {
      $suma = matriz_suma($ma, $mb);
      $diferencia = matriz_diferencia($ma, $mb);

      imprimir_matriz("Suma", $suma);
      imprimir_matriz("Diferencia", $diferencia);
    } catch (Exception $e) {
      echo "<strong>Las matrices no se pueden sumar o restar.</strong> <br>";
    }

    try {
      $producto = matriz_producto($ma, $mb);

      imprimir_matriz("Producto", $producto);
    } catch (Exception $e) {
      echo "<strong>Las matrices no se pueden multiplicar.</strong> <br>";
    }
  ?>
</body>
</html>
