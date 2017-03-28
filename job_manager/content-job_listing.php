<?php global $post; ?>

 <li id="job_listing-<?php the_ID(); ?>" <?php job_listing_class(); ?> <?php echo apply_filters( 'jobify_listing_data', '' ); ?>>
	<a href="<?php the_job_permalink(); ?>" class="job_listing-clickbox"></a>

	<div class="job_listing-logo">
		<?php the_company_logo( 'fullsize' ); ?>
	</div><div class="job_listing-about">

		<div class="job_listing-position job_listing__column">
			<h3 class="job_listing-title"><?php the_title(); ?></h3>

			<div class="job_listing-company">
				<?php the_company_name( '<strong>', '</strong> ' ); ?>
				<?php the_company_tagline( '<span class="job_listing-company-tagline">', '</span>' ); ?>
			</div>
		</div>

		<div class="job_listing-location job_listing__column">
			<?php the_job_location( false ); ?>
		</div>

		<ul class="job_listing-meta job_listing__column">
			<?php do_action( 'job_listing_meta_start' ); ?>

			<li class="job_listing-type job-type <?php echo get_the_job_type() ? sanitize_title( get_the_job_type()->slug ) : ''; ?>"><?php the_job_type(); ?></li>
			<li class="job_listing-date"><date><?php printf( __( '%s ago', 'wp-job-manager' ), human_time_diff( get_post_time( 'U' ), current_time( 'timestamp' ) ) ); ?></date></li>

			<?php do_action( 'job_listing_meta_end' ); ?>
		</ul>

	</div>
</li>


