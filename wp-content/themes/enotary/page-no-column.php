<?php 

/*
Template Name: Страница без колонки
*/

get_header(); ?>
<!--content start here-->
<div class="container m-t-1 p-r-0">
	<div class="row-full">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<h1 class="title"><?php the_title(); ?></h1>
					<?php the_content(); ?>
				<?php endwhile; else: ?>
			<p><?php _e('Sorry, this page does not exist.'); ?></p>
			<?php endif; ?>
		</div>
</div>
<!--content end here-->
<?php get_footer(); ?>