<?php

/***** This file don't include on production,it can to remove after development
TEMP - MAIN DEBUGING FUNCTIONS *******/

// echo "debug function work!"; exit;

add_action("wp_head","as21_temp_google",999);

function as21_temp_google(){
	if( is_home() or is_front_page() ) 		echo '<meta name="google-site-verification" content="DzNJ5_KD5zeNnQXMMOcMLyb5I9b1FUPH3H1nd1Wy7lo" />';
}

function deb_last_query(){

	global $wpdb;
	echo '<hr>';
	echo "<b>last query:</b> ".$wpdb->last_query."<br>";
	echo "<b>last result:</b> "; echo "<pre>"; print_r($wpdb->last_result); echo "</pre>";
	echo "<b>last error:</b> "; echo "<pre>"; print_r($wpdb->last_error); echo "</pre>";
	echo '<hr>';
}

function as21_system_message(){
  	echo "<p class='a21-system-box'>It's temporary for debugging:</p>";
}


/* вывод системных данных в форматированном виде */
function alex_debug ( $show_text = false, $is_arr = false, $title = false, $var, $var_dump = false, $sep = "| "){

	// e.g: alex_debug(0, 1, "name_var", $get_tasks_by_event_id, 1);
	$debug_text = "<br>========Debug MODE==========<br>";
	if( boolval($show_text) ) echo $debug_text;
	if( boolval($is_arr) ){
		echo "<br>".$title."-";
		echo "<pre>";
		if($var_dump) var_dump($var); else print_r($var);
		echo "</pre>";
	} else echo $title."-".$var;
	if( is_string($var) ) { if($sep == "l") echo "<hr>"; else echo $sep; }
}
/* вывод системных данных в форматированном виде */




function alex_add_soclinks_for_all_groups_db(){

	global $wpdb;
	$groups = groups_get_groups();
	$k = 0;
	foreach ($groups['groups'] as $gr) {
		// echo $gr->id;s
		$gid[$k] = $gr->id;
		$k++;
	}

	$postid_and_fields = alex_get_postid_and_fields($wpdb);
	$postid = $postid_and_fields[0]+1;
	$fields = $postid_and_fields[1];

	$g = 0;
	$total_group = count($gid);
	for( $i=0; $i < $total_group; $i++){
		foreach ($fields as $field_name) {
			if(preg_match("#google#i", $field_name) === 1) $field_name = $field_name."+";
			$wpdb->insert(
				$wpdb->posts,
				array( 'ID' => $postid, 'post_title' => $field_name, 'post_type' => 'alex_grsoclink', 'post_parent'=>$gid[$g]),
				// array( 'ID' => $postid, 'post_title' => $field_name, 'post_type' => 'alex_gfilds', 'post_parent'=>$gid[$g]),
				array( '%d','%s','%s','%d' )
			);
			$postid++; 
		} 
		$g++;
	}
	echo "Fields for groups has been successfully imported! Total group: ".$total_group;

}

// IMPORTANT !!! execute only 1 time !!! add all fields social links for groups in data base
// add_action("wp_head","alex_add_soclinks_for_all_groups_db");
// add_action( 'bp_before_group_body','alex_add_soclinks_for_all_groups_db');

/* //////// ADDITIONALS DEBUGING FUNCTIONS ////////////  */


// add_action("wp_footer","as21_temp_func2");
function as21_temp_func2(){

	if ( !preg_match("#media/$#i", $_SERVER['REQUEST_URI'] ) ) return false;
	echo "THIS IS MEDIA";
	echo "==== work as21_temp_func2 ====";
	 $group = groups_get_group( array( 'group_id' => 2) );
	 echo $group->name;
	 alex_debug(0,1,"",$group);
	// $action = "Signed up to event task";
	$action ='<a href="http://dugoodr2.dev/i-am/admin/">Admin</a> Signed up to event task<a href="http://dugoodr2.dev/causes/ottawa-mission/">Ottawa Mission</a>';
	$content = "event link details";

	// INSERT INTO `wp8k_bp_activity` (`id`, `user_id`, `component`, `type`, `action`, `content`, `primary_link`, `item_id`, `secondary_item_id`, `date_recorded`, `hide_sitewide`, `mptt_left`, `mptt_right`, `is_spam`) VALUES (NULL, '1', 'groups', 'joined_group', 'Signed up to event task', '', 'http://dugoodr2.dev/i-am/admin/', '2', '0', '2017-04-27 14:54:15', '0', '0', '0', '0')
	// component = members,groups
	// $act_id = bp_activity_add( array(
	// 								'action' => $action,
	// 								// 'item_id'=>2,
	// 								'component' => 'groups',
	// 								'type'=>'joined_cal_event_task',
	// 								'content'=>$content,
	// 								'error_type' => 'wp_error') );
	var_dump($act_id);
}

/* **** 1-получение/удаление опции 2-получение списка всех таблиц у базы данных 3-удаление одной таблицы **** */

// add_action("wp_footer","as21_temp_func");
function as21_temp_func(){

	if( current_user_can('administrator') && is_front_page()){

		global $wpdb;

		echo "<h3>for debug:</h3>";
		$option = "bp_group_calendar_installed";
		echo " option:: $option=".get_option($option);
		// if( delete_option( $option ) ) echo "<br>$option - success delete";

		// $tables = $wpdb->get_results("SHOW TABLES FROM dugoodr2");
		// $tables = $wpdb->get_results("SHOW TABLES FROM dugoodr6_wp956");
		// echo "count tables=".count($tables);echo "<br>";
		// alex_debug(0,1,"",$tables);

		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bp_groups_calendars");
		alex_debug(0,1,"{$wpdb->prefix}bp_groups_calendars",$results);
		$results2 = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bp_groups_bgc_tasks");
		alex_debug(0,1,"{$wpdb->prefix}bp_groups_bgc_tasks",$results2);
		$results3 = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bp_groups_bgc_time");
		alex_debug(0,1,"{$wpdb->prefix}bp_groups_bgc_time",$results3);

		// $wpdb->query("DROP TABLE {$wpdb->prefix}bp_groups_calendars;");
	}
}
/* **** получение/удаление опции 2-получение списка всех таблиц у базы данных 3-удаление одной таблицы **** */


