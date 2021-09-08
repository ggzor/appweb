<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfica</title>
    <link rel="stylesheet" href="compartido.css" type="text/css" media="all" />
    <link rel="stylesheet" href="grafica.css" type="text/css" media="all" />
</head>

<body>
    <header>
        <image src="assets/pato.png" />
        <a href="index.php">Ventas</a>
        <a href="registro.php">Registro</a>
        <a href="grafica.php" data-active>Gráfica</a>
    </header>
    <main>
        <h2>Conteo de ventas por vendedor</h2>

        <?php
        $conn = new mysqli("localhost", "root", null, "concesionaria");
        $conn->query("SET NAMES utf8");
        $concesionaria = $conn->query("SELECT nombre, conteo FROM conteo_ventas");

        $conteos = array();
        while ($row = $concesionaria->fetch_assoc()) {
            $conteos[$row['nombre']] = intval($row['conteo']);
        }

        $max = max($conteos);
        $zeroes = $max === 0 ? 'data-zeroes' : '';

        echo "
        <article class='grafica' $zeroes style='--data-max: $max'>
        ";

        foreach ($conteos as $nombre => $conteo) {
            echo "
            <div class='bar' style='--data-count: $conteo'></div>
            <p>$nombre</p>
            ";
        }

        echo "
        </article>
        ";
        ?>
    </main>
</body>

</html>