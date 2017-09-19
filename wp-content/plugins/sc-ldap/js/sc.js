function submit()
{
//alert("Получен фокус");
}
function down()
{
    alert("Получен фокус");
}

function validate_form()
{
    if ( (document.qua_user.sn.value == "" ) && (document.qua_user.cn.value == "") )
    {
        alert ( "Внимание!!! Не заполнено ни одно поле!\nПоиск производиться не будет.\nДля проведения поиска хотя бы одно поле должно быть заполнено!" );
        valid = false;
    }
    else if ( (document.qua_user.sn.value.indexOf('*') >= 0 ) || (document.qua_user.cn.value.indexOf('*') >=0  ) )
    {
	alert ("Внимание!!! Вы указали параметр поиска, который нельзя использовать!\nИзмените значения поиска!\n");
	valid = false;
    }
    else
    {
	valid = true;
    }

    return valid;
}

function validate_form_1()
{
    if ( (document.qua_user.sn.value == "" ) && (document.qua_user.cn.value == "") && (document.qua_user.inn.value == "") && (document.qua_user.snils.value == "") && (document.qua_user.ogrn.value == "") )
    {
        alert ( "Внимание!!! Не заполнено ни одно поле!\nПоиск производиться не будет.\nДля проведения поиска хотя бы одно поле должно быть заполнено!" );
        valid = false;
    }
    else if ( (document.qua_user.inn.value == "0000000000") || (document.qua_user.inn.value == "000000000000") )
    {
	alert ("Указан неправильный ИНН!\nНеобходимо указать правильный ИНН");
	valid = false;
    }
    else
    {
	valid = true;    
    }

    return valid;
}


$(function() {
    $('#datepicker').datepicker();
    $('#datepicker1').datepicker();
});

