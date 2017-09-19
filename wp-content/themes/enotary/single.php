<?php get_header(); ?>
<!--content start here-->
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <div class="container m-b-2 m-t-1"><h1 class="title"><?php the_title(); ?></h1></div>
  <div class="container enotary-news">
    <div class="row">
      <?php if (has_post_thumbnail()): ?>
        <div class="col-md-4 text-xs-center m-r-1">
	<?php 
	  $thumbnail_id = get_post_thumbnail_id();
	  $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
	  if (empty($alt)){
	    $alt =  trim(esc_html(get_the_title()));
	    update_post_meta($thumbnail_id, '_wp_attachment_image_alt', $alt );
	   }
	   $url = get_the_post_thumbnail_url( $page->ID, 'full');
	   echo "<img alt=\"$alt\" src=\"$url\">"; 
	 ?>
	 </div>
	 <div class="col-md-8"></div>
        <?php endif; ?>
	<div class="p-t-1"><?php the_content('');?></div>
	  <div class="text-muted "><small><?php echo get_the_date('d.m.y'); ?></small></div>
	  <div class="tools text-xs-right">
	    <?php 
	      $cats =  get_the_category();
	      $cat = $cats[0];
	      $ahref = '<a href="'.(($cat->slug == 'blog') ? '/info/blog/' : '/news/').'" class="btn btn-success">Другие публикации</a>';
              echo ($ahref);
             ?>
	</div>
    </div>
    <?php endwhile; else: ?>
      <div class="container m-b-2"><h1 class="title"><?php _e('Sorry, this page does not exist.'); ?></h1></div>
    <?php endif; ?>
<!--content end here-->
<?php get_footer(); ?>
