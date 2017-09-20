<?php

/**
 * Plugin Name: eNotary
 * Description: Enable eNotary base functional
 * Author: Denis V, Michail Sinjagin
 * Version: 1.2
 * License: GPLv2
 */

if ( is_admin() ){ // admin actions
    $css_admin = plugins_url('/css/styles.css', __FILE__);
    wp_enqueue_style('enotary_admin', $css_admin);
    
    add_action('admin_menu', 'eNotary_pages');
    add_action('admin_init', 'register_eNotary_settings' );
//    add_option('servers');
//    add_option('dn');

} else {

if (!defined('ABSPATH')) {
    die('Please use correct URL');
}

global $wpdb;

define('ENOTARY_SC_SEARCH', 'enotary-search');
define('ENOTARY_SC_CERT', 'enotary-certificate');
define('ENOTARY_SC_PACKAGES', 'enotary-packages');
define('ENOTARY_SC_PLATFORM', 'enotary-platform');
define('ENOTARY_FOLDER', 'enotary');
define('ENOTARY_DEBUG', false);

$type_name = $wpdb->get_blog_prefix() . 'typeusers';
$query_type = "select id from ".$type_name;
$result_type = $wpdb->get_results($query_type);
foreach ($result_type as $types)
{
    $type = $types->id;
    switch ($type){
	case 1:
	    define('LAW_TYPE_UR', $type);
	    break;
	case 2:
	    define('LAW_TYPE_FL', $type);
	    break;
	case 3:
	    define('LAW_TYPE_IP', $type);
	    break;
    }
}

class enotary_plugin {

    
    function __construct() {
        add_action('init', array($this, 'init'));
    }

    function init() {
        $this->check_includes();
        add_shortcode(ENOTARY_SC_SEARCH, array('enotary_plugin', 'sc_search'));
        add_shortcode(ENOTARY_SC_CERT, array('enotary_plugin', 'sc_cert'));
        add_shortcode(ENOTARY_SC_PACKAGES, array('enotary_plugin', 'sc_packages'));
	add_shortcode(ENOTARY_SC_PLATFORM, array('enotary_plugin', 'sc_platform'));

        //$css = plugins_url('/' . 'assets' . '/' . 'css' . '/' . 'qstrap.css', __FILE__);
        //wp_enqueue_style('enotary', $css);
    
        $css = plugins_url('/assets/css/styles.css', __FILE__);
        wp_enqueue_style('enotary2', $css);

        wp_enqueue_script("jquery");

        wp_deregister_script('enotary');

        $js = plugins_url('/assets/js/search.js', __FILE__);
        wp_register_script('enotary', $js);
        wp_enqueue_script('enotary');

        $js = plugins_url('/assets/js/tools.js', __FILE__);
        wp_register_script('enotary2', $js);
        wp_enqueue_script('enotary2');

	$js = plugins_url('/assets/js/form-order-email.js', __FILE__);
        wp_register_script('formOrderdEmail', $js);
        wp_enqueue_script('formOrderdEmail');
    }

    function _add_tp_item($group, $title, $descr) {
        $item = $group->add_a_ex('', 'tp-item', '#');
        $item->add_img('', '');

        $group_info = $item->add_div_ex('', 'tp-info');
        $group_info->add_h($title, 3);
        $group_info->add_p($descr);
    }

    /**
      Show-Generate search box for sites
     */
    public static function sc_search($atts, $content = '') {
        $html = new OneItem();
        //$html->add_p('Sample eNotary shortcode generator...');
	$group_search = $html->add_div_ex('search-form-platforms', '');
        $input = $group_search->add_tag('input text');
        $input->set_param('onkeyup', 'enot_search();');
        $input->set_param('placeholder', 'Введите название портала или площадки...');
        $input->set_param('name', 'search');
		$input->set_param('class', 'form-control clr-telegray');
        $input->id = 'search';

	$button = $group_search->add_tag('button');
	$button->set_param('class', 'clr-telegray');

        $html->add_p_ex('', 'tiny clr-telegray m-l-1', 'Пример поиска: госуслуги');
        $html->add_div_ex('tp-items', 'group-icons');
        //$GLOBALS['enotary_plugin']->_add_tp_item($group_res, 'Госуслуги', 'Портал городских услуг');
        //$GLOBALS['enotary_plugin']->_add_tp_item($group_res, 'Стройторги', 'Новый портал строительных технологий');
        //$GLOBALS['enotary_plugin']->_add_tp_item($group_res, 'СудМедПро', 'Судебные техноолгии для примера');
        //$GLOBALS['enotary_plugin']->_add_tp_item($group_res, 'Новострой', 'Стройплощадки для новых построек');

        $html->add_script('enot_add_default_tp();');
        return $html->as_html();
    }
	
    public static function sc_platform($atts, $content = '') {
	global $wpdb;
	
        if (!defined('PLATFORMS')) {
            require_once($GLOBALS['enotary_plugin']->get_my_path('classes') . 'platforms.php');
        }
		$params = shortcode_atts(array('type' => 'single'), $atts );
		$html = new OneItem('', 'body-platform');
		if ($params['type'] == 'list')
		{
		    $type_platforms = $wpdb->get_blog_prefix() . 'platforms';
		    $query_platforms = "select * from ".$type_platforms;
		    $result_platforms = $wpdb->get_results($query_platforms);




		    foreach ($result_platforms as $platform)
		    {
			$html->add_h($platform->name, 2);
			$html->add_p("<strong><em>Описание площадки(портала):</em></strong> {$platform->descr}");
			$html->add_p("<strong><em>Web-адрес площадки(портала):</em></strong> {$platform->links}");
			if ($platform->type == 1)
			{
			    $types = "Государственная площадка";
			}
			if ($platform->type == 2)
			{
			    $types = "Коммерческая площадка";
			}

			$html->add_p("<strong><em>Тип площадки(портала):</em></strong> {$types}");
			$html->add_p("<strong><em>Стоимость участия на площадке(портале):</em></strong> {$platform->price} руб.");
			$html->add_p("<strong><em>Примечание:</em></strong> {$platform->note}");
			$html->add_div_ex('', 'platform-buttons');
			$html->add_hr();
		    }

		}
		else
		{
		    $id = (get_query_var('page')) ? get_query_var('page') : 1;
		    $tb_pl = $wpdb->get_blog_prefix()."platforms";
		    $query_pl = "select count(*) as num from ".$tb_pl." where id=".$id;
		    $result_pl = $wpdb->get_results($query_pl);
		    foreach ($result_pl as $num_pl)
		    {
			$count_pl = $num_pl->num;
		    }
		    
		    if ($count_pl >0)
		    {
			$tb_pl = $wpdb->get_blog_prefix()."platforms";
			$query_pl = "select * from ".$tb_pl." where id=".$id;
			$result_pl = $wpdb->get_results($query_pl);
			foreach ($result_pl as $platform)
			{
			    $html->add_h($platform->name, 2);
			    $html->add_p("<strong><em>Описание площадки(портала):</em></strong> {$platform->descr}");
			    $html->add_p("<strong><em>Web-адрес площадки(портала):</em></strong> {$platform->links}");
			    if ($platform->type == 1)
			    {
				$types = "Государственная площадка";
			    }
			    if ($platform->type == 2)
			    {
				$types = "Коммерческая площадка";
			    }

			    $html->add_p("<strong><em>Тип площадки(портала):</em></strong> {$types}");
			    $html->add_p("<strong><em>Стоимость участия на площадке(портале):</em></strong> {$platform->price} руб.");
			    $html->add_p("<strong><em>Примечание:</em></strong> {$platform->note}");
			    $html->add_div_ex('', 'platform-buttons');
			}
			
		    }
		    else $html->add_h("Извините, площадка не найдена, попробуйте снова", 2);
		}
		return $html->as_html();
    }

    public static function sc_packages($atts, $content = '') {
        if (!defined('PLATFORMS')) {
            require_once($GLOBALS['enotary_plugin']->get_my_path('classes') . 'platforms.php');
        }
     $list_packages = new eNotaryListPackages();
      return $list_packages->as_html();
    }
    
    /**
     * Show Package page
     * 
     * @param type $atts
     * @param type $content
     * @return type
     */
    public static function sc_cert($atts, $content = '') {
        if (!defined('PLATFORMS')) {
            require_once($GLOBALS['enotary_plugin']->get_my_path('classes') . 'platforms.php');
        }
        if (!defined('ORDER_FORM_3S')) {
            require_once($GLOBALS['enotary_plugin']->get_my_path('classes') . 'order_form_3_step.php');
        }

        $form = new orderForm3Step();
        return $form->as_html();
     
    }

    function check_includes() {
        if (!defined('ONEITEM')) {
            require_once($this->get_my_path('classes') . 'oneitem.php');
        }
        
      require_once($this->get_my_path('classes') . 'enot_lists.php');
    }

    function get_my_path($folder = '') {
        if ($folder == '') {
            return WP_PLUGIN_DIR . '/' . ENOTARY_FOLDER . '/';
        } else {
            return WP_PLUGIN_DIR . '/' . ENOTARY_FOLDER . '/' . $folder . '/';
        }
    }

}

// class

function enot_send_email_to_admin() {
    /**
     * At this point, $_GET/$_POST variable are available
     */
    // Sanitize the POST field
    // Generate email content
    // Send to appropriate email

	echo 'xxxx - sample get form data - Name: ' . $_POST['fullname'] . ', Email: ' . $_POST['email'];
    }

    add_action('admin_post_nopriv_order_form', 'enot_send_email_to_admin');
    add_action('admin_post_order_form', 'enot_send_email_to_admin');

    $GLOBALS['enotary_plugin'] = new enotary_plugin();
}//End for plugins Denis


