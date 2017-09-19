<?php
	/*
	Template Name: Дин. Блог
	*/
?>

<?php get_header(); ?>
<!--content start here-->
<div class="container m-b-2 m-t-1">
	<h1 class="title"><?php  single_post_title(); ?></h1>
</div>
<div class="container enotary-news">
	
	<?php 
		$cur_cat_id = get_cat_id(single_cat_title("",false));
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args = array('numberposts' => 0, 'paged' => $paged, 'category_name'=>'blog');
		$postslist = new WP_Query( $args );
	?> 
	<?php if ( $postslist->have_posts() ) : ?>
	<?php while ( $postslist->have_posts() ) : $postslist->the_post(); ?>
	   <div class="row">
		<div class="col-md-4 text-xs-center">
			<img src="<?php echo get_the_post_thumbnail_url( $page->ID, 'full');?>">
		</div>
		<div class="col-md-8">
			<h2 class="m-t-1"><?php the_title(); ?></h2>
			<div>
				<?php the_content('');?>
			</div>
			<div class="text-muted "><small><?php echo get_the_date('d.m.y'); ?></small></div>
			<div class="tools text-xs-right">
				<a href="<?php the_permalink() ?>" class="btn btn-success">Дальше</a>
			</div>   
		</div>
	</div>
	<hr>
	<?php endwhile;?>
	<?php wp_reset_postdata(); ?>
	<?php if (function_exists('wp_corenavi')) wp_corenavi($postslist->max_num_pages); ?> 
	<?php endif; ?>
</div>
<!--content end here-->
<?php get_footer(); ?>
