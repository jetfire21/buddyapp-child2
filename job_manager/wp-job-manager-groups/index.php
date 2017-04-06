<?php

function a21_get_groups_post_job_field_select( $field, $key ) {

   // echo "The field is {$field} and the key is {$key}";
   // print_r($field);
  // echo "===debug a21=== url script: a21_get_groups_frontend_selectbox ";

  $job_id = (int)$_GET['job_id'];
  if($_GET['action']=="edit" && $job_id > 0 ) $job_gr_id = get_job_field("job_group_a21",$job_id);

    global $wpdb;
    $groups = groups_get_groups();
    foreach ($groups['groups'] as $gr) {
        if($gr->id):
            $html .= '<option value="'.$gr->id.'"';
                      if($job_gr_id == $gr->id) $html .= ' selected ';
            $html .= '>'.$gr->name.'</option>';
         endif;
    }
    // print_r($groups);
   ?>
    <select class="jmfe-select-field jmfe-input-select input-select select-<?php echo $key;?>" name="<?php echo $key;?>" id="<?php echo $key;?>">
        <!--     
        <option value="Fly" selected="selected">Fly</option>
        <option value="Samsung">Samsung</option>
        -->
        <?php echo $html;?>
    </select>
<?php
}
 
add_action( 'job_manager_field_actionhook_job_group_a21', 'a21_get_groups_post_job_field_select', 10, 2 );


// hook place - /plugins/wp-job-manager/templates/content-single-job_listing.php
// add_action("single_job_listing_start","a21_get_job_data_group");
add_action("single_job_listing_meta_end","a21_details_get_job_data_group_");

function a21_details_get_job_data_group_(){

    // echo "JMFE a21_get_job_data_group ";
    $gr_id =  (int)get_job_field("job_group_a21");
    $group = groups_get_group( array( 'group_id' => $gr_id ) );
    $group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
    $avatar_options = array ( 'item_id' => $gr_id, 'object' => 'group', 'type' => 'full', 'avatar_dir' => 'group-avatars', 'alt' => 'Group avatar', 'css_id' => 1234, 'class' => 'avatar', 'html' => false );
    $gr_avatar = bp_core_fetch_avatar($avatar_options);
    $html ='<li id="alex_groups_user"><a href="'.$group_permalink.'"><img src="'.$gr_avatar.'"/></a></li>
            <li><a href="'.$group_permalink.'">'.$group->name.'</a></li>';
    global $wpdb;
    $gr_address = $wpdb->get_var("SELECT `meta_value` FROM `{$wpdb->prefix}bp_groups_groupmeta` WHERE group_id={$gr_id} AND meta_key='city_state'");
    if(!empty($gr_address)) $html .= '<li>Cause address: '.$gr_address.'</li>';

    echo $html;
}

function a21_wpjm_hide_dismiss( $current_screen ) {
  
  // if(is_admin()):
  ?>
      <script> 
       // document.getElementsByClassName('jmfe-table-alert is-dismissible').remove();
      </script>
  <?php
  // endif;
}

// add_action( 'admin_footer', 'a21_wpjm_hide_dismiss' );

/***********/


add_filter( 'all_plugins', 'hide_plugins');
function hide_plugins($plugins)
{
  if(is_plugin_active('wp-job-manager-field-editor/wp-job-manager-field-editor.php')) {
    unset( $plugins['wp-job-manager-field-editor/wp-job-manager-field-editor.php'] );
  }
  return $plugins;
}



// Update WP Job Manager Field on Save or Update (For saving value when used with Action Hook field type)
// add_action( 'job_manager_update_job_data', 'a21_get_groups_u', 100, 2 );

function a21_get_groups_u($job_id,$values){
    alex_debug(0,0,'job_id',$job_id);
    alex_debug(0,1,'values',$values);
    echo "========debug a21 a21_get_groups_u====";
    exit;

}

// add_action( 'job_manager_save_job_listing', array( $this, 'save_admin_fields' ), 100, 2 );
// add_action( 'job_manager_save_job_listing', 'a21_get_groups_up', 100, 2 );

function a21_get_groups_up($post_id,$post){
    var_dump($post_id);
    var_dump($post);
    echo "========debug a211==== a21_get_groups_up";
    exit;
}

// add_filter("job_manager_get_listings_args","a21_ttt");
function a21_ttt($args){
    alex_debug(0,1,"a",$args);
    exit("==========a21_ttt");
}

add_action( 'job_manager_job_filters_search_jobs_end', 'a21_frontend_filter_by_group_field' );
function a21_frontend_filter_by_group_field() {
    ?>
    <!-- 
    <div class="search_categories">
      <label for="search_categories"><?php _e( 'Search Salary Amounts', 'wp-job-manager' ); ?></label>
      <input type="text" class="job-manager-filter" name="filter_by_salary" placeholder="Search Salary Amounts" value="lala">
    </div>
    -->
     <?php if(!empty($_GET['id']) && $_GET['id'] > 0 ):?> 
        <!-- <input type="hidden" class="job-manager-filter" name="gr_id" value="<?php echo (int)$_GET['id'];?>"> -->
        <input type="text" style="display: none;" class="job-manager-filter" name="gr_id" value="<?php echo (int)$_GET['id'];?>">
    <?php
    endif;
}

