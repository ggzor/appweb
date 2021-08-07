<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php
    // Variables
    $ra = 5; $ca = 5;
    $rb = 5; $cb = 5;

    function matriz_crear($filas, $columnas, $max = 50, $min = 0) {
      for($i = 0; $i < $filas; $i++)
        for($j = 0; $j < $columnas; $j++)
          $matriz[$i][$j] = rand($min, $max);

      return $matriz;
    }

    $matriz_a = matriz_crear($ra, $ca);
    $matriz_b = matriz_crear($rb, $cb);

    function matriz_str($matriz) {
      $filas = count($matriz);
      $columnas = count($matriz[0]);

      for($i = 0; $i < $filas; $i++) {
        for($j = 0; $j < $columnas; $j++)
          $resultado .= sprintf('%2d ', $matriz[$i][$j]);
        
        $resultado .= "\n";
      }

      return $resultado;
    }

    function imprimir_matriz($i, $matriz) {
      $filas = count($matriz);
      $columnas = count($matriz[0]);
      $str = matriz_str($matriz);

      echo "
        <p>
          <strong>Matriz $i</strong> - $filas x $columnas
        </p>
        <pre>$str</pre>";
    }

    imprimir_matriz(1, $matriz_a);
    imprimir_matriz(2, $matriz_b);

    function matriz_suma($a, $b) {
      if (count($a) !== count($b) || count($a[0]) == count($b[0])) {

      }

      $filas = count($a);
      $columnas = count($a[0]);

      for($i = 0; $i < $filas; $i++)
        for($j = 0; $j < $columnas; $j++)
          $resultado[$i][$j] = $a[$i][$j] + $b[$i][$j];

      return $resultado;
    }

    // function resta($a, $b) {
    //   $c = array();

    //   for($i = 0; $i < count($a); $i++) {
    //     for($j = 0; $j < count($a[0]); $j++) {
    //       $c[$i][$j] = $a[$i][$j] - $b[$i][$j];
    //     }
    //   }

    //   return($c);
    // }

    // function multi($a, $b) {
    //   if(count($a[0]) != count($b)) {
    //     return 'Matrices incompatibles, no se puede realizar la operación';
    //   }
      
    //   $c = array();

    //   for($i = 0; $i < count($a); $i++) {
    //     for($j = 0; $j < count($b[0]); $j++) {
    //       $c[$i][$j] = 0;
    //       for($k = 0; $k < count($b); $k++) {
    //         $c[$i][$j] += $a[$i][$k] * $b[$k][$j];
    //       }
    //     }
    //   }

    //   return($c);
    // }

    // $matrizA = Array([1,2,6], [4,7,6], [3,2,3]);
    // $matrizB = Array([10,11], [20,21]);
    // //imprimirMatriz($matrizA);
    // //imprimirMatriz($matrizB);
    // imprimirMatriz(crearMatriz(2,2));
  ?>
</body>
</html>
