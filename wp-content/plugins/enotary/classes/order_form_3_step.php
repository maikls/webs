<?php

define('ORDER_FORM_3S', '1');


/**
  Show order certificate form with 3 steps

*/
add_option('prices');

class orderForm3Step {

	var $id = 0;
//	var $curr_law_type = 0;
	var $curr_law_type = 1;
	var $cnt_default = 3;
	var $need_csp = false; 
	var $need_token = false; 
	var $price = 0;
	
	function as_html() {
        global $wp, $wpdb;

        $html = new OneItem('', 'body-form');
        $need_csp = false;
        $need_token = false;

        //$id = intval(get_query_var('tag', 0)); // Package ID
        $id = (get_query_var('page')) ? get_query_var('page') : 1;
        $this->id = $id;

        $this->detect_law_type();
        $html->add_hf('law-type', $this->curr_law_type); // 
        $html->add_hf('law-type-title', $this->getLawTypeTitle($this->curr_law_type));

//	$html->add_hf('prices',0);


        $order_name = $wpdb->get_blog_prefix() . 'order';
	$query_order = "select max(id) as id from ".$order_name;
	$result_order = $wpdb->get_results($query_order);

	foreach($result_order as $results){
	    $id_order = $results->id;
	}
	
	if ($id_order === NULL)
	{
	    $id_order = 1;
	}
	
        $package = getPackageByID($id);
        $group = $html->add_div_ex('wizard_order_items', '');
        
            $group_preview  = $html->add_div_ex('wizard_order_preview', ''); // for short order preview
            $group_contact  = $html->add_div_ex('wizard_order_contact', ''); // for contacts
            $group_company  = $html->add_div_ex('wizard_order_company', ''); // for company requizits
            $group_payment  = $html->add_div_ex('wizard_order_payment', ''); // for payments method
            $group_delivery = $html->add_div_ex('wizard_order_delivery', ''); // for delivery method
            $group_final    = $html->add_div_ex('wizard_order_final', ''); // for final send button


        if ($package == false) {
            $group->add_h('Сертификат не найден');
            $group->add_a(get_home_url(), 'Попробуйте подобрать другой');
            return $html->as_html();
            exit;
        }


        $row = $group->add_div_ex('', 'row');


        // New design title ...............................
        $c1 = $row->add_div_ex('', 'col-md-12');
        $c2 = $row->add_div_ex('', 'col-md-12');
        $c3 = $row->add_div_ex('', 'col-md-12');
//        $c3->add_item($this->getListLawTypesDb($package));
	$c1->add_item($this->getListLawTypesDb($package));
//	$c1->add_item($this->getListLawTypes($package));
	$c1->add_hr();
	$c2->add_h_ex('package-title', '', $package['title'], 2);
	$c2->add_p_ex('', '', $package['descr']);
	$c2->add_hr();

	$group->add_hr();
	

        $txt_law = $this->getLawTypeTitle($this->curr_law_type);
        $txt_price = $package[$this->getFieldLaw($this->curr_law_type, 0)];


        $group->add_item($this->getListIncludes($package));
        $group->add_item($this->getListPlatforms($package));
        $group->add_item($this->getListAdditional($package));


        // Show additional packages with prices ........................
        $price_column = $this->getFieldLaw($this->curr_law_type, 0);

        
        // -----------------------------------------------------------------
        if($this->need_csp) { $this->addCryptoprovider($group); } else { $html->add_hf('csp-type', -2); }
        if($this->need_token) { $this->addToken($group); } else { $html->add_hf('token-type', -2); }
        // -----------------------------------------------------------------
        $group->add_hr();

    
        $total = $group->add_div_ex('', 'row group-total');
        $c1 = $total->add_div_ex('', 'col-md-9');
        $c2 = $total->add_div_ex('', 'col-md-3');

        $c1->add_h_ex('', 'text-xs-right', 'Всего:', 3);
        $c2->add_h_ex('price-total', 'text-xs-left', $txt_price . ' Руб.', 3);


        $group->add_hr();
        $buttons = $group->add_div_ex('', 'row tools');
        $buttons->add_div_ex('', 'col-md-1');
        $c2 = $buttons->add_div_ex('', 'col-md-8');
        $c1 = $buttons->add_div_ex('', 'col-md-2');
        $this->addNextButton(0, $c1, 'Далее [1/3]', 'btn-next-start');

        //$btn = $c1->add_tag_ex('', '', 'input button', 'Оформить покупку');
        //$btn->set_onclick('order_wizard(0);');
	
	
        $group->add_script(''
                . 'var base_price = ' . $txt_price . ';'
                . 'var base_item = "' . htmlentities($package['title'] . ' - ' . $txt_law) . '";'
                );


        // ***************************** GROUP PREVIEW ************************
        //$group_preview->add_h('Ваша покупка: <span id="price-total2">' . $txt_price . ' Руб.</span>', 3);
//        update_option ('price', $txt_price);
        $group_preview->add_p('Заказчик: <b id="law-preview">' . $this->getLawTypeTitle($this->curr_law_type) . '</b>', 4);
        $ul2 = $group_preview->add_ul_ex('list-inputs');
        $group_preview->add_tag('b', 'Ваш заказ № '.$id_order.':');
        $ul1 = $group_preview->add_ul_ex('list-preview');
        $group_preview->add_h('Итого: <span id="price-total2">' . $txt_price . ' Руб.</span>', 4);
        $group_preview->add_hf('price-total3', $txt_price);
        $group_preview->add_hf('id-order', $id_order);
        $group_preview->add_hf('law-type', $this->curr_law_type);
        //$group_preview->add_hr();
	
	
        
        // ***************************** GROUP CONTACTS ***********************
        $group_contact->add_h_ex('', 'm-t-3 m-b-2', "Контактная информация", 4);

        $txt_title = "ФИО";
        if ($this->curr_law_type == LAW_TYPE_IP) { $txt_title = "ФИО ИП"; }


        $this->addFormRowText('name', $txt_title, '', $group_contact, true);
        $this->addFormRowText('phone', 'Контактный телефон', '', $group_contact, true);
        $this->addFormRowText('email', 'Электронная почта', '', $group_contact, true);
        $group_contact->add_hr();
        
        
        // ***************************** GROUP COMPANY ***********************
        $group_company->add_h_ex('', 'm-t-3 m-b-2', "Реквизиты для выставления счета", 4);
        if ($this->curr_law_type == LAW_TYPE_UR) {
            $this->addFormRowText('company-title', 'Наименование юр-го лица', '', $group_company, true);
            $this->addFormRowText('company-inn', 'ИНН (юр-го лица)', '', $group_company, true);
            $this->addFormRowText('company-kpp', 'КПП', '', $group_company, true);
            $this->addFormRowText('company-okpo', 'ОКПО', '', $group_company, true);
            $this->addFormRowText('company-address', 'Юридический адрес', '', $group_company, true);
        } else   {
            $this->addFormRowText('fl-inn', 'ИНН (физ-го лица)', '', $group_company, true);
            $this->addFormRowText('fl-address', 'Адрес (по паспорту)', '', $group_company, true);
        } 
        $this->addFormRowText('post-index', 'Почтовый индекс', '', $group_company, true);
        $group_company->add_hr();
        //$GLOBALS['enotary_plugin']->addNextButton(1, $group_company, 'Далее [2/3]',  'btn-next-contacts');       
        
        // ***************************** GROUP DELIVERY ***********************
        $group_delivery->add_h_ex('', 'm-t-3 m-b-2', 'Доставка', 4);
        $this->addFormRowRadio('delivery-type', 'Получение в нашем офисе. Время оформления от 10 до 30 мин.', '', $group_delivery, true, 1);
        $this->addFormRowRadio('delivery-type', 'Выезд нашего специалиста в пределах МКАД (от 3000 руб.). Цену и условия можно обсудить с нашим менеджером при подтверждении заказа.', '', $group_delivery, false, 2);
        $this->addNextButton(2, $group_delivery, 'Далее [3/3]', 'btn-next-payment');       
        $group_delivery->add_hr();

        
        
        // ***************************** GROUP PAYMENT ***********************
//        $group_payment->add_h('Оплата', 4);
        //$GLOBALS['enotary_plugin']->addFormRowRadio('delivery-type', 'Безналичная оплата', '', $group_payment, true);
//        $this->addFormRowRadio('delivery-type', 'Безналичная оплата', '', $group_payment, true);
        //$GLOBALS['enotary_plugin']->addNextButton(2, $group_payment, 'Далее [3/3]', 'btn-next-payment');
//        $this->addNextButton(2, $group_payment, 'Далее [3/4]', 'btn-next-payment');
	
//        $this->addNextButton(3, $group_payment, [wpedon id=1922], 'btn-next-payment');
//	echo do_shortcode('[wpedon id=1922]', true);
//        $group_payment->add_hr();
        
        
        // ***************************** GROUP FINAL ***********************
        $group_final->add_h_ex('', 'm-t-3 m-b-2','Ваша заявка готова к отправке', 4);
        $group_final->add_p('Нажмите кнопку [Отправить заявку] и дождитесь звонка нашего менеджера для согласования способа оплаты и получения.');
        $this->addNextButton(3, $group_final, 'Отправить заявку');
        $group_final->add_hr();
        
        // setTimeout(function() { order_wizard(99); }, 1000);
        $group->add_script('setTimeout(function() { order_wizard(99); check_csp(-1); check_token(-1); calc_price(); }, 500);');
        $group->add_script('jQuery(function () {  jQuery(\'[data-toggle="popover"]\').popover()})');


        return $html->as_html();
	}

