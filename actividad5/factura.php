<?php
   require('fpdf16/fpdf.php');

   $pdf=new FPDF('P', 'mm', 'Letter');
   $pdf->SetFont('Arial','',14);
   $pdf->AddPage();

   $pdf->Image('assets/pato.png',5,8,15);
   $pdf->Cell(80,15,'        Videoteca',0,1);
   $pdf->Output('factura.pdf');
?>