add_filter( 'job_manager_get_listings', 'a21_filter_by_group_field_query_args', 10, 2 );
function a21_filter_by_group_field_query_args( $query_args, $args ) {

    add_filter( 'job_manager_get_listings_custom_filter', '__return_true' );

  if ( isset( $_POST['form_data'] ) ) {

      parse_str( $_POST['form_data'], $form_data );
      // alex_debug(0,1,"FORM DATA ",$form_data);

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
                // 'posts_per_page' => 5
              );
              return $args;
          }
      }
  }

  // This will show the 'reset' link
  // add_filter( 'job_manager_get_listings_custom_filter', '__return_true' );
//   add_filter( 'job_manager_get_listings_custom_filter', 'a21_f' );
//   function a21_f(){ return true;}
// echo job_manager_get_filtered_links($args);

// add_filter("job_manager_job_filters_showing_jobs_links", '__return_true');

  // alex_debug(0,1,"query args ",$query_args);
  // alex_debug(0,1,"args ",$args);
  // var_dump($gr_id);
  // alex_debug(0,1," ",$_REQUEST);
  // exit;
    
  return $query_args;
}


/********/

// add_filter("job_manager_get_listings_result","a21_d",100,2);
// function a21_d($result, $jobs){
add_filter("job_manager_get_listings_result","a21_d");
function a21_d($result){
// function a21_d($result, $jobs){

  // alex_debug(0,1," ",$jobs);
  // echo "----".$jobs->query['meta_key'];
  /*
  if($jobs->query['meta_key'] == "_job_group_a21")  {
      $result['html'] = '<div class="showing_jobs wp-job-manager-showing-all" style="display: block;">'.$result['showing_links'].'</div>'.$result['html'];
  }
  */

  // var_dump($_GET['id']);
   // echo "===debug a21=== url script: job_manager_get_listings_result===";
   //  alex_debug(0,1,"",$result);
/*
   if( $jobs->query['meta_key'] == "_job_group_a21" && !$result['found_jobs']) {
         // $result['html'] = $result['html'].$result['showing_links'];
         // $result['html'] = '<li class="no_job_listings_found">No results,so not jobs related this group'.$result['showing_links'].'</li>';
         // $result['html'] = '<div class="showing_jobs wp-job-manager-showing-all" style="display: block;"><span>No results,so not jobs related this group</span>'.$result['showing_links'].'</div>';
            $result['html'] = '';
         // message
         $result['showing'] = "No jobs related this group";
    }
*/
        if ( isset( $_POST['form_data']) && !$result['found_jobs']) {

        //all filltered fields
        parse_str( $_POST['form_data'], $form_data );
        // alex_debug(0,1,"post",$form_data);

        if ( ! empty( $form_data['gr_id'] ) ) {
           $gr_id = sanitize_text_field( $form_data['gr_id'] );
          if($gr_id > 0){
               $group = groups_get_group( array( 'group_id' => $gr_id ) );
                $result['html'] = '';
                // message
                $result['showing'] = "No jobs related this group <strong>".$group->name."</strong>";
           }
          }
        }
   // exit;
   return $result;
}

add_filter( 'job_manager_get_listings_custom_filter_text',"a21_q",100,2 );
function a21_q($message, $search_values){

    // var_dump($jobs);

      // echo "===debug a21=== url script: job_manager_get_listings_custom_filter_text===<br>";
      // var_dump($message);
      // var_dump($result);
      // alex_debug(0,1,"search_value",$search_values);


    // exit;
    // alex_debug(0,1,"request",$_REQUEST);
    // alex_debug(0,1,"post",$_POST);

    if ( isset( $_POST['form_data'] ) ) {

        //all filltered fields
        parse_str( $_POST['form_data'], $form_data );
        // alex_debug(0,1,"post",$form_data);

        if ( ! empty( $form_data['gr_id'] ) ) {
           $gr_id = sanitize_text_field( $form_data['gr_id'] );
          if($gr_id > 0){
               $group = groups_get_group( array( 'group_id' => $gr_id ) );
               $message = "Finds jobs associated with the group <strong>".$group->name."</strong>";
          }
        }
    }
    // alex_debug(0,1,"form_data",$form_data);
    // echo "form d".$_POST['form_data']."<br>";
    // $_POST['form_data'] = preg_replace("/gr_id=[^&]+/i", "a21_no_use=",$_POST['form_data']);
    // echo "rep form d".$_POST['form_data'];
    // alex_debug(0,1,"request del gr_id",$_REQUEST);

    // var_dump($message);
    // exit;

    return $message;
}