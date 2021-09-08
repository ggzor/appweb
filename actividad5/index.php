<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ventas</title>
    <link rel="stylesheet" href="compartido.css" type="text/css" media="all" />
    <link rel="stylesheet" href="index.css" type="text/css" media="all" />

</head>

<body>
    <header>
        <image src="assets/pato.png" />
        <a href="index.php" data-active>Ventas</a>
        <a href="registro.php">Registro</a>
        <a href="grafica.php">Gráfica</a>
    </header>
    <main>
        <?php
        $conn = new mysqli("localhost", "root", null, "concesionaria");
        $conn->query("SET NAMES utf8");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_automovil = $_REQUEST['id_automovil'];
            $id_vendedor = $_REQUEST['id_vendedor'];
            $nombre_cliente = $_REQUEST['nombre_cliente'];

            $stmt = $conn->prepare("
            INSERT INTO venta (id_automovil, id_vendedor, nombre_cliente, fecha)
            VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iis", $id_automovil, $id_vendedor, $nombre_cliente);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("
                SELECT marca, modelo, precio, fecha, id_venta 
                FROM venta_completa 
                WHERE id_automovil = ?
            ");
            $stmt->bind_param("i", $id_automovil);
            $stmt->execute();
            $stmt->bind_result($marca, $modelo, $precio, $fecha, $id_venta);
            $stmt->fetch();
            $stmt->close();

            $fecha = explode('-', $fecha);
            $fecha = sprintf("%02d/%02d/%04d", $fecha[2], $fecha[1], $fecha[0]);

            require('factura.php');
            crear_factura($nombre_cliente, $fecha, $id_venta, $marca, $modelo, $precio);

            echo "
        <input type='checkbox' name='cerrar' id='cerrar' hidden>
        <article class='notificacion'>

            <p>
                ¡Venta exitosa! Se ha realizado la venta de un <strong> $marca $modelo</strong>.<br>
                <a href='facturas/factura$id_venta.pdf'>Ver factura</a>
            </p>
            <div class='cerrar'>
                <label for='cerrar'>×
            </div>
        </article>
            ";
        }

        ?>
        <form action="index.php" method="POST">
            <?php
            $concesionaria = $conn->query("SELECT * FROM venta_completa WHERE id_venta IS NULL");

            $primera = $concesionaria->fetch_assoc();

            if ($primera === null) {
                echo "<h2>¡Ya se vendieron todos los autos!</h2>";
            } else {
                echo "
                <label for='vehiculo'>Vehículo</label>
                <div class='select' data-value='$primera[id_automovil]'>";
            ?>
                <select name="id_automovil" id="id_automovil" onchange="this.parentElement.dataset.value = this.value">
                    <?php
                    $row = $primera;
                    while ($row) {
                        echo "
                        <option name='id_automovil' value='$row[id_automovil]'>
                        $row[marca] $row[modelo]
                        </option>";

                        $row = $concesionaria->fetch_assoc();
                    }
                    ?>
                </select><br>
                </div>

                <label for="vendedor">Vendedor</label>
                <div class="select">
                    <select name="id_vendedor" id="id_vendedor">
                        <?php
                        $concesionaria = $conn->query("SELECT * FROM vendedor");

                        while ($row = $concesionaria->fetch_assoc()) {
                            echo "
                        <option name='id_vendedor' value='$row[id_vendedor]'>
                        $row[nombre]
                        </option>";
                        }
                        ?>
                    </select><br>
                </div>

                <label for="cliente">Cliente</label>
                <input type="text" name="nombre_cliente" id="nombre_cliente" value="" required /><br>

                <input type="submit" value="Vender">

            <?php
                $concesionaria = $conn->query("SELECT * FROM automovil");
                $fmt = new NumberFormatter('es_MX', NumberFormatter::CURRENCY);

                while ($row = $concesionaria->fetch_assoc()) {
                    $precio = $fmt->formatCurrency($row['precio'], "MXN");

                    echo "
            <article class='auto' id='auto$row[id_automovil]'>
                <image src='autos/$row[imagen]' />
                <p>$precio</p>
            </article>
            <style type='text/css'>
                .select[data-value='$row[id_automovil]']~#auto$row[id_automovil] {
                    display: block;
                }
            </style>
                ";
                }
            }
            ?>
        </form>
    </main>
</body>

</html>