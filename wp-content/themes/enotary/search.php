<?php get_header(); ?>

<!--content start here-->
<div class="container m-b-2 m-t-1 p-r-0">
	<h1 class="title">Результаты поиска: <?php echo (get_search_query()); ?></h1>
</div>
<div class="container enotary-news">
	<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="row">
		<?php if(has_post_thumbnail()) :?>
			<div class="col-md-4 text-xs-center"><?php the_post_thumbnail('full'); ?></div>
			<div class="col-md-8">
		<?php else : ?>
			<div class="col-md-12">
		<?php endif;?>
			<h2 class="m-t-1"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
			<div><?php the_content();?></div>
			<div class="text-muted "><small><?php echo get_the_date('d.m.y'); ?></small></div>
		</div>
	</div>
	<hr>
	<?php endwhile;?>
	<?php enotary_search_pagination(get_the_posts_pagination(array('end_size'=> '2', 'mid_size'=> '5', 'prev_text' => '«','next_text'=> '»'))); ?>
	<?php else : ?>
		<h2 class="m-t-1">Извините, ничего не найдено</h2>
	<?php endif; ?>
<!--content end here-->
<?php get_footer(); ?>

