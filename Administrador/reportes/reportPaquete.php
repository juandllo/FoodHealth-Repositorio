<?php
  session_start();
  if(!isset($_SESSION['Perfil']) || $_SESSION['Perfil'] != 1){
    session_destroy();
    header('location: ../');
  }

?>

<?php
require('../../Chef/reportes/fpdf/fpdf.php');

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
    // Logo
    $this->Image('../../Chef/assets/img/favicon.png',20,8,35,25);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Movernos a la derecha
    $this->Cell(60);
    // Título
    $this->Cell(150,10,'Reporte de Paquetes',0,0,'C');
    // Salto de línea
    $this->Ln(30);

    $this->Cell(10,10,"# ",1,0,'C','0');
    $this->Cell(60,10,"Nombre",1,0,'C','0');
	$this->Cell(35,10,'Tiempo',1,0,'C','0');
	$this->Cell(35,10,'Precio',1,0,'C','0');
	$this->Cell(100,10,utf8_decode('Descripción'),1,0,'C','0');
	$this->Cell(30,10,'Estado',1,1,'C','0');
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
}
}
require('../../Chef/conn/conn.php');
$consulta = "SELECT * FROM  paquetes";
$resultado = $conn->query($consulta);

$pdf = new PDF('L');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

$num = "1";
while ($row = $resultado->fetch_assoc()) {
    $pdf->Cell(10,10,$num,1,0,'C','0');
    $pdf->Cell(60,10,utf8_decode($row['Nombre']),1,0,'C','0');
    $pdf->Cell(35,10,utf8_decode($row['Tiempo']),1,0,'C','0');
	$pdf->Cell(35,10,utf8_decode($row['Precio']),1,0,'C','0');
	$pdf->Cell(100,10,utf8_decode($row['Descripcion']),1,0,'C','0');

    //Aqui cambie el 1 y 0 por activo y inactivo
    $exis = $row['Estado'];
    if ($exis == 1) {
    	$estado = 'Activo';
    }
    else{
    	$estado = 'Inactivo';
    }

	$pdf->Cell(30,10,$estado,1,1,'C','0');

	$num++;

}
$pdf->Output();
?>