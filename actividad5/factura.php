<?php
$nombre_cliente = 'Ardillita';
$fecha = '05/09/2021';
$id_venta = 1;
$marca = 'Audi';
$modelo = 'R8';
$precio = 3_470_000;

$iva = $precio * 0.16;
$total = $precio + $iva;

$fmt = new NumberFormatter("es_MX", NumberFormatter::CURRENCY);
$precio_str = $fmt->formatCurrency($precio, "MXN");
$iva_str = $fmt->formatCurrency($iva, "MXN");
$total_str = $fmt->formatCurrency($total, "MXN");

require('fpdf16/fpdf.php');

class PDF extends FPDF {
   function Header() {
      $this->SetFont('', '', 36);
      $this->Ln(12);
      $this->Cell(0, 0, 'Concesionaria');

      $this->Ln(12);
      $this->Cell(0, 0, 'Patito');

      $this->SetFont('', 'I', 18);
      $this->Ln(12);
      $this->Cell(0, 0, '"Los patos ya no andan a pata"');

      $this->Image('assets/pato.png', 180, 15, 20);
   }
}

$pdf=new PDF('P', 'mm', 'Letter');
$pdf->SetFont('Arial','',14);
$pdf->AddPage();

{
   $pdf->Ln(20);

   $pdf->SetFont('', 'B', 18);
   $pdf->Cell(0, 0, 'Facturar a:');

   $pdf->Cell(0, 0, 'Fecha:'.str_repeat(' ', 20), 0, 0, 'R');

   $pdf->SetFont('', '', 18);
   $pdf->Cell(0, 0, $fecha, 0, 0, 'R');
}

{
   $pdf->Ln(8);

   $pdf->SetFont('', '', 18);
   $pdf->Cell(0, 0, $nombre_cliente);

   $pdf->SetFont('', 'B', 18);
   $pdf->Cell(0, 0, 'Factura:'.str_repeat(' ', 20), 0, 0, 'R');

   $pdf->SetFont('', '', 18);
   $pdf->Cell(0, 0, sprintf('%04d', $id_venta), 0, 0, 'R');
}

{
   $pdf->Ln(20);

   $pdf->SetFont('', 'B', 14);
   $pdf->Cell(0, 0, 'Cantidad', 0, 1);
   $pdf->Cell(0, 0, str_repeat(' ', 30).utf8_decode('Descripción'), 0, 1);
   $pdf->Cell(0, 0, 'Precio Unitario'.str_repeat(' ', 35), 0, 1, 'R');
   $pdf->Cell(0, 0, str_repeat(' ', 105).'Importe', 0, 1, 'R');

}

{
   $pdf->Ln(10);

   $pdf->SetFont('', '', 14);
   $pdf->Cell(0, 0, str_repeat(' ', 7).'1', 0, 1);
   $pdf->Cell(0, 0, str_repeat(' ', 30)."$marca $modelo", 0, 1);
   $pdf->Cell(0, 0, $precio_str.str_repeat(' ', 35), 0, 1, 'R');
   $pdf->Cell(0, 0, str_repeat(' ', 105).$precio_str, 0, 1, 'R');
}

{
   $pdf->Ln(25);

   $pdf->SetFont('', 'B', 16);
   $pdf->Cell(0, 0, 'Subtotal:'.str_repeat(' ', 25), 0, 0, 'R');

   $pdf->SetFont('', '', 14);
   $pdf->Cell(0, 0, $precio_str, 0, 0, 'R');
}

{
   $pdf->Ln(8);

   $pdf->SetFont('', 'B', 16);
   $pdf->Cell(0, 0, 'IVA 16%:'.str_repeat(' ', 25), 0, 0, 'R');

   $pdf->SetFont('', '', 14);
   $pdf->Cell(0, 0, $iva_str, 0, 0, 'R');
}

{
   $pdf->Ln(8);

   $pdf->SetFont('', 'B', 16);
   $pdf->Cell(0, 0, 'TOTAL:'.str_repeat(' ', 25), 0, 0, 'R');

   $pdf->SetFont('', '', 14);
   $pdf->Cell(0, 0, $total_str, 0, 0, 'R');
}
$pdf->Output('factura.pdf');

?>
