<?php
/*
Template Name: Категория
*/
?>

<?php get_header(); ?>
<!--content start here-->
<div class="container categories text-xs-center m-t-1">
	<h1 class="title"><?php the_title(); ?></h1>
	<?php $pages = get_pages( array( 'child_of' => $post->ID, 'sort_column' => 'menu_order') );foreach( $pages as $page ) { if ($page->menu_order >= 0){ ?><a href="<?php echo get_page_link( $page->ID ); ?>"><div class="card"><?php echo get_the_post_thumbnail( $page->ID, array(125,125));?><div class="card-block"><p class="card-title"><?php $title = $page->post_title; 
echo $title;?></p></div></div></a><?php }}?>
</div>
<!--content end here-->
<?php get_footer(); ?>