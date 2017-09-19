<?php
/*
Template Name: Список сертификатов
*/
get_header(); ?>

<!--content start here-->



<div class="container m-t-1 page-enotary">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<h1 class="title uppercase"><?php the_title(); ?></h1>
<!--
	<p style="color:#37637e">Любой из наших пакетов могут использовать юридические лица, физические лица и индивидуальные предприниматели.</p>
-->
	<?php the_content(); ?>

	<?php endwhile; else: ?>
		<p><?php _e('Sorry, this page does not exist.'); ?></p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>	

<!--content end here-->
