<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividad 3</title>
</head>

<body>
    <h1>Conteo de votos</h1>
    <hr>

    <?php

    include "libchart/classes/libchart.php";

    $contadores = [
        "PAN" => 0,
        "PRI" => 0,
        "PRD" => 0,
        "MORENA" => 0,
        "NULO" => 0,
    ];

    $fp = fopen("resultados.txt", "r");

    // Verifica si se pudo abrir el archivo
    if ($fp != false) {
        while (!feof($fp)) {
            if (fscanf($fp, '%s %s', $nombre, $partido)) {
                $contadores[$partido] += 1;
            }
        }
        fclose($fp);
    }

    $dataset = new XYDataSet();
    foreach ($contadores as $partido => $votos) {
        $dataset->addPoint(new Point($partido, $votos));
    }

    $chart = new VerticalBarChart();
    $chart->setDataSet($dataset);
    $chart->getPlot()->setGraphPadding(new Padding(20, 30, 40, 100));
    $chart->setTitle("Conteo de votos");

    $chart->render("generated/conteo.png");
    ?>

    <br>
    <img src="generated/conteo.png">

</body>

</html>