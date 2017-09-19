<?php get_header(); ?>
<!--content start here-->
<div class="container m-t-1">
	<div class="row">
		<div class="col-xs-12 col-lg-9">
			<div id="main-slider" class="carousel slide" data-ride="carousel">
				<ol class="carousel-indicators">
					<li data-target="#main-slider" data-slide-to="0" class="active"></li>
					<li data-target="#main-slider" data-slide-to="1"></li>
					<li data-target="#main-slider" data-slide-to="2"></li>
				</ol>
			  <div class="carousel-inner" role="listbox">
				<div class="carousel-item active">
				  <img src="<?php bloginfo('template_url'); ?>/images/slide1.jpg" alt="Уже более 500 000 сертификатов для торговых площадок, юридических и физических лиц">
				</div>
				<div class="carousel-item">
				  <img src="<?php bloginfo('template_url'); ?>/images/slide2.jpg" alt="Разработчик и поставщик решений в области защиты информации">
				</div>
				<div class="carousel-item">
				  <img src="<?php bloginfo('template_url'); ?>/images/slide3.jpg" alt="Более 25 лет на рынке защиты информации">
				</div>
			  </div>
			  <a class="left carousel-control" href="#main-slider" role="button" data-slide="prev">
				<span class="icon-prev" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			  </a>
			  <a class="right carousel-control" href="#main-slider" role="button" data-slide="next">
				<span class="icon-next" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			  </a>
			</div>
		</div>
		<div class="col-lg-3 col-xs-12 helpful-inf text-xs-center">
			<div class="p-t-1 hidden-lg-up"></div>

			<h5 class="clr-diamondBlue text-xs-left">Важно</h5>
			<div class="small important text-xs-left">
			<p><?php echo enotary_home_link_important('info/reglament', 'fa-shield')?></p>
			<p><?php echo enotary_home_link_important('info/contracts_offer', 'fa-pencil-square-o')?></p>
			<p><?php echo enotary_home_link_important('info/documents', 'fa-book')?></p>
			<p><?php echo enotary_home_link_important('services/ldap', 'fa-server')?></p>
			</div>
			<a href="/packages" class="btn btn-order btn-block uppercase"><span class="hidden-md-down"><i class="icon fa fa-file-text-o"></i></span>ЗАКАЗАТЬ <span class="hidden-md-down"><br></span>СЕРТИФИКАТ</a>


		</div>
	</div>
</div>



<div class="container m-t-2 p-l-1">
	<div class="row">

		<div class="col-lg-4 overflow-hid">
			<h5 class="text-xs-center uppercase home bck-niagara m-b-1">Популярные пакеты</h5>
			<?php echo do_shortcode('[enotary-search]'); ?>
		</div>

		<div class="col-lg-8 text-xs-center p-a-0">
			<h5 class="text-xs-center uppercase home bck-niagara">Всего 4 простых шага для получения электронной подписи</h5>
			<!--
			<img class="width-100 m-t-3" src="<?php bloginfo('template_url'); ?>/images/4step-home.png" alt="4 простых шага для получения электронной подписи">