	function getListLawTypes($package) {
		$group = new OneItem('', 'list-law', '', 'div');
		$group->add_h_ex('', '', 'Заказчик:', 5);
		$group_list = $group->add_div_ex('', 'list-group');
		$this->buildOneLawType($group_list, LAW_TYPE_UR, $package);
		$this->buildOneLawType($group_list, LAW_TYPE_IP, $package);
		$this->buildOneLawType($group_list, LAW_TYPE_FL, $package);
		return $group;
	}

	function getListLawTypesDb($package) {
	    global $wpdb;
	    $group = new OneItem('', 'list-law', '', 'div');
	    $group->add_h_ex('', '', 'Заказчик:', 5);
	    $group_list = $group->add_div_ex('', 'list-group');
	    $law_name = $wpdb->get_blog_prefix() . 'typeusers';
	    $query_law = "select id from ".$law_name;
	    $result_law = $wpdb->get_results($query_law);
	    foreach ($result_law as $res)
	    {
	        $law_id = $res->id;
	        switch ($law_id){
	    	    case 1:
	    	        $law = $law_id;
	    	        break;
	    	    case 2:
	    	        $law = $law_id;
	    	        break;
	    	    case 3:
	    	        $law = $law_id;
	    	        break;
	        }
	    	$this->buildOneLawType($group_list, $law, $package);
	    }
/*
		$this->buildOneLawType($group_list, $law_ur, $package);
		$this->buildOneLawType($group_list, $law_ip, $package);
		$this->buildOneLawType($group_list, $law_fl, $package);
*/
		return $group;
	}


// <a href="#" class="list-group-item">Юридиское лицо<span class="label label-info label-pill pull-xs-right">2400 руб.</span></a>
	function buildOneLawType($group, $lawID, $package) {

		$active = '';
		if($lawID == $this->curr_law_type) { $active = ' active'; }

		$current_url = add_query_arg(array('tag' => $this->getLinkLawParam($lawID)), null);
		$el = $group->add_a_ex('', 'list-group-item' . $active, $current_url);
		$el->add_span($this->getLawTypeTitle($lawID));
		$el->add_span_ex('', 'label label-default label-pill pull-xs-right', $this->getLawPrice($lawID, $package) . 'руб.');
		//$price = $li->add_
		//$item = $group->add__ex('', '', $link, $this->getLawTypeTitle($lawID));
	}

