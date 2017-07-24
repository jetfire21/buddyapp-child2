<?php
/**
 * Review form
 */
defined( 'ABSPATH' ) || exit; ?>
<?php
    $reviews = new WP_Query(array(
        'post_type'   => 'bp-user-reviews',
        'post_status' => 'publish',
        'posts_per_page'  => 10,
        'paged' => (isset($_GET['page'])) ? max(1, intval($_GET['page'])) : 1,
        'meta_query' => array(
            array(
                'key'     => 'user_id',
                'value'   => bp_displayed_user_id()
            )
        )
    ));
?>
<h2><?php _e('Reviews', 'bp-user-reviews'); ?></h2>
<div class="bp-users-reviews-list">
    <?php
    global $post;
    if($reviews->have_posts()){
        while($reviews->have_posts()){
            $reviews->the_post();
            ?>
        <div class="review">
            <div class="author">
                <?php $this->author(); ?>
                <br>
                <?php the_time($this->settings['date_format']); ?>
            </div>
            <div class="details">
                <?php if($post->type == 'single'){ ?>
                    <div class="rating">
                        <div class="text">
                            <div class="stars">
                                <?php echo $this->print_stars($post->stars); ?>
                                <div class="active" style="width:<?php echo $post->average; ?>%">
                                    <?php echo $this->print_stars($post->stars); ?>
                                </div>
                            </div>
                        </div>
                        <span class="text">&nbsp;</span>
                    </div>
                <?php } ?>
                <?php if($post->type == 'multiple'){ ?>
                    <?php foreach($post->criterions as $name => $value){
		                // show the correct translated strings
		                $name = apply_filters( 'wpml_translate_single_string', $name, 'User Reviews', $name );
                        ?>
                        <div class="rating">
                            <span class="text"><?php echo $name; ?></span>
                            <div class="text">
                            <div class="stars">
                                <?php echo $this->print_stars($post->stars); ?>
                                <div class="active" style="width:<?php echo $value; ?>%">
                                    <?php echo $this->print_stars($post->stars); ?>
                                </div>
                            </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <div class="text">
                    <?php echo nl2br($post->review); ?>
                </div>
            </div>
        </div>
    <?php }

    } else {
        echo '<p>'.__('No reviews yet', 'bp-user-reviews').'</p>';
    }  ?>
</div>
<?php
echo '<div class="bp-users-reviews-pager">';
echo paginate_links( array(
	'base' => str_replace( 999999999, '%#%', '?page=999999999' ),
	'format' => '?page=%#%',
	'current' => max( 1, get_query_var('page') ),
	'total' => $reviews->max_num_pages
) );
echo '</div>';