<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
</head>

<body>
    <?php
    require_once 'conjunto.php';

    function coleccion_crear($tamaño)
    {
        for ($i = 0; $i < $tamaño; $i++)
            $coleccion[$i] = rand(1, 20);

        return $coleccion;
    }

    $tamaño_a = $_REQUEST['tamaño_a'];
    $tamaño_b = $_REQUEST['tamaño_b'];

    $A = new Conjunto(coleccion_crear($tamaño_a));
    $B = new Conjunto(coleccion_crear($tamaño_b));

    $union = $A->union($B);
    $interseccion = $A->interseccion($B);
    $diferencia = $A->diferencia($B);

    echo "<h1> Resultado de operaciones </h1>";

    echo "<strong> A </strong> = $A <br>";
    echo "<strong> B </strong> = $B <br>";
    echo "<br>";
    echo "<strong> A ∪ B </strong> = $union <br>";
    echo "<strong> A ∩ B </strong> = $interseccion <br>";
    echo "<strong> A - B </strong> = $diferencia <br>";

    ?>
</body>

</html>