	function getLinkLawParam($id) {
		if($id == LAW_TYPE_FL) {return 'fl';} else
		if($id == LAW_TYPE_IP) {return 'ip';} else
		{return 'le';} 
	}


	function getLawTypeTitle($id) {
		if($id== LAW_TYPE_UR) { return 'Юридическое лицо'; } else 
		if($id== LAW_TYPE_IP) { return 'Индивидуальный предприниматель'; } else 
		if($id== LAW_TYPE_FL) { return 'Физическое лицо'; } 
	}


	function getLawPrice($lawID, $package) {
		$column = $this->getFieldLaw($lawID, 0);
		return $package[$column];
	}

	function getFieldLaw($id, $mode) {
		if($id== LAW_TYPE_UR) { return 'price-le'; } else 
		if($id== LAW_TYPE_IP) { return 'price-ip'; } else 
		if($id== LAW_TYPE_FL) { return 'price-fl'; } 
	}


	function getListIncludes($package) {
		$group = new OneItem('', 'list-includes', 'list-items');
        
        if(!empty($package['include'])) {
        $items = explode(',', $package['include']);
        if (($package['include'] != '') && (count($items) > 0)) {
            $group->add_h('Состав:', 3);
            $ul = $group->add_ul();
            foreach ($items as $key => $value) {
                $info = getPlatformByID($value);
                $ul->add_li($info['title']);
                //$ul->add_li($value);
            }
        }
        }
        return $group;		
	}

