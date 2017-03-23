<?php

/**
 * Fires after the display of member profile edit content.
 *
 * @since BuddyPress (1.1.0)
 */
$user = wp_get_current_user();
$user_link = bp_core_get_user_domain( $user->ID );
// alex_debug(0,1,"user",$user);

do_action( 'bp_before_profile_edit_content' );

if ( bp_has_profile( 'profile_group_id=' . bp_get_current_profile_group_id() ) ) :
	while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

<form action="<?php bp_the_profile_group_edit_form_action(); ?>" method="post" id="profile-edit-form" class="standard-form <?php bp_the_profile_group_slug(); ?>">

	<?php

		/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
		do_action( 'bp_before_profile_field_content' ); ?>

		<h4><?php printf( __( "Editing '%s' Profile Group", "buddypress" ), bp_get_the_profile_group_name() ); ?></h4>

		<?php if ( bp_profile_has_multiple_groups() ) : ?>
			<ul class="button-nav">

				<?php bp_profile_group_tabs(); ?>

			</ul>
		<?php endif ;?>

		<div class="clear"></div>

		<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>
			<?php
			?>
			<?php if( preg_match("#timeline#i", bp_get_the_profile_group_slug()) ) 
			{

				global $bp;
				$quest_id = $bp->displayed_user->id;

				/* select timeline data (title,content,date etc) */

				$fields = $wpdb->get_results( $wpdb->prepare(
					"SELECT ID, post_title, post_content, post_excerpt,post_name,menu_order
					FROM {$wpdb->posts}
					WHERE post_parent = %d
					    AND post_type = %s
					ORDER BY post_excerpt DESC",
					intval( $quest_id ),
					"alex_timeline"
				) );
				// print_r($fields);
				// foreach ($fields as $field) {
				// 	echo $field->post_title;
				// }

				echo '<a id="link_edit_timeline" href="'.$user_link.'">To click for editing</a>'; 
				// echo "<h3>This section is under development</h3>";
				echo '<table id="a21_timeleline_quick_edit">
					<tr><th class="timel_title">Title</th><th>Date</th><th>Description</th></tr>';
				$i=1; $dp=1;
				foreach ($fields as $field):
					// if($i%6==0) $dp++;
					// if($dp>1) $datepicker_id = "a21_wrap_datepicker".$dp ;
					// else $datepicker_id = "a21_wrap_datepicker";
				?>
				<tr class="<?php echo $field->post_name;?>">
				<td class="timel_title">
				<?php // echo $field->post_title;?>
				 <input type="hidden" placeholder="" name="data[<?php echo $i;?>][timel_id]" class="form-control" value="<?php echo $field->ID;?>">
				 <input type="text" placeholder="" name="data[<?php echo $i;?>][timel_title]" class="form-control" value="<?php echo stripcslashes($field->post_title);?>">
				</td>
				<td id="a21_wrap_datepicker">
					 <input data-date-orientation="left bottom" data-provide="datepicker" type="text" placeholder="" name="data[<?php echo $i;?>][timel_date]" class="form-control" required="required" data-date-format="dd M yyyy" value="<?php echo $field->post_excerpt;?>">
				</td>
				<td>
				<?php //echo $field->post_content;?>
					 <textarea placeholder="" name="data[<?php echo $i;?>][timel_content]" class="form-control"><?php echo stripcslashes($field->post_content);?></textarea>
				</td>
				</tr>
				<?php
				$i++;
				endforeach;
				// echo '<tr>
				// <td class="timel_title"><input type="text" placeholder="" class="form-control" value=""></td>
				// <td id="a21_wrap_datepicker2"> <input data-date-container="#a21_wrap_datepicker2"  data-provide="datepicker" type="text" placeholder="" class="form-control" data-date-format="dd M yyyy" value=""></td>
				// <td><textarea placeholder="" class="form-control"></textarea></td>
				// </tr>';
				echo '</table>';
				echo '<div id="a21_add_new_row_qedit_timel">Add more fields</div>';

			?>
			
			<?php
			}else { ?>

			<div<?php bp_field_css_class( 'editfield' ); ?>>

				<?php
				$field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );

				$field_type->edit_field_html();

				/**
				 * Fires before the display of visibility options for the field.
				 *
				 * @since BuddyPress (1.7.0)
				 */
				do_action( 'bp_custom_profile_edit_fields_pre_visibility' );
				?>

				<?php if ( bp_current_user_can( 'bp_xprofile_change_field_visibility' ) ) : ?>
					<p class="field-visibility-settings-toggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
						<?php printf( __( 'This field can be seen by: <span class="current-visibility-level">%s</span>', 'buddypress' ), bp_get_the_profile_field_visibility_level_label() ) ?> <a href="#" class="visibility-toggle-link"><?php _e( 'Change', 'buddypress' ); ?></a>
					</p>

					<div class="field-visibility-settings" id="field-visibility-settings-<?php bp_the_profile_field_id() ?>">
						<fieldset>
							<legend><?php _e( 'Who can see this field?', 'buddypress' ) ?></legend>

							<?php bp_profile_visibility_radio_buttons() ?>

						</fieldset>
						<a class="field-visibility-settings-close" href="#"><?php _e( 'Close', 'buddypress' ) ?></a>
					</div>
				<?php else : ?>
					<div class="field-visibility-settings-notoggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
						<?php printf( __( 'This field can be seen by: <span class="current-visibility-level">%s</span>', 'buddypress' ), bp_get_the_profile_field_visibility_level_label() ) ?>
					</div>
				<?php endif ?>

				<?php

				/**
				 * Fires after the visibility options for a field.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_custom_profile_edit_fields' ); ?>

				<p class="description"><?php bp_the_profile_field_description(); ?></p>
			</div>
			<?php } ?>
		<?php endwhile; ?>

	<?php

	/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
	do_action( 'bp_after_profile_field_content' ); ?>

	<?php // if( !preg_match("#timeline#i", bp_get_the_profile_group_slug()) ): ?>
	<div class="submit">
		<input type="submit" name="profile-group-edit-submit" id="profile-group-edit-submit" value="<?php esc_attr_e( 'Save Changes', 'buddypress' ); ?> " />
	</div>
	<?php // endif;?>

	<input type="hidden" name="field_ids" id="field_ids" value="<?php bp_the_profile_field_ids(); ?>" />

	<?php wp_nonce_field( 'bp_xprofile_edit' ); ?>

</form>

<?php endwhile; endif; ?>

<?php

/**
 * Fires after the display of member profile edit content.
 *
 * @since BuddyPress (1.1.0)
 */
do_action( 'bp_after_profile_edit_content' ); ?>