// add_action("wp_footer","wp_get_name_page_template2");
function wp_get_name_page_template2(){

	// global $wpdb;
	// $post_ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type='job_listing'");
	// // print_r($post_ids);

	// foreach ($post_ids as $k=>$v) {
	// 	$geolocation = $wpdb->get_col($wpdb->prepare("(SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key='geolocation_lat' AND post_id=%d LIMIT 1) UNION (SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key='geolocation_long' AND post_id=%d LIMIT 1)",(int)$v,(int)$v));
	// 	$location .= "{lat: ".$geolocation[0].", lng: ".$geolocation[1]."},";
	// }
	// echo $location = substr($location, 0,-1);
	echo "===debug a21=== url script: ";
	// var_dump(preg_match("#^\/job\/#i", $_SERVER['REQUEST_URI']));
	// var_dump(is_page("Volunteers"));
	// var_dump(is_page("volunteers"));
	// var_dump(is_singular("Volunteers"));
	// var_dump(is_singular("volunteers"));
	// var_dump(is_single("single-job_listing"));
	// var_dump(is_single("single-job_listing.php"));
	// var_dump(is_single("single-job"));
	// var_dump(is_singular("single-job_listing"));
	// var_dump(is_page_template("single-job_listing.php"));
	// var_dump(is_page_template("single-job_listing"));
	echo "<hr>";
	// var_dump(is_singular("post-a-job"));
	// var_dump(is_single("post-a-job"));
	// var_dump(is_page("post-a-job"));

}


// only for debug
// add_action("wp_footer","as21_groups_action_join_group");
// this if defined path /buddypress/bp-groups/bp-groups-actions.php
function as21_groups_action_join_group1() {

	// echo "==as21_groups_action_join_group==";
	if ( !bp_is_groups_component() ) return false;

	// // Nonce check.
	// if ( !check_admin_referer( 'groups_join_group' ) )
	// 	return false;

	$bp = buddypress();

	// checking values
	// echo "<br> bp_loggedin_user_id=".bp_loggedin_user_id();
	// echo "<br> bp->groups->current_group->id=".$bp->groups->current_group->id;
	// var_dump( groups_is_user_member( bp_loggedin_user_id(), $bp->groups->current_group->id ) );
	// var_dump(groups_is_user_banned( bp_loggedin_user_id(), $bp->groups->current_group->id) );
	// var_dump( groups_join_group( $bp->groups->current_group->id ) );

	// Skip if banned or already a member.
	if ( !groups_is_user_member( bp_loggedin_user_id(), $bp->groups->current_group->id ) && !groups_is_user_banned( bp_loggedin_user_id(), $bp->groups->current_group->id ) ) {

		/*// User wants to join a group that is not public.
		if ( $bp->groups->current_group->status != 'public' ) {
			if ( !groups_check_user_has_invite( bp_loggedin_user_id(), $bp->groups->current_group->id ) ) {
				bp_core_add_message( __( 'There was an error joining the group.', 'buddypress' ), 'error' );
				bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) );
			}
		}
		*/

		/*// User wants to join any group.
		if ( !groups_join_group( $bp->groups->current_group->id ) )
			bp_core_add_message( __( 'There was an error joining the group.', 'buddypress' ), 'error' );
		else
			bp_core_add_message( __( 'You joined the group!', 'buddypress' ) );
		*/
		// bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) );
		groups_join_group( $bp->groups->current_group->id );
	}

}


// only for debug
// add_action("wp_footer","wp_get_name_page_template");
function wp_get_name_page_template(){

    global $template,$bp;
	// get user_id for logged user
	$user = wp_get_current_user();
	$user_id_islogin = $user->ID;
	// get user_id for notlogged user
	global $bp;
	$user_id_isnotlogin = $bp->displayed_user->id;

	if(!$user_id_islogin){ $user_id_islogin = $user_id_isnotlogin; }
	$url_s = $_SERVER['REQUEST_URI'];
	$profile_view_notdefault = preg_match("#^/i-am/".$member_name."/$#i", $url_s);

	echo "has page profile= "; var_dump(bp_has_profile());

    echo "1- ".$template;
	echo "<br>2- ".$page_template = get_page_template_slug( get_queried_object_id() )." | ";
	echo "<br>3- ".$_SERVER['PHP_SELF'];
	echo "<br>4- ".__FILE__;
	echo "<br>5- ".$_SERVER["SCRIPT_NAME"];
	echo "<br>6- ".$_SERVER['DOCUMENT_ROOT'];
	alex_debug(1,1,0,$_SERVER);
	alex_debug(1,1,0,$template);
	echo get_current_template( true ); 

	$included_files = get_included_files();
// $stylesheet_dir = str_replace( '\\', '/', get_stylesheet_directory() );
// $template_dir   = str_replace( '\\', '/', get_template_directory() );

// foreach ( $included_files as $key => $path ) {

//     $path   = str_replace( '\\', '/', $path );

//     if ( false === strpos( $path, $stylesheet_dir ) && false === strpos( $path, $template_dir ) )
//         unset( $included_files[$key] );
// }

var_dump( $included_files );
echo 'dddd';
exit;
	// global $wpdb;
	// $table = $wpdb->prefix."bp_groups";
	// “{person_name_link} just added the amazing {job_title_and_link} opportunity in {city} for the cause {insert_cause_logo_title_link}”
	// $gr_name = $wpdb->get_var( "SELECT name FROM {$wpdb->prefix}bp_groups WHERE id='8' ");
	// // $action = "just added the amazing ".$get_job[0]->guid." ".$get_job[0]->post_title." <a href='http://dugoodr2.dev/causes/ottawa-food-bank/'>{$gr_name}</a> ";
	// $action="just added the amazing {job_title_and_link} opportunity in {city} for the cause <a href='http://dugoodr2.dev/causes/ottawa-food-bank/'>{$gr_name}</a>";

	// $args = array( "action"=>$action, "component" => "groups", "type" => "new_event2", "item_id"=> 8,"secondary_item_id"=> 10454,"content"=>"content" );
	// echo " activ_id= ".$activity_id = bp_activity_add( $args );

 //      $group = groups_get_group( array( 'group_id' => 8 ) );
 //      alex_debug(0,1,"",$group);

	// deb_last_query();


}