    function getListPlatforms($package) {
        $group = new OneItem('', 'list-platforms', 'list-items');
        
        if(!empty($package['platforms'])) {
        $cnt = 0;    
        $platforms = explode(',', $package['platforms']);
        $group->add_h_ex('', 'm-t-0', 'Площадки:', 4);

        $ul = $group->add_ul_ex("list-platforms", "form-list");
        $ul_more = $group->add_ul_ex("collapseExample", "collapse");

            foreach ($platforms as $key => $value) {
            	$info = getPlatformByID($value);
            	$descr = trim($info['descr']);

                if($cnt < $this->cnt_default) {
                    $li = $ul->add_li();
                    $li->add_a('/platform/'.$info["id"], $info["title"]);
                    if($descr != '') { $li->add_span($this->getHelpButton('Справка', $descr)); }
                } else {
                     $li = $ul_more->add_li();
                     $li->add_a('/platform/'.$info["id"], $info["title"]);
                     if($descr != '') { $li->add_span($this->getHelpButton('Справка', $descr)); }
                }
                $cnt++;
            }

          if($cnt > $this->cnt_default) {
			$group->add_div('<a class="btn btn-link" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Весь список (' . $cnt . ')...</a>');           	
          }  
        }

        return $group;
    }

    function getListAdditional($package) {
    $group = new OneItem('', 'list-additional', 'list-items');

	if(!empty($package['additional'])) {
	        $items = explode(',', $package['additional']);
	        $group->add_h_ex('', 'm-t-3', 'Дополнительно:', 4);

	        $ul = $group->add_div_ex("list-checks", "form-list"); 
	        $ul_more = $group->add_div_ex("collapse-aadons", "collapse");

		$price_column = $this->getFieldLaw($this->curr_law_type, 0);
	        $js_id_list = '';
	        $js_price_list = '';
	        $cnt = 0;		

	        foreach ($items as $key => $value) {
	            $info = getAddonByID($value);
	            $skip_item = false;

	            if($info['id'] == 2) {
	              $this->need_csp = true;  
	              $skip_item = true;
	            }

	            if($info['id'] == 5) {
	              $this->need_token = true;  
	              $skip_item = true;
	            }
	            
	            if(trim($info['title'] == '')) { $skip_item = true;  }
	            
	            if(!$skip_item) {
	            	if($cnt < $this->cnt_default) {
	                	$li = $ul->add_div_ex('', 'row form-row-check m-l-1');
						$this->insertOneItemAdditional($li, $value, $info);
				    }
				 else {
	                $li = $ul_more->add_div_ex('', 'row form-row-check m-l-1');
					$this->insertOneItemAdditional($li, $value, $info);
				 }

					$cnt++;

                	$js_id_list .= $info['id'] . ';';
                	$js_price_list .= $info[$price_column] . ';';

	            }
	        } // for


	        if($cnt > $this->cnt_default) {
              $group->add_div('<a class="btn btn-link" data-toggle="collapse" href="#collapse-aadons" aria-expanded="false" aria-controls="collapse-aadons">Весь список (' . $cnt . ')...</a>'); 	        	
	        }

	} // if empty

    $group->add_script('var list_id = "' . $js_id_list . '";'
              		 . 'var list_price = "' . $js_price_list . '";');

    return $group; 	
    }


