<h4><img class="navigation" src="<?php bloginfo('template_url'); ?>/images/navigation.png"></i>Навигация</h4>
<ul class="list-unstyled">
  <?php 
    $current_id = $post->ID;
    $object = get_ancestors( $current_id, 'page' );
    if (!empty($object)){
      $pages = get_pages( array( 'parent' => $object, 'sort_column' => 'menu_order', 'post_status' => 'publish') );
      foreach( $pages as $page ) { 
        if ($page->menu_order >= 0){
	   $str = '<li'.(($current_id == $page->ID)?' class="active"':'').'><a href="'.get_page_link( $page->ID ).'">'.$page->post_title.'</a></li>';
	   echo ($str);
        }
      }
    }
  ?>	
</ul>