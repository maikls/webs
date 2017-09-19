<?php

// 2.
// Оплата заданной суммы с выбором валюты на сайте ROBOKASSA
// Payment of the set sum with a choice of currency on site ROBOKASSA

// регистрационная информация (логин, пароль #1)
// registration info (login, password #1)

require ("../../../../wp-config.php");

$price = $_GET["price"];
//$price = get_option('prices');
//$price = $_POST["prices"];
$zakaz = $_GET["zakaz"];
echo "price = ".$price."<br />zag = Оплата заказа №  ".$zakaz;

$mrh_login = "e-Notary";
$mrh_pass1 = "test-enot";

// номер заказа
// number of order
$inv_id = $zakaz;

// описание заказа
// order description
$inv_desc = "Оплата заказа №".$zakaz;

// сумма заказа
// sum of order
$out_summ = $price;

// тип товара
// code of goods
$shp_item = "2";

// предлагаемая валюта платежа
// default payment e-currency
$in_curr = "";

// язык
// language
$culture = "ru";

// формирование подписи
// generate signature
$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");
echo "<p>crc=".$crc."</p>";
// форма оплаты товара
// payment form
echo "<html>".
      "<form action='https://merchant.roboxchange.com/Index.aspx' method=POST>".
      "<input type=hidden name=MrchLogin value=$mrh_login>".
      "<input type=hidden name=OutSum value=$out_summ>".
      "<input type=hidden name=InvId value=$inv_id>".
      "<input type=hidden name=Desc value='$inv_desc'>".
      "<input type=hidden name=SignatureValue value=$crc>".
      "<input type=hidden name=Shp_item value='$shp_item'>".
      "<input type=hidden name=IncCurrLabel value=$in_curr>".
      "<input type=hidden name=Culture value=$culture>".
      "<input type=submit value='Pay'>".
      "</form></html>";
?>