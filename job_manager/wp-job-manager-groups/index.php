<?php

function a21_get_groups_frontend_selectbox( $field, $key ) {

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
 
add_action( 'job_manager_field_actionhook_job_group_a21', 'a21_get_groups_frontend_selectbox', 10, 2 );


// hook place - /plugins/wp-job-manager/templates/content-single-job_listing.php
add_action("single_job_listing_start","a21_get_job_data_group");

function a21_get_job_data_group(){

    // echo "JMFE a21_get_job_data_group ";
    $gr_id =  get_job_field("job_group_a21");
    $group = groups_get_group( array( 'group_id' => $gr_id ) );
    echo $group->name;

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

// add_action( 'current_screen', 'action_function_name_11' );
add_action( 'admin_footer', 'a21_wpjm_hide_dismiss' );

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
add_action( 'job_manager_update_job_data', 'a21_get_groups_u', 100, 2 );

function a21_get_groups_u($job_id,$values){
    alex_debug(0,0,'job_id',$job_id);
    alex_debug(0,1,'values',$values);
    echo "========debug a21 a21_get_groups_u====";
    exit;

}

add_action( 'job_manager_save_job_listing', 'a21_get_groups_up', 100, 2 );
// add_action( 'job_manager_save_job_listing', array( $this, 'save_admin_fields' ), 100, 2 );

function a21_get_groups_up($post_id,$post){
    var_dump($post_id);
    var_dump($post);
    echo "========debug a211==== a21_get_groups_up";
    exit;
}

// add_action("wp_footer","a21_test1",999);
function a21_test1(){

    // echo get_job_field("job_gr2");
    echo "=======for debug a21";
    global $bp;
    // $group = groups_get_group( array( 'group_id' => 3 ) );
    // echo $group->name;
    // print_r($group);

}

/*********/

// add_action("plugins_loaded","a21_admin_1");
// function a21_admin_1(){

//   var_dump($_REQUEST['plugin']);
//   echo "===debug a21=== url script: a21_admin ";
//   exit;
// }


/*************  попытка реализации без сторонних плагинов *******************/


// add_filter( 'submit_job_form_fields', 'custom_submit_job_form_fields',999 );

// This is your function which takes the fields, modifies them, and returns them
// You can see the fields which can be changed here: https://github.com/mikejolley/WP-Job-Manager/blob/master/includes/forms/class-wp-job-manager-form-submit-job.php
function custom_submit_job_form_fields( $fields ) {

    // Here we target one of the job fields (job_title) and change it's label
    // $fields['job']['job_title']['label'] = "Custom Label";


           // [job_category] => Array
           //      (
           //          [label] => Job category
           //          [type] => term-multiselect
           //          [required] => 1
           //          [placeholder] => 
           //          [priority] => 4
           //          [default] => 
           //          [taxonomy] => job_listing_category
           //      )
     $fields['job']['job_group'] = array
                (
                    'label' => "Group",
                    'type' => 'term-select',
                    'required' => false,
                    // 'placeholder' => '1',
                    'priority' => 4,
                    // 'taxonomy' => 'job_listing_category'
                    // 'default' => 
                );

    // And return the modified fields
    return $fields;
    // print_r($fields);
}


// add_filter( 'submit_job_form_validate_fields', "a21_custom_job" );
// function a21_custom_job($passed,$fields,$values){
//  var_dump($passed);
//  var_dump($fields);
//  var_dump($values);
//  echo "alex888";
//  exit;
// }

// add_action( 'submit_job_form_fields_get_job_data', 'a21_get_job_tag_field_data',999 );
function a21_get_job_tag_field_data($data,$job){
  var_dump($data);
  var_dump($job);
  echo "alex888";
  exit;
}


// add_action("wp_footer","a21_job_manager_groups");
function a21_job_manager_groups(){
  echo "<h3>debug alex777</h3>";

  // add_filter( 'submit_job_form_fields', array( $this, 'job_tag_field' ) );
  // add_filter( 'submit_job_form_fields', 'a21_job_manager_group_field' );
  // function a21_job_manager_group_field($fields){
  //  var_dump($fields);
  //  echo "f777";
  // }

  // Add your own function to filter the fields

}



/* ***************** */