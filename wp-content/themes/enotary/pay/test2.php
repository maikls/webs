<?php 
    require("tcpdf/tcpdf.php");
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetFont('freeserif', '', 8);
//    $utf8header = "Внимание! Оплата данного счета означает согласие с условиями поставки товара. Уведомление об оплате\n обязательно, в противном случае не гарантируется наличие товара на складе. Товар отпускается по факту\n прихода денег на р/с Поставщика, самовывозом, при наличии довернности и паспорта."
    define ('PDF_HEADER_STRING', "Внимание! Оплата данного счета означает согласие с условиями поставки товара. Уведомление об оплате\n обязательно, в противном случае не гарантируется наличие товара на складе. Товар отпускается по факту\n прихода денег на р/с Поставщика, самовывозом, при наличии довернности и паспорта.");
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Michail Sinjagin');
$pdf->SetTitle('Test 002 test');
$pdf->SetSubject('Test');
$pdf->SetKeywords('Test2');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' ewqeqweqwewq', PDF_HEADER_STRING);
//$pdf->SetHeaderData('images/logo.png', PDF_HEADER_TITLE.' ewqeqweqwewq', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array('times', '', 8));
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
if (@file_exists(dirname(__FILE__).'/lang/ru.php')) {
    require_once(dirname(__FILE__).'/lang/ru.php');
        $pdf->setLanguageArray($l);
        }
        
        // ---------------------------------------------------------
        
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);
        
        // set font
        $pdf->SetFont('freeserif', '', 12);
        
        // add a page
        $pdf->AddPage();
// get esternal file content
//$utf8text = file_get_contents('data/utf8test.txt', false);
$utf8text = "ewqejqweklqwj арволыароларыволарвыоларвыол ьтваытыраволарфоларфыолуцщысмт №№ЦЕ%ЫАвыдавыовывыафвыавы";
// set color for text
$pdf->SetTextColor(0, 63, 127);

//Write($h, $txt, $link='', $fill=0, $align='', $ln=false, $stretch=0, $firstline=false, $firstblock=false, $maxh=0)

// write the text
$pdf->Write(5, $utf8text, '', 0, '', false, 0, false, false, 0);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_008.pdf', 'I');

?>