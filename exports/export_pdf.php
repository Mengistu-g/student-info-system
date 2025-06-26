
<?php
//http://www.fpdf.org/en/download.php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require('libs/fpdf/fpdf.php');

class PDF extends FPDF {
    // Page header
    function Header() {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'Student List',0,1,'C');
        $this->Ln(5);
        // Table header
        $this->SetFont('Arial','B',12);
        $this->Cell(10,10,'ID',1);
        $this->Cell(50,10,'Full Name',1);
        $this->Cell(60,10,'Email',1);
        $this->Cell(40,10,'Department',1);
        $this->Cell(40,10,'Teacher',1);
        $this->Ln();
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

$sql = "SELECT 
            s.id, s.fullname, s.email, 
            d.name AS department, 
            t.name AS teacher 
        FROM students s 
        LEFT JOIN departments d ON s.department_id = d.id
        LEFT JOIN teachers t ON s.teacher_id = t.id
        ORDER BY s.id DESC";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $pdf->Cell(10,10,$row['id'],1);
    $pdf->Cell(50,10,$row['fullname'],1);
    $pdf->Cell(60,10,$row['email'],1);
    $pdf->Cell(40,10,$row['department'],1);
    $pdf->Cell(40,10,$row['teacher'],1);
    $pdf->Ln();
}

$pdf->Output('D', 'students.pdf');
exit;


// <a href="export_pdf.php" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
//   Export Students PDF
// </a>