/************************************************************************************/
/*                                                                                  */
/*             Admins                                                               */
/*                                                                                  */
/************************************************************************************/

function eNotary_pages() {
    // Add a new top-level menu (ill-advised):
//    add_menu_page('Type Users', 'eNotary Options', 8, __FILE__, 'eNotary_typeusers');
    add_menu_page('Type Users', 'eNotary Options', 7, 'eNotary Options', 'eNotary_toplevel_page');
    // Add a submenu to the custom top-level menu:
    add_submenu_page('eNotary Options', 'Тип Заказчика', 'Тип Заказчика', 7, 'eNotary_typeusers', 'eNotary_typeusers');
    add_submenu_page('eNotary Options', 'Площадка', 'Площадка', 7, 'eNotary_platforms', 'eNotary_platforms');

}

function register_eNotary_settings() {
    //register our settings
//    register_setting( 'ldap-settings-group', 'servers' );
//    register_setting( 'ldap-settings-group', 'dn' );
}

function eNotary_toplevel_page() {
    echo "<h4>Settings eNotary plugin pages</h4>";
    echo "";
    echo '<div class="notebook" id="notebook1">';
    echo ' <ul class="tabs">';
    echo '<li><a href="#page1-1">Заказчики</a></li>';
    echo '<li><a href="#page1-2">Вторая</a></li>';
    echo '<li><a href="#page1-3">И третья</a></li>';
    echo '<li><a href="#page1-4">Даже четвертая</a></li>';
    echo '<li><a href="#page1-5">И совсем уж пятая</a></li>';
    echo '</ul>';
    echo '<ul class="pages">';
    echo '<li class="page" id="page1-1">';
    global $wpdb;
    $tb_name = $wpdb->get_blog_prefix() . 'typeusers';
    $hidden_name = 'name_hidden';
    
    $query = "select id, name from ".$tb_name;
    $result = $wpdb->get_results($query);

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if(  $_POST[ $hidden_name ] == 'Y'  ) {
        // Read their posted value
        $opt_name = $_POST[ "names" ];
	
	$wpdb->insert(
	    $tb_name, array(
	    'name' => $opt_name
	    )
	);
	echo '<div class="updated"><p><strong>'. _e('Options saved.', 'mt_trans_domain').'</strong></p></div>';
    }
    echo '<div class="wrap">';
    echo '<h2>Заказчики</h2>';
    echo '<h3>Существующие заказчики в базе данных</h3>';
    echo '<table class="table-admins">';
    echo '<tr><th>id</th><th>Заказчик</th></tr>';
    foreach ($result as $results){
	echo '<tr>';
	echo '<td>'.$results->id.'</td>';
	echo '<td>'.$results->name.'</td>';
	echo '</tr>';
    }
    echo '</table>';
    echo ' <form method="post" actions="'. str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'">';
    echo '<input type="hidden" name="'.$hidden_name.'" value="Y">';
    echo '<table class="form-table">';
    echo ' <tr valign="top">';
    echo ' <th scope="row">Заказчик</th>';
    echo '<td><input type="text" name="names" id="names" /></td>';
    echo '</tr>';
    
    echo '</table>';

    echo '<input type="hidden" name="action" value="update" />';
    echo '<input type="submit" id="Save" name="Save" class="button-primary" value="'. _e('Add').'" />';

    echo '</form>';
    echo '</div>';
    
    
    echo '</li>';
    echo '<li class="page" id="page1-2">2</li>';
    echo '<li class="page" id="page1-3">3</li>';
    echo '<li class="page" id="page1-4">4</li>';
    echo '<li class="page" id="page1-5">5</li>';
    echo '</ul>';
    echo '</div>';
}

