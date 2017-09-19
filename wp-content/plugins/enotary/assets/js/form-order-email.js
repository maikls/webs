function ValidMail(mail) {
    var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
    return pattern.test(mail);
}
 
function ValidPhone(phone) {
    var pattern = /^(\d|\+)[\d\(\)\ -]{4,14}\d$/;
    return pattern.test(phone);
}

function validateForm($form){
	$form = jQuery($form);
	if (typeof $form === 'undefined'){
		toLog('$form is undefined value');
		return false;
	}
	var $name = jQuery('#name'),
		$phone = jQuery('#phone'),
		$email = jQuery('#email'),
		$pindex = jQuery('#post-index'),
		deliveryType = jQuery( "input[name=delivery-type]:checked" ).siblings('span').html(),
		price = jQuery("#price-total2").html(),
		list = '<ul>'+jQuery("#list-preview").html()+'</ul>', 
		fl = jQuery('input').is('#fl-inn');

	var law_type = parseInt(get_value('law-type', true));
	if(law_type == 0) {
		// UL
		var $companyTitle = jQuery('#company-title'),
			$companyInn = jQuery('#company-inn'),
			$companyKpp = jQuery('#company-kpp'),
			$companyOkpo = jQuery('#company-okpo'),
			$companyAddress = jQuery('#company-address');		
	} else {
		// FL + IP
		var $flInn = jQuery('#fl-inn'),
			$flAddress = jQuery('#fl-address');
	}

/*
	if (fl){
		var $flInn = jQuery('#fl-inn'),
			$flAddress = jQuery('#fl-address');
		
	}else{
		var $companyTitle = jQuery('#company-title'),
			$companyInn = jQuery('#company-inn'),
			$companyKpp = jQuery('#company-kpp'),
			$companyOkpo = jQuery('#company-okpo'),
			$companyAddress = jQuery('#company-address');
	}
*/

	if (is_empty($name.val())){
		alert('задайте значение ФИО');
		name.addClass('hightlight');
		return false;
	}

	if (is_empty($phone.val())){
		alert('Задайте значение телефона');
		$phone.addClass('hightlight');
		return false;
	}else{
		if(!ValidPhone($phone.val())){
			alert('некорректное значение телефона');
			$phone.addClass('hightlight');
			return false;
		}
	}

	if (is_empty($email.val())){
		alert('задайте значение электронной почты');
		$email.addClass('hightlight');
		return false;
	}else{
		if(!ValidMail($email.val())){
			alert('некорректное значение электронной почты');
			$email.addClass('hightlight');
			return false;
		}
	}

	if(law_type == 0) {
		// UL
		if (is_empty($companyTitle.val())){
			alert('Задайте значение Название организации или ИП');
			$companyTitle.addClass('hightlight');
			return false;
		}
		if (is_empty($companyInn.val())){
			alert('задайте значение ИНН');
			$companyInn.addClass('hightlight');
			return false;
		}
		if (is_empty($companyAddress.val())){
			alert('задайте значение Юридический адрес');
			$companyAddress.addClass('hightlight');
			return false;
		}		
	} else {
		// FL + IP
		if (is_empty($flInn.val())){
			alert('Задайте значение ИНН');
			$flInn.addClass('hightlight');
			return false;
		}
		if (is_empty($flAddress.val())){
			alert('задайте значение адреса');
			$flAddress.addClass('hightlight');
			return false;
		}
	}


/*
	if (fl){
		if (is_empty($flInn.val())){
			alert('Задайте значение ИНН');
			$flInn.addClass('hightlight');
			return false;
		}
		if (is_empty($flAddress.val())){
			alert('задайте значение адреса');
			$flAddress.addClass('hightlight');
			return false;
		}
	}else{
		if (is_empty($companyTitle.val())){
			alert('Задайте значение Название организации или ИП');
			$companyTitle.addClass('hightlight');
			return false;
		}
		if (is_empty($companyInn.val())){
			alert('задайте значение ИНН');
			$companyInn.addClass('hightlight');
			return false;
		}
		if (is_empty($companyAddress.val())){
			alert('задайте значение Юридический адрес');
			$companyAddress.addClass('hightlight');
			return false;
		}
		
	}
*/

	var data = 'lawtype='+law_type + 
	'&name='+$name.val()+'&phone='+$phone.val()+'&email='+$email.val()+'&pindex='+$pindex.val()+
	'&price='+price+'&delivery='+deliveryType+'&tools='+list;


	if(law_type == 0) {
		// UL
		data += '&company='+$companyTitle.val()+'&inn='+$companyInn.val()+'&address='+$companyAddress.val()+'&kpp='+$companyKpp.val()+'&okpo='+$companyOkpo.val();
	} else {
		// FL + IP
		data += '&inn='+$flInn.val()+'&address='+$flAddress.val();
	}

/*	
	if (fl){
		data += '&inn='+$flInn.val()+'&address='+$flAddress.val();
	}else{
		data += '&company='+$companyTitle.val()+'&inn='+$companyInn.val()+'&address='+$companyAddress.val()+'&kpp='+$companyKpp.val()+'&okpo='+$companyOkpo.val();
	}
*/

    sendAjax(data);
    sendAjaxRobo(data);
    return true;
}

function sendAjax(data){
	jQuery.ajax({
		url: "/wp-content/plugins/enotary/classes/send_email.php",
		type: "POST",
		data: data,
		cache: false,
		success: function (url) {
			if (url) {
				alert('Ваше сообщение отправлено, наш менеджер свяжется с Вами в ближайщее время');
				location.href = url;
			} else alert('Ваше сообщение не отправлено, попробуйте позже');
		},
		error: function (html){
            alert('Проблемы на сервере, Ваше сообщение не отправлено, попробуйте позже');
        }
	});

}

function sendAjaxRobo(data){
	jQuery.ajax({
		url: "/wp-content/plugins/enotary/classes/robo.php",
		type: "POST",
		data: data,
		cache: false,
		success: function (url) {
			if (url) {
				alert('Ваше сообщение отправлено');
//				location.href = url;
				window.open(url);
			} else alert('Ваше сообщение не отправлено, попробуйте позже');
		},
		error: function (html){
            alert('Проблемы на сервере, Ваше сообщение не отправлено, попробуйте позже');
        }
	});
}