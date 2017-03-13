<?php
/**
 * Template Name: TEST
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
<!-- <!DOCTYPE HTML>
<html>
<head>
<title>Flat Search Box Responsive Widget Template | Home :: w3layouts</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" media="all"/>
<link href="http://dugoodr.dev/wp-content/themes/buddyapp-child/libs/jqtimeliner/css/jquery-timeliner.css" rel="stylesheet" type="text/css" media="all"/>
<script src="<?php echo get_stylesheet_directory_uri();?>/search-templ/js/jquery-1.11.0.min.js"></script>
</head>
<body> -->

  <?php get_header();?>
<h3>timeline===</h3>
<div id="timeliner">
  <ul class="columns">
      <li>
          <div class="timeliner_element teal">
              <div class="timeliner_title">
                  <span class="timeliner_label">Event Title</span><span class="timeliner_date">03 Nov 2014</span>
              </div>
              <div class="content">
                  <b>Lorem Ipsum</b> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
              </div>
              <div class="readmore">
                  <a class="btn btn-primary" href="javascript:void(0);" ><i class="fa fa-pencil fa fa-white"></i></a>
                  <a class="btn btn-bricky" href="javascript:void(0);" ><i class="fa fa-trash fa fa-white"></i></a>
                  <a href="#" class="btn btn-info">
                      Read More <i class="fa fa-arrow-circle-right"></i>
                  </a>
              </div>
          </div>
      </li>
      <li>
          <div class="timeliner_element green">
              <div class="timeliner_title">
                  <span class="timeliner_label">Event Title</span><span class="timeliner_date">11 Nov 2014</span>
              </div>
              <div class="content">
                  <b>Lorem Ipsum</b> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
              </div>
              <div class="readmore">
                  <a class="btn btn-primary" href="javascript:void(0);" ><i class="fa fa-pencil fa fa-white"></i></a>
                  <a class="btn btn-bricky" href="javascript:void(0);" ><i class="fa fa-trash fa fa-white"></i></a>
                  <a href="#" class="btn btn-info">
                      Read More <i class="fa fa-arrow-circle-right"></i>
                  </a>
              </div>
          </div>
      </li>
  </ul>
</div>

<!-- <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="http://dugoodr.dev/wp-content/themes/buddyapp-child/libs/jqtimeliner/js/jquery-timeliner.js"></script>	
<script type="text/javascript">
	jQuery( document ).ready(function($) {
	    var tl = $('#timeliner').timeliner();
	});
</script>
 -->
 <?php get_footer();?>
</body>
</html>