-->
			<div class="row group-icons">
				<div class="col-md-3 col-xs-6 p-a-0">
					<a href="/packages" class="inlineBlock p-a-1"><img src="<?php bloginfo('template_url'); ?>/images/home-01.jpg" alt="Выберите сертификат"></a>
				</div>
				<div class="col-md-3 col-xs-6 p-a-0">
					<a href="/info/documents" class="inlineBlock p-a-1"><img src="<?php bloginfo('template_url'); ?>/images/home-02.jpg" alt="Подготовьте документы"></a>
				</div>
				<div class="col-md-3 col-xs-6 p-a-0">
					<a href="#" class="inlineBlock  p-a-1"><img src="<?php bloginfo('template_url'); ?>/images/home-03.jpg" alt="Оплатите счет"></a>
				</div>
				<div class="col-md-3 col-xs-6 p-a-0">
					<a href="/wp-content/uploads/2017/05/Signal-COM.pdf" class="inlineBlock  p-a-1"><img src="<?php bloginfo('template_url'); ?>/images/home-04.jpg" alt="Получите сертификат"></a>
				</div>
			</div>
			<h5 class="text-xs-center uppercase home bck-niagara m-t-4 m-b-1">Популярные Услуги</h5>
			<div class="row group-icons">
				<div class="col-md-3 col-xs-6 p-a-0">
					<a href="services/timestamp" class="inlineBlock p-a-1"><img src="<?php bloginfo('template_url'); ?>/images/services1.png" alt="Сервер штампов времени (TSP)">
						<div>Сервер штампов времени (TSP)</div></a>
				</div>
				<div class="col-md-3 col-xs-6 p-a-0">
					<a href="/services/expertise" class="inlineBlock p-a-1"><img src="<?php bloginfo('template_url'); ?>/images/services2.png" alt="Экспертиза Электронной Подписи">
					<div>Экспертиза Электронной Подписи</div></a>
				</div>
				<div class="col-md-3 col-xs-6 p-a-0">
					<a href="/services/consulting" class="inlineBlock  p-a-1"><img src="<?php bloginfo('template_url'); ?>/images/services3.png" alt="Услуги корпоративным клиентам. Консалтинг">
					<div>Консалтинг</div></a>
				</div>
				<div class="col-md-3 col-xs-6 p-a-0">
					<a href="/services/pki" class="inlineBlock  p-a-1"><img src="<?php bloginfo('template_url'); ?>/images/services4.png" alt="Аутсорсинг PKI">
					<div>Аутсорсинг PKI</div></a>
				</div>
			</div>

			<div class="row m-t-3 text-xs-left">
				<div class="col-md-6">
					<h5 class="text-xs-center uppercase home bck-niagara m-b-1">Полезная информация</h5>
					<?php
						global $post;
						$args = array( 'posts_per_page' => 5, 'category_name'=> 'blog' );
						$myposts = get_posts( $args );
						foreach( $myposts as $post ){ setup_postdata($post);
					?>
					<p class="tiny"><a class="clr-diamondBlue" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
					<?php
					}
					wp_reset_postdata();
					?>
				</div>
				<div class="col-md-6">
					<h5 class="uppercase home">Новости</h5>
					<div class="news-home clr-diamondBlue m-t-1">
						<?php
							global $post;
							$args = array( 'posts_per_page' => 3, 'category_name'=> 'news' );
							$myposts = get_posts( $args );
							foreach( $myposts as $post ){ setup_postdata($post);
						?>
						<div>
							<p class="small"><span class="date m-r-1"><?php echo get_the_date('d.m.y'); ?></span><a class="clr-diamondBlue" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
							<hr/>
						</div>
						<?php
							}
							wp_reset_postdata();
						?>
						<p><a href="/news/" class="archive hidden-sm-down">Все новости...</a></p>
					</div>
				</div>
			</div>


		</div>
	</div>
</div>
<?php echo do_shortcode('[contact-form-7 id="1224" title="Форма Перезвоните мне"]'); ?>
<div class="container">
	<h5 class="uppercase home clr-diamondBlue">Наши клиенты</h5>
	<div id="sub-slider" class="carousel slide" data-ride="carousel">
		<ol class="carousel-indicators">
			<li data-target="#sub-slider" data-slide-to="0" class="active"></li>
			<li data-target="#sub-slider" data-slide-to="1"></li>
			<li data-target="#sub-slider" data-slide-to="2"></li>
		</ol>
		<div class="carousel-inner " role="listbox">
			<div class="carousel-item active">
				<img src="<?php bloginfo('template_url'); ?>/images/e-notary-clients-1.jpg" alt="Наши клиенты">
			</div>
			<div class="carousel-item">
				<img src="<?php bloginfo('template_url'); ?>/images/e-notary-clients-2.jpg" alt="Наши клиенты">
			</div>
			<div class="carousel-item">
				<img src="<?php bloginfo('template_url'); ?>/images/e-notary-clients-3.jpg" alt="Наши клиенты">
			</div>
		</div>
		<a class="left carousel-control" href="#sub-slider" role="button" data-slide="prev">
			<span class="icon-prev" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#sub-slider" role="button" data-slide="next">
			<span class="icon-next" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
</div>
<div class="container m-t-3 p-a-1 text-xs-center">
Удостоверяющий центр &laquo;e-Notary&raquo; существует с 2004 года, построен на базе сертифицированного ПАК УЦ Notary-PRO v.2.7, разработки компании “Сигнал-КОМ” и имеет действующую аккредитацию в Минкомсвязи России (<a href="/info/license/">свидетельство № 730</a>).
</div>
<!--content end here-->
<?php get_footer(); ?>