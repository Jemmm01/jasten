<?php
//============================================================+
// File name   : example_011.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 011 for TCPDF class
//               Colored Table (very simple table)
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Colored Table
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf.php');

// extend TCPF with custom functions
class MYPDF extends TCPDF {

    // Load table data from file
    public function LoadData() {
        // Read file lines
        include 'tracking_db.php';
        $select = "SELECT * FROM schedules";
        
        $query = mysqli_query($conn, $select);
        return $query;
    }


    public function ColoredTable($header, $data)
    {
        // Colors, line width and bold font
        $this->SetFillColor(0, 34, 102);
        $this->SetTextColor(255);
        $this->SetDrawColor(245, 245, 245);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(25, 30, 30, 30, 40, 25);
        $num_headers = count($header);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 3, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        $fill = 0;
    foreach ($data as $row) {
    $this->SetFont('', '', 10); // Set the default font size for the row
    $this->Cell($w[0], 6, $row["yr_and_block"], 'LR', 0, 'C', $fill);
    $this->Cell($w[1], 6, $row["day_of_week"], 'LR', 0, 'C', $fill);
    $this->Cell($w[2], 6, $row["start_time"], 'LR', 0, 'C', $fill);
    $this->Cell($w[3], 6, $row["end_time"], 'LR', 0, 'C', $fill);

    // Check if the coordinator text fits in the column width
    $subjectWidth = $this->GetStringWidth($row["subject"]) + 6; // Adding some extra padding
    if ($subjectWidth > $w[4]) {
        // Calculate the height needed for the cell to fit the text
        $lineHeight = ceil($subjectWidth / $w[3]) * 4;
        // Save the current position
        $x = $this->GetX();
        $y = $this->GetY();
        // Display the coordinator text on the next line if it doesn't fit
        $this->SetFontSize(10); // Set the font size for the "Coordinator" column
        $this->MultiCell($w[4], 6, $row["subject"], 'LR', 'C', $fill);
        // Set the position for the empty cell
        $this->SetXY($x + $w[4], $y);
    } else {
        // Display the coordinator text in the current row if it fits
        $this->Cell($w[4], 6, $row["subject"], 'LR', 0, 'C', $fill);
    }
    $this->SetFontSize(10); // Set the font size for the "Coordinator" column
    $this->Cell($w[5], 6, $row["room_id"], 'LR', 0, 'C', $fill);
    $this->Ln(); // Add a new line after each row
    $fill = !$fill;
    // Add space between rows
    $this->Ln(9); // Adjust the value (4) for the desired space
}
    }
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Faculty List');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, 15.5, "Republic of the Philippines", PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 12);

// add a page
$pdf->AddPage();

// column titles
$header = array('Yr & Block', 'Day of week', 'Start Time', 'End Time', 'Subject', 'Room Name');

// data loading
$data = $pdf->LoadData();

// print colored table
$pdf->ColoredTable($header, $data);

// ---------------------------------------------------------

// close and output PDF document
$pdf->Output('generate_faculty_sched_pdf.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>



