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
	<?php the_company_logo(); ?>

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
	<?php the_company_video(); ?>
</div>