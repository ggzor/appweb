<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="compartido.css" type="text/css" media="all" />
    <link rel="stylesheet" href="registro.css" type="text/css" media="all" />
</head>

<body>
    <header>
        <image src="assets/pato.png" />
        <a href="index.php">Ventas</a>
        <a href="registro.php" data-active>Registro</a>
        <a href="grafica.php">Gráfica</a>
    </header>

    <main>
        <?php
        $conn = new mysqli("localhost", "root", null, "concesionaria");
        $conn->query("SET NAMES utf8");
        $concesionaria = $conn->query("SELECT * FROM venta_completa");
        $fmt = new NumberFormatter('es_MX', NumberFormatter::CURRENCY);

        echo "<table>";


        while ($row = $concesionaria->fetch_assoc()) {
            $precio = $fmt->formatCurrency($row['precio'], "MXN");

            echo "
                <tr>
                    <td>$row[id_automovil]</td>
                    <td><img src='autos/$row[imagen]'></td>
                    <td>$row[marca]</td>
                    <td>$row[modelo]</td>
                    <td>$precio</td>";
            if (is_null($row['id_venta'])) {
                echo "<td>Disponible</td>";
            } else {
                echo "
                    <td><strong> Vendido </strong></td>
                    <td>
                        por $row[nombre] <br>
                        <a href='./facturas/factura$row[id_venta].pdf'>Ver factura</a>
                    </td>
                    ";
            }

            echo "</tr>";
        }
        echo "</table>";
        ?>
    </main>
</body>

</html>