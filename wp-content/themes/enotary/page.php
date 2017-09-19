<?php get_header(); ?>
<!--content start here-->
<div class="container m-t-1">
	<div class="row">
		<div class="col-md-2 sidebar-enotary p-a-0 p-r-1"><?php get_sidebar(); ?></div>
		<div class="col-md-10 page-enotary p-a-0">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<h1 class="title"><?php echo(get_the_title($post->post_parent)) ?> / <?php the_title(); ?></h1>
					<?php the_content(); ?>
				<?php endwhile; else: ?>
			<p><?php _e('Sorry, this page does not exist.'); ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>
<!--content end here-->
<?php get_footer(); ?>