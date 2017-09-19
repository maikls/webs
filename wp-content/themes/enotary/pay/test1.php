<?php 
require("fpdf181/fpdf.php");
    
class PDF extends FPDF
{
    function Decode($txt)
    {
	$from_decode = "UTF-8";
	$to_decode = "WINDOWS-1251";
	$decode = iconv($from_decode, $to_decode, $txt);
	return $decode;
    }

    function SetMyTitle()
    {
	$this->SetFont('Times','',10);
	$text = "Внимание! Оплата данного счета означает согласие с условиями поставки товара. Уведомление об оплате";
	$txt_decode = $this->Decode($text);
	$this->Text(20,10, $txt_decode);
	$text = "обязательно, в противном случае не гарантируется наличие товара на складе. Товар отпускается по факту";
	$txt_decode = $this->Decode($text);
	$this->Text(20,14,$txt_decode);
	$text = "прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и паспорта.";
	$txt_decode = $this->Decode($text);
	$this->Text(20,18,$txt_decode);
	
//	$text = "Внимание! Оплата ....";
//	$txt_decode = $this->Decode($text);
//	$this->Text(5,24, $txt_decode);
	$this->SetLineWidth(0,4);
	$this->Line(5,24,194,24);
	$this->Line(5,37,194,37);
	$this->Line(54,37,54,41);
	$this->Line(95,24,95,53);
	$this->Line(5,41,95,41);
	$this->Line(5,53,194,53);
	$this->Line(5,24,5,53);
	$this->Line(194,24,194,53);
	$this->Line(95,29,112,29);
	$this->Line(112,24,112,53);
	
	
	$text = "АО ЮНИКРЕДИТ БАНК Г. МОСКВА";
	$txt_decode = $this->Decode($text);
	$this->Text(6,28,$txt_decode);
	$text = "Банк получателя";
	$txt_decode = $this->Decode($text);
	$this->Text(6,36,$txt_decode);

	$text = "ИНН  7723751330";
	$txt_decode = $this->Decode($text);
	$this->Text(6,40,$txt_decode);
	$text = "КПП  772301001";
	$txt_decode = $this->Decode($text);
	$this->Text(55,40,$txt_decode);

	$text = "ООО \"Сигнал-КОМ\"";
	$txt_decode = $this->Decode($text);
	$this->Text(6,44,$txt_decode);
	$text = "Получатель";
	$txt_decode = $this->Decode($text);
	$this->Text(6,52,$txt_decode);

	$text = "БИК";
	$txt_decode = $this->Decode($text);
	$this->Text(96,28,$txt_decode);
	$text = "Сч. №";
	$txt_decode = $this->Decode($text);
	$this->Text(96,32,$txt_decode);
	$this->Text(96,40,$txt_decode);
	$text = "044525545";
	$txt_decode = $this->Decode($text);
	$this->Text(113,28,$txt_decode);
	$text = "30101810300000000545";
	$txt_decode = $this->Decode($text);
	$this->Text(113,32,$txt_decode);
	$text = "40702810500014830359";
	$txt_decode = $this->Decode($text);
	$this->Text(113,40,$txt_decode);
    }
}
    
    
$pdf = new PDF();
$pdf->AddPage('P', 'A4');
$pdf->SetMyTitle();
//    $pdf->SetFont('Times','',10);
//    $pdf->Text(20,10,'Hello World! Привет, Мир!');
//    $pdf->Text(20,14,'Hello World! Привет, Мир!');
//    $pdf->Text(20,18,'Hello World! Привет, Мир!');
//    $pdf->Output('ch/doc2.pdf','F');
$pdf->Output('doc2.pdf','I');
?>