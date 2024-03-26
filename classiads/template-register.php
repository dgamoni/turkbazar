<?php
/**
 * Template name: Register Page
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

global $user_ID, $user_identity, $user_level, $registerSuccess;

$registerSuccess = "";


if (!$user_ID) {

	if($_POST) 

	{

		$message = "Registration successful.";

		$username = $wpdb->escape($_POST['username']);

		$email = $wpdb->escape($_POST['email']);

		$password = $wpdb->escape($_POST['pwd']);

		$confirm_password = $wpdb->escape($_POST['confirm']);

		$registerSuccess = 1;

		$status = wp_create_user( $username, $password, $email );			
			if(empty($username)) {
				$message =  _e( 'User name should not be empty', 'agrg' );
				$registerSuccess = 0;
			}
			elseif(isset($password) || isset($email)) {						
				if (strlen($password) < 5 || strlen($password) > 15) {

				$message = _e( 'Password must be 5 to 15 characters in length.', 'agrg' );

				$registerSuccess = 0;

				}

				//elseif( $password == $confirm_password ) {

				elseif(isset($password) && $password != $confirm_password) {

					$message = _e( 'Password Mismatch', 'agrg' );

					$registerSuccess = 0;

				}elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
				  {
				  $message =  _e( 'Please enter a valid email.', 'agrg' );
				  $registerSuccess = 0;				 
				  }	
		  
			}elseif(isset($email)) {
				if(!filter_var($email, FILTER_VALIDATE_EMAIL))
				  {
				  $message = _e( 'Please enter a valid email.', 'agrg' );
				  $registerSuccess = 0;				 
				  }	
			}elseif ( is_wp_error($status) ) {
				$registerSuccess = 0;
				$message =  _e( 'Username or E-mail already exists. Please try another one.', 'agrg' );
			}
			 else {
				if($registerSuccess = 1){
				$from = get_option('admin_email');
				$headers = 'From: '.$from . "\r\n";
				$subject =  _e( 'Registration successful', 'agrg' );
				$msg = "Registration successful.\nYour login details\nUsername: $username\nPassword: $password";
				wp_mail( $email, $subject, $msg, $headers );
				
				$registerSuccess = 1;
				}
			}


		if($registerSuccess == 1) {

			$login_data = array();
			$login_data['user_login'] = $username;
			$login_data['user_password'] = $password;
			$user_verify = wp_signon( $login_data, false ); 

			global $redux_demo; 
			$profile = $redux_demo['profile'];
			wp_redirect( $profile ); exit;

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
				<a class="logo" href="<?php echo home_url(); ?>" title="Главная">
					<?php global $redux_demo; $logo = $redux_demo['logo']['url']; if (!empty($logo)) { ?>
						<img src="<?php echo $logo; ?>" alt="Logo" />
					<?php } else { ?>
						<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
					<?php } ?>
				</a>				
			</div>
	    	<div class="first clearfix log-in">

				<h2 class="login-title"><?php _e( 'РЕГИСТРАЦИЯ', 'agrg' ); ?></h2> 
				<?php 					
					if(get_option('users_can_register')) { //Check whether user registration is enabled by the administrator
				?>

				<div id="edit-profile" class="clearfix" >
						
					<div class="span4">
						<form class="form-item login-form" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">

								<?php if($_POST) { 

									global $redux_demo; 
									$login = $redux_demo['login'];

									echo "<div id='result' style='margin-bottom: 30px;'><div class='message'><h4>".$message." ";

									if($registerSuccess == 1) {
										echo "<a href='".$login."'>Войти</a>.";
									}

									echo "</h4></div></div>";

								} ?>

									<?php if($registerSuccess == 1) { } else { ?>

									

										
									<input id="contactName" placeholder="<?php _e( 'Имя Пользователя', 'agrg' ); ?>" type="text" name="username" class="text input-textarea half" value="" maxlength="30" />

									

									

										
										<input id="email" placeholder="<?php _e( 'Адрес почты', 'agrg' ); ?>" type="text" name="email" class="text input-textarea half" value=""  maxlength="30" />

									

				
										<input id="password" placeholder="<?php _e( 'Пароль', 'agrg' ); ?>" type="password" name="pwd" class="text input-textarea half" maxlength="15"  value="" />

	
										<input id="password" placeholder="<?php _e( 'Введите пароль еще раз', 'agrg' ); ?>" type="password" name="confirm" class="text input-textarea half" maxlength="15" value="" />

							

									<br/>

									
										<input type="hidden" name="submit" value="Регистрация" id="submit" />
										<div class="clearfix"></div>
										<div class="btn-container">	
											<button class="btn form-submit" id="edit-submit" name="op" value="Publish Ad" type="submit"><?php _e('Готово!', 'agrg') ?></button>
										</div>
									

								<?php } ?>

						</form>


						<div class="clearfix"></div>
					</div>
					<!-- <div id="fixmobil23"></div> -->
					<div  class="span4 last">
					<span><?php _e( 'У вас уже есть аккаунт ?', 'agrg' ); ?></span> <a class="login-a" href=""><?php _e( 'Войти сейчас', 'agrg' ); ?></a>
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
							//if ( is_plugin_active( "nextend-twitter-connect/nextend-twitter-connect.php" ) ) {
							  //plugin is activated
							
							?>

							
								<div class="social-btn-container">
									<a class="register-social-button-twitter" href="<?php echo get_site_url(); ?>/wp-login.php?loginTwitter=1" onclick="window.location = '<?php echo get_site_url(); ?>/wp-login.php?action=wordpress_social_authenticate&mode=login&provider=Twitter&redirect_to='+window.location.href; return false;"><?php _e('Войти с помощью Twitter', 'agrg') ?></a>
								</div>
							

							<?php //} ?>

							<?php
							/**
							 * Detect plugin. For use on Front End only.
							 */
							include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

							// check for plugin using plugin name
							//if ( is_plugin_active( "nextend-google-connect/nextend-google-connect.php" ) ) {
							  //plugin is activated
							
							?>

							
								<div class="social-btn-container">
									<a class="register-social-button-google" href="<?php echo get_site_url(); ?>/wp-login.php?loginGoogle=1" onclick="window.location = '<?php echo get_site_url(); ?>/wp-login.php?action=wordpress_social_authenticate&mode=login&provider=Google&redirect='+window.location.href; return false;"><?php _e('Войти с помощью Google', 'agrg') ?></a>
								</div>
							

							<?php //} ?>
							
						</div>

						<div class="publish-ad-button login-page">

							<?php

								global $redux_demo; 
								$login = $redux_demo['login'];
								$reset = $redux_demo['reset'];

							?>
							
							

						</div>

					</div>

	    		</div>

	    		<?php }
						
					else echo "<span class='registration-closed'>Регистрация в данный момент отключена. Попробуйте пожалуйста позже.</span>";

				?>

	    	</div>
			


	    	

	    </div>

    </section>

<?php get_footer(); ?>