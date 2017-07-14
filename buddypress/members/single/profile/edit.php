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

			<?php if( preg_match("#timeline#i", bp_get_the_profile_group_slug()) ) 
			{

				global $bp;
				$quest_id = $bp->displayed_user->id;

				/* select timeline data (title,content,date etc) */

				$fields = $wpdb->get_results( $wpdb->prepare(
					"SELECT ID, post_title, post_content, post_excerpt,post_name,menu_order,post_date
					FROM {$wpdb->posts}
					WHERE post_parent = %d
					    AND post_type = %s
					ORDER BY post_date DESC",
					intval( $quest_id ),
					"alex_timeline"
				) );
				// print_r($fields);
				// foreach ($fields as $field) {
				// 	echo $field->post_title;
				// }

				echo '<a id="link_edit_timeline" href="'.$user_link.'" class="button">To click for editing</a>'; 
				// echo "<h3>This section is under development</h3>";
				echo '<table id="a21_timeleline_quick_edit">
					<tr><th class="timel_title">Title</th><th>Date</th><th>Description</th><th class="qe_color">Color</th></tr>';
				$i=1; $dp=1;
				foreach ($fields as $field):
					// if($i%6==0) $dp++;
					// if($dp>1) $datepicker_id = "a21_wrap_datepicker".$dp ;
					// else $datepicker_id = "a21_wrap_datepicker";
					// echo $field->ID."==".$field->post_name."--";
					if( !empty($field->post_title) ):
					?>
					<tr class="<?php if( !empty($field->post_name)) echo $field->post_name; else echo "teal";?>">
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
					<td class="qe_color">
					    <select class="form-control" name="data[<?php echo $i;?>][timel_class]">
					        <!-- <option value="">None</option> -->
					        <option value="none" <?php if($field->post_name=="none") echo 'selected="selected"';?>>None</option>
					        <option value="bricky" <?php if($field->post_name=="bricky") echo 'selected="selected"';?>>Red</option>
					        <option value="green" <?php if($field->post_name=="green") echo 'selected="selected"';?>>Green</option>
					        <option value="purple" <?php if($field->post_name=="purple") echo 'selected="selected"';?>>Purple</option>
					        <option value="teal" <?php if($field->post_name=="teal" || empty($field->post_name)) echo 'selected="selected"';?>>Teal</option>
					        <!--<option value="teal"><?php echo $field->post_name;?></option>-->
					    </select>
					</td>	
					</tr>
					<?php
					$i++;
					endif;
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

					<?php
		if( strpos( strtolower(bp_get_the_profile_group_name()), "experience") !== false ): ?>
		<div class="editfield">
		<label>Experience</label>
		<table id="as21_experience_volunteer">
			<tr><th>Details of experience</th><th class="exper_hours">Hours</th><th></th></tr>
 			<?php $all_exper = as21_get_all_experience_from_page_edit_profile();?>
			<?php if(!empty($all_exper) ):?>
				<?php $i=0; foreach ($all_exper as $exper): ?>
	 			<tr class="a21_dinam_row">
					<td><input type="text" name="as21_experiences[<?php echo $i;?>][title]" value="<?php echo stripslashes($exper->post_title);?>"></td>
					<td><input type="text" name="as21_experiences[<?php echo $i;?>][hours]" value="<?php echo $exper->menu_order;?>"></td>
					<td><a href="#" data-id="<?php echo $exper->ID;?>" class="experience_del">x</a></td>
					<input type="hidden" name="as21_experiences[<?php echo $i;?>][exper_id]" value="<?php echo $exper->ID;?>">
				</tr>
				<?php $i++;endforeach;?>
			<?php else:?>
				<tr class="a21_dinam_row">
					<td><input type="text" name="as21_new_experiences[0][title]" placeholder="Eg. This is an example item to add"></td>
					<td><input type="text" name="as21_new_experiences[0][hours]">
					<td><a href="#" data-id="" class="experience_del">x</a></td>
				</tr>
			<?php endif;?>
		</table>
		<div id="a21_experience_add_new_row">+ Add New Row</div>
		</div>
		<?php endif;?>
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