    function insertOneItemAdditional($owner, $value, $info) {
        $price_column = $this->getFieldLaw($this->curr_law_type, 0);
        $c1 = $owner->add_div_ex('', 'col-md-10');
        $c2 = $owner->add_div_ex('', 'col-md-2');

        $descr = trim($info['descr']);
        $checked = trim($info['checked']) != '';

        $label = $c1->add_tag('label');
        $input = $label->add_tag('input checkbox');

        if($checked) { $input->params->add('checked', 'true'); }

        $label->add_span_ex('addon-title-' . $info['id'], '', $info['title']);
        if($descr != '') {
        	$c1->add_span($this->getHelpButton('Справка', $descr));
        }

        $c2->add_span('<b>' . $info[$price_column] . ' руб.</b>');

        $input->set_id('addon-' . $info['id']);
        $input->set_onclick('calc_price();');

        $js_id_list .= $info['id'] . ';';
        $js_price_list .= $info[$price_column] . ';';
    }
    
    function addNextButton($iStep, $html, $title = '', $id='') {
        $row = $html->add_div_ex('', 'row');
        $c1 = $row->add_div_ex('', 'col-md-4');
        $c2 = $row->add_div_ex('donate','col-md-4');
        $c3 = $row->add_div_ex('', 'col-md-4 text-xs-right');

        if($id != 'btn-next-start') {
        $btn = $c1->add_tag_ex('', 'btn btn-secondary', 'input button', 'Назад');
        if($iStep == 3) { $btn->set_onclick('order_wizard(0);'); }
             else { $btn->set_onclick('order_wizard(99);');  }
        }

        if($title == '') { $title = 'Далее'; }
        $btn = $c3->add_tag_ex('', 'btn btn-success ' . 'step-' . $iStep, 'input button', $title);
	if ($iStep === 3) 
	{ 
	    $btn->set_onclick('validateForm(\'#wizard_order_contact\');'); 
	    $btn = $c2->set_onclick('set_type();');
	    $btn = $c2->add_tag_ex('','btn btn-succes','input button','Оплатить заказ');
	} 
        else { 
            $btn->set_onclick('order_wizard('. $iStep .');scroll(0,0);'); 
        }
	
        if($id != '') { $btn->set_id($id); }
        
    }

    function addFormRowText($id, $title, $help, $html, $isRequered = false) {
        $row = $html->add_div_ex('row-' . $id, 'form-group row');
        $col_title = $row->add_div_ex('', 'col-md-3 text-xs-right');
        $col_edit  = $row->add_div_ex('', 'col-md-9');
        
        if($isRequered) { $col_title->add_span_ex('', 'form-control-label requered', $title . '*'); }
         else { $col_title->add_span_ex('', 'form-control-label', $title); }
        $ed = $col_edit->add_tag_ex('', 'form-control', 'input text', '');
        $ed->set_id($id);
        $ed->set_param('onkeyup', 'input_validator();');
        $ed->set_param('onchange', 'input_validator();');
                
        if($id == 'email') {$ed->set_param('type', 'email');}
    }


    function addFormRowRadio($id, $title, $help, $html, $isChecked = false, $value = '') {
        $row = $html->add_div_ex('', 'form-group row');
        $col_title = $row->add_div_ex('', 'col-md-1');
        $col_edit  = $row->add_div_ex('', 'col-md-11');
        
        $lbl = $col_edit->add_tag('label');
        $ed = $lbl->add_tag('input radio', '');
        $lbl->add_span_ex('lbl-delivery-' . $value, '', $title);
        $ed->set_id($id);
        $ed->set_param('name', $id);
        $ed->set_param('value', $value);
        $ed->set_param('onchange', 'calc_price();');
        //$ed->set_value($value);
        if($isChecked) { $ed->set_param('checked', 'true'); }
    }    