function eNotary_typeusers() {
    global $wpdb;
    $tb_name = $wpdb->get_blog_prefix() . 'typeusers';
    $hidden_name = 'name_hidden';
    
    $query = "select id, name from ".$tb_name;
    $result = $wpdb->get_results($query);

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if(  $_POST[ $hidden_name ] == 'Y'  ) {
        // Read their posted value
        $opt_name = $_POST[ "names" ];
	
	$wpdb->insert(
	    $tb_name, array(
	    'name' => $opt_name
	    )
	);
	echo '<div class="updated"><p><strong>'. _e('Options saved.', 'mt_trans_domain').'</strong></p></div>';
    }
    echo '<div class="wrap">';
    echo '<h2>Заказчики</h2>';
    echo '<h3>Существующие заказчики</h3>';
    echo '<table class="table-admins">';
    echo '<tr><th>id</th><th>Заказчик</th></tr>';
    foreach ($result as $results){
?>
	<tr>
	<td><?php echo $results->id; ?></td>
	<td><?php echo $results->name; ?></td>
	</tr>
<?php
    }
    echo '</table>';
    echo ' <form method="post" actions="'. str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'">';
    echo '<input type="hidden" name="'.$hidden_name.'" value="Y">';
    echo '<table class="form-table">';
    echo ' <tr valign="top">';
    echo ' <th scope="row">Заказчик</th>';
    echo '<td><input type="text" name="names" id="names" /></td>';
    echo '</tr>';
    
    echo '</table>';
?>
    <input type="hidden" name="action" value="update" />
    <input type="submit" id="Save" name="Save" class="button-primary" value="<?php _e('Добавить запись'); ?>" />
    </form>
    </div>

<?php
}

