<?php
/**
 * Template Name: Custom search
 *
 * Description: Show a page without header/footer
 *
 *
 * @package WordPress
 * @subpackage BuddyApp
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since BuddyApp 1.0
 */

/* remove sidemenu */
remove_action( 'kleo_after_body', 'kleo_show_side_menu' );

/* remove header */
remove_action( 'kleo_header', 'kleo_show_header', 12 );


// get_header();
?>
    <?php
    // if ( have_posts() ) :
    //     // Start the Loop.
    //     while ( have_posts() ) : the_post();
    
    //      get_template_part( 'content','page' ); 
    
    //     endwhile;
    // endif;
    ?>

<?php
// get_footer();
?>


<!--Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title>Flat Search Box Responsive Widget Template | Home :: w3layouts</title>
<!-- Custom Theme files -->
<link href="<?php echo get_stylesheet_directory_uri();?>/search-templ/css/style2.css" rel="stylesheet" type="text/css" media="all"/>
<link href="<?php echo get_stylesheet_directory_uri();?>/search-templ/css/style.css" rel="stylesheet" type="text/css" media="all"/>

<!-- Custom Theme files -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<meta name="keywords" content="Flat Search Box Responsive, Login form web template, Sign up Web Templates, Flat Web Templates, Login signup Responsive web template, Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
<!--Google Fonts-->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
<!--Google Fonts-->


<!-- for login tab -->
<!--Google Fonts-->
<link href='//fonts.googleapis.com/css?family=Raleway:400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Federo' rel='stylesheet' type='text/css'>
<link rel='stylesheet' id='kleo-google-fonts-css'  href='//fonts.googleapis.com/css?family=Montserrat:400,700|Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' type='text/css' media='all' />
<!--google fonts-->
<!--remove-->
<script src="<?php echo get_stylesheet_directory_uri();?>/search-templ/js/jquery-1.11.0.min.js"></script>
<script>$(document).ready(function(c) {
	$('.close').on('click', function(c){
		$('.header').fadeOut('slow', function(c){
	  		$('.header').remove();
		});
	});	  
});
</script>
<!--remove-->
<script src="<?php echo get_stylesheet_directory_uri();?>/search-templ/js/easyResponsiveTabs.js" type="text/javascript"></script>
		    <script type="text/javascript">
			    $(document).ready(function () {
			        $('#horizontalTab').easyResponsiveTabs({
			            type: 'default', //Types: default, vertical, accordion           
			            width: 'auto', //auto or any width like 600px
			            fit: true   // 100% fit in a container
			        });
			    });
				
</script>
<!-- <script type="text/javascript">var ajaxurl = 'http://dugoodr.dev/wp-admin/admin-ajax.php';</script> -->
</head>
<body>
<div class="alex-front-page">
<!--search start here-->
<!-- <div class="search">
	<i> </i>
	<div class="s-bar">
	   <form>
		<input type="text" value="Search Template" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search Template';}">
		<input type="submit"  value="Search"/>
	  </form>
	</div>
	<p>Popular searches: <a href="#">Modern PSD template,</a> <a href="#"> Portfolio design </a></p>
</div>
 --><!--search end here-->	

    <?php
    if ( have_posts() ) :
        // Start the Loop.
        while ( have_posts() ) : the_post();
    
         get_template_part( 'content','page' ); 
    
        endwhile;
    endif;
    ?>


<!-- login form -->
<!--header start here-->
<!-- <h1>Flat Tab Forms</h1> -->
<!-- <div class="header agile">
	<div class="headder-main w3layouts">
      <div class="login agileinfo">
			<div class="sap_tabs">
				<div id="horizontalTab" style="display: block; width: 100%; margin: 0px;">
					<ul class="resp-tabs-list w3">
						<li class="resp-tab-item" aria-controls="tab_item-0" role="tab"><span>Sign In</span></li>
						<li class="resp-tab-item" id="create" aria-controls="tab_item-1" role="tab"><span>Sign Up</span></li>
						<div class="clearfix"></div>
					</ul>				  	 
					<div class="resp-tabs-container w3-agile">
						<div class="tab-1 resp-tab-content" aria-labelledby="tab_item-0">
							<div class="login-top">
								<form action="#" method="post">
									<h6>Email</h6>
									<input type="text" class="email" placeholder="Johnsmith@gmail.com" name="Johnsmith@gmail.com" required="">
									<h6>Password</h6>
									<input type="password" class="password" placeholder="Password" name="password" required="">
									<span class="checkbox1">
										<label class="checkbox"><input type="checkbox" name="" checked=""><i> </i>Remember me</label>
									</span>									
								<div class="login-bottom login-bottom1 w3ls">									
										<input type="submit" value="Sign In"/>	
										-->								
									   <!-- <h4><a href="#">Forgot Your Password?</a></h4> -->
									  <!--
									 <div class="social-btn">
									  <a href="#" class="facebook">Sign In with Facebook</a>
									 </div>
									   <h4><a href="#">Lost your password?</a></h4>
									   <div class="reg_account">
									   <a class="no_acc" href="#">Don't have an account yet?</a>
									   <a class="link_add_acc" href="/register/">Create an account </a>
									   </div>
									   

								</div>	
								</form>
							</div>
						</div>
						<div class="tab-1 resp-tab-content" aria-labelledby="tab_item-1">
						<div class="login-top wthree">
								<form action="#" method="post">
									<h6>Name</h6>
									<input type="text" placeholder="Name" name="Name" required="">
									<h6>Email</h6>
									<input type="text" class="email" placeholder="Email" name="Email">
									<h6>Password</h6>
									<input type="password" class="password" placeholder="Password" name="password">	
									<h6>Confirm Password</h6>									
									<input type="password" class="password confirm_password" placeholder="Confirm Password" name="password">	
								<div class="login-bottom">									
										<input type="submit" value="Sign Up">	
									<div class="clear"></div>
								</div>	
						  </form>
							</div>
							
						</div>							
					</div>	
				</div>
			</div>	
		</div>	
    </div>
    <div class="close">
	<img src="<?php echo get_stylesheet_directory_uri();?>/search-templ/images/cancel.png" alt="">
    </div>
</div>
 -->
<!--header end here-->



<div class="copyright">
	 <p>2015 &copy Flat Search Box All rights reserved | Template by  <a href="http://w3layouts.com/" target="_blank">  W3layouts </a></p>
</div>	

<script type="text/javascript">
/* <![CDATA[ */
var KLEO = {"ajaxurl":"http:\/\/<?php echo $_SERVER['HTTP_HOST'];?>\/wp-admin\/admin-ajax.php","loadingMessage":"<i class=\"icon-refresh animate-spin\"><\/i> Sending info, please wait...","errorMessage":"Sorry, an error occurred. Try again later.","bpAjaxRefresh":"30000"};
/* ]]> */
</script>
<script src="<?php echo get_template_directory_uri();?>/assets/js/functions.min.js"></script>
<script src="<?php echo get_template_directory_uri();?>/assets/js/plugins.js"></script>
</div>
</body>
</html>