    function addCryptoprovider($html) {
      $html->add_h_ex('', 'm-t-3', 'Криптопровайдер:', 4);
      $html->add_p_ex('', 'p-a-1', 'Для работы на государственных и коммерческих порталах и площадках на компьютере должен быть установлен криптопровайдер.');
      $html->add_hf('csp-type', -1);
      $row = $html->add_div_ex('', 'row m-t-1');
      $value = '
	<div class="btn-group">
	<button id="selected-csp" name="selected-csp" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Выберите криптопровайдер</button>
  	<div class="dropdown-menu">';

	$price_field = $this->getFieldLaw($this->curr_law_type, 0);
  	$cntCWP = getMaxIndex_CSP();
  	$script = 'var arrCSP = []; ';

  	for($i=0; $i < $cntCWP; $i++) {
  		$csp = getCSPByID($i);
  		if($csp != false) {
  			$price = intval($csp[$price_field]);
  			$txt = $csp['title'];
  			if($csp['license'] != '') { $txt .= ' - ' . $csp['license']; }
  			$html->add_hf('csp-title-' . intval($i), $txt);

  			if($price > 0) { $txt .= ' (' . $price . ' руб.)'; }
  			$value .= '<button class="dropdown-item" onclick="check_csp(' . $i . ');">' . $txt . '</button>';
  			$script .= ' ' . 'arrCSP['. $i . '] = ' . intval($price) .';';
  		}
  	}
     $value .= '</div></div>';
     $row->add_div_ex('', 'col-md-8', $value);
     $row->add_div_ex('', 'col-md-4', '');
     $html->add_script($script);
    }
        
    function addToken($html) {
      $html->add_h_ex('', 'm-t-3', 'Выберите носитель:', 4);
      $html->add_hf('token-type', -1);
      $row = $html->add_div_ex('', 'row m-t-1');
      $value = '
	<div class="btn-group">
	<button id="selected-token" name="selected-token" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Выберите носитель</button>
  	<div class="dropdown-menu">';

	$price_field = $this->getFieldLaw($this->curr_law_type, 0);
  	$cntToken = getMaxIndex_Token();
  	$script = 'var arrToken = []; ';

  	for($i=0; $i <= $cntToken; $i++) {
  		$token = getTokenByID($i);
  		if($token != false) {
  			$price = intval($token[$price_field]);
  			$txt = $token['title'];
  			$html->add_hf('token-title-' . intval($i), $txt);

  			if($price > 0) { $txt .= ' (' . $price . ' руб.)'; }
  			$value .= '<button class="dropdown-item" onclick="check_token(' . $i . ');">' . $txt . '</button>';
  			$script .= ' ' . 'arrToken['. $i . '] = ' . intval($price) .';';
  		}
  	}
     $value .= '</div></div>';
     $row->add_div_ex('', 'col-md-8', $value);
     $row->add_div_ex('', 'col-md-4', '');
     $html->add_script($script);
    }

    function getHelpButton($caption, $help) {
    	// title="' . $caption . '"
    	$value = '<a tabindex="0" type="button" class="btn btn-primary btn-micro" data-toggle="popover" data-trigger="focus" tooltip-trigger="click focus" data-content="'. $help . '">?</a>';
        return $value;

    }

    function detect_law_type() {
	$this->curr_law_type = LAW_TYPE_UR;
	//$this->curr_law_type = intval(get_query_var('term', LAW_TYPE_UR)); // Физ ор Юр лицо
	$tmp = get_query_var('tag', LAW_TYPE_UR); // Физ ор Юр лицо
	if($tmp == 'fl') { $this->curr_law_type = LAW_TYPE_FL; } else 
	if($tmp == 'ip') { $this->curr_law_type = LAW_TYPE_IP; } 
    }
    
}