<?php
//############################################### INIT ######################################
function enqueue_styles() {
	wp_enqueue_style( 'bootstrap-style', get_template_directory_uri().'/bootstrap/css/bootstrap.min.css');
	wp_enqueue_style( 'enotary-style', get_stylesheet_uri(),array(), hash_file('crc32', get_stylesheet_directory() . '/style.css'));
	wp_register_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
	wp_enqueue_style( 'font-awesome');
}
add_action('wp_enqueue_scripts', 'enqueue_styles');

function enqueue_scripts () {
	wp_enqueue_script( 'jquery', get_template_directory_uri().'/bootstrap/js/jquery-1.11.0.min.js');
	wp_enqueue_script( 'tether-js', get_template_directory_uri().'/bootstrap/js/tether.min.js', array('jquery'), '' );
	wp_enqueue_script( 'bootstrap-js', get_template_directory_uri().'/bootstrap/js/bootstrap.min.js', array('jquery'), '' );
}
add_action('wp_enqueue_scripts', 'enqueue_scripts');

if (function_exists('add_theme_support')) {
	add_theme_support('menus');
}
register_nav_menus(
	array(
		'top_menu' => 'Шапка сайта'
	)
);
add_filter('widget_text', 'do_shortcode');
add_theme_support( 'post-thumbnails', array( 'page', 'post' ) );
//###############################################END INIT######################################


//############################################### MENU ######################################
class enotary_walker_nav_menu extends Walker_Nav_Menu {

	// add classes to ul sub-menus
	function start_lvl( &$output, $depth ) {
		// depth dependent classes
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
		$display_depth = ( $depth + 1); // because it counts the first submenu as 0
		$classes = array(
			'dropdown-menu',
			'bck-steelBlue',
			( $display_depth >=2 ? 'sub-sub-menu' : '' ),
			'menu-depth-' . $display_depth
			);
		$class_names = implode( ' ', $classes );

		// build html
		$output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
	}

	// add main/sub classes to li's and links
	 function start_el( &$output, $item, $depth, $args ) {
		global $wp_query;
		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

		// depth dependent classes
		$parent = (in_array( 'menu-item-has-children', $item->classes )) ? true : false;
		$depth_classes = array(
			( $parent ? 'dropdown' : '' ),
			( $depth >=2 ? 'sub-sub-menu-item' : '' ),
			'nav-item'
		);
		$depth_class_names = esc_attr( implode( ' ', $depth_classes ) );
		// passed classes
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );
	
		// build html
		if ($depth == 0) $output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';
		else $output .= $indent . '<li>';

		// link attributes
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$attributes .= ' class="'.( $depth > 0 ? 'dropdown-item small' : 'nav-link uppercase' ) . ($parent?' dropdown-toggle':'').'"';
		if ($parent) $attributes  .= ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"';
		$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			$args->before,
			$attributes,
			$args->link_before,
			apply_filters( 'the_title', $item->title, $item->ID ),
			$args->link_after,
			$args->after
		);

		// build html
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
class enotary_mobile_walker_nav_menu extends Walker_Nav_Menu {

	 function start_el( &$output, $item, $depth, $args ) {
		global $wp_query;
		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

		// depth dependent classes
		$depth_classes = array(
			'nav-item'
		);
		$depth_class_names = esc_attr( implode( ' ', $depth_classes ) );
		// passed classes
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );
	
		// build html
		if ($depth == 0) $output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';

		// link attributes
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$attributes .= ' class="nav-link uppercase"';
		$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			$args->before,
			$attributes,
			$args->link_before,
			apply_filters( 'the_title', $item->title, $item->ID ),
			$args->link_after,
			$args->after
		);

		// build html
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

function enotary_nav_menu( $menu_id ) {
	$args = array(
		'theme_location'    => $menu_id,
		'container'     => 'div',
		'container_id'  => 'main-menu',
		'menu_class'    => 'nav navbar-nav',
		'echo'          => true,
		'items_wrap'    => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'depth'         => 10,
		'walker'        => new enotary_walker_nav_menu
	);
	wp_nav_menu( $args );
}
function enotary_mobile_nav_menu( $menu_id ) {
	$args = array(
		'theme_location'=> $menu_id,
		'container' 	=> false,
		'menu_class'    => 'nav nav-pills nav-stacked',
		'echo'          => true,
		'items_wrap'    => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'depth'         => 1,
		'walker'        => new enotary_mobile_walker_nav_menu
	);
	wp_nav_menu( $args );
}
//############################################### END MENU ######################################

function enotary_home_link_important($url, $icon){
	$page = get_page_by_path($url);
	return "<a href=\"$url\"><i class=\"icon fa $icon\"></i><span>{$page->post_title}</span></a>";
}

function wp_corenavi($max) {
	global $wp_query;
	if ($max > 1){
		if (!$current = get_query_var('paged')) $current = 1;
		$current_str = 'current';
		$a['base'] = str_replace(999999999, '%#%', get_pagenum_link(999999999));
		$a['total'] = $max;
		$a['current'] = $current;
		$a['type'] = 'array';
		$a['start_size'] = 0;
		$a['mid_size'] = 0;
		$a['end_size'] = 0;
		$a['prev_text'] = 'Новые';
		$a['next_text'] = 'Старые';
		$nav = '<nav><ul class="pager">';
		$links = paginate_links($a);
		$first = array_shift($links);
		$end = array_pop($links);
		if (strrpos($first, $current_str) === false) $nav .= "<li class=\"pager-prev\">$first</li>";
		if (strrpos($end, $current_str) === false) $nav .= "<li class=\"pager-next\">$end</li>";
		$nav .= '</ul></nav>';
		echo $nav;
	}
}
function enotary_search_pagination($str) {
	preg_match_all('/(href|current|dots)+.*(<\/a>|<\/span>)+/', $str, $matches, PREG_SET_ORDER, 0);
	$str = '<nav class="text-xs-center"><ul class="pagination">';
	foreach ($matches as $match){
		if (strpos($match[1], 'current') !== false){
			$str .= '<li class="page-item active"><a class="page-link" href="#">'.str_replace("current'>", '', $match[0]).'<span class="sr-only">(current)</span></a></li>';
		}else $str .=  '<li class="page-item"><a class="page-link" '.$match[0]. '</a></li>';
	}
	$str .= '</ul></nav>';
	echo $str;
}