<?php

// add_action("wp_footer","a21_test1",999);
function a21_test1(){

    //a21
    global $wpdb;
    /****** выбриает все посты которые принадлежат к указанной группе (связь с таблицей_postmeta) ************/
    $args_meta = array(
          'meta_key' => '_job_group_a21',
          'meta_value' => 14,
          'meta_type' => "NUMERIC",
          'meta_compare' => "=",
          'post_type' => 'job_listing',
          'posts_per_page' => 5
        );
    $job = new WP_Query($args_meta);

    alex_debug(0,1,"a ",$job);
    // echo "<pre>";
    // print_r($wpdb->queries);
    // echo "</pre>";

    // exit("===a21 end===");


        // echo get_job_field("job_gr2");
        echo "=======for debug a21";
        global $bp;
        // $group = groups_get_group( array( 'group_id' => 3 ) );
        // echo $group->name;
        // print_r($group);

}


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

/*************  попытка реализации без сторонних плагинов *******************/


/*********/

// add_action("plugins_loaded","a21_admin_1");
// function a21_admin_1(){

//   var_dump($_REQUEST['plugin']);
//   echo "===debug a21=== url script: a21_admin ";
//   exit;
// }
