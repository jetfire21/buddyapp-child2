<?php
/**
 * Single view Company information box
 *
 * Hooked into single_job_listing_start priority 30
 *
 * @since  1.14.0
 */

if ( ! get_the_company_name() ) {
	return;
}
?>
<div class="company" itemscope itemtype="http://data-vocabulary.org/Organization">
	<div class="main-logo"><?php the_company_logo('full'); ?></div>

	<div class="name">
		<?php the_company_twitter(); ?>
		<?php the_company_name( '<h2 itemprop="name">', '</h2>' ); ?>
	</div>
	<?php the_company_tagline( '<p class="tagline">', '</p>' ); ?>
	<?php
	 // the_job_location();
     $gr_id =  (int)get_job_field("job_group_a21");
    if( !empty($gr_id) && $gr_id > 0 ){
    	global $wpdb;
		 $gr_address = $wpdb->get_var("SELECT `meta_value` FROM `{$wpdb->prefix}bp_groups_groupmeta` WHERE group_id={$gr_id} AND meta_key='city_state'");
    	if(!empty($gr_address)) echo "<p>".esc_html($gr_address)."</p>";
    }
    // deb_last_query();
 ?>
	<?php if ( $website = get_the_company_website() ) : ?>
		<a class="website" href="<?php echo esc_url( $website ); ?>" itemprop="url" target="_blank" rel="nofollow"><?php echo esc_url( $website ); ?></a>
	<?php endif; ?>
	<?php if( !empty(get_job_field("job_available")) ) echo "<div>Volunteer #Gigs: ".get_job_field("job_available")."</div>";

	function as21_wjm_wjmfe_details_get_job_data_group(){

	    // echo "JMFE a21_get_job_data_group ";
	    $gr_id =  (int)get_job_field("job_group_a21");
	    $group = groups_get_group( array( 'group_id' => $gr_id ) );
	    $group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
	    $avatar_options = array ( 'item_id' => $gr_id, 'object' => 'group', 'type' => 'full', 'avatar_dir' => 'group-avatars', 'alt' => 'Group avatar', 'css_id' => 1234, 'class' => 'avatar', 'html' => false );
	    $gr_avatar = bp_core_fetch_avatar($avatar_options);
	    if(!empty($group->name)){
	       $html ='<ul class="as21-company-custom-field"><li class="group-logo-left-text">Visit DuGoodr Cause:</li><li id="alex_groups_user"><a class="group-logo" href="'.$group_permalink.'"><img src="'.$gr_avatar.'"/></a> <a href="'.$group_permalink.'">'.$group->name.'</a></li></ul>';
	      // global $wpdb;
	      // $gr_address = $wpdb->get_var("SELECT `meta_value` FROM `{$wpdb->prefix}bp_groups_groupmeta` WHERE group_id={$gr_id} AND meta_key='city_state'");
	      // if(!empty($gr_address)) $html .= '<li>'.esc_html($gr_address).'</li>';
	      echo $html;
	  }
	}

	as21_wjm_wjmfe_details_get_job_data_group();
	 ?>


	<?php the_company_video(); ?>
</div>
<script>
jQuery( document ).ready(function($) {
	if( jQuery(".company").width() > 480 ){
		var content_height = jQuery(".company").height() - 20;
		var logo_height = jQuery('.company .company_logo').height()
		console.log('content_height: '+content_height);
		console.log( 'height logo: '+logo_height );
		if( logo_height > content_height ) jQuery(".company .main-logo").css({"height":logo_height});
		else jQuery(".company .main-logo").css({"height":content_height});
	}
});
</script>