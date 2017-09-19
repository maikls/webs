<?php
	define('ENOTARY_SUBJECT_EMAIL', 'Заказ сертификата e-notary.ru');
	$admin_order_email = 'e-notary@signal-com.ru';
	require_once("../../../../wp-config.php");
	
	$data["lawtype"] 	= isset($_POST["lawtype"]) ? $_POST["lawtype"] : "";
	$data["name"] 		= isset($_POST["name"]) ? $_POST["name"] : "";
	$data["phone"] 		= isset($_POST["phone"]) ? $_POST["phone"] : "";
	$data["email"] 		= isset($_POST["email"]) ? $_POST["email"] : "";
	$data["price"] 		= isset($_POST["price"]) ? $_POST["price"] : "";
	$data["delivery"] 	= isset($_POST["delivery"]) ? $_POST["delivery"] : "";
	$data["tools"] 		= isset($_POST["tools"]) ? $_POST["tools"] : "";
	$data["inn"] 		= isset($_POST["inn"]) ? $_POST["inn"] : "";
	$data["address"] 	= isset($_POST["address"]) ? $_POST["address"] : "";
	$data["company"] 	= isset($_POST["company"]) ? $_POST["company"] : "";
	$data["kpp"] 		= isset($_POST["kpp"]) ? $_POST["kpp"] : "";
	$data["okpo"] 		= isset($_POST["okpo"]) ? $_POST["okpo"] : "";
	$data["pindex"]		= isset($_POST["pindex"]) ? $_POST["pindex"] : "";
	
	$mailTo = get_option('admin_email');

	if (!empty($data["name"]) && !empty($data["phone"]) && !empty($data["email"]) && !empty($data["inn"]) && !empty($mailTo)){
		if (sendEmail($data, $mailTo)) _e(get_option('home'));
		else _e(false);
	}else{
		_e(false);
	}


	function getLawTypeTitle($id) {
		if($id== 1) { return 'Юридическое лицо'; } else 
		if($id== 3) { return 'Индивидуальный предприниматель'; } else 
		if($id== 2) { return 'Физическое лицо'; } 
	}
/*
	function getLawTypeTitle($id) {
		if($id== 0) { return 'Юридическое лицо'; } else 
		if($id== 2) { return 'Индивидуальный предприниматель'; } else 
		if($id== 1) { return 'Физическое лицо'; } 
	}
*/

function sendEmail($data, $mailTo) {
	global $admin_order_email;
	
	$name = filter($name);
	$body = "<h2>Новый заказ с сайта e-notary.ru</h2>";
	$body .= '<hr>';
	$body .= "<h3>Данные клиента:</h3>";
	$body .= "<p>Заказчик: " . getLawTypeTitle($data["lawtype"]) . "</p>";
	$body .= "<p>ФИО: {$data['name']}</p>";
	$body .= "<p>Email: {$data['email']}</p>";
	$body .= "<p>Телефон: {$data['phone']}</p>";
	$body .= '<hr>';

	if($data["lawtype"] == 0) {
		// UL
		if (!empty($data["company"])) $body .= "<p>Название организации: {$data['company']}</p>";
		$body .= "<p>ИНН: {$data['inn']}</p>";
		if (!empty($data["kpp"])) $body .= "<p>КПП: {$data['kpp']}</p>";
		if (!empty($data["okpo"])) $body .= "<p>ОКПО: {$data['okpo']}</p>";
		$body .= "<p>Адрес: {$data['address']}</p>";		

	} else {
		// IP + FL
		$body .= "<p>ИНН: {$data['inn']}</p>";
		$body .= "<p>Адрес: {$data['address']}</p>";		
	}

	$body .= "<p>Почтовый индекс: {$data['pindex']}</p>";		

	$body .= "<h3>Данные заказа:</h3>";
	$body .= "<h2>Сумма заказа: {$data["price"]}</h2>";
	$body .= $data["tools"];
	//$body .= "<h3>Способ доставки:</h3>";
	//$body .= $data["delivery"];
	date_default_timezone_set('Europe/Moscow');
	$body .= "<p>Дата заказа: ". date("d-m-Y H:i:s") . "</p>";

	$subject = ENOTARY_SUBJECT_EMAIL;
	$email = filter($data['email']);

	if (!validateEmail($data['email'])) {
		$subject .= " - invalid email";
		$message .= "\n\nBad email: {$data['email']}";
		$email = $mailTo;
	}

	$headers = array('Content-Type: text/html; charset=UTF-8', 'From: e-notary <' . $admin_order_email .'>');
	if(wp_mail($mailTo, $subject, $body, $headers)) return true;
	else return false;
	return false;
}

function filter($value) {
	$pattern = array("/\n/", "/\r/", "/content-type:/i", "/to:/i", "/from:/i");
	$value = preg_replace($pattern, "", $value);
	return $value;
}

function validateEmail($email) {
	$at = strrpos($email, "@");
	if ($at && ($at < 1 || ($at + 1) == strlen($email)))
		return false;
	if (preg_match("/(\.{2,})/", $email))
		return false;
	$local = substr($email, 0, $at);
	$domain = substr($email, $at + 1);
	$locLen = strlen($local);
	$domLen = strlen($domain);
	if ($locLen < 1 || $locLen > 64 || $domLen < 4 || $domLen > 255)
		return false;
	if (preg_match("/(^\.|\.$)/", $local) || preg_match("/(^\.|\.$)/", $domain))
		return false;
	if (!preg_match('/^"(.+)"$/', $local)) {
		if (!preg_match('/^[-a-zA-Z0-9!#$%*\/?|^{}`~&\'+=_\.]*$/', $local))
			return false;
	}
	if (!preg_match('/^[-a-zA-Z0-9\.]*$/', $domain) || !strpos($domain, "."))
		return false;	
	return true;
}
exit;
?>