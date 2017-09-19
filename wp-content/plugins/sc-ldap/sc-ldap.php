<?php

/**
 * Plugin Name: Search Certificate in LDAP Pro
 * Plugin URI: 
 * Description: This plugin searches for certificates in the LDAP directory
 * Version: 1.0.6
 * Author: Michail Sinjagin
 * Author URI: https://webs.e-notary.ru/
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html

**************************************************************************

Copyright (C) 2017 Michail Sinjagin (email: sinjagma@yandex.ru)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

**************************************************************************/

define('Search_Ldap_Dir', WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
define('Search_Ldap_Url', plugins_url(plugin_basename(dirname(__FILE__))));

if ( is_admin() ){ // admin actions
    add_action('admin_menu', 'search_ldap_pages');
    add_action('admin_init', 'register_mysettings' );
    add_option('servers');
    add_option('dn');

} else {
// non-admin enqueues, actions, and filters

    wp_deregister_script('jquery');
    wp_deregister_script('jquery-ui');
    wp_register_script('jquery', 'https://code.jquery.com/jquery-1.12.4.js');
    wp_register_script('jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js');
    wp_register_script( 'my-plugin-script', plugins_url('/js/sc.js', __FILE__) );
    wp_register_script( 'datepicker', plugins_url('/js/ui.datepicker-ru.js', __FILE__) );
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui');
    wp_enqueue_script('my-plugin-script');
    wp_enqueue_script('datepicker');
    $css = plugins_url('/css/style.css',__FILE__);
    wp_enqueue_style ('datapicker','https://jqueryui.com/resources/demos/style.css');
    wp_enqueue_style ('datapicker-ui', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_enqueue_style ('ldap', $css);
    
function _hex2dec($number)
    {
	$decvalues = array('0' => '0', '1' => '1', '2' => '2',
	    '3' => '3', '4' => '4', '5' => '5',
	    '6' => '6', '7' => '7', '8' => '8',
	    '9' => '9', 'A' => '10', 'B' => '11',
	    'C' => '12', 'D' => '13', 'E' => '14',
	    'F' => '15');
	$decval = '0';
	$number = strtoupper($number);
	$number = strrev($number);
	for($i = 0; $i < strlen($number); $i++)
	{
	    $decval = bcadd(bcmul(bcpow('16',$i,0),$decvalues[$number{$i}]), $decval);
	}
	return $decval;
    }

function _dec2hex($number)
{
    $hexvalues = array('0','1','2','3','4','5','6','7',
	'8','9','A','B','C','D','E','F');
    $hexval = '';
    while($number != '0') {
        $hexval = $hexvalues[bcmod($number,'16')].$hexval;
	$number = bcdiv($number,'16',0);
    }
    return $hexval;
}

function _str2timeGMT($t)
{
    $t = substr($t, 0, strlen($t)-1);
    $y = substr($t, 0, 4);
    $m = substr($t, 4, 2);
    $d = substr($t, 6, 2);
    $H = substr($t, 8, 2);
    $mm = substr($t, 10, 2);
    $c = substr($t, 12, 2);
    return "$d-$m-$y $H:$mm:$c GMT";
}

function _getReasonCode($value)
{
    switch ($value)
    {
	case 0:
            $result = 'не указана';
    	    break;
        case 1:
    	    $result = 'компроментация ключа';
    	    break;
        case 2:
            $result = 'компроментация ключа УЦ';
            break;
        case 3:
            $result = 'смена данных владельца';
            break;
        case 4:
            $result = 'смена ключа';
            break;
        case 5:
            $result = 'прекращение работы УЦ';
            break;
        case 6:
            $result = 'приостановление действия';
            break;
        case 8:
	    $result = 'removeFromCRL';
	    break;
        case 9:
            $result = 'ограничение привилегий';
            break;
        case 10:
            $result = 'aACompromise';
            break;
        default:
    	    $result = $value;
	    break;
    }
    return $result;
}

function _getStatus($serial, $basedn, $type)
{
	$result = '';
	$sn_cert = $serial;
	$dn = $basedn;
	$filter = "(objectClass=x509CRL)";
	$crl_flag = true;
	$res = _ldapSearch($type, $dn, $filter, $crl_flag);

	$count = $res["count"];
	$rev_res = array_reverse($res);
	$sn_crl = $rev_res[0]["x509crlnumber"][0];
	$ser = $rev_res[0]["x509crlthisupdate"][0];
	$new_dn = "x509crlThisUpdate=".$ser.", ".$dn;
	$res_crl = _ldapSearch($type, $new_dn, "objectClass=x509CRLentry");
	$count_crl = $res_crl["count"];
	if ($count_crl == 0)
	{
	    $filter = "(&(objectClass=pkiUser)(x509serialNumber=".$sn_cert."))";
	    $res_cert = _ldapSearch($type, $dn,$filter);
	    $after = $res_cert[0]["x509validitynotafter"][0];
	    $before = $res_cert[0]["x509validitynotbefore"][0];

	    $current_time = date( "YmdHis", time())."Z";
	    if ($current_time <= $after && 
	        $current_time >= $before)
	    {
	        $result = "<font style='color:green'>Действителен</font>";
	    }
	    else
	    {
	        $result =  "<font style='color:red'>Просрочен</font>";
	    }
	}
	else
	{
		for ($i=0;$i<$count_crl; $i++)
		{
		    $crl_sn = $res_crl[$i]["x509serialnumber"][0];
		    if ($crl_sn == $sn_cert)
		    {
			$crl_code = $res_crl[$i]["x509crlcertreasoncode"][0];
			$crl_msg = _getReasonCode($crl_code);
			$result = "<font style='color: red'>Отозван (причина отзыва: ".$crl_msg.")</font>";
			break;
		    }
		    else
		    {
			$filter = "(&(objectClass=pkiUser)(x509serialNumber=".$sn_cert."))";
			$res_cert = _ldapSearch($type, $dn,$filter);
			$after = $res_cert[0]["x509validitynotafter"][0];
			$before = $res_cert[0]["x509validitynotbefore"][0];
		
			$current_time = date( "YmdHis", time())."Z";
			if ($current_time <= $after && 
			    $current_time >= $before)
			{
			    $result = "<font style='color:green'>Действителен</font>";
			}
			else
			{
			    $result =  "<font style='color:red'>Просрочен</font>";
			}
		    }
		}
	    }
	return $result;
    }

function _ldapSearch($types, $dn=false, $filters=false, $crl_flag=false)
{
    $host = get_option('servers');
    $port = 389;
    $ver = 3;

    switch ($types)
    {
	case 1:
	    if ($dn == false)
	    {
		$dn = get_option('dn_qa');
	    }
	    $caCnFilter='';
	break;
	case 2:
	    if ($dn == false)
	    {
	        $dn = get_option('dn_qa');
	    }
	    $caCnFilter='';
	    if ($crl_flag == true)
	    {
		$sort_crl = true;
	    }
	break;
	case 3:
	    if ($dn == false)
	    {
		$dn = get_option('dn_qa');
	    }
	    $caCnFilter='';
	    if ($filters == false)
	    {
		$filters =  "objectClass=pkiCA";
	    }
	    if ($crl_flag == true)
	    {
		$sort_crl = true;
	    }
	break;
	case 4:
	    if ($dn == false)
	    {
		$dn = get_option('dn_ca');
	    }
	    if ($filters == false)
	    {
    		$filters =  "objectClass=pkiCA";
    	    }
	break;
	case 5:
	    if ($dn == false)
	    {
		$dn = get_option('dn_ca');
	    }
    	    if ($filters == false)
    	    {
    		$filters =  "objectClass=pkiCA";
    	    }
	break;
	case 6:
	    if ($dn == false)
	    {
		$dn = get_option('dn_ca');
	    }
    	    if ($filters == false)
    	    {
    		$filters =  "objectClass=pkiCA";
    	    }
	break;
    }	

	$conn = ldap_connect($host, $port)
	  or die("Невозможно соединиться с $ldaphost");

	if ($conn)
	{
	    ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, $ver);

	    $bind = ldap_bind($conn);
	    if ($bind)
	    {
		$sr = @ldap_search($conn, $dn, $filters) or die ("Вы указали неверные данные. Вернитесь на предыдущую страницу и укажите правильные данные.");
		if ($sort_crl == true)
		{
		    ldap_sort($conn, $sr, 'x509crlthisupdate');
		}
		$result = ldap_get_entries($conn, $sr);
	    }
	}
        return $result;
}

function StatusMsg(&$info)
{
    $current_time = date( "YmdHis", time())."Z";
    $dn = urldecode($info['dn']);
    if (isset( $revoked_certs[$dn]) && is_array($revoked_certs[$dn]))
    {
        if (!empty($revoked_certs[$dn]['x509crlCertReasonCode']))
	    return $this->_msg['revoked_cert_start_tag']
	    .$this->_msg['revocation_reason_code'][$revoked_certs[$dn]['x509crlCertReasonCode']]
	    ."\n"
	    .$this->_msg['revoked_cert_end_tag'];
	else
		return $this->_msg['revoked_cert'];
    }
    if (array_key_exists('x509validitynotbefore', $info) && is_array( $info['x509validitynotbefore']) 
	&& array_key_exists('x509validitynotafter', $info) && is_array($info['x509validitynotafter']))
    {
	if ($current_time <= $info['x509validitynotafter'][0] && 
	    $current_time >= $info['x509validitynotbefore'][0]) {
		return "<font style='color:green'>Действителен</font>";
	    }
	    else {
		return "<font style='color:red'>Просрочен</font>";
	    }
	}
	else
	    return "<font style='color:green'>Действителен</font>";
    return $dn;
}

function _getOid($value)
{
    switch ($value)
    {
	case "1.2.840.113549.1.1.1":
	    $result = "RSA";
	break;
	case "1.2.840.10040.4.1":
	    $result = "DSA";
	break;
	case "1.2.643.2.2.19":
	    $result =  "ГОСТ-2001";
	break;
	case "1.3.6.1.4.1.5849.1.6.2":
	    $result = "ГОСТ-2001(SC)";
	break;
	case "1.2.643.2.2.3":
	    $result =  "ГОСТ-2001";
	break;
	case "1.2.840.113549.1.1.5":
	    $result = "RSA";
	break;
	case "1.2.840.113549.1.1.11":
	    $result = "RSA";
	break;
	default:
	    $result = $value;
	break;
    }
    return $result;
}

function serial2hex($from)
{
    $serial = _dec2hex($from);
    if ($serial / 2)
	$serial = "0".$serial;
    if ($serial)
    {
	$s_tmp = '';
	for ($ii = 0; $ii< strlen($serial); $ii+=2 )
	{
	    $s_tmp .=$serial[$ii].$serial[$ii+1].":";
	}
	$serial = substr($s_tmp, 0, strlen($s_tmp)-1);
    }
    return $serial;
}

function _getExtKeyUsage($value)
{
    switch ($value)
    {
	case "1.3.6.1.4.1.311.2.1.21":
	    $result = "Microsoft Individual Code Signing";
	break;
	case "1.3.6.1.4.1.311.2.1.22":
	    $result = "Microsoft Commercial Code Signing";
	break;
	case "1.3.6.1.4.1.311.10.3.1":
	    $result = "Microsoft Trust List Signing";
	break;
	case "1.3.6.1.4.1.311.10.3.3":
	    $result = "Microsoft Server Gated Crypto";
	break;
	case "1.3.6.1.4.1.311.10.3.4":
	    $result = "Microsoft Encrypted File System";
	break;
	case "1.3.6.1.5.5.7.3.1":
	    $result = "TLS WWW server authentication";
	break;
	case "1.3.6.1.5.5.7.3.2":
	    $result = "TLS WWW client authentication";
	break;
	case "1.3.6.1.5.5.7.3.3":
	    $result =  "Code Signing";
	break;
	case "1.3.6.1.5.5.7.3.4":
	    $result = "E-mail protection";
	break;
	case "1.3.6.1.5.5.7.3.8":
	    $result = "Time stamping";
	break;
	case "1.3.6.1.5.5.7.3.9":
	    $result = "Signing OCSP responses";
	break;
	case "2.16.840.1.113730.4.1":
	    $result = "Netscape Server Gated Crypto";
	break;
	case "1.2.643.3.5.10.2.12":
	    $result = "Интерфакс (Федресурс)";
	break;
	case "1.2.643.6.3.1.1":
	    $result = "ЭТП 1";
	break;
	case "1.2.643.6.3.1.2.1":
	    $result = "ЭТП 2-ЮЛ";
	break;
	case "1.2.643.6.3.1.3.1":
	    $result = "ЭТП 3";
	break;
	case "1.2.643.6.3.1.4.1":
	    $result = "ЭТП 4";
	break;
	case "1.2.643.6.3.1.4.2":
	    $result = "ЭТП 5";
	break;
	case "1.2.643.6.3.1.4.3":
	    $result = "ЭТП 6";
	break;
	case "1.2.643.6.7":
	    $result = "B2B";
	break;
	case "1.2.643.3.241":
	    $result = "ТЭК Торг";
	break;
	case "1.2.643.6.3.1.2.2":
	    $result = "ЭТП 2-ФЛ";
	break;
	case "1.2.643.6.3.1.2.3":
	    $result = "ЭТП 2-ИП";
	break;
	case "1.2.643.5.1.24.2.1.3":
	    $result = "Росреестр ФЛ";
	break;
	case "1.2.643.5.1.24.2.30":
	    $result = "Росреестр ЮЛ";
	break;
	case "1.2.643.5.1.24.2.1.3.1":
	    $result = "Росреестр_Кадастровый инженер";
	break;
	case "1.2.643.6.15":
	    $result = "ЭТП Фабрикант";
	break;
	case "1.2.643.6.17.1":
	    $result = "ЭТП Газпромбанк";
	break;
	case "1.3.6.1.5.5.7.3.9":
	    $result = "Подпись ответов службы OCSP";
	break;
	default:
	    $result = $value;
	break;
	
    }
    return $result;
}

function ldap_context ($code, $type)
{
    switch ($code)
    {
	case "lsearch_ca":
	    switch ($type)
	    {
		case 0:
?>
    		    <div>
		    <form id="form_1" name="form_1" action="" method="post">
		    <div class="radio"><label>
		    <input type="radio" name="type" value="4" onclick="submit()"> Сертификаты УЦ e-notary
		    </label></div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="5" onclick="submit()"> Сертификаты подписчиков
		    </label></div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="6" onclick="submit()"> Списки отозванных сертификатов (CRL)
		    </label></div>
	    	    </form>
		    </div>
<?php
		break;
		case 4:
?>
    	    	    <div>
	            <form id="form_1" name="form_1" action="" method="post">
		    <div class="radio"><label>
		    <input type="radio" name="type" value="4" onclick="submit()" checked> Сертификаты УЦ e-notary
		    </label></div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="5" onclick="submit()"> Сертификаты подписчиков
		    </label></div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="6" onclick="submit()"> Списки отозванных сертификатов (CRL)
	    	    </form>
		    </div>

		    <h4>Корневые сертификаты УЦ e-Notary</h4>
<?php
		    $res = _ldapSearch($type);
		    $count =  $res["count"];
		    for ($i=0;$i<$count;$i++)
		    {
			$k = $i+1;
			$id = "ca-".$i;
			$sn = $res[$i]["x509serialnumber"][0];
			$snhex = serial2hex($sn);
			$sub = $res[$i]["x509subject"][0];
			$full_name = $res[$i]["o"][0];
			$my_key = $res[$i]["x509subjectpublickeyinfoalgorithm"][0];
			$data_before = $res[$i]["x509validitynotbefore"][0];
			$data_after = $res[$i]["x509validitynotafter"][0];
			$my_signature = $res[$i]["x509signaturealgorithm"][0];
			$city = $res[$i]["l"][0];
			$state = $res[$i]["st"][0];
			$mail = $res[$i]["mail"][0];
			$ou = $res[$i]["ou"][0];
			$sub_key = _getOid($my_key);
			$signature = _getOid($my_signature);
			$status = StatusMsg($res[$i]);
			$before = _str2timeGMT($data_before);
			$after = _str2timeGMT($data_after);
			$key_usage_count = $res[$i]["x509keyusage"]["count"];
			for ($j=0; $j<$key_usage_count; $j++)
			{
			    $mykey_usage = $res[$i]["x509keyusage"][$j];
			    if ($j > 0)
			    {
				$key_usage = $key_usage.', '.$res[$i]["x509keyusage"][$j];
			    }
			    else
			    {
				$key_usage = $mykey_usage;
			    }
			}
			$cert_number = $res[$i]["x509serialnumber"][0];
			$cert_body = $res[$i]["cacertificate;binary"][0];
			$cert = "-----BEGIN CERTIFICATE-----\r\n";
			$bcert = base64_encode($cert_body);
			$cert .= chunk_split($bcert, 64 );
			$cert .= "-----END CERTIFICATE-----";

			$s_path = $_SERVER['DOCUMENT_ROOT'];
			$f_pem = "/wp-content/tmp/ca_".$cert_number."_pem.cer";
			$f_der = "/wp-content/tmp/ca_".$cert_number.".cer";

			$fp = fopen($s_path.$f_pem,"w");
			fwrite ($fp, $cert);
			fclose ($fp);

			$fp = fopen($s_path.$f_der, "w");
			fwrite ($fp, $cert_body);
			fclose ($fp);
?>
	<div class="table-responsive">
		<table class="table-bordered table-sm">
		<tbody>
		<tr>
		<th>№</th><td colspan="3"><?php echo $k; ?></td>
		</tr>
		<tr>
		<th>Полное имя</th><td colspan="3"><?php echo $full_name; ?></td>
		</tr>
		<tr>
		<th>Дата начала</th><td colspan="3"><?php echo $before; ?></td>
		</tr>
		<tr>
		<th>Дата окончания</th><td colspan="3"><?php echo $after; ?></td>
		</tr>
		<tr>
		<th>Статус сертификата</th><td colspan="3"><?php echo $status; ?></td>
		</tr>
		<tr>
		<th>Уникальное имя</th><td colspan="3"><?php echo $sub; ?></td>
	        </tr>
	        <tr>
	        <th>Серийный номер</th><td colspan="3"><?php echo $snhex; ?></td>
	        </tr>
	        <tr>
	        <th>Алгоритм подписи</th><td colspan="3"><?php echo $signature; ?></td>
	        </tr>
	        <tr>
	        <th>Город</th><td colspan="3"><?php echo $city; ?></td>
	        </tr>
	        <tr>
	        <th>Область</th><td colspan="3"><?php echo $state; ?></td>
	        </tr>
	        <tr>
	        <th>E-mail</th><td colspan="3"><?php echo $mail; ?></td>
	        </tr>
	        <tr>
	        <th>Подразделение</th><td colspan="3"><?php echo $ou; ?></td>
	        </tr>
	        <tr>
	        <th>Key Usage</th><td colspan="3"><?php echo $key_usage; ?></td>
	        </tr>
	        <tr>
		<th>Алгоритм ключа</th><td colspan="3"><?php echo $sub_key; ?></td>
		</tr>
    		<tr>
        	<th>Скачать в формате</th><td><a href="<?php echo $f_pem; ?>">PEM</a></td><td>&nbsp;</td><td><a href="<?php echo $f_der; ?>">DER</a></td>
        	</tr>
        	<tr>
        	    <th>Текущий CRL</th>
        	    <td>
        		<a href="https://www.e-notary.ru/crl/e-notary_1.crl">Основной сайт</a>
        	    </td>
        	    <td>
        		<a href="http://ldap.e-notary.ru/crl/e-notary_1.crl">Зеркало 1</a>
        	    </td>
        	    <td>
        		<a href="https://ldap2.e-notary.ru/crl/e-notary_1.crl">Зеркало 2</a>
        	    </td>
        	</tr>
		</tbody>
		</table>
	</div>
<?php
		    }
		break;
		case 5:
?>
	    	    <div>
		    <form id="form_1" name="form_1" action="" method="post">
		    <div class="radio"><label>
		    <input type="radio" name="type" value="4" onclick="submit()"> Сертификаты УЦ e-notary
		    </label></div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="5" onclick="submit()" checked> Сертификаты подписчиков
		    </label></div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="6" onclick="submit()"> Списки отозванных сертификатов (CRL)
		    </label></div>
        	    </form>
		    </div>
		    <h4>Поиск сертификатов подписчиков</h4>
<?php
		    $res = _ldapSearch($type);
		    $count =  $res["count"];
?>
		    <form id="user" name="user" action="/services/ldap-result" method="post">
		    <table width="100%">
		    <tr>
		    <td width="30%">Серийный номер (Serial Number): </td><td><input type="text" name="sn" size="40"></td>
		    </tr>
		    <tr>
	    	    <td style="vertical-align: middle;">Уникальное имя УЦ: </td>
		    <td>
		    <select value="" id="uc" name="uc" style="width:300px;">
		    <option value="all" selected>Все</option> 
<?php
		    for ($i=0;$i<$count;$i++)
		    {
			$current_time = date( "YmdHis", time())."Z";
			if ($current_time <= $res[$i]['x509validitynotafter'][0] && 
			    $current_time >= $res[$i]['x509validitynotbefore'][0])
			{
			    $iss = $res[$i]["x509issuer"][0];
			    $iss_prep = str_replace ("=","\=",$iss);
			    $iss_pr = str_replace (",","\,", $iss_prep);
			    $iss_val = "x509issuer=".$iss_pr."+x509serialnumber=".$res[$i]["x509serialnumber"][0];
			    echo '<option value="'.$iss_val.'">'.$iss.'</option>';
			}
		    }
		    echo '</select>';
		    echo '<tr>';
		    echo '<td>Уникальное имя(CommonName)</td><td><input type="text" name="cn" size="40" maxsize="1200"></td>';
		    echo '</tr>';
		    echo '<tr>';
		    echo '<td>Подразделение(OrganizationUnitName)</td><td><input type="text" name="ou" size="40" maxsize="1200"></td>';
		    echo '</tr>';
		    echo '<tr>';
		    echo '<td>Должность(Title)</td><td><input type="text" name="title" size="40" maxsize="1200"></td>';
		    echo '</tr>';
		    echo '<tr>';
		    echo '<td>Город(LocalityName)</td><td><input type="text" name="city" size="40" maxsize="1200"></td>';
		    echo '</tr>';
		    echo '<tr>';
		    echo '<td>Область(StateOrProvinceName)</td><td><input type="text" name="st" size="40" maxsize="1200"></td>';
		    echo '</tr>';
		    echo '<tr>';
		    echo '<td>Адрес электронной почты(Email)</td><td><input type="email" name="email" size="40" maxsize="1200"></td>';
		    echo '</tr>';
		    echo '<td>Дата начала действия:</td><td>';
		    echo '<select id="ifbefore" name="ifbefore">';
		    echo '<option value="<="><=</option>';
		    echo '<option value=">=">>=</option>';
		    echo '</select>';
		    echo '<input type="text" name="notbefore" id="datepicker"/>';
		    echo '</td>';
		    echo '</tr>';
		    echo '<tr>';
		    echo '<td>Дата окончания действия:</td><td>';
		    echo '<select id="ifafter" name="ifafter">';
		    echo '<option value="<="><=</option>';
		    echo '<option value=">=">>=</option>';
		    echo '</select>';
		    echo '<input type="text" name="notafter" id="datepicker1"/>';
		    echo '</td>';
		    echo '</tr>';
?>
		    <tr>
		    <td rowspan="2">Выдавать сертификаты в формате:</td><td><input type="radio" name="encoding" value="der" checked="true">DER</td>
		    </tr>
		    <tr>
			<td><input type="radio" name="encoding" value="pem">PEM</td>
		    </tr>
<?php
		    echo '<tr>';
		    echo '<td><input type="hidden" name="type" value="'.$type.'"></td><td><input type="submit" value="Поиск"><input type="Reset" value="Очистить"></td>';
		    echo '</tr>';
		    echo '</table>';
		    echo '</form>';
		break;
		case 6:
?>
	    	    <div>
		    <form id="form_1" name="form_1" action="" method="post">
		    <div class="radio"><label>
		    <input type="radio" name="type" value="4" onclick="submit()"> Сертификаты УЦ e-notary
		    </label></div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="5" onclick="submit()"> Сертификаты подписчиков
		    </label></div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="6" onclick="submit()" checked> Списки отозванных сертификатов (CRL)
		    </label></div>
	    	    </form>
		    </div>
<?php
		    $res = _ldapSearch($type);
		    $count =  $res["count"];
?>
		    <h4>Поиск списков отозванных сертификатов подписчиков</h4>
<?php
		    echo '<form id="crl" name="crl" action="/services/ldap-result" method="post">';
    		    echo '<table width="100%">';
		    echo '<tr>';
		    echo '<td style="vertical-align: middle;">Уникальное имя УЦ: </td>';
		    echo '<td>';
		    echo '<select value="" id="uc" style="width:300px;" name="uc">';
		    echo '<option value="all" selected>Все</option> ';
		    for ($i=0;$i<$count;$i++)
		    {
		        $current_time = date( "YmdHis", time())."Z";
			if ($current_time <= $res[$i]['x509validitynotafter'][0] && 
			    $current_time >= $res[$i]['x509validitynotbefore'][0])
			{
			    $iss = $res[$i]["x509issuer"][0];
			    $iss_prep = str_replace ("=","\=",$iss);
			    $iss_pr = str_replace (",","\,", $iss_prep);
			    $iss_val = "x509issuer=".$iss_pr."+x509serialnumber=".$res[$i]["x509serialnumber"][0];
			    echo '<option value="'.$iss_val.'">'.$iss.'</option>';
			}
		    }
		    echo '</select></td>';
		    echo '</tr>';
		    echo '<tr>';
		    echo '<td>Дата начала действия:</td><td>';
		    echo '<select name="ifbefore" id="ifbefore">';
		    echo '<option value="<="><=</option>';
		    echo '<option value=">=">>=</option>';
		    echo '</select>';
		    echo '<input type="text" name="notbefore" id="datepicker"/>';
		    echo '</td>';
		    echo '</tr>';
		    echo '<tr>';
		    echo '<td>Дата окончания действия:</td><td>';
		    echo '<select name="ifafter" id="ifafter">';
		    echo '<option value="<="><=</option>';
		    echo '<option value=">=">>=</option>';
		    echo '</select>';
		    echo '<input type="text" name="notafter" id="datepicker1"/>';
		    echo '</td>';
		    echo '</tr>';
?>
	<tr>
	    <td rowspan="2">Выдавать списки CRL в формате:</td><td><input type="radio" name="encoding" value="der" checked="true">DER</td>
	</tr>
	<tr>
	    <td><input type="radio" name="encoding" value="pem">PEM</td>
	</tr>
<?php
	    echo '<tr>';

		    echo '<td><input type="hidden" name="type" value="'.$type.'"></td><td><input type="submit" value="Поиск"><input type="Reset" value="Очистить"></td>';
		    echo '</tr>';
		    echo '</table>';
		    echo '</form>';
		break;
	    }
	    break;
	case "lsearch_qa":
	    switch ($type)
	    {
		case 1:
?>
		    <div>
		    <form id="form_1" name="form_1" action="" method="post">
		    <div class="radio"><label>
		    <input type="radio" name="type" value="1" onclick="submit()" checked> Сертификаты Аккредитованного УЦ e-notary
		    </label></div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="2" onclick="submit()"> Квалифицированные сертификаты подписчиков
		    </label></div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="3" onclick="submit()"> Списки отозванных квалифицированных сертификатов (CRL)
		    </label></div>
		    </form>
		    </div>
		    <h4 style="padding-top: 20px;">Сертификаты Аккредитованного УЦ e-Notary</h4>
		    <div class="table-responsive">
		    <table class="table-bordered table-sm">
		    <tbody>
		    <tr>
		    <th>Полное имя</th><td><a href="https://www.e-notary.ru/cert/e-notary.cer">Аккредитованный УЦ e-Notary</a></td>
		    </tr>
		    <tr>
		    <th>Дата начала</th><td>03-09-2012 г. 11:19:22 GMT</td>
		    </tr>
		    <tr>
		    <th>Дата окончания</th><td>03-09-2028 г. 11:19:22 GMT</td>
		    </tr>
		    <tr>
		    <th>Статус сертификата</th><td><font color="green">Действителен</font></td>
		    </tr>
		    <tr>
		    <th>Серийный номер</th><td>02:01:07:01:01:01:07:01:01</td>
		    </tr>
		    <tr>
		    <th>Алгоритм ключа</th><td>ГОСТ P 34.10-2001</td>
		    </tr>
		    <tr>
		    <th>Город</th><td>Москва</td>
		    </tr>
		    <tr>
		    <th>Область</th><td>77 г. Москва</td>
		    </tr>
		    <tr>
		    <th>E-mail</th><td>e-notary@signal-com.ru</td>
		    </tr>
		    <tr>
		    <th>Подразделение</th><td>Удостоверяющий центр</td>
		    </tr>
		    <tr>
		    <th>Алгоритм подписи</th><td>ГОСТ P 34.10-2001</td>
		    </tr>
		    </tbody>
		    </table>

		    <hr />
		    <table class="table-bordered table-sm">
		    <tbody>
		    <tr>
		    <th>Полное имя</th><td><a href="https://www.e-notary.ru/cert/e-notary-q3.cer">Аккредитованный УЦ e-Notary</a></td>
		    </tr>
		    <tr>
		    <th>Дата начала</th>
		    <td>13-01-2014 г. 08:27:16 GMT</td>
		    </tr>
		    <tr>
		    <th>Дата окончания</th><td>14-04-2030 г. 08:27:16 GMT</td>
		    </tr>
		    <tr>
		    <th>Статус сертификата</th>
		    <td><font color="green">Действителен</font></td>
		    </tr>
		    <tr>
		    <th>Серийный номер</th>
		    <td>02:01:07:01:01:01:09:01:02</td>
		    </tr>
		    <tr>
		    <th>Алгоритм ключа</th><td>ГОСТ P 34.10-2001</td>
		    </tr>
		    <tr>
		    <th>Город</th><td>Москва</td>
		    </tr>
		    <tr>
		    <th>Область</th><td>77 г. Москва</td>
		    </tr>
		    <tr>
		    <th>E-mail</th><td>e-notary@signal-com.ru</td>
		    </tr>
		    <tr>
		    <th>Подразделение</th><td>Удостоверяющий центр</td>
		    </tr>
		    <tr>
		    <th>Алгоритм подписи</th><td>ГОСТ P 34.10-2001</td>
		    </tr>
		    </tbody>
		    </table>
		    
		    <hr />
		    <table class="table-bordered table-sm">
		    <tbody>
		    <tr>
		    <th>Полное имя</th><td><a href="https://www.e-notary.ru/cert/e-notary-q4-root.cer">Аккредитованный УЦ e-Notary</a></td>
		    </tr>
		    <tr>
		    <th>Дата начала</th><td>12-11-2014 г. 11:21:53 GMT</td>
		    </tr>
		    <tr>
		    <th>Дата окончания</th><td>12-11-2032 г. 11:21:53 GMT</td>
		    </tr>
		    <tr>
		    <th>Статус сертификата</th><td><font color="green">Действителен</font></td>
		    </tr>
		    <tr>
		    <th>Серийный номер</th><td>02:01:07:01:01:01:0c:01:01</td>
		    </tr>
		    <tr>
		    <th>Алгоритм ключа</th><td>ГОСТ P 34.10-2001</td>
		    </tr>
		    <tr>
		    <th>Город</th><td>Москва</td>
		    </tr>
		    <tr>
		    <th>Область</th><td>77 г. Москва</td>
		    </tr>
		    <tr>
		    <th>E-mail</th><td>e-notary@signal-com.ru</td>
		    </tr>
		    <tr>
		    <th>Подразделение</th><td>Удостоверяющий центр</td>
		    </tr>
		    <tr>
		    <th>Алгоритм подписи</th><td>ГОСТ P 34.10-2001</td>
		    </tr>
		    <tr>
		    <th rowspan="3">Текущий CRL</th><td><a href="http://www.e-notary.ru/crl/e-notary-q4.crl">Основной сайт</a></td>
		    </tr>
		    <tr>
		    <td><a href="http://ldap.e-notary.ru/crl/e-notary-q4.crl">Зеркало 1</a></td>
		    </tr>
		    <tr>
		    <td><a href="http://ldap2.e-notary.ru/crl/e-notary-q4.crl">Зеркало 2</a></td>
		    </tr>
		    </tbody>
		    </table>

		    <hr />
		    <table class="table-bordered table-sm">
		    <tbody>
		    <tr>
		    <th>Полное имя</th><td><a href="https://www.e-notary.ru/cert/e-notary-q5.cer">Аккредитованный УЦ e-Notary</a></td>
		    </tr>
		    <tr>
		    <th>Дата начала</th><td>25-08-2016 г. 06:32:00 GMT</td>
		    </tr>
		    <tr>
		    <th>Дата окончания</th><td>25-08-2016 г. 06:42:00 GMT</td>
		    </tr>
		    <tr>
		    <th>Статус сертификата</th><td><font color="green">Действителен</font></td>
		    </tr>
		    <tr>
		    <th>Серийный номер</th><td>52:e6:f6:37:00:03:00:00:07:7a</td>
		    </tr>
		    <tr>
		    <th>Алгоритм ключа</th><td>ГОСТ P 34.10-2001</td>
		    </tr>
		    <tr>
		    <th>Город</th><td>Москва</td>
		    </tr>
		    <tr>
		    <th>Область</th><td>77 г. Москва</td>
		    </tr>
		    <tr>
		    <th>E-mail</th><td>e-notary@signal-com.ru</td>
		    </tr>
		    <tr>
		    <th>Подразделение</th><td>Удостоверяющий центр</td>
		    </tr>
		    <tr>
		    <th>Алгоритм подписи</th><td>ГОСТ P 34.10-2001</td>
		    </tr>
		    <tr>
		    <th rowspan="3">Текущий CRL</th><td><a href="http://www.e-notary.ru/crl/e-notary-q5.crl">Основной сайт</a></td>
		    </tr>
		    <tr>
		    <td><a href="http://ldap.e-notary.ru/crl/e-notary-q5.crl">Зеркало 1</a></td>
		    </tr>
		    <tr>
		    <td><a href="http://ldap2.e-notary.ru/crl/e-notary-q5.crl">Зеркало 2</a></td>
		    </tr>
		    </tbody>
		    </table>
		    </div>
<?php
		break;
		case 2:
?>
    		    <div>
		    <form id="form_1" name="form_1" action="" method="post">
		    <div class="radio"><label>
		    <input type="radio" name="type" value="1" onclick="submit()"> Сертификаты Аккредитованного УЦ e-notary
		    </label></div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="2" onclick="submit()" checked> Квалифицированные сертификаты подписчиков
		    </label></div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="3" onclick="submit()"> Списки отозванных квалифицированных сертификатов (CRL)
		    </label></div>
		    </form>
		    </div>
		    <h4 style="padding-top: 20px;">Поиск квалифицированных сертификатов подписчиков</h4>
		    <form id="qua_user" name="qua_user" action="/services/ldap-result" method="post" onsubmit="return validate_form();">
		    <table width="100%">
		    <tr>
		    <td>Серийный номер (SerialNumber):</td><td><input type="text" size="40" name="sn"></td>
		    </tr>
		    <tr>
		    <td>Уникальное имя(CommonName):</td><td><input type="text" size="40" name="cn"></td>
		    </tr>
		    <tr>
		    <td rowspan="2">Выдавать сертификаты в формате:</td><td><input type="radio" name="encoding" value="der" checked="true">DER</td>
		    </tr>
		    <tr>
			<td><input type="radio" name="encoding" value="pem">PEM</td>
		    </tr>
		    <tr>
<?php
		    echo '<td width="40%"><input type="hidden" name="type" value="'.$type.'"></td><td><input type="submit" value="Поиск" name="search_qua"><input type="reset"></td>';
?>
		    </tr>
		    </table>
		    </form>
<?php
		break;
		case 3:
?>
		    <div>
		    <form id="form_1" name="form_1" action="" method="post">
		    <div class="radio"><label>
		    <input type="radio" name="type" value="1" onclick="submit()"> Сертификаты Аккредитованного УЦ e-notary
		    </label></div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="2" onclick="submit()"> Квалифицированные сертификаты подписчиков
		    </label></div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="3" onclick="submit()" checked> Списки отозванных квалифицированных сертификатов (CRL)
		    </label></div>
		    </form>
		    </div>
		    
		    <h4 style="padding-top: 20px;">Поиск списков CRL для квалифицированного УЦ</h4>
		    <form id="qua_crl" name="qua_crl" action="/services/ldap-result" method="post">
		    <table width="100%">
		    <tr>
		    <td style="vertical-align: middle;">Уникальное имя УЦ: </td>
		    <td>
		    <select name="uc" id="uc" style="width:300px;">
    		    <option value="all" selected>Все</option>
		    <option value="x509issuer=c\=RU\,st\=77 г. Москва\,l\=Москва\,o\=ЗАО \\\22Сигнал-КОМ\\\22\,ou\=Удостоверяющий центр\,cn\=Аккредитованный УЦ e-Notary\,INN\=007714028893\,OGRN\=1027700239863\,email\=e-notary@signal-com.ru+x509serialnumber=36967517170117837057">
		cn=Аккредитованный УЦ e-Notary,ou=Удостоверяющий центр,o=ЗАО "Сигнал-КОМ",l=Москва,st=77 г. Москва,c=RU,email=e-notary@signal-com.ru,INN=007714028893,OGRN=1027700239863</option>
		    <option value="x509issuer=c\=RU\,st\=77 г. Москва\,l\=Москва\,o\=ЗАО \\\22Сигнал-КОМ\\\22\,ou\=Удостоверяющий центр\,cn\=Аккредитованный УЦ e-Notary\,INN\=007714028893\,OGRN\=1027700239863\,email\=e-notary@signal-com.ru+x509serialnumber=36967517170117968130">
		cn=Аккредитованный УЦ e-Notary,ou=Удостоверяющий центр,o=ЗАО "Сигнал-КОМ",l=Москва,st=77 г. Москва,c=RU,email=e-notary@signal-com.ru,INN=007714028893,OGRN=1027700239863</option>
		    <option value="x509issuer=c\=RU\,st\=77 г. Москва\,l\=Москва\,o\=ЗАО \\\22Сигнал-КОМ\\\22\,ou\=Удостоверяющий центр\,cn\=Аккредитованный УЦ e-Notary\,INN\=007714028893\,OGRN\=1027700239863\,email\=e-notary@signal-com.ru+x509serialnumber=36967517170118164737">
		cn=Аккредитованный УЦ e-Notary,ou=Удостоверяющий центр,o=ЗАО "Сигнал-КОМ",l=Москва,st=77 г. Москва,email=e-notary@signal-com.ru,c=RU,INN=007714028893,OGRN=1027700239863</option>
		    <option value="x509issuer=INN\=007710474375\,OGRN\=1047702026701\,email\=dit@minsvyaz.ru\,street\=125375 г. Москва ул. Тверская д.7\,o\=Минкомсвязь России\,l\=Москва\,st\=77 г. Москва\,c\=RU\,cn\=УЦ 1 ИС ГУЦ+x509serialnumber=391494544381534038656890,
		x509issuer=email\=dit@minsvyaz.ru\,c\=RU\,st\=77 г. Москва\,l\=Москва\,street\=125375 г. Москва\\\2C ул. Тверская\\\2C д. 7\,o\=Минкомсвязь России\,OGRN\=1047702026701\,INN\=007710474375\,cn\=Головной удостоверяющий центр+x509serialnumber=1113316729012460802015321,
		x509issuer=email\=dit@minsvyaz.ru\,c\=RU\,st\=77 г. Москва\,l\=Москва\,street\=125375 г. Москва\\\2C ул. Тверская\\\2C д. 7\,o\=Минкомсвязь России\,OGRN\=1047702026701\,INN\=007710474375\,cn\=Головной удостоверяющий центр+x509serialnumber=69660468259898924697999788069342059049">
		cn=Аккредитованный УЦ e-Notary,ou=Удостоверяющий центр,o=ЗАО "Сигнал-КОМ",l=Москва,st=77 г. Москва,c=RU,email=e-notary@signal-com.ru,INN=007714028893,OGRN=1027700239863</option>
		</select></td>
	    </tr>
	    <tr>
	<td>Дата начала действия:</td><td>
	<select id="ifbefore" name="ifbefore">
	<option value="<="><=</option>
	<option value=">=">>=</option>
	</select>
	<input type="text" name="notbefore" id="datepicker"/>
	</td>
	</tr>
	<tr>
	<td>Дата окончания действия:</td><td>
	<select id="ifater" name="ifafter">
	<option value="<="><=</option>
	<option value=">=">>=</option>
	</select>
	<input type="text" name="notafter" id="datepicker1"/>
	</td>
	</tr>
	<tr>
	    <td rowspan="2">Выдавать списки CRL в формате:</td><td><input type="radio" name="encoding" value="der" checked="true">DER</td>
	</tr>
	<tr>
	    <td><input type="radio" name="encoding" value="pem">PEM</td>
	</tr>
<?php

	echo '<tr>';
	echo '<td><input type="hidden" name="type" value="'.$type.'"></td><td><input type="submit" value="Поиск"><input type="Reset" value="Очистить"></td>';
	echo '</tr>';
	echo '</table>';
	echo '</form>';
		break;
		default:
		case 0:
		?>
    		    <div class="wrap">
		    <form id="form_1" name="form_1" action="" method="post">
		    <div class="radio">
		    <label>
		    <input type="radio" name="type" value="1" onclick="submit()"> Сертификаты Аккредитованного УЦ e-notary<br>
		    </label>
		    </div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="2" onclick="submit()"> Квалифицированные сертификаты подписчиков<br>
		    </label></div>
		    <div class="radio"><label>
		    <input type="radio" name="type" value="3" onclick="submit()"> Списки отозванных квалифицированных сертификатов (CRL)
		    </label></div>
		    </form>
		    </div>
		<?php
		break;
	    }
	    break;
    }
    
}
function ldap_ca_pages()
{
    echo '<h4>Поиск по справочнику сертификатов</h4>';
    $type = $_POST["type"];
    if (empty($type) )
    {
	$type = 0;
    }
    ldap_context('lsearch_ca',$type);
}
add_shortcode ('lsearch_ca', 'ldap_ca_pages');

function ldap_qa_pages()
{
        echo '<h4>Поиск по справочнику квалифицированных сертификатов</h4>';
	$type = $_POST["type"];
        if (empty($type) )
        {
	    $type = 0;
        }
        ldap_context('lsearch_qa',$type);
}
add_shortcode ('lsearch_qa', 'ldap_qa_pages');

function ldap_result()
{
    $type = $_POST["type"];
    if ($type == 2)
    {
	$page_result = 10;
	$serial = $_POST["sn"];
	$serial = str_replace(" ", "", $serial);
	$serial = str_replace(":", "", $serial);
	$sn = _hex2dec( $serial);
	$common = $_POST["cn"];
	$common = str_replace('"', '\"', $common);
	$format = $_POST["encoding"];
	
	if (!empty($sn))
	{
	    $filt_ser = "(x509serialNumber=".$sn.")";
	}
	if (!empty($common))
	{
	    $cn = $common;
	    $filt_cn = "(cn=".$cn.")";
	}

	$filter = "(&";

	if (!empty($filt_ser))
	{
	    $filter .= $filt_ser."(objectClass=pkiUser))";
	}
	if (!empty($filt_cn))
	{
	    $filter .= $filt_cn."(objectClass=pkiUser))";
	}
//	echo $filter;
	$dn = false;
	$res = _ldapSearch ($type, $dn ,$filter);
	$count = $res["count"];
	if ($count == 0)
	{
?>
	<h4>Результаты поиска квалифицированных сертификатов подписчиков</h4>
	    <p>По заданным реквизитам не найдены сертификаты. Измените условие поиска и попробуйте еще раз</p>
<?php
	}
	else
	{
	    if ($count > $page_result)
	    {
		$count = $page_result;
	    }	
	    $res_rev = array_reverse ($res);
?>
	<h4>Результаты поиска квалифицированных сертификатов подписчиков</h4>
	<p><strong><em>Внимание!</em></strong> По результатам поиска выдается не более 10 записей, удовлетворяющих заданным параметрам.<br>
	 Если среди них Вы не обнаружили искомый объект, необходимо задать более точные параметры поиска.</p>

<?php

	    echo '<table>';
	    echo '<tr>';
	    echo '<th class="ldaps-th">№</th>';
	    echo '<th class="ldaps-th">Полное имя</th>';
	    echo '<th class="ldaps-th">Дата начала</th>';
	    echo '<th class="ldaps-th">Дата окончания</th>';
	    echo '<th class="ldaps-th">Статус сертификата</th>';
	    echo '<th class="ldaps-th">Уникальное имя</th>';
	    echo '<th class="ldaps-th">Серийный номер</th>';
/*
	    echo '<th class="ldaps-th">Алгоритм подписи</th>';
	    echo '<th class="ldaps-th">Город</th>';
	    echo '<th class="ldaps-th">Область</th>';
	    echo '<th class="ldaps-th">E-Mail</th>';
	    echo '<th class="ldaps-th">Подразделение</th>';
	    echo '<th class="ldaps-th">Key Usage</th>';
	    echo '<th class="ldaps-th">Extended Key Usage</th>';
*/
	    echo '<th class="ldaps-th">Алгоритм ключа</th>';
	    echo '</tr>';
	    for ($i=0; $i<$count; $i++)
	    {
		$sn = $res_rev[$i]["x509serialnumber"][0];
		$cert_body = $res_rev[$i]["usercertificate;binary"][0];
		$issuer = $res_rev[$i]["x509issuer"][0];
		$auth_key = $res_rev[$i]["x509authoritykeyidentifier"][0];
		$dn = $dn_base = $res_rev[$i]["dn"];
		$dn = strstr ($dn, ",");
		
		$dn = eregi_replace("\\5C", "\\",substr($dn, 1));
		
		$status = _getStatus($sn, $dn, $type);
		$k=$i+1;

		$s_path = $_SERVER['DOCUMENT_ROOT'];
		
		$my_file = "tmp/cert_".$sn.".cer";
		$my_dir = plugin_dir_path(__FILE__);
		
		$my_link = plugin_dir_url(__FILE__).$my_file;
		$files = $my_dir.$my_file;
		
		switch ($format)
		{
		    case 'der':
			$cert = $cert_body;
		    break;
		    case 'pem':
		    	$cert = "-----BEGIN CERTIFICATE-----\r\n";
			$bcert = base64_encode($cert_body);
			$cert .= chunk_split($bcert, 64 );
			$cert .= "-----END CERTIFICATE-----";
		    break;
		}
		$fp = fopen($files, "w");
		fwrite ($fp, $cert);
		fclose ($fp);
		echo '<tr>';
		echo '<td class="ldaps-th">'.$k.'</td>';
        	echo '<td class="ldaps-th"><a href="'.$my_link.'">'.str_replace('\\"','"',$res_rev[$i]["cn"][0]).'</a></td>';
        	echo '<td class="ldaps-th">'._str2timeGMT($res_rev[$i]["x509validitynotbefore"][0]).'</td>';
        	echo '<td class="ldaps-th">'._str2timeGMT($res_rev[$i]["x509validitynotafter"][0]).'</td>';
        	echo '<td class="ldaps-th">'.$status.'</td>';
		echo '<td class="ldaps-th">'.str_replace('\22','"',str_replace(',',', ',$res_rev[$i]["x509subject"][0])).'</td>';
        	echo '<td class="ldaps-th">'.serial2hex($sn).'</td>';
/*
        	echo '<td class="ldaps-th">'._getOID($res_rev[$i]["x509signaturealgorithm"][0]).'</td>';
        	echo '<td class="ldaps-th">'.$res_rev[$i]["l"][0].'</td>';
        	echo '<td class="ldaps-th">'.$res_rev[$i]["st"][0].'</td>';
        	echo '<td class="ldaps-th">'.$res_rev[$i]["mail"][0].'</td>';
        	echo '<td class="ldaps-th">'.$res_rev[$i]["ou"][0].'</td>';
        	echo '<td class="ldaps-th">';
        	for ($j=0; $j<$res_rev[$i]["x509keyusage"]["count"]; $j++)
        	{
        	    if ($j < ($res_rev[$i]["x509keyusage"]["count"] - 1))
        	    {
        		echo $res_rev[$i]["x509keyusage"][$j].', ';
        	    }
        	    else
        	    {
        		echo $res_rev[$i]["x509keyusage"][$j];
        	    }
        	}
        	echo '</td>';
        	echo '<td class="ldaps-th">';
        	for ($l=0; $l<$res_rev[$i]["x509extkeyusage"]["count"]; $l++)
        	{
        	    if ($l < ($res_rev[$i]["x509extkeyusage"]["count"] -1 ))
        	    {
        		echo _getExtKeyUsage($res_rev[$i]["x509extkeyusage"][$l]).', ';
        	    }
        	    else
        	    {
        		echo _getExtKeyUsage($res_rev[$i]["x509extkeyusage"][$l]);
        	    }
        	}
        	echo '</td>';
*/
        	echo '<td class="ldaps-th">'._getOID($res_rev[$i]["x509subjectpublickeyinfoalgorithm"][0]).'</td>';
		echo '</tr>';

	    }
	    echo '</table>';
	}
	
    }

    if ($type == 3)
    {
	$res_pages = 10;
	$uc_name = $_POST["uc"];
	$after = $_POST["ifafter"];
	$data_after = $_POST["notafter"];
	$before = $_POST["ifbefore"];
	$data_before = $_POST["notbefore"];
	$format = $_POST["encoding"];
	
	if ($uc_name == 'all')
	{
	    $dn = 'o=qa';
	}
	else
	{
	    $dn = $uc_name.',o=qa';
    	    $new_dn = str_replace ("\\\\", "\\", $dn);
	    $dn = $new_dn;
	}
	
	if (!empty($data_before))
	{
	    $data = date_create($data_before);
	    $data_bef = date_format($data, 'YmdHis')."Z";
	    $filters = "(x509crlThisUpdate".$before.$data_bef.")";
	    $filt = "(&".$filters;
	}
	
	if (!empty($data_after))
	{
	    $data = date_create ($data_after);
	    $data_aft = date_format($data, 'YmdHis')."Z";
	    $filters = "(x509crlNextUpdate".$after.$data_aft.")";
	    $filt .= $filters;
	}
	
	if (!empty($filt))
	{
	    $filter = $filt."(objectClass=x509CRL))";
	}
	else
	{
	    $filter = 'objectClass=x509CRL';
	}

	$res = _ldapSearch ($type, $dn, $filter, true);
	$count = $res["count"];
?>
	 <h4>Результаты поиска списков отозванных квалифицированных сертификатов подписчиков</h4>
	<p><strong><em>Внимание!</em></strong> По результатам поиска выдается не более 10 записей, удовлетворяющих заданным параметрам.<br>
	 Если среди них Вы не обнаружили искомый объект, необходимо задать более точные параметры поиска.<br>
	 Если параметры поиска не заданы, выдается список из 10 записей, последних по времени занесения в справочник сертификатов.</p>

<?php
	if ($count >= $res_pages)
	{
	    $res_count = $res_pages;
	}
	else
	{
	    $res_count = $count;
	}
	echo '<table border="1">';
	echo "<tr>";
	echo '<th style="text-align: center" width="3%">№</th>';
	echo '<th style="text-align: center">Полное имя</th>';
	echo '<th style="text-align: center">Алгоритм подписи</th>';
	echo '<th style="text-align: center">Дата выпуска</th>';
	echo '<th style="text-align: center">Дата обновления</th>';
	echo "</tr>";
	$rev_res = array_reverse($res);
	for ($i=0;$i<$res_count; $i++)
	{
	    $crl_number = $rev_res[$i]["x509crlnumber"][0];
	    $crl_body = $rev_res[$i]["certificaterevocationlist;binary"][0];
	    
	    $s_path = $_SERVER['DOCUMENT_ROOT'];
	    $my_file = "tmp/crl_".$crl_number.".crl";
	    $my_dir = plugin_dir_path(__FILE__);
		
	    $my_link = plugin_dir_url(__FILE__).$my_file;
	    $files = $my_dir.$my_file;
		
	    switch ($format)
	    {
	        case 'der':
	    	    $crl = $crl_body;
		break;
		case 'pem':
		    $crl = "-----BEGIN X509 CRL-----\r\n";
		    $bcrl = base64_encode($crl_body);
		    $crl .= chunk_split($bcrl, 64 );
		    $crl .= "-----END X509 CRL-----\r\n";
		break;
	    }

	    $fp = fopen($files, "w");
	    fwrite ($fp, $crl);
	    fclose ($fp);

	
	    $my_alg = $rev_res[$i]["x509signaturealgorithm"][0];
	    $alg = _getOid($my_alg);
	    $thisUpdate = _str2timeGMT($rev_res[$i]["x509crlthisupdate"][0]);
	    $nextUpdate = _str2timeGMT($rev_res[$i]["x509crlnextupdate"][0]);
	    
	    echo "<tr>";
	    echo '<td style="text-align: center">'.($i+1).'</td>';
	    echo '<td style="text-align: center"><a href="'.$my_link.'">'.$rev_res[$i]["cn"][0].'</a></td>';
	    echo '<td style="text-align: center">'.$alg.'</td>';
	    echo '<td style="text-align: center">'.$thisUpdate.'</td>';
	    echo '<td style="text-align: center">'.$nextUpdate.'</td>';
	    echo "</tr>";
	}
	echo '</table>';
    }
    if ($type == 5)
    {
	$res_pages = 10;
	$serial = $_POST["sn"];
	$uc = $_POST["uc"];
	$cn = $_POST["cn"];
	$ou = $_POST["ou"];
	$title = $_POST["title"];
	$locality = $_POST["city"];
	$st = $_POST["st"];
	$email = $_POST["email"];
	$before = $_POST["ifbefore"];
	$data_before = $_POST["notbefore"];
	$after = $_POST["ifafter"];
	$data_after = $_POST["notafter"];
	$format = $_POST["encoding"];
	
	if ($uc == 'all')
	{
	    $dn = 'o=ca';
	}
	else
	{
	    $dn = $uc.',o=ca';
    	    $new_dn = str_replace ("\\\\", "\\", $dn);
	    $dn = $new_dn;
	}

	$filter = "(&";
	if (!empty($serial))
	{
	    $serial = str_replace(" ", "", $serial);
	    $serial = str_replace(":", "", $serial);
	    $sn = _hex2dec( $serial);
	    $filter .= "(x509serialNumber=".$sn.")";
	}
	if (!empty($cn))
	{
	    $filter .= "(cn=".$cn.")";
	}
	if (!empty($ou))
	{
	    $filter .= "(ou=".$ou.")";
	}
	if (!empty($title))
	{
	    $filter .= "(title=".$title.")";
	}
	if (!empty($locality))
	{
	    $filter .= "(l=".$locality.")";
	}
	if (!empty($st))
	{
	    $filter .= "(st=".$st.")";
	}
	if (!empty($email))
	{
	    $filter .= "(mail=".$email.")";
	}
	if (!empty($data_before))
	{
//	    $data = date_create($data_before);
            if ($before == ">=")
            {
                $data = date_create ($data_before.'000000');
            }
            else
            {
                $data = date_create ($data_before.'235959');
            }

	    $data_bef = date_format($data, 'YmdHis')."Z";
	    $filter .= "(x509validityNotBefore".$before.$data_bef.")";
	}
	if (!empty($data_after))
	{
            if ($after == ">=")
            {
                $data = date_create ($data_after.'000000');
            }
            else
            {
                $data = date_create ($data_after.'235959');
            }

//	    $data = date_create($data_after);
	    $data_aft = date_format($data, 'YmdHis')."Z";
	    $filter .= "(x509validityNotAfter".$after.$data_aft.")";
	}
	
	$filter .= "(objectClass=pkiUser))";
	$res = _ldapSearch ($type, $dn, $filter);
	$count = $res["count"];
	
	if ($count > $res_pages)
	{
	    $count_res = $res_pages;

	}
	else
	{
	    $count_res = $count;
	}
?>
	<h4>Результаты поиска сертификатов подписчиков</h4>
	    <p><strong><em>Внимание!</em></strong> По результатам поиска выдается не более 10 записей, удовлетворяющих заданным параметрам.<br>Если среди них Вы не обнаружили искомый объект, необходимо задать более точные параметры поиска.<br> Если параметры поиска не заданы, выдается список из 10 записей, последних по времени занесения в справочник сертификатов.</p>
<?php
	$res_rev = array_reverse($res);

	echo '<table border="0" class="ldap-table">';
	echo '<tr class="ldap-table">';
	echo '<th class="ldap-table">№</th>';
	echo '<th class="ldap-table">Полное имя</th>';
	echo '<th class="ldap-table">Дата начала</th>';
	echo '<th class="ldap-table">Дата окончания</th>';
	echo '<th class="ldap-table">Статус сертификата</th>';
	echo '<th class="ldap-table">Уникальное имя</th>';
	echo '<th class="ldap-table">Серийный номер</th>';
//	echo '<th class="ldap-table">Алгоритм подписи</th>';
//	echo '<th class="ldap-table">Город</th>';
//	echo '<th class="ldap-table">Область</th>';
//	echo '<th class="ldap-table">E-Mail</th>';
//	echo '<th class="ldap-table">Подразделение</th>';
//	echo '<th class="ldap-table">Key Usage</th>';
//	echo '<th class="ldap-table">Extended Key Usage</th>';
	echo '<th class="ldap-table">Алгорит ключа</th>';
	echo '</tr>';

	for ($i=0; $i<$count_res; $i++)
	{
	    $sn = $res_rev[$i]["x509serialnumber"][0];
	    $cert_body = $res_rev[$i]["usercertificate;binary"][0];
	    $issuer = $res_rev[$i]["x509issuer"][0];
	    $auth_key = $res_rev[$i]["x509authoritykeyidentifier"][0];

	    $dn = $dn_base = $res_rev[$i]["dn"];
	    $dn = strstr ($dn, ",");
	    $dn = eregi_replace("\\5C", "\\",substr($dn, 1));
	    
	    $status = _getStatus($sn, $dn, $type);

	    $s_path = $_SERVER['DOCUMENT_ROOT'];
		
	    $my_file = "tmp/cert_".$sn.".cer";
	    $my_dir = plugin_dir_path(__FILE__);
		
	    $my_link = plugin_dir_url(__FILE__).$my_file;
	    $files = $my_dir.$my_file;
		
	    switch ($format)
	    {
	        case 'der':
		    $cert = $cert_body;
		break;
		case 'pem':
		    $cert = "-----BEGIN CERTIFICATE-----\r\n";
		    $bcert = base64_encode($cert_body);
		    $cert .= chunk_split($bcert, 64 );
		    $cert .= "-----END CERTIFICATE-----";
		break;
	    }
	    
	    $fp = fopen($files, "w");
	    fwrite ($fp, $cert);
	    fclose ($fp);
	    
	    $k = $i + 1;
	    echo '<tr class="ldap-table">';
	    echo '<td class="ldap-table">'.$k.'</td>';
    	    echo '<td class="ldap-table"><a href="'.$my_link.'">'.$res_rev[$i]["cn"][0].'</a></td>';
    	    echo '<td class="ldap-table">'._str2timeGMT($res_rev[$i]["x509validitynotbefore"][0]).'</td>';
    	    echo '<td class="ldap-table">'._str2timeGMT($res_rev[$i]["x509validitynotafter"][0]).'</td>';
    	    echo '<td class="ldap-table">'.$status.'</td>';
	    echo '<td class="ldap-table">'.$res_rev[$i]["x509subject"][0].'</td>';
    	    echo '<td class="ldap-table">'.serial2hex($sn).'</td>';
/*
    	    echo '<td class="ldap-table">'._getOID($res_rev[$i]["x509signaturealgorithm"][0]).'</td>';
    	    echo '<td class="ldap-table">'.$res_rev[$i]["l"][0].'</td>';
    	    echo '<td class="ldap-table">'.$res_rev[$i]["st"][0].'</td>';
    	    echo '<td class="ldap-table">'.$res_rev[$i]["mail"][0].'</td>';
    	    echo '<td class="ldap-table">'.$res_rev[$i]["ou"][0].'</td>';
    	    echo '<td class="ldap-table">';
    	    for ($j=0; $j<$res_rev[$i]["x509keyusage"]["count"]; $j++)
    	    {
    	        echo $res_rev[$i]["x509keyusage"][$j].',<br />';
    	    }
    	    echo '</td>';
        	
    	    echo '<td class="ldap-table">';
    	    for ($l=0; $l<$res_rev[$i]["x509extkeyusage"]["count"]; $l++)
    	    {
    	        echo _getExtKeyUsage($res_rev[$i]["x509extkeyusage"][$l]).',<br />';
    	    }
    	    echo '</td>';
*/
    	    echo '<td class="ldap-table">'._getOID($res_rev[$i]["x509subjectpublickeyinfoalgorithm"][0]).'</td>';
    	    echo '</tr>';

	}
	echo '</table>';
    }
    if ($type == 6)
    {
	$res_pages = 10;
	$uc_name = $_POST["uc"];
	$after = $_POST["ifafter"];
	$data_after = $_POST["notafter"];
	$before = $_POST["ifbefore"];
	$data_before = $_POST["notbefore"];
	$format = $_POST["encoding"];

	if ($uc_name == 'all')
	{
	    $dn = 'o=ca';
	}
	else
	{
	    $dn = $uc_name.',o=ca';
    	    $new_dn = str_replace ("\\\\", "\\", $dn);
	    $dn = $new_dn;
	}
        
	$filt = "(&";
	
	if (!empty($data_before))
	{
//	    $data = date_create ($data_before);
	    if ($before == ">=")
	    {
	        $data = date_create ($data_before.'000000');
	    }
	    else
	    {
	        $data = date_create ($data_before.'235959');
	    }

	    $data_bef = date_format($data, 'YmdHis')."Z";
	    $filters = "(x509crlThisUpdate".$before.$data_bef.")";
	    $filt .= $filters;
	}
	
	if (!empty($data_after))
	{
	    $data = date_create ($data_after);
	    $data_aft = date_format($data, 'YmdHis')."Z";
	    $filters = "(x509crlNextUpdate".$after.$data_aft.")";
	    $filt .= $filters;
	}
	
	if (!empty($filt))
	{
	    $filter = $filt."(objectClass=x509CRL))";
	}
	else
	{
	    $filter = 'objectClass=x509CRL';
	}
	$res = _ldapSearch ($type, $dn, $filter);
	$count = $res["count"];
?>
	<h4>Результат поиска списка отозванных сертификатов подписчиков</h4>
	<p><strong><em>Внимание!</em></strong> По результатам поиска выдается не более 10 записей, удовлетворяющих заданным параметрам.
	 Если среди них Вы не обнаружили искомый объект, необходимо задать более точные параметры поиска. 
	 Если параметры поиска не заданы, выдается список из 10 записей, последних по времени занесения в справочник сертификатов.</p>
<?php
	echo '<table border="1" class="form-table">';
	echo "<tr>";
	echo '<th style="text-align: center" width="3%">№</th>';
	echo '<th style="text-align: center">Полное имя</th>';
	echo '<th style="text-align: center">Алгоритм подписи</th>';
	echo '<th style="text-align: center">Дата выпуска</th>';
	echo '<th style="text-align: center">Дата обновления</th>';
	echo "</tr>";
	
	if ($count >= $res_pages)
	{
	    $res_count = $res_pages;
	}
	else
	{
	    $res_count = $count;
	}
	$rev_res = array_reverse($res);
	for ($i=0;$i<$res_count; $i++)
	{
	    $crl_number = $rev_res[$i]["x509crlnumber"][0];
	    $crl_body = $rev_res[$i]["certificaterevocationlist;binary"][0];

	    $s_path = $_SERVER['DOCUMENT_ROOT'];
	    $my_file = "tmp/crl_".$crl_number.".crl";
	    $my_dir = plugin_dir_path(__FILE__);
		
	    $my_link = plugin_dir_url(__FILE__).$my_file;
	    $files = $my_dir.$my_file;
		
	    switch ($format)
	    {
	        case 'der':
	    	    $crl = $crl_body;
		break;
		case 'pem':
		    $crl = "-----BEGIN X509 CRL-----\r\n";
		    $bcrl = base64_encode($crl_body);
		    $crl .= chunk_split($bcrl, 64 );
		    $crl .= "-----END X509 CRL-----\r\n";
		break;
	    }

	    $fp = fopen($files, "w");
	    fwrite ($fp, $crl);
	    fclose ($fp);	
	    $my_alg = $rev_res[$i]["x509signaturealgorithm"][0];
	    $alg = _getOid($my_alg);
	    $thisUpdate = _str2timeGMT($rev_res[$i]["x509crlthisupdate"][0]);
	    $nextUpdate = _str2timeGMT($rev_res[$i]["x509crlnextupdate"][0]);

	    echo "<tr>";
	    echo '<td style="text-align: center">'.($i+1).'</td>';
	    echo '<td style="text-align: center"><a href="'.$my_link.'">'.$rev_res[$i]["cn"][0].'</a></td>';
	    echo '<td style="text-align: center">'.$alg.'</td>';
	    echo '<td style="text-align: center">'.$thisUpdate.'</td>';
	    echo '<td style="text-align: center">'.$nextUpdate.'</td>';
	    echo "</tr>";
	}
	echo '</table>';
    }

}

add_shortcode ('lsearch_res', 'ldap_result');

}

// action function for above hook
function search_ldap_pages() {
    // Add a new submenu under Options:
//    add_options_page('LDAP Options', 'LDAP Options', 8, 'testoptions', 'ldap_options_pages');

    // Add a new submenu under Manage:
//    add_management_page('Test Manage', 'Test Manage', 8, 'testmanage', 'mt_manage_page');

    // Add a new top-level menu (ill-advised):
    add_menu_page('Search LDAP Options', 'Search LDAP Options', 8, __FILE__, 'ldap_toplevel_page');

    // Add a submenu to the custom top-level menu:
//    add_submenu_page(__FILE__, 'Test Sublevel', 'Test Sublevel', 8, 'sub-page', 'mt_sublevel_page');

    // Add a second submenu to the custom top-level menu:
//    add_submenu_page(__FILE__, 'Test Sublevel 2', 'Test Sublevel 2', 8, 'sub-page2', 'mt_sublevel_page2');
}

function register_mysettings() {
    //register our settings
    register_setting( 'ldap-settings-group', 'servers' );
    register_setting( 'ldap-settings-group', 'dn' );
}
function ldap_toplevel_page() {
    $hidden_servers = 'servers_submit_hidden';
    $hidden_dn = 'dn_submit_hidden';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( ( $_POST[ $hidden_servers ] == 'Y' ) or ( $_POST[ $hidden_dn ] == 'Y' ) ) {
        // Read their posted value
        $opt_servers = $_POST[ "servers" ];
        $opt_dn_ca = $_POST[ "dn_ca" ];
        $opt_dn_qa = $_POST[ "dn_qa" ];
        
        // Save the posted value in the database
        update_option( 'servers', $opt_servers );
        update_option( 'dn_ca', $opt_dn_ca );
        update_option( 'dn_qa', $opt_dn_qa );

?>
<div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>
<?php
    }
?>
<div class="wrap">
<h2>Search LDAP Options</h2>
 <form method="post" actions="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="<?php echo $hidden_servers; ?>" value="Y">
    <input type="hidden" name="<?php echo $hidden_dn; ?>" value="Y">
 <?php settings_fields( 'ldap-settings-group' ); ?>

 <table class="form-table">

 <tr valign="top">
 <th scope="row">The search server</th>
<td><input type="text" name="servers" value="<?php echo get_option('servers'); ?>" /></td>
</tr>
 
<tr valign="top">
<th scope="row">DN CA Search</th>
<td><input type="text" name="dn_ca" value="<?php echo get_option('dn_ca'); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">DN QA Search</th>
<td><input type="text" name="dn_qa" value="<?php echo get_option('dn_qa'); ?>" /></td>
</tr>

</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="servers,dn_ca,dn_qa" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

 </form>
</div>
<?php } ?>