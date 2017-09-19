<!doctype html>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="yandex-verification" content="5b1e357e33990de1" />
	<title><?php wp_get_document_title(); ?> <?php bloginfo('name'); ?></title>
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<!--header start here-->

<div class="header">

<small>
	<div id="top-quick-contact" class="container-fluid top-header p-a-05 clr-diamondBlue bck-steelBlue">
		<div class="container">
			<div class="row  text-xs-center">
				<div class="col-md-6 text-md-left">
					<p><i class="icon fa fa-phone"></i>8(495)663-30-34 | 8(495)663-30-73<span class="hidden-sm-down"> | 8(495)663-30-93</span></p>
				</div>
				<div class="col-md-3">
					<p><a class="clr-diamondBlue" href="mailto:e-notary@signal-com.ru"><i class="icon fa fa-envelope"></i>e-notary@signal-com.ru</a></p>
				</div>
				<div class="col-md-3 text-md-right">
					<p><i class="icon fa fa-location-arrow"></i><a href="/wp-content/uploads/2017/05/Signal-COM.pdf" target="_blank">Москва, ул. Угрешская д.2 с.15</a></p>
				</div>
			</div>
		</div>
	</div>
</small>


	<div class="container p-a-0">
		<div class="col-xl-2 col-sm-3 p-a-0">
			<a class="navbar-brand logo" href="/"><img alt="e-Notary" src="<?php bloginfo('template_url'); ?>/images/logo.png"></a>
		</div>
		<div class="col-xl-10 col-sm-9 m-t-2-enotary p-r-0 p-l-0">
			<nav class="navbar navbar-light navbar-enotary hidden-lg-down bck-diamondBlue">
				<?php enotary_nav_menu('top_menu') ?>
				<form id="search-form" name="search" action="<?php echo home_url( '/' ) ?>" method="get" class="search-form">
					<input type="text" value="<?php echo get_search_query() ?>" name="s" placeholder="Поиск" class="form-control bck-diamondBlue">
					<button type="submit"></button>
				</form>
			</nav>
			<button class="navbar-toggler hidden-xl-up pull-xs-right" type="button" data-toggle="collapse" data-target="#menu-mobile">Меню &#9776;</button>
		</div>
	</div>

	<div class="container bck-diamondBlue hidden-xl-up">
		<div class="collapse" id="menu-mobile">
			<form name="search" action="<?php echo home_url( '/' ) ?>" method="get" class="m-b-1">
				<div class="row">
					<div class="col-xs-10">
						<input class="form-control" value="<?php echo get_search_query() ?>" name="s" placeholder="Поиск" name="search" type="search">
					</div>
					<div class="col-xs-2">
						<button class="btn btn-secondary" type="submit"></button>
					</div>
			   </div>
			</form>
			<?php enotary_mobile_nav_menu('top_menu') ?>
		</div>
	</div>
</div>
<!--header end here-->