// add_action("wp_footer","a21_memb_reviews");
function a21_memb_reviews(){

delete_option( 'bp_group_calendar_installed' );
	// if(class_exists('BP_Member_Reviews')){
	// 	global $BP_Member_Reviews;
	// 	alex_debug(1,1,"BP_Member_Reviews",$BP_Member_Reviews);
	// 	remove_action('bp_profile_header_meta', array($BP_Member_Reviews, 'embed_rating'));
	// }

	/***** проверка: есть ли у таблицы колонка (check exist column in table db ******

	global $wpdb;
	$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
	WHERE table_name = '{$wpdb->prefix}bp_groups_calendars' AND column_name = 'event_slug'"  );
	alex_debug(1,1,"",$row);
	// if(!empty($row)) { echo "has value";  ... in no value - add new column in db  }
	if(empty($row)){
	   $wpdb->query("ALTER TABLE {$wpdb->prefix}bp_groups_calendars ADD event_slug VARCHAR(200) NOT NULL");
	   // ALTER TABLE `wp8k_bp_groups_calendars` ADD `event_slug` VARCHAR(200) NOT NULL AFTER `last_edited_stamp`;
       // $wpdb->query("ALTER TABLE wp_customer_say ADD say_state INT(1) NOT NULL DEFAULT 1");
	}
	***** проверка: есть ли у таблицы колонка ******/

	
	/* **** получение целого столбца (например все id, и изменение/добавление slug к нему ***** *
	global $wpdb;

	$data = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}bp_groups_calendars`");
	alex_debug(0,1,"",$data);
	echo $count_rows = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}bp_groups_calendars`");
	// получить полностью один столбец (все id)

	$ids = $wpdb->get_col("SELECT id FROM {$wpdb->prefix}bp_groups_calendars");
	alex_debug(0,1,"",$ids);

	foreach($ids as $id){
		echo $event_title = $wpdb->get_var( "SELECT event_title FROM {$wpdb->prefix}bp_groups_calendars WHERE id='{$id}'");
		$event_title = strtolower($event_title);
		$event_slug = str_replace(" ", "-", $event_title);
		$query = 
		$wpdb->update(
			$wpdb->prefix."bp_groups_calendars",
			array( 'event_slug' => $event_slug, ),
			array( 'id' => $id )
		);
	}
	 **** получение целого столбца (например все id, и изменение/добавление slug к нему ***** */

}



// add_action("wp_footer","a21_tmp_query_db");

function a21_tmp_query_db(){

	global $wpdb;

	$get_all_timeline = $wpdb->get_results( "SELECT post_date,post_title,guid FROM ".$wpdb->posts." WHERE post_type='alex_timeline' AND post_date'=>'1970-01-01 00:00:00'" );
	// alex_debug(0,1,"get_all_timeline",$get_all_timeline);
	echo "---------------------777";

	// $is_cur_thankyou = $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE 'post_type'=>%s,post_date'=>%s",'alex_timeline'));
	// deb_last_query();
	var_dump($is_cur_thankyou);
	// exit;
	 $wpdb->delete( $wpdb->posts, array('post_type' => 'alex_timeline','post_date'=>'1970-01-01 00:00:00'), array('%s','%s') );
	 deb_last_query();



	// Преобразует дату '28 Jan 2017' в '2017-01-28', a mysql работает с форматом 0000-00-00 00:00:00
	echo date("Y-m-d",strtotime("28 Jan 2017"));

	/* **** as21 добавление правильной даты в формате mysql нужное потом для сортировки по этому полю post_date **** *

	$date_timeline = $wpdb->get_results( "SELECT ID,post_excerpt FROM ".$wpdb->posts." WHERE post_type='alex_timeline'" );
	// alex_debug(0,1,"",$date_timeline);

	foreach ($date_timeline as $date) {
		$parse_date = date("Y-m-d",strtotime($date->post_excerpt));
		$query = $wpdb->prepare( "UPDATE " . $wpdb->posts."
		        	SET post_date=%s WHERE post_type=%s AND ID=%d
		        	",$parse_date, 'alex_timeline',(int)$date->ID);
		$wpdb->query( $query );
		deb_last_query();
	}

	 **** as21 добавление правильной даты в формате mysql нужное потом для сортировки по этому полю post_date **** */

	/*
	$fields = $wpdb->get_results( $wpdb->prepare(
		"SELECT *
		FROM {$wpdb->posts}
		WHERE post_parent = %d
		    AND post_type = %s
		  ORDER BY post_date DESC LIMIT 0,2", 
		 // ORDER BY post_date DESC LIMIT 15",
		1,
		"alex_timeline"
	) );
	alex_debug(0,1,"",$fields);

	$fields2 = $wpdb->get_results( $wpdb->prepare(
		"SELECT *
		FROM {$wpdb->posts}
		WHERE post_parent = %d
		    AND post_type = %s
		  ORDER BY post_date DESC LIMIT 2,5", 
		 // ORDER BY post_date DESC LIMIT 15",
		1,
		"alex_timeline"
	) );
	alex_debug(0,1,"",$fields2);
	*/
}

// add_action("wp_footer","a21_check_tables");

