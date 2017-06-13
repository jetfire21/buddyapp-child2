<?php

function a21_get_groups_post_job_field_select( $field, $key ) {

   // echo "The field is  and the key is ";
   // print_r($field);
  // echo "===debug a21=== url script: a21_get_groups_frontend_selectbox ";

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
    if(!empty($group->name)){
       $html ='<li class="group-logo-left-text">Visit DuGoodr Cause:</li><li id="alex_groups_user"><a class="group-logo" href="'.$group_permalink.'"><img src="'.$gr_avatar.'"/></a> <a href="'.$group_permalink.'">'.$group->name.'</a></li>';
      // global $wpdb;
      // $gr_address = $wpdb->get_var("SELECT `meta_value` FROM `{$wpdb->prefix}bp_groups_groupmeta` WHERE group_id={$gr_id} AND meta_key='city_state'");
      // if(!empty($gr_address)) $html .= '<li>'.esc_html($gr_address).'</li>';
      echo $html;
  }
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

add_action("job_manager_job_submitted","a21_add_event_job_post_in_group_stream");
function a21_add_event_job_post_in_group_stream($job_id){

   // echo "===debug a21=== url script: a21_qqq ".$job_id;

   global $wpdb;
   $get_job = $wpdb->get_results( $wpdb->prepare(
    "SELECT post_author,guid,post_title
    FROM {$wpdb->posts}
    WHERE ID = %d AND post_type = %s
    ",
    intval( $job_id ),
    "job_listing"
  ) );
      
   // deb_last_query();
   // alex_debug(0,1,"",$get_job);

   // if job post added
  if( !empty($get_job[0]->post_author) ) {

      // $action = "{person_name_link} just added the amazing {job_title_and_link} opportunity in {city} for the cause {insert_cause_logo_title_link}";

      global $bp;

      $city = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key='_job_location' AND post_id='".(int)$job_id."'");
      $gr_id = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key='_job_group_a21' AND post_id='".(int)$job_id."'");
      // $gr_name = $wpdb->get_results( "SELECT name,slug FROM {$wpdb->prefix}bp_groups WHERE id='{$gr_id}' ");
      $gr_root_slug = $bp->groups->root_slug;
      $group = groups_get_group( array( 'group_id' => $gr_id ) );
      $gr_link = "<a href='http://".$_SERVER['HTTP_HOST']."/{$gr_root_slug}/{$group->slug}/'>{$group->name}</a>";

      $action = "just added the amazing <a href='".$get_job[0]->guid."'>".$get_job[0]->post_title."</a> opportunity in {$city} for the cause ".$gr_link;
      // $args = array( "action"=>$action, "component" => "groups", "type" => "new_event", 'item_id' => (int)$gr_id, 'secondary_item_id' => (int)$job_id);
      
      // type new_job to no break tweet count in group stream in ajax method
      $args = array( "action"=>$action, "component" => "groups", "type" => "new_job", 'item_id' => (int)$gr_id, 'secondary_item_id' => (int)$job_id);
      $activity_id = bp_activity_add( $args );
      // deb_last_query();
  }

   // exit;
}

/********************************/

// add_filter("job_manager_get_listings_result","a21_d",100,2);
// function a21_d($result, $jobs){
add_filter("job_manager_get_listings_result","as21_jm_get_current_group");
function as21_jm_get_current_group($result){
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

add_filter( 'job_manager_get_listings_custom_filter_text',"as21_jm_output_current_group",100,2 );
function as21_jm_output_current_group($message, $search_values){

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


/* *****addtiton 'Posted Date' field on page post-a-job****** */

// for work need in wp dashboard to add new field 'as21_job_posted_date' as date-picker
function a21_register_session_for_job_posted_date(){
    if( !session_id() )session_start();
}
add_action('init','a21_register_session_for_job_posted_date');

// add_filter( 'submit_job_form_save_job_data', $job_data, $post_title, $post_content, $status, $values );
add_filter( 'submit_job_form_save_job_data', 'as21_jm_save_posted_date',5 );
function as21_jm_save_posted_date($job_data, $post_title, $post_content, $status, $values){


  // global $as21_job_data, $job_preview, $post;
  // global $job_manager;
  // echo "<hr>";
  // $as21_job_data = 'eeeeeeee';
  // alex_debug(0,1,'$_SESSION',$_SESSION);


  // echo '----function as21_job1-----<hr>';
  // alex_debug(1,1,'POST',$_POST);

  /* **** as21 new !!!!!!!!! **** все работает,но в этом месте dcp не успевает обновиться! */
  // if($_POST['job_group_a21']>0) { /* echo 'new job for some group,count +1'; */ as21_wjm_write_file_all_groups(true); }

  // alex_debug(1,1,'job_data',$job_data);
  // deb_last_query();
  // alex_debug(1,1,'post submit_job_form_save_job_data',$_POST);

  // alex_debug(1,1,'post',$job_data);
  // $job_data['post_date'] = '2017-06-21 00:00:00';
  if( !empty($_POST['job_as21_expired_date']) ){
    // $job_data['post_date'] = sanitize_text_field($_POST['job_as21_expired_date']);
    // $job_data['post_date'] = date("Y-m-d",strtotime($job_data['post_date']));
    // $expire_date = date("Y-m-d",strtotime($expire_date));
    // ." ".date("H:i:s");
    // echo 'post date='.$job_data['post_date'];
    $expire_date = sanitize_text_field($_POST['job_as21_expired_date']);
    $_SESSION['job_as21_expired_date'] = date("Y-m-d",strtotime($expire_date));
  }
  // alex_debug(0,1,'$_SESSION',$_SESSION);

  // alex_debug(0,1,'job_data',$job_data);
  // var_dump($values);
  // var_dump($status);
  // exit;
  return $job_data;
}

add_action('job_manager_job_submitted','as21_jm_update_posted_date');
function as21_jm_update_posted_date($job_id){

  // echo '<h3>----as21_jm_update_posted_date-----</h3><hr>';

  // global $as21_job_data, $job_preview, $post;
  // global $job_manager;
  // echo "<hr>";
  // var_dump($job_manager);

  // alex_debug(1,1,'post ',$_POST);
  // exit;
  // alex_debug(0,1,'$job_preview',$job_preview);
  // alex_debug(0,1,'$as21_job_data',$as21_job_data);
  // alex_debug(0,1,'session',$_COOKIE);
  // alex_debug(0,1,'$_SESSION',$_SESSION);
  // var_dump($as21_job_data);
  // var_dump($job_id);

  if( !empty($_SESSION['job_as21_expired_date']) ) {
      global $wpdb;
      /*$wpdb->update(
        $wpdb->posts,
        array( 'post_date' => $_SESSION['job_as21_expired_date'],'post_date_gmt' => $_SESSION['job_as21_expired_date'] ),
        array( 'ID' => (int)$job_id ),
        array('%s','%s'),
          array('%d')
      );*/
      $wpdb->update(
        $wpdb->postmeta,
        array( 'meta_value' => $_SESSION['job_as21_expired_date']),
        array( 'post_id' => (int)$job_id, 'meta_key' => '_job_expires'),
        array('%s'),
        array('%d','%s')
      );
      unset($_SESSION['job_as21_expired_date']);
  }

  // deb_last_query();
  // unset($_COOKIE["job_as21_expired_date"]);
  // setcookie("job_as21_expired_date", '',time()-1000, COOKIEPATH, COOKIE_DOMAIN,is_ssl());
  // alex_debug(0,1,'$_SESSION',$_SESSION);

   as21_wjm_write_file_all_groups(true);

}

/* *****addtiton 'Posted Date' field on page post-a-job****** */

add_action( 'groups_group_create_complete',"as21_jm_write_file_after_group_create" );
function as21_jm_write_file_after_group_create(){
   as21_wjm_write_file_all_groups(true);
}

add_action('submit_job_form_end','as21_wjm_actions_after_submit_form');
function as21_wjm_actions_after_submit_form(){

     // alex_debug(1,1,'post job_manager_job_submitted',$_POST);
     // var_dump($job_fields);
     if( !empty($_POST['job_as21_expired_date']) ) {
        // echo '------not empty '.$_POST['job_as21_expired_date'];
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

    // echo "===Debug submit_job_form_job_fields_start=======".$job_id;

    // var_dump( $job_fields['job_as21_posted_date']['value']);
    // if( empty($job_fields['job_as21_posted_date']['value']) ) 


    // alex_debug(1,1,'',$job_fields['job_as21_posted_date']);
    // var_dump($job_manager);

        // deb_last_query();

    if( $_POST['job_manager_form'] == 'edit-job' )    as21_wjm_write_file_all_groups(true);
}

