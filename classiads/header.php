<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage classiads
 * @since classiads 1.2.2
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="user-scalable = yes">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php 
	if (isset($_SERVER['HTTP_USER_AGENT']) &&
    (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        header('X-UA-Compatible: IE=9');
	global $redux_demo; 
	$favicon = $redux_demo['favicon']['url'];
	?>

	<?php if (!empty($favicon)) : ?>
	<link rel="shortcut icon" href="<?php echo $favicon; ?>" type="image/x-icon" />
	<?php endif; ?>

	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<?php 

$layout = $redux_demo['layout-version'];

?>

<body <?php if($layout == 2){ ?>id="boxed" <?php } ?> <?php body_class(); ?>>

	<section id="top-menu-block">

		<div class="container">

			<?php 

			$header_version = $redux_demo['header-version'];

			?>

			<?php if($header_version == 2) { ?>				
			
			<section id="register-login-block-top">
				<ul class="ajax-register-links inline">
					<?php 
						if ( is_user_logged_in() ) {


						$profile = $redux_demo['profile'];						
						
						$new_post = $redux_demo['new_post'];

					?>
<!-- 					<li class="first">
						<a href="<?php echo $new_post; ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style"><?php printf( __( 'Разместить объявление', 'heman' )); ?></a>
					</li> -->
					
					<li class="first1">
						<a href="<?php echo $profile; ?>" class="myButton1 ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Login"><?php printf( __( 'Мой профиль', 'heman' )); ?></a>
					</li>
					
					<li class="last1">
						<a href="<?php echo wp_logout_url(get_option('siteurl')); ?>" class="myButton1 ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Logout"><?php printf( __( 'Выйти', 'heman' )); ?></a>
					</li>
					<?php } else { 

						$login = $redux_demo['login'];
						$register = $redux_demo['register'];
						$new_post = $register;
					?>
					<li class="first1">
						<a href="<?php echo $register; ?>" class="myButton1 ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Register"><?php printf( __( 'Регистрация', 'heman' )); ?></a>
					</li>
					<li class="last1 login1">
						<a href="<?php echo $login; ?>" class="myButton1 ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Login"><?php printf( __( 'Войти', 'heman' )); ?></a>
					</li>
				<?php } ?>
				</ul>  
			</section>

			<?php } ?>
			<?php 
				$top_phone = $redux_demo['top_phone'];
				$top_phone_icon = $redux_demo['top_phone_icon'];
				$top_mail = $redux_demo['top_mail'];
				$top_mail_icon = $redux_demo['top_mail_icon'];
			?>
			<div class="top-call">
				<?php echo $top_phone_icon; ?>
				<span class="call-head"><?php _e( 'WhatsApp | Viber', 'agrg' ); ?> :</span>
				<span class="call-number"><?php echo $top_phone; ?> </span>
				<?php echo $top_mail_icon; ?>
				<span class="call-head"><?php _e( '', 'agrg' ); ?></span>
				<span class="call-number top-email"><?php echo $top_mail; ?> </span>
			</div>

		</div>

	</section>
																		
	<header id="navbar">

		<div class="container">

			<?php if($header_version == 2) { ?>

			<a class="logo pull-left" href="<?php echo home_url(); ?>" title="Home">
				<?php global $redux_demo; $logo = $redux_demo['logo']['url']; if (!empty($logo)) { ?>
					<img src="<?php echo $logo; ?>" alt="Logo" />
				<?php } else { ?>
					<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
				<?php } ?>
			</a>

			<div id="version-two-menu" class="main_menu">
				<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => 'false')); ?>
			</div>

			<div id="obyavlenie">
			<ul>
				<li>
					<a href="<?php echo $new_post;  ?>" class=" myButton ctools-use-modal ctools-modal-ctools-ajax-register-style"><?php printf( __( 'Разместить объявление', 'heman' )); ?></a>
				</li>
			</ul>					
			</div>

			

			<?php } ?>
							
		</div>

	</header><!-- #masthead -->