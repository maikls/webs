<?php 
    require("fpdf181/fpdf.php");
    $pdf= new FPDF();
    $pdf->SetAuthor('Michail Sinjagin');
    $pdf->SetTitle('FPDF tutorial');
    $pdf->SetFont('Helvetica','B',20);
    $pdf->SetTextColor(50,60,100);
    $pdf->AddPage('P');
    $pdf->SetDisplayMode('real','default');
    $pdf->SetFont('Times','',10);
    $pdf->Text(20,10,'Внимание! Оплата данного счета означает согласие с условиями поставки товара.\n Уведомление об оплате обязательно, в противном случае');
    $pdf->Image('images/logo.png',10,10,-300);
    $pdf->Link(10, 20, 33,33, 'http://www.fpdf.org/');
    $pdf->SetXY(50,20);
    $pdf->SetDrawColor(50,60,100);
    $pdf->Cell(100,10,'FPDF Tutorial',1,0,'C',0);
    $pdf->SetXY(10,50);
    $pdf->SetFontSize(10);
    $pdf->Write(5,'Congratulations! You have generated a PDF. ');
    $pdf->Output('example1.pdf','I');
?>