<?php
require('../libs/fpdf/fpdf.php');
require('../conn.php'); // Database connection

class PDF extends FPDF {
    // Header
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Teacher List', 0, 1, 'C');
        $this->Ln(5);

        // Column headings
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(10, 10, 'ID', 1);
        $this->Cell(50, 10, 'Full Name', 1);
        $this->Cell(60, 10, 'Email', 1);
        $this->Cell(50, 10, 'Department', 1);
        $this->Ln();
    }

    // Footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, 'Page '.$this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Fetch teacher data
$sql = "SELECT teachers.id, teachers.fullname, teachers.email, departments.name AS department
        FROM teachers
        LEFT JOIN departments ON teachers.department_id = departments.id";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $pdf->Cell(10, 10, $row['id'], 1);
    $pdf->Cell(50, 10, $row['fullname'], 1);
    $pdf->Cell(60, 10, $row['email'], 1);
    $pdf->Cell(50, 10, $row['department'], 1);
    $pdf->Ln();
}

$pdf->Output();
?>