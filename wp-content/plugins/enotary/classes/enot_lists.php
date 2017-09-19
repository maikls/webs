<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class eNotaryListPackages {
    
    var $html;  
    var $table; 
    
    function eNotaryListPackages() {
		$this->html = new OneItem();

		$this->table = $this->html->add_tag_ex('', 'table table-certificates-enotary table-hover', 'table');
		$this->table->params->add('width', '100%');
		$this->table->params->add('border', '0');
		$this->table->params->add('cellspacing', '1');
		$this->table->params->add('cellpadding', '5');

		$this->buildHeader();
		$this->buildList();
    }
    

/*
	<table class="table table-certificates-enotary table-hover" width="100%" border="0" cellspacing="1" cellpadding="5">
		<thead>
			<tr>
				<td width="20%" align="center">Пакет</td>
				<td class="hidden-sm-down" align="center">Включает площадки</td>
				<td width="12%" align="center">Цена Ф/Л</td>
				<td width="12%" align="center">Цена ИП</td>
				<td width="12%" align="center">Цена Ю/Л</td>
			</tr>
		</thead>
		<?php the_content(); ?>
	</table>

*/	

    function buildHeader() {
    	$tr = $this->table->add_tag('thead')->add_tag('tr');
    	$this->addHeaderColumn($tr, '', '20%', 'center', 'Тариф');
    	$this->addHeaderColumn($tr, 'hidden-sm-down', '', 'center', 'Область применения сертификата');
    	$this->addHeaderColumn($tr, '', '12%', 'center', 'Цена для ЮЛ');
    	$this->addHeaderColumn($tr, '', '12%', 'center', 'Цена для ИП');
    	$this->addHeaderColumn($tr, '', '12%', 'center', 'Цена для ФЛ');
    }
    

    function addHeaderColumn($tr, $classes, $width, $align, $title) {
		$td = $tr->add_tag_ex('', $classes, 'td', $title);
		$td->params->add('width', $width);
		$td->params->add('align', $align);
    }


    function buildList() {
        $cnt = getMaxIndex_Packages();
        for($i=1; $i <= $cnt; $i++) {
			$p = getPackageByID($i);
			if($p != false) {
                $tr = $this->table->add_tag('tr', '');


				$td = $tr->add_tag_ex('','', 'td');
				$td->add_a("/certificate/{$p['id']}", '', 'Подробнее')->add_h($p['title'], 4);
				$td->add_value( substr($p['descr'], 0, 304) . ' ...');


				$td = $tr->add_tag_ex('','hidden-sm-down valign-top', 'td');
				$platforms = explode(',', $p['platforms']);
				$countPlatforms = count($platforms);
                if($countPlatforms > 0) {

					
					$this->buildCollapse($i, $td, $platforms);
/*					
					$ul = $td->add_ul_ex('', 'small');
					foreach ($platforms as $platform) {
						$info = getPlatformByID($platform);
						$title = trim($info['title']);
						if($title != '') {
							$ul->add_li($title);
							$cnt_added++;
						}
					} // foreach

					$ul->visible = $cnt_added > 0;
*/
                } // $countPlatforms > 0

				$td = $tr->add_tag_ex('','text-xs-center', 'td');
				$td->add_div_ex('', 'dashed')->add_a_ex('', 'price', "/certificate/{$p['id']}?tag=le", $p['price-le'], 'Купить пакет для Ю/Л')->add_value('&#8381;');

				$td = $tr->add_tag_ex('','text-xs-center', 'td');
				$td->add_div_ex('', 'dashed')->add_a_ex('', 'price', "/certificate/{$p['id']}?tag=ip", $p['price-ip'], 'Купить пакет для ИП')->add_value('&#8381;');

				$td = $tr->add_tag_ex('','text-xs-center', 'td');
				$td->add_div_ex('', 'dashed')->add_a_ex('', 'price', "/certificate/{$p['id']}?tag=fl", $p['price-fl'], 'Купить пакет для Ф/Л')->add_value('&#8381;');
            }
        }
    }

    public function buildCollapse($id, $td, $items) {
    	$idCollapse = 'collapse-' . $id;
		$ul1 = $td->add_ul_ex('list-' . $id, 'small');
		$ul2 = $td->add_ul_ex($idCollapse, 'small collapse');
		$cnt_added = 0;

		foreach ($items as $platform) {
		    $info = getPlatformByID($platform);
		    $title = trim($info['title']);
		    if($title != '') {
			if($cnt_added < 7) {
				$ul1->add_li($title);
			}
			else
			{
			  $ul2->add_li($title);
			}
			$cnt_added++;
		    }
		} // foreach

		$ul1->visible = $cnt_added > 0;

          if($cnt_added > 7) {
			$td->add_div('<a class="btn btn-link" data-toggle="collapse" href="#'. $idCollapse .'" aria-expanded="false" aria-controls="' . $idCollapse .'">Весь список (' . $cnt_added . ')...</a>');
          } 
    }

    public function as_html() {
        return $this->html->as_html();
    }
}

