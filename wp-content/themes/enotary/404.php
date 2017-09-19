<?php
/**
 * The template for displaying 404 pages (not found)
 */

get_header(); ?>
<div class="container categories text-xl-right text-xs-center m-t-1">
	<h1 class="title"><?php _e( 'Ой! Эта страница не найдена.'); ?></h1>
	<h2><?php _e( 'Похоже, в этом месте ничего не найдено. Может попробуй поискать?'); ?></h2>
	<div class="text-xs-center m-t-2"><?php get_search_form(); ?></div>
</div>
<?php get_footer(); ?>