function a21_check_tables(){
	if( current_user_can('administrator') && is_front_page()){
		global $wpdb;
		$get_event_images = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->base_prefix."bp_groups_groupmeta WHERE meta_key=%s", 'a21_bgc_event_image') );
		alex_debug(0,1,"",$get_event_images);
	    $wpdb->delete( $wpdb->base_prefix."bp_groups_groupmeta", array('id'=>138), array('%d') );
	    deb_last_query();
	}
}

// add_action("wp_footer",'as21_temp_timeline',999);

function as21_temp_timeline(){
?>
<!-- /* **** as21 динмаическое добавление данных в метод плагина, например через ajax
 http://www.jqueryscript.net/time-clock/Responsive-Dynamic-Timeline-Plugin-For-jQuery-Timeliner.html  **** */
     Timeliner.prototype = {
        init: function() { // ...какой-то код метода },
        add: function(_item){ // ...какой-то код метода }
      };
  -->    
 <!-- разметка для плагина -->   
<div id="as21">
	 <li>
	  <div class="timeliner_element teal">
	      <div class="timeliner_title">
	          <span class="timeliner_label">Event Title</span><span class="timeliner_date">03 Nov 2014</span>
	      </div>
	      <div class="content">
	           <b>Lorem Ipsum</b> is simply dummy text of the printing and typesetting industry Lorem Ipsum has been the industry's standard dummy text evsince he 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only fcenturies, ut also the leap into electronic typesetting, remaining essentially unchang
	      </div>
	      <div class="readmore">
	          <a class="btn btn-primary" href="javascript:void(0);" ><i class="fa fa-pencil fa fa-white"></i></a>
	          <a class="btn btn-bricky" href="javascript:void(0);" ><i class="fa fa-trash fa fa-white"></i></a>
	          <a href="#" class="btn btn-info">
	              Read More <i class="fa fa-arrow-circle-right"></i>
	          </a>
	      </div>
	   </div>
	  </li>
	  <!-- ....... можно добавить сколько угодно li -->
</div>

<script type="text/javascript">
jQuery( document ).ready(function($) {

var new_li = '<li>\
				  <div class="timeliner_element teal">\
				      <div class="timeliner_title">\
				          <span class="timeliner_label">Event Title</span><span class="timeliner_date">10 Nov 2014</span>\
				      </div>\
				      <div class="content">\
				           11111111111\
				      </div>\
				      <div class="readmore">\
				          <a class="btn btn-primary" href="javascript:void(0);" ><i class="fa fa-pencil fa fa-white"></i></a>\
				          <a class="btn btn-bricky" href="javascript:void(0);" ><i class="fa fa-trash fa fa-white"></i></a>\
				          <a href="#" class="btn btn-info">\
				              Read More <i class="fa fa-arrow-circle-right"></i>\
				          </a>\
				      </div>\
				   </div>\
			  </li>\
			  <li>\
				  <div class="timeliner_element teal">\
				      <div class="timeliner_title">\
				          <span class="timeliner_label">Event Title</span><span class="timeliner_date">15 Nov 2014</span>\
				      </div>\
				      <div class="content">\
				           22222\
				      </div>\
				      <div class="readmore">\
				          <a class="btn btn-primary" href="javascript:void(0);" ><i class="fa fa-pencil fa fa-white"></i></a>\
				          <a class="btn btn-bricky" href="javascript:void(0);" ><i class="fa fa-trash fa fa-white"></i></a>\
				          <a href="#" class="btn btn-info">\
				              Read More <i class="fa fa-arrow-circle-right"></i>\
				          </a>\
				      </div>\
				   </div>\
			  </li>';

	var tl = $('#as21').timeliner();
	tl.add(new_li).render();
	// tl.destroy(); //work
});
</script>

<!-- /* **** as21 динмаическое добавление данных в метод плагина, например через ajax
 http://www.jqueryscript.net/time-clock/Responsive-Dynamic-Timeline-Plugin-For-jQuery-Timeliner.html  **** */ -->

<?php

}

// add_action("wp_footer","as21_get_info_group_calendar");

function as21_get_info_group_calendar(){

	// it wll work if ?dev=1
	if( (bool)$_GET['dev'] == true ) {

		global $wpdb;
		// $wpdb->query("DELETE FROM {$wpdb->prefix}bp_groups_groupmeta WHERE id='160' AND meta_key='a21_bgc_event_image' ");
		// deb_last_query();
		$get_all_event_image = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $wpdb->base_prefix . "bp_groups_groupmeta
	            	WHERE meta_key=%s", 'a21_bgc_event_image') );
		alex_debug(0,1,"get_all_event_image",$get_all_event_image);

		$all_events = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}bp_groups_calendars"));
		foreach ($all_events as $k => $event) {
			unset($all_events[$k]->event_description);
		}
		alex_debug(0,1,"all_events",$all_events);

	}
}

// add_action("wp_footer","as21_out_data_if_fb_login");

function as21_out_data_if_fb_login(){
	// get all facebook user
	if( (bool)$_GET['dev'] == true ) {
		global $wpdb;
		//$wpdb->query("DELETE FROM {$wpdb->prefix}bp_groups_groupmeta WHERE id='128' AND meta_key='a21_bgc_event_image' ");
		// deb_last_query();
		$get_all_fbdata = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " .  $wpdb->prefix."usermeta
	            	WHERE meta_key=%s", '_afbdata') );
		alex_debug(0,1,"get_all_fbdata",$get_all_fbdata);

		$get_all_users = $wpdb->get_results( "SELECT * FROM " .  $wpdb->prefix."users" );
		alex_debug(0,1,"get_all_users",$get_all_users);

	}
}

// add_action("wp_footer","as21_get_grsoclink");

function as21_get_grsoclink(){

	if( (bool)$_GET['dev'] == true ) {

		global $wpdb;
		$fields = $wpdb->get_results( $wpdb->prepare(
			"SELECT post_parent,ID, post_title, post_content
			FROM {$wpdb->posts}
			WHERE post_type = %s AND ID>10605 AND ID<10620
			ORDER BY post_parent DESC",
			"alex_grsoclink"
			// "alex_gfilds"
		) );
		alex_debug(0,1,'',$fields);
		// 10647-10652

		$wpdb->query("DELETE FROM {$wpdb->posts} WHERE ID='10609' AND post_type='alex_grsoclink' ");
		$wpdb->query("DELETE FROM {$wpdb->posts} WHERE ID='10610' AND post_type='alex_grsoclink' ");
		$wpdb->query("DELETE FROM {$wpdb->posts} WHERE ID='10611' AND post_type='alex_grsoclink' ");
		$wpdb->query("DELETE FROM {$wpdb->posts} WHERE ID='10612' AND post_type='alex_grsoclink' ");
		$wpdb->query("DELETE FROM {$wpdb->posts} WHERE ID='10613' AND post_type='alex_grsoclink' ");
		$wpdb->query("DELETE FROM {$wpdb->posts} WHERE ID='10614' AND post_type='alex_grsoclink' ");
		deb_last_query();
	}
}


// add_action("wp_footer","as21_get_job_listing");

function as21_get_job_listing(){

	if( (bool)$_GET['dev'] == true ) {
		global $wpdb;
		// $id = '10636';
		$id = '10655';
		$listings = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID=$id AND post_type='job_listing'");
		//$listings = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type='job_listing'");
		alex_debug(0,1,'l',$listings);
		$content= "test aaaa";

		// $wpdb->update(
		// 	$wpdb->posts,
		// 	array( 'post_content'=> $content),
		// 	array( 'ID' => $id,'post_type' => 'job_listing' )
		// );
		deb_last_query();
		$listings = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID=$id AND post_type='job_listing'");
		alex_debug(0,1,'l',$listings);

	}
}


// add_action("wp_footer","as21_get_all_jobs_by_group_id");

function as21_get_all_jobs_by_group_id(){

	// echo "========================777=========";
	// if( (bool)$_GET['dev'] == true ) {
	// 	global $wpdb;
	// 	$group_id = 14;
	// 	$ids = $wpdb->get_col("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_job_group_a21' AND meta_value='$group_id' ");
	// 	deb_last_query();
	// 	var_dump($ids);
	// }

	var_dump(Kleo::get_config('menu_icon_default'));
}

/************** logging of user clicks to help fix bugs ***********/

// $usag = '31-05-2017 12:13:55==88.198.54.49==/causes/help-santa-toy-parade/callout/2007/10/26/====Mozilla/5.0 (compatible; MJ12bot/v1.4.7; http://mj12bot.com/)==';
// if( strpos($usag, "mj12bot") !== fasle ) echo "====999 mj12bot";
// var_dump(strpos($_SERVER['HTTP_USER_AGENT'], "mj12bot"));
// Googlebot
// if( strpos($_SERVER['HTTP_USER_AGENT'], "mj12bot") === false && strpos($_SERVER['HTTP_USER_AGENT'], "YandexBot") === false ){
if( strpos($_SERVER['HTTP_USER_AGENT'], "mj12bot") === false && strpos($_SERVER['HTTP_USER_AGENT'], "YandexBot") === false && strpos($_SERVER['HTTP_USER_AGENT'], "OpenLinkProfiler.org/bot") === false  && strpos($_SERVER['HTTP_USER_AGENT'], "Googlebot") === false && strpos($_SERVER['HTTP_USER_AGENT'], "bingbot") === false  && strpos($_SERVER['HTTP_USER_AGENT'], "adsbot") === false){

	add_action("wp_footer","as21_find_where_bug_was");
	function as21_find_where_bug_was(){
		/*
		?>
		<script>
		var useragent = navigator.userAgent;
		var w = window.screen.availWidth;
		var h = window.screen.availHeight;
		console.log("\r\n"+w+"x"+h);
		console.log(useragent);
		// var geo = navigator.geolocation
		// console.log("User-agent header: " + geo);
		// for (k in geo){
		// 	console.log(k+"-"+geo[k]);
		// }
		</script>
		<?php
		*/
		// echo "==========inside function===";

		$ip = $_SERVER['REMOTE_ADDR'];
		// $ip = "127.0.0.111";
		$name = "as21_error.log";
		$is_ip = false;

		function as21_write($ip,$name){

			// echo "===write===";
			$date = date("d-m-Y")." ".(date("H")+3).date(":i:s");
			$fp = fopen($name, "a"); 
			$text = "\r{$date}=={$ip}==".$_SERVER['REQUEST_URI']."==".$_SERVER['HTTP_REFERER']."==".$_SERVER['HTTP_USER_AGENT']."=="; 
			$test = fwrite($fp, $text); 
			// if ($test) echo 'Данные в файл успешно занесены.'; else echo 'Ошибка при записи в файл.';
			fclose($fp); 
		}

			as21_write($ip,$name);


		function as21_read($ip,$name){

			// echo "===read===";
			$file = file($name); // Считываем весь файл в массив 
			// var_dump($ip);

			for($i = 0; $i < sizeof($file); $i++)
			{
				// if($i == $num_stroka) unset($file[$i]); 
				// echo $file[$i]."<br>";
				if($ip == trim($file[$i])) { $is_ip = true; break; }
				else{$is_ip = false;}
				// var_dump($file[$i]);
			}
			// var_dump($is_ip);
			if( !$is_ip ) as21_write($ip,$name);
			// $fp = fopen($name, "w");
			// fputs($fp, implode("", $file));
			// fclose($fp);
		}
		// as21_read($ip,$name);


		?>
		<script type="text/javascript">
		jQuery( document ).ready(function($) {

					var h = window.screen.availHeight;
					var w = window.screen.availWidth;
					var screen = w+"x"+h;
					var data = { 'action': 'as21_get_screen_resolution','screen': screen};
					// console.log(screen);	console.log(ajaxurl);  console.log(data);
					$.ajax({
						url:ajaxurl, // обработчик
						data:data, // данные
						type:'POST', // тип запроса
						success:function(data){
							// if( data ) // console.log(data); console.log('success ajax');
							 // else { console.log("data send with errors!");}
						}

					 });
			});
		</script>
		<?php

	}


	function as21_write_screen($name,$screen){

		// echo "===write===";
		$date = date("d-m-Y")." ".(date("H")+3).date(":i:s");
		$fp = fopen($name, "a"); 
		// $text = "{$date} ".$_SERVER['REMOTE_ADDR']." - ".$_SERVER['REQUEST_URI']." ref: ".$_SERVER['HTTP_REFERER']." - ".$_SERVER['HTTP_USER_AGENT']." - ".$screen."\r\n"; 
		$text = $screen; 
		$test = fwrite($fp, $text); 
		// if ($test) echo 'Данные в файл успешно занесены.'; else echo 'Ошибка при записи в файл.';
		fclose($fp); 
	}

	add_action('wp_ajax_as21_get_screen_resolution', 'as21_get_screen_resolution');
	add_action('wp_ajax_nopriv_as21_get_screen_resolution', 'as21_get_screen_resolution');
	function as21_get_screen_resolution(){
		// echo "-----------------";
		// alex_debug(0,1,'',$_POST);
		as21_write_screen($_SERVER["DOCUMENT_ROOT"].'/as21_error.log', $_POST['screen']);
		exit;
	}
}
/************** logging of user clicks to help fix bugs ***********/

/* **** as21 json-api new controller **** */

/*
// add_action("wp_footer","as21_get_memeber_cover_image");
function as21_get_memeber_cover_image(){
	echo "7771 =============".kleo_bp_get_member_cover_attr(2);
} 

function add_hello_controller($controllers) {
  $controllers[] = 'hello';
  return $controllers;
}
add_filter('json_api_controllers', 'add_hello_controller');

function set_hello_controller_path() {
  return __DIR__."/hello.php";
}
add_filter('json_api_hello_controller_path', 'set_hello_controller_path');
// echo __DIR__;

// add_action("init",'as21_x');
function as21_x(){

	// if (!is_admin()) exit;
	// class JSON_API_Alex_Controller extends  JSON_API_BuddypressRead_Controller {

	//   public function hello_world() {
	//     return array(
	//       "message" => "Hello, world"
	//     );
	//   }
	// }
}
*/

/* **** as21 json-api new controller **** */





// add_action('submit_job_form_start','as21_ddd1');
// add_action('init','as21_ddd1');
function as21_ddd1(){
// echo $_SERVER['REQUEST_URI'];
		// setcookie("as21_job_post_date", '',time()-1000, COOKIEPATH, COOKIE_DOMAIN,is_ssl());
		echo "job start form";
}


// 'handler'  => array( $this, 'preview_handler' ),
/*
add_filter( 'submit_job_steps','as21_2',1);
function as21_2($arr){
	alex_debug(1,1,'costructor',$arr['preview']['handler']);
	// $arr['preview']['handler'][0] = 'AS21_WP';
	$arr['preview']['handler'][1] = 'as21_preview_handler';
	alex_debug(1,1,'costructor',$arr['preview']['handler']);
	// echo 'as21_2 ==============';
	// exit;
	return $arr;
}
*/

// echo " ".current_time('mysql');
// echo " ".current_time('mysql',1);
// echo " ".current_time('timestamp');



// add_action("wp_footer",'as21_ccc');
function as21_ccc(){

	echo "debug777====";
	var_dump($job_types );
	// global $wp_filter;
	// alex_debug(0,1,'',$wp_filter['submit_job_form_save_job_data']);
	// alex_debug(0,1,'',$wp_filter['job_manager_update_job_data']);
	// alex_debug(0,1,'',$wp_filter['job_manager_job_submitted']);
	// alex_debug(0,1,'',$wp_filter['wp_head']);
	// list_hooked_functions('job_manager_job_submitted');

	// var_dump($GLOBALS['job_manager']);
	// global $job_manager;
	// $as21_job_data = 'eeeeeeee';
	// $job_preview['as21'] = $as21_job_data;
	// $job_manager->as21 = $as21_job_data;

	// echo "<hr>";
	// var_dump($job_manager);
	// alex_debug(0,1,'',$job_manager);
}

/* **** as21 count_jobs_in_group **** */
// add_action('wp_footer','as21_count111');
function as21_count111(){
	// as21_wjm_write_file_all_groups();
	// as21_jobs_get_display_count_plus_by_group_id(38);
	// as21_jm_get_all_display_count_plus();

		// wp_clear_scheduled_hook( 'job_manager_check_for_expired_jobs' );
		// wp_schedule_event( time(), 'hourly', 'job_manager_check_for_expired_jobs777' );

		wp_clear_scheduled_hook( 'job_manager_check_for_expired_jobs777' );
		wp_schedule_event( time(), 'daily', 'job_manager_check_for_expired_jobs777' );
		add_action('job_manager_check_for_expired_jobs777', 'do_this_hourly');
		function do_this_hourly() {
			echo "lala============";
		}

		global $wpdb;

		// Change status to expired
		$job_ids = $wpdb->get_col( $wpdb->prepare( "
			SELECT postmeta.post_id FROM {$wpdb->postmeta} as postmeta
			LEFT JOIN {$wpdb->posts} as posts ON postmeta.post_id = posts.ID
			WHERE postmeta.meta_key = '_job_expires'
			AND postmeta.meta_value > 0
			AND postmeta.meta_value < %s
			AND posts.post_status = 'publish'
			AND posts.post_type = 'job_listing'
		", date( 'Y-m-d', current_time( 'timestamp' ) ) ) );

		/* **** as21 **** */
		echo "a777================";
		var_dump($job_ids);
		deb_last_query();
		// exit;


		if ( $job_ids ) {
			foreach ( $job_ids as $job_id ) {
				$job_data       = array();
				$job_data['ID'] = $job_id;
				$job_data['post_status'] = 'expired';
				wp_update_post( $job_data );
				deb_last_query();
			}
		}
		// exit;


}



/* temp 

function as21_jm_wrire_file_calc_total_count(){

	$filename = AS21_PATH_JOBS_COUNT_TXT;
	// ?jobs_count_calc=yes
	// echo date("Y-m-d H:i:s",filemtime($filename) );
	// echo $filename = 'http://'.$_SERVER['HTTP_HOST'].'/count_jobs_in_group.txt';
	// exit;

	if( file_exists($filename)) {

		$file = file($filename); 

		// **** as21 get info from file and addition dcp to initial jobs count 

		$file = explode("\r", $file[0]);
		// var_dump($file);
		// alex_debug(1,1,'',$file);
		$dipsplay_count_plus = explode("|", $file[0]);
		$dipsplay_count_plus = $dipsplay_count_plus[1];

		foreach ($file as $k=>$line) {
			if($k == 0 or $k == 1) continue;
			if(strpos($line, '|') !== false){
				$separator = explode("|", $line);
				// print_r($separator);
				$separator[3] = (int)$separator[2]+(int)$dipsplay_count_plus;
				$separator[3] = " ".$separator[3];
				// print_r($separator);
				// exit;
				$file[$k] = implode("|", $separator);
			}
		}

		// print_r($file);
		// alex_debug(1,1,'after calc',$file);

		$file_to_str = implode("\r", $file);
		// var_dump($file_to_str);
		as21_write_file_jobs_count($filename,$file_to_str);

	}
	
}

function as21_jm_wrire_file_calc_total_count_each_group(){

	$filename = AS21_PATH_JOBS_COUNT_TXT;
	// ?jobs_count_calc=yes
	// echo date("Y-m-d H:i:s",filemtime($filename) );
	// echo $filename = 'http://'.$_SERVER['HTTP_HOST'].'/count_jobs_in_group.txt';
	// exit;

	if( file_exists($filename)) {

		$file = file($filename); 

		// **** as21 get info from file and addition dcp to initial jobs count **** 

		$file = explode("\r", $file[0]);
		// var_dump($file);
		// alex_debug(1,1,'',$file);
		$dipsplay_count_plus = explode("|", $file[0]);
		$dipsplay_count_plus = $dipsplay_count_plus[1];

		foreach ($file as $k=>$line) {
			if($k == 0) continue;
			if(strpos($line, '|') !== false){
				$separator = explode("|", $line);
				// print_r($separator);
				$separator[4] = (int)$separator[2]+(int)$separator[3];
				$separator[4] = " ".$separator[4];
				// print_r($separator);
				// exit;
				$file[$k] = implode("|", $separator);
			}
		}

		// print_r($file);
		// alex_debug(1,1,'after calc',$file);

		$file_to_str = implode("\r", $file);
		// var_dump($file_to_str);
		as21_write_file_jobs_count($filename,$file_to_str);

	}
	
}

*/

// add_action('wp_footer','as21_wirte_all_groups_in_file');
function as21_wirte_all_groups_in_file(){

   if($_GET['dev']==1){
		$filename = AS21_PATH_JOBS_COUNT_TXT;
		$text = 'testing write any text; ';
		as21_wjm_write_file_all_groups();
	// as21_wjm_write_file_jobs_count($filename,$text);
		echo "============as21_wjm_write_file_jobs_count | ";
		alex_debug(0,1,'',stat($filename));
		echo substr(sprintf('%o', fileperms($filename)), -4); echo "<br>";
		// on hostings permissions increase e.g 666->777
		var_dump( chmod($filename, 0777) );
	}
}

add_action('wp_footer','as21_display_width_window');
function as21_display_width_window(){

	if((bool)$_GET['dev'] == true){
	?>
	<script type="text/javascript">
	    var w = window.innerWidth;
	    var html = "Width window: "+w+"px"
	    console.log(html);
	    var node = document.createTextNode(html);
	    // var para = document.createElement("p");
		// para.appendChild(node);
		var element = document.getElementById("main-container");
		element.appendChild(node);
	</script>
	<?php
	}

}

add_action( 'wp_enqueue_scripts','as21_job_debug',999);

function as21_job_debug(){
	if( (bool)$_GET['dev'] === true){
		?>
		<script type='text/javascript'>
		/* <![CDATA[ */
		var job_manager_ajax_filters = {"ajax_url":"\/jm-ajax\/%%endpoint%%\/","is_rtl":"0","i18n_load_prev_listings":"Load previous listings","lang":null};
		/* ]]> */
		</script>
		<?php
		wp_deregister_script( 'wp-job-manager-ajax-filters' );
		wp_enqueue_script( 'wp-job-manager-ajax-filters', plugins_url() . '/wp-job-manager/assets/js/ajax-filters-debug.js', '','', true );
		// wp_enqueue_script( 'wp-job-manager-ajax-filters', plugins_url() . '/wp-job-manager/assets/js/ajax-filters-debug.js', array('jquery-deserialize'),'', true );
	}
}

// add_action('wp_footer','as21_del_noused_xprofile_fields');
function as21_del_noused_xprofile_fields(){
	if( (bool)$_GET['dev'] === true){
		global $wpdb;
		$wpdb->delete( $wpdb->prefix."bp_xprofile_data", array('field_id' => 57 ), array('%d') );
		$wpdb->delete( $wpdb->prefix."bp_xprofile_data", array('field_id' => 56 ), array('%d') );
		$wpdb->delete( $wpdb->prefix."bp_xprofile_data", array('field_id' => 18 ), array('%d') );
		$get_f = $wpdb->get_results( $wpdb->prepare(
			"SELECT *
			FROM {$wpdb->prefix}bp_xprofile_data
			WHERE field_id = %d
			ORDER BY id",
			56
		) );
		alex_debug(0,1,'as21_del_noused_xprofile_fields',$get_f);
	}
}

// add_action("wp_footer","list_hooks");
function list_hooks(){

	echo ' list_hooks======';
	// function list_hooked_functions($tag=false){

	//      global $wp_filter;
	//      if ($tag) {
	//       $hook[$tag]=$wp_filter[$tag];
	//       if (!is_array($hook[$tag])) {
	//       trigger_error("Nothing found for '$tag' hook", E_USER_WARNING);
	//       return;
	//       }
	//      }
	//      else {
	//       $hook=$wp_filter;
	//       ksort($hook);
	//      }
	//      echo '<pre>';
	//      foreach($hook as $tag => $priority){
	//       echo "<br />&gt;&gt;&gt;&gt;&gt;\t<strong>$tag</strong><br />";
	//       ksort($priority);
	//       foreach($priority as $priority => $function){
	//       echo $priority;
	//       foreach($function as $name => $properties) echo "\t$name<br />";
	//       }
	//      }
	//      echo '</pre>';
	//      return;
	// }
	// list_hooked_functions('wp_head');

	// show all callbaks for some hook/filter by priority
	 global $wp_filter;
	 alex_debug(0,1,'',$wp_filter['wp_head']);
	 // alex_debug(0,1,'',$wp_filter['wp_title']);
	 // alex_debug(0,1,'',$wp_filter['bp_head']);

}


// add_action('wp_head','as21_head');
function as21_head(){
	 // global $wp_filter;
	 // alex_debug(0,1,'',$wp_filter['wp_head']);

	 add_filter('wp_title', 'kleo_wp_title2', 999999,2);
	function kleo_wp_title2($title,$sep){
		// echo ' wp_title ========='; exit;
		return " ALEX ADD! ".$title;
	}
	exit;
}

// add_action('wp_head','as21_left_nav_hide');
function as21_left_nav_hide(){
?>
<style>	#item-header-wrap{display:none !important;}</style>
<?php	
}

// remove_all_actions("wp_head");
// add_action('bp_head','as21_bphead');
// function as21_bphead(){
// 	exit;
// }
/* **** as21 **** */


// add_action("wp_head",'as21_add_index_page',1);
// function as21_add_index_page(){
// if( bp_is_user_profile() )  echo "<meta name='robots' content='all'/>\n";
// }

// add_action( 'bp_template_content', array($this, 'screen_content') );
// add_action('wp_footer','as21_000');
// add_action('plugins_loaded','as21_000');
function as21_000(){

	if(class_exists('BP_Member_Reviews')){
		global $BP_Member_Reviews;
		alex_debug(1,1,"BP_Member_Reviews",$BP_Member_Reviews);

		 global $wp_filter;
	    alex_debug(0,1,'',$wp_filter['bp_template_content']);

	    // $BP_Member_Reviews = new BP_Member_Reviews();
		remove_action('bp_template_content', array($BP_Member_Reviews, 'screen_content'),20);
		add_action("bp_template_content","screen_content_2");
		function screen_content_2(){
			echo "a21 new_html========";
		}
	}
}

/*
        public function screen_content() {
            if( (($this->settings['access'] == 'registered') && is_user_logged_in()) ||  $this->settings['access'] == 'all'){
                if(get_current_user_id() != bp_displayed_user_id() &&
                   apply_filters( 'bp_members_reviews_review_allowed', true, bp_loggedin_user_id(), bp_displayed_user_id() )
                ) {
                    include($this->path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'review-form.php');
                }
            }

            include($this->path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'review-list.php');
        }
*/

// add_action('wp_ajax_bp_user_review1', 'ajax_review2');
// add_action('wp_ajax_nopriv_bp_user_review1', 'ajax_review2');
function ajax_review2(){
	echo 'output test text in js console-------';
	// $a['res'] = 'aaa';
	// echo json_encode($a);
	exit;
}

// add_action('wp_footer','as21_last1');
function as21_last1(){
	// global $wpdb; 
	// $q = "INSERT INTO $wpdb->posts (post_title) VALUES ('rrrrr')";
	// $wpdb->query($q);
	// deb_last_query();
	echo '---lala----';
// var_dump(bp_get_members_directory_permalink());
// var_dump($bp->displayed_user);
$user = wp_get_current_user();
	var_dump(bp_get_members_directory_permalink().$user->data->user_nicename);

alex_debug(0,1,'',$user);
	// global $BP_Member_Reviews;
	// remove_action( 'bp_template_content',array($BP_Member_Reviews,'screen_content'),999);
	
	// global $wp_filter;
	// var_dump($wp_filter['bp_template_content']->callbacks);
	// var_dump($wp_filter['bp_template_content']);
	// echo '---lala----';
}

// add_action('wp_footer','as21_temp_data_experience');
function as21_temp_data_experience(){

	if( (bool)$_GET['dev'] == true ) {
		global $bp,$wpdb;
		// $quest_id = $bp->displayed_user->id;

		$fields = $wpdb->get_results( $wpdb->prepare(
			"SELECT *
			FROM {$wpdb->posts} WHERE post_type=%s
			ORDER BY ID",
			'experience_volunteer'
		) );			

		$fields2 = $wpdb->get_results( $wpdb->prepare(
			"SELECT *
			FROM {$wpdb->posts} WHERE post_type=%s
			ORDER BY ID",
			'invation_verif_exper'
		) );	
		// deb_last_query();


		alex_debug(1,1,'experience_volunteer',$fields);
		alex_debug(1,1,'invation_verif_exper',$fields2);

		// $user = get_user_by( 'email', 'freerun-2012@yandex.ru' );
		// print_r($user);
		// echo $user->data->ID;
		// echo bp_core_get_user_domain($user->data->ID).'/verification-experience?id='.(int)$item_id;
	}
}


