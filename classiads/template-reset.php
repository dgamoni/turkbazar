<?php
/**
 * Template name: Reset Password Page
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

global $resetSuccess;

if (!$user_ID) {

	if($_POST) 

	{

		// First, make sure the email address is set
		if ( isset( $_POST['email'] ) && ! empty( $_POST['email'] ) ) {

		  	// Next, sanitize the data
		  	$email_addr = trim( strip_tags( stripslashes( $_POST['email'] ) ) );

		  	$user = get_user_by( 'email', $email_addr );
		  	$user_ID = $user->ID;

		  	if( !empty($user_ID)) {

				$new_password = wp_generate_password( 12, false ); 

				if ( isset($new_password) ) {

					wp_set_password( $new_password, $user_ID );

					$message = "Check your email for new password.";

			      	$from = get_option('admin_email');
					$headers = 'From: '.$from . "\r\n";
					$subject = "Password reset!";
					$msg = "Reset password.\nYour login details\nNew Password: $new_password";
					wp_mail( $email_addr, $subject, $msg, $headers );

					$resetSuccess = 1;

				}

		    } else {

		      	$message = "There is no user available for this email.";

		    } // end if/else

		} else {
			$message = "Email should not be empty.";
		}

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
				<h2 class="login-title"><?php _e( 'СБРОС ПАРОЛЯ', 'agrg' ); ?></h2>	
				<div id="edit-profile" class="clearfix">

					<div class="span4">
						<form class="form-item login-form" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">

							<?php if($_POST) { 

								echo "<div id='result' style='margin-bottom: 30px;'><div class='message'><h3>".$message."</h3></div></div>";

							} ?>

								<?php if($resetSuccess == 1) { 

							} else { ?>


									<input id="email" placeholder="Адрес почты" type="text" name="email" class="text input-textarea half" value="" maxlength="30" />

									<p><?php _e( 'Введите адрес почты, куда придет пароль.', 'agrg' ); ?></p>
																	
									<input type="hidden" name="submit" value="Сбросить" id="submit" />
									<div class="clearfix"></div>
									<div class="btn-container">	
										<button class="btn form-submit" id="edit-submit" name="op" value="Publish Ad" type="submit"><?php _e('Готово', 'agrg') ?></button>
									</div>
								

							<?php } ?>

						</form>

						<div class="clearfix"></div>
					</div>
					
					<div class="span4 last">
						<span><?php _e( 'Регистрация', 'agrg' ); ?></span>
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
									<a class="register-social-button-facebook" href="<?php echo get_site_url(); ?>/wp-login.php?loginFacebook=1" onclick="window.location = '<?php echo get_site_url(); ?>/wp-login.php?loginFacebook=1&redirect='+window.location.href; return false;"><?php _e( 'Войти с помощью Facebook', 'agrg' ); ?></a>
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
									<a class="register-social-button-twitter" href="<?php echo get_site_url(); ?>/wp-login.php?loginTwitter=1" onclick="window.location = '<?php echo get_site_url(); ?>/wp-login.php?loginTwitter=1&redirect='+window.location.href; return false;"><?php _e( 'Войти с помощью Twitter', 'agrg' ); ?></a>
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
									<a class="register-social-button-google" href="<?php echo get_site_url(); ?>/wp-login.php?loginGoogle=1" onclick="window.location = '<?php echo get_site_url(); ?>/wp-login.php?loginGoogle=1&redirect='+window.location.href; return false;"><?php _e( 'Войти с помощью Google', 'agrg' ); ?></a>
								</div>
							

							<?php } ?>
							
						</div>
						
						<div class="publish-ad-button login-page">

							<?php

								global $redux_demo; 
								$register = $redux_demo['register'];
								$reset = $redux_demo['reset'];

							?>
							
							

						</div>

					</div>
				</div>

	    		</div>


	    	</div>

	    	
	    </div>

    </section>

<?php get_footer(); ?>