<?php

// 1.
// Оплата заданной суммы с выбором валюты на сайте мерчанта
// Payment of the set sum with a choice of currency on merchant site 

// регистрационная информация (логин, пароль #1)
// registration info (login, password #1)

require ("../../../../wp-config.php");

$price = $_GET["price"];
//$price = get_option('prices');
//$price = $_POST["prices"];
$zakaz = $_GET["zakaz"];
echo "price = ".$price."<br />zag = Оплата заказа №  ".$zakaz;
$mrh_login = "demo";
$mrh_pass1 = "password_1";

// номер заказа
// number of order
$inv_id = $zakaz;

// описание заказа
// order description
$inv_desc = "Оплата заказа № ".$zakaz;

// сумма заказа
// sum of order
$out_summ = $price;

// тип товара
// code of goods
$shp_item = 1;

// предлагаемая валюта платежа
// default payment e-currency
$in_curr = "";

// язык
// language
$culture = "ru";

// кодировка
// encoding
$encoding = "utf-8";

// формирование подписи
// generate signature
$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");

// HTML-страница с кассой
// ROBOKASSA HTML-page
echo "<html><script language=JavaScript ".
      "src='https://auth.robokassa.ru/Merchant/PaymentForm/FormFLS.js?".
      "MrchLogin=$mrh_login&OutSum=$out_summ&InvId=$inv_id&IncCurrLabel=$in_curr".
      "&Desc=$inv_desc&SignatureValue=$crc&Shp_item=$shp_item".
      "&Culture=$culture&Encoding=$encoding'></script></html>";
?>