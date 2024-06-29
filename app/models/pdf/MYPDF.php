<?php

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = "media/".'logo.png';

        $this->Image($image_file, 10, 10, 25, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Title
        $this->SetFont('helvetica', 'B', 12);
        $this->setXY(40, 15);
        $this->Cell(0, 5, 'La Comanda Restaurante', '', false, '', 0, '', 0, false, 'T', 'T');
        $this->Ln(5);
        
        $this->SetFont('helvetica', '', 10);
        $this->setXY(40, 20);
        $this->Cell(0, 15, 'Estadísticas', 'B', false, '', 0, '', 0, false, 'T', 'T');
        $this->Ln(30);
        
        
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, ''.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}




?>