function eNotary_platforms(){
    global $wpdb;
    $tb_name = $wpdb->get_blog_prefix() . 'platforms';
    $hidden_name = 'name_hidden';
    
    $query = "select id, name, descr, links, type, price, note from ".$tb_name;
    $result = $wpdb->get_results($query);

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if(  $_POST[ $hidden_name ] == 'Y'  ) {
        // Read their posted value
        $name = $_POST[ "names" ];
	$descr = $_POST["descr"];
	$link = $_POST["links"];
	$type = $_POST["type"];
	$price = $_POST["price"];
	$note = $_POST["note"];
	
	$links = '<a href="'.$link.'" target="_blank">'.$link.'</a>';
	$wpdb->insert(
	    $tb_name, array(
	    'name' => $name,
	    'descr' => $descr,
	    'links' => $links,
	    'type' => $type,
	    'price' => $price,
	    'note' => $note
	    )
	);
	echo '<div class="updated"><p><strong>'. _e('Option saved', 'mt_trans_domain').'</strong></p></div>';

    }
    echo '<div class="wrap">';
    echo '<h2>Платформы</h2>';
    echo '<h3>Существующие платформы</h3>';
    echo '<table class="table-admins" border="1">';
    echo '<tr><th>id</th><th>Наименование площадки</th><th>Описание площадки</th><th>Ссылка на сайт площадки</th><th>Тип площадки</th><th>Стоимость участия на площадке</th><th>Примечание</th></tr>';
    foreach ($result as $results){
	echo '<tr>';
	echo '<td>'.$results->id.'</td>';
	echo '<td>'.$results->name.'</td>';
	echo '<td>'.$results->descr.'</td>';
	echo '<td>'.$results->links.'</td>';
	if ($results->type == 1)
	{
	    echo '<td>Государственная площадка</td>';
	}
	if ($results->type == 2)
	{
	    echo '<td>Коммерческая площадка</td>';
	}
	echo '<td>'.$results->price.'</td>';
	echo '<td>'.$results->note.'</td>';
	echo '</tr>';
    }
    echo '</table>';
    echo ' <form method="post" actions="'. str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'">';
    echo '<input type="hidden" name="'.$hidden_name.'" value="Y">';
    echo '<table class="form-table">';
    echo ' <tr valign="top">';
    echo ' <th scope="row">Добавить платформу</th>';
    echo '</tr>';
    echo '<tr>';
    echo '<th>Наименование платформы</th>';
    echo '<td><input type="text" name="names" id="names" /></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th>Описание платформы</th>';
    echo '<td><input type="text" name="descr" id="descr" /></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th>Ссылка на сайт платформы</th>';
    echo '<td><input type="text" name="links" id="links" /></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<th>Тип платформы</th>';
    echo '<td>';
    echo '<select name="type" id="type">';
    echo '<option value="1">Государственная площадка</option>';
    echo '<option value="2">Коммерческая площадка</option>';
    echo '</select>';
    echo '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<th>Стоимость участия на площадке</th>';
    echo '<td><input type="text" name="price" id="price" /></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th>Примечание</th>';
    echo '<td><input type="text" name="note" id="note" /></td>';
    echo '</tr>';

    echo '</table>';
?>
    <input type="hidden" name="action" value="update" />
    <input type="submit" class="button-primary" value="<?php  _e('Добавить площадку'); ?>" />
<?php
    echo '</form>';
    echo '</div>';



}

?>