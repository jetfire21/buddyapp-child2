<?php

function a21_get_groups_post_job_field_select( $field, $key ) {

  $job_id = (int)$_GET['job_id'];
  if($_GET['action']=="edit" && $job_id > 0 ) $job_gr_id = get_job_field("job_group_a21",$job_id);

    global $wpdb;
    $groups = groups_get_groups();
    $html .= '<option value="None">None</option>';
    foreach ($groups['groups'] as $gr) {
        if($gr->id):
            $html .= '<option value="'.$gr->id.'"';
                      if($job_gr_id == $gr->id) $html .= ' selected ';
            $html .= '>'.$gr->name.'</option>';
         endif;
    }
   ?>
    <select class="jmfe-select-field jmfe-input-select input-select select-<?php echo $key;?>" name="<?php echo $key;?>" id="<?php echo $key;?>">
        <?php echo $html;?>
    </select>
<?php
}
 
add_action( 'job_manager_field_actionhook_job_group_a21', 'a21_get_groups_post_job_field_select', 10, 2 );


// hook place - /plugins/wp-job-manager/templates/content-single-job_listing.php
// add_action("single_job_listing_start","a21_get_job_data_group");
add_action("single_job_listing_meta_end","a21_details_get_job_data_group");


add_filter( 'all_plugins', 'hide_plugins');
function hide_plugins($plugins)
{
  if(is_plugin_active('wp-job-manager-field-editor/wp-job-manager-field-editor.php')) {
    unset( $plugins['wp-job-manager-field-editor/wp-job-manager-field-editor.php'] );
  }
  return $plugins;
}



// add_action( 'job_manager_save_job_listing', array( $this, 'save_admin_fields' ), 100, 2 );

add_action( 'job_manager_job_filters_search_jobs_end', 'a21_frontend_filter_by_group_field' );
function a21_frontend_filter_by_group_field() {
    ?>

     <?php if(!empty($_GET['id']) && $_GET['id'] > 0 ):?> 
        <input type="text" style="display: none;" class="job-manager-filter" name="gr_id" value="<?php echo (int)$_GET['id'];?>">
    <?php
    endif;
}

add_filter( 'job_manager_get_listings', 'a21_filter_by_group_field_query_args', 10, 2 );
function a21_filter_by_group_field_query_args( $query_args, $args ) {

    add_filter( 'job_manager_get_listings_custom_filter', '__return_true' );

  if ( isset( $_POST['form_data'] ) ) {

      parse_str( $_POST['form_data'], $form_data );

      // If this is set, we are filtering by salary
      if ( ! empty( $form_data['gr_id'] ) ) {
           $gr_id = sanitize_text_field( $form_data['gr_id'] );
          if($gr_id > 0){
            $args = array(
                'meta_key' => '_job_group_a21',
                'meta_value' => $gr_id,
                'meta_type' => "NUMERIC",
                'meta_compare' => "=",
                'post_type' => 'job_listing'
              );
              return $args;
          }
      }
  }    
  return $query_args;
}

add_action("job_manager_job_submitted","a21_add_event_job_post_in_group_stream");
function a21_add_event_job_post_in_group_stream($job_id){

   global $wpdb;
   $get_job = $wpdb->get_results( $wpdb->prepare(
    "SELECT post_author,guid,post_title
    FROM {$wpdb->posts}
    WHERE ID = %d AND post_type = %s
    ",
    intval( $job_id ),
    "job_listing"
  ) );
      
   // if job post added
  if( !empty($get_job[0]->post_author) ) {

      global $bp;

      $city = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key='_job_location' AND post_id='".(int)$job_id."'");
      $gr_id = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key='_job_group_a21' AND post_id='".(int)$job_id."'");
      $gr_root_slug = $bp->groups->root_slug;
      $group = groups_get_group( array( 'group_id' => $gr_id ) );
      $gr_link = "<a href='http://".$_SERVER['HTTP_HOST']."/{$gr_root_slug}/{$group->slug}/'>{$group->name}</a>";

      $action = "just added the amazing <a href='".$get_job[0]->guid."'>".$get_job[0]->post_title."</a> opportunity in {$city} for the cause ".$gr_link;
      
      // type new_job to no break tweet count in group stream in ajax method
      $args = array( "action"=>$action, "component" => "groups", "type" => "new_job", 'item_id' => (int)$gr_id, 'secondary_item_id' => (int)$job_id);
      $activity_id = bp_activity_add( $args );
  }

}

/********************************/

// add_filter("job_manager_get_listings_result","a21_d",100,2);
add_filter("job_manager_get_listings_result","as21_jm_get_current_group");
function as21_jm_get_current_group($result){

        if ( isset( $_POST['form_data']) && !$result['found_jobs']) {

        //all filltered fields
        parse_str( $_POST['form_data'], $form_data );

        if ( ! empty( $form_data['gr_id'] ) ) {
           $gr_id = sanitize_text_field( $form_data['gr_id'] );
          if($gr_id > 0){
               $group = groups_get_group( array( 'group_id' => $gr_id ) );
                $result['html'] = '';
                $result['showing'] = "No jobs related this group <strong>".$group->name."</strong>";
           }
          }
        }
   return $result;
}

add_filter( 'job_manager_get_listings_custom_filter_text',"as21_jm_output_current_group",100,2 );
function as21_jm_output_current_group($message, $search_values){

    if ( isset( $_POST['form_data'] ) ) {

        //all filltered fields
        parse_str( $_POST['form_data'], $form_data );

        if ( ! empty( $form_data['gr_id'] ) ) {
           $gr_id = sanitize_text_field( $form_data['gr_id'] );
          if($gr_id > 0){
               $group = groups_get_group( array( 'group_id' => $gr_id ) );
               $message = "Finds jobs associated with the group <strong>".$group->name."</strong>";
          }
        }
    }

    return $message;
}


/* *****addtiton 'Posted Date' field on page post-a-job****** */

// for work need in wp dashboard to add new field 'as21_job_posted_date' as date-picker
function a21_register_session_for_job_posted_date(){
    if( !session_id() ) session_start();
}
add_action('init','a21_register_session_for_job_posted_date');

// add_filter( 'submit_job_form_save_job_data', $job_data, $post_title, $post_content, $status, $values );
add_filter( 'submit_job_form_save_job_data', 'as21_jm_save_posted_date',5 );
function as21_jm_save_posted_date($job_data, $post_title, $post_content, $status, $values){


  if( !empty($_POST['job_as21_expired_date']) ){
    $expire_date = sanitize_text_field($_POST['job_as21_expired_date']);
    $_SESSION['job_as21_expired_date'] = date("Y-m-d",strtotime($expire_date));
  }
  return $job_data;
}

add_action('job_manager_job_submitted','as21_jm_update_posted_date');
function as21_jm_update_posted_date($job_id){


  if( !empty($_SESSION['job_as21_expired_date']) ) {
      global $wpdb;
      $wpdb->update(
        $wpdb->postmeta,
        array( 'meta_value' => $_SESSION['job_as21_expired_date']),
        array( 'post_id' => (int)$job_id, 'meta_key' => '_job_expires'),
        array('%s'),
        array('%d','%s')
      );
      unset($_SESSION['job_as21_expired_date']);
  }

   as21_wjm_write_file_all_groups(true);

}

/* *****addtiton 'Posted Date' field on page post-a-job****** */

add_action( 'groups_group_create_complete',"as21_jm_write_file_after_group_create" );
function as21_jm_write_file_after_group_create(){
   as21_wjm_write_file_all_groups(true);
}

add_action('submit_job_form_end','as21_wjm_actions_after_submit_form');
function as21_wjm_actions_after_submit_form(){

     if( !empty($_POST['job_as21_expired_date']) ) {
        $expire_date = sanitize_text_field($_POST['job_as21_expired_date']);
        $expire_date = date("Y-m-d",strtotime($expire_date));

        global $wpdb;
        $wpdb->update(
          $wpdb->postmeta,
          array( 'meta_value' => $expire_date),
          array( 'post_id' => (int)$_POST['job_id'], 'meta_key' => '_job_expires'),
          array('%s'),
          array('%d','%s')
        );
    }

    if( $_POST['job_manager_form'] == 'edit-job' )   {
       as21_wjm_write_file_all_groups(true); 
   }
   
}

