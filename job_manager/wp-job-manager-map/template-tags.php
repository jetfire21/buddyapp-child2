<?php

/**
 * The company featured image
 *
 * @since 3.0.0
 *
 * @param object $post
 * @return string $image
 */
function jobify_get_the_featured_image( $size = 'content-job-featured', $post = false ) {
	if ( ! $post ) {
		$post = get_post();
	}

	$image = $post->_featured_image;

	if ( ! $image ) {
		return;
	}

	$image = attachment_url_to_postid( $image );

	if ( $image ) {
		return wp_get_attachment_image( $image, $size, false );
	}

	return false;
}

/**
 * The Company Description template tag.
 *
 * @since Jobify 1.0
 *
 * @return void
 */
function jobify_the_company_description( $before = '', $after = '' ) {
    $company_description = jobify_get_the_company_description();

    if ( strlen( $company_description ) == 0 ) {
        return;
    }

    $company_description = wp_kses_post( $company_description );
    $company_description = $before . wpautop( $company_description ) . $after;

    echo $company_description;
}

/**
 * Get the company description.
 *
 * @since Jobify 1.0
 *
 * @return void
 */
function jobify_get_the_company_description( $post = 0 ) {
    $post = get_post( $post );

    if ( $post->post_type !== 'job_listing' ) {
        return;
    }

    return apply_filters( 'the_company_description', $post->_company_description, $post );
}

/**
 * Get the Company Twitter
 *
 * @since 3.0.0
 *
 * @return string $company_twitter
 */
function jobify_get_the_company_twitter( $post = 0 ) {
    $post = get_post( $post );

    if ( $post->post_type !== 'job_listing' ) {
        return;
    }

    $company_twitter = $post->_company_twitter;

    if ( strlen( $company_twitter ) == 0 ) {
        return;
    }

	if ( filter_var( $company_twitter, FILTER_VALIDATE_URL ) === false ) {
		$company_twitter = 'http://twitter.com/' . $company_twitter;
	}

    return apply_filters( 'the_company_twitter', $company_twitter, $post );
}

/**
 * Get the Company Facebook
 *
 * @since Jobify 1.0
 *
 * @return void
 */
function jobify_get_the_company_facebook( $post = 0 ) {
    $post = get_post( $post );

    if ( $post->post_type !== 'job_listing' ) {
        return;
    }

    $company_facebook = $post->_company_facebook;

    if ( strlen( $company_facebook ) == 0 ) {
        return;
    }

	if ( filter_var( $company_facebook, FILTER_VALIDATE_URL ) === false ) {
		$company_facebook = 'http://facebook.com/' . $company_facebook;
	}

    return apply_filters( 'the_company_facebook', $company_facebook, $post );
}

/**
 * Get the Company Google Plus
 *
 * @since Jobify 1.0
 *
 * @return void
 */
function jobify_get_the_company_gplus( $post = 0 ) {
    $post = get_post( $post );

    if ( $post->post_type !== 'job_listing' ) {
        return;
    }

    $company_google = $post->_company_google;

    if ( strlen( $company_google ) == 0 ) {
        return;
    }

	if ( filter_var( $company_google, FILTER_VALIDATE_URL ) === false ) {
		$company_google = 'http://plus.google.com/' . $company_google;
	}

    return apply_filters( 'the_company_google', $company_google, $post );
}

/**
 * Get the Company LinkedIn
 *
 * @since Jobify 1.6.0
 *
 * @return void
 */
function jobify_get_the_company_linkedin( $post = 0 ) {
    $post = get_post( $post );

    if ( $post->post_type !== 'job_listing' ) {
        return;
    }

    $company_linkedin = $post->_company_linkedin;

    if ( strlen( $company_linkedin ) == 0 ) {
        return;
    }

	if ( filter_var( $company_linkedin, FILTER_VALIDATE_URL ) === false ) {
		$company_linkedin = 'http://linkedin.com/company/' . $company_linkedin;
	}

    return apply_filters( 'the_company_linkedin', $company_linkedin, $post );
}

function jobify_array_filter_deep( $item ) {
    if ( is_array( $item ) ) {
        return array_filter( $item, 'jobify_array_filter_deep' );
    }
    if ( ! empty( $item ) ) {
        return true;
    }
}
