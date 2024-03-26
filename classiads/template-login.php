<?php
/**
 * Template name: Login Page
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage classiads
 * @since classiads 1.2.2
 */

if ( is_user_logged_in() ) { 

	global $redux_demo; 
	$profile = $redux_demo['profile'];
	wp_redirect( $profile ); exit;

}

global $user_ID, $username, $password, $remember;

//We shall SQL escape all inputs
$username = esc_sql(isset($_REQUEST['username']) ? $_REQUEST['username'] : '');
$password = esc_sql(isset($_REQUEST['password']) ? $_REQUEST['password'] : '');
$remember = esc_sql(isset($_REQUEST['rememberme']) ? $_REQUEST['rememberme'] : '');
	
if($remember) $remember = "true";
else $remember = "false";
$login_data = array();
$login_data['user_login'] = $username;
$login_data['user_password'] = $password;
$login_data['remember'] = $remember;
$user_verify = wp_signon( $login_data, false ); 
//wp_signon is a wordpress function which authenticates a user. It accepts user info parameters as an array.
if($_POST){
	if ( is_wp_error($user_verify) ) {
		$UserError = "Invalid username or password. Please try again!";
	} else {

		global $redux_demo; 
		$profile = $redux_demo['profile'];
		wp_redirect( $profile ); exit;

	}
}

get_header(); ?>


	<div class="ad-title">
	
        		<h2><?php the_title(); ?> </h2> 	
	</div>

    <section class="ads-main-page">

    	<div class="container">
			<div class="log-in-logo">
				<a class="logo" href="<?php echo home_url(); ?>" title="Home">
					<?php global $redux_demo; $logo = $redux_demo['logo']['url']; if (!empty($logo)) { ?>
						<img src="<?php echo $logo; ?>" alt="Logo" />
					<?php } else { ?>
						<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
					<?php } ?>
				</a>
			</div>
	    	<div class="first clearfix log-in">
			
				<h2 class="login-title"><?php _e( 'ВОЙТИ', 'agrg' ); ?></h2> 
				<div id="edit-profile" class="clearfix">					
						
						<div class="span4">
							<form class="form-item login-form" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">

								<?php global $user_ID, $user_identity; get_currentuserinfo(); ?>

								<?php if(!empty($UserError)) { ?>
									<span class='error' style='color: #d20000; margin-bottom: 20px; font-size: 18px; font-weight: bold; float: left;'><?php echo $UserError; ?></span><div class='clearfix'></div>
								<?php } ?>

										<input type="text" id="contactName" Placeholder="<?php _e( 'ИмяПользователя', 'agrg' ); ?>" name="username" class="text input-textarea half" value="" />

								
										<input type="password" id="password" Placeholder="<?php _e( 'Пароль', 'agrg' ); ?>" name="password" class="text input-textarea half" value="" />



									<fieldset class="input-title">

										<label for="edit-title" class="remember-me">
											<input name="rememberme" type="checkbox" value="forever" style="float: left;"/><span style="margin-left: 10px; float: left;"><?php _e( 'Запомнить меня', 'agrg' ); ?></span>

											<?php 

												global $redux_demo; 
												$reset = $redux_demo['reset'];

											?>

										</label>

									</fieldset>


									
										<input type="hidden" id="submitbtn" name="submit" value="Войти" />
										<div class="clearfix"></div>
										<div class="btn-container">	
											<button class="btn form-submit" id="edit-submit" name="op" value="<?php _e( 'Разместить Объявление', 'agrg' ); ?>" type="submit"><?php _e('Войти', 'agrg') ?></button>
										</div>
									
									<a style="float: left;" class="forgot-link" href="<?php echo $reset; ?>"><?php printf( __( 'Забыли пароль?', 'agrg' )); ?></a>
								

							</form>
							<div class="clearfix"></div>
						</div>
						
						<div class="span4 last">
						<span><?php _e('Регистрация!', 'agrg') ?></span>
						<div class="register-page-title">
							
							<h5><?php _e( 'Войти с помощью социальной сети', 'agrg' ); ?></h5>

						</div>
						<div class="social-btn clearfix">
							<?php
							/**
							 * Detect plugin. For use on Front End only.
							 */
							include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

							// check for plugin using plugin name
							if ( is_plugin_active( "nextend-facebook-connect/nextend-facebook-connect.php" ) ) {
							  //plugin is activated
							
							?>

							
								<div class="social-btn-container">
									<a class="register-social-button-facebook" href="<?php echo get_site_url(); ?>/wp-login.php?loginFacebook=1" onclick="window.location = '<?php echo get_site_url(); ?>/wp-login.php?loginFacebook=1&redirect='+window.location.href; return false;"><?php _e('Войти с помощью Facebook', 'agrg') ?></a>
								</div>
							

							<?php } ?>

							<?php
							/**
							 * Detect plugin. For use on Front End only.
							 */
							include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

							// check for plugin using plugin name
							if ( is_plugin_active( "nextend-twitter-connect/nextend-twitter-connect.php" ) ) {
							  //plugin is activated
							
							?>

							
								<div class="social-btn-container">
									<a class="register-social-button-twitter" href="<?php echo get_site_url(); ?>/wp-login.php?loginTwitter=1" onclick="window.location = '<?php echo get_site_url(); ?>/wp-login.php?loginTwitter=1&redirect='+window.location.href; return false;"><?php _e('Войти с помощью Twitter', 'agrg') ?></a>
								</div>
							

							<?php } ?>

							<?php
							/**
							 * Detect plugin. For use on Front End only.
							 */
							include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

							// check for plugin using plugin name
							if ( is_plugin_active( "nextend-google-connect/nextend-google-connect.php" ) ) {
							  //plugin is activated
							
							?>

							
								<div class="social-btn-container">
									<a class="register-social-button-google" href="<?php echo get_site_url(); ?>/wp-login.php?loginGoogle=1" onclick="window.location = '<?php echo get_site_url(); ?>/wp-login.php?loginGoogle=1&redirect='+window.location.href; return false;"><?php _e('Войти с помощью Google', 'agrg') ?></a>
								</div>
							

							<?php } ?>
							
						</div>
						
						<div class="publish-ad-button login-page">

							<?php

								global $redux_demo; 
								$register = $redux_demo['register'];
								$reset = $redux_demo['reset'];

							?>
							
							<p><?php _e('Вы здесь впервые?', 'agrg') ?> <a href="<?php echo $register; ?>"><?php _e( "Регистрация!", "agrg" ); ?></a></p>

						</div>
						
					</div>

					
	    		</div>

	    	</div>

	    	
	    </div>

    </section>

<?php get_footer(); ?>