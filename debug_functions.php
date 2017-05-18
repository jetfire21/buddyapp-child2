<?php

/***** TEMP - ONLY FOR DEBUG *******/

// echo "debug function work!"; exit;

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


function deb_last_query(){

	global $wpdb;
	echo '<hr>';
	echo "<b>last query:</b> ".$wpdb->last_query."<br>";
	echo "<b>last result:</b> "; echo "<pre>"; print_r($wpdb->last_result); echo "</pre>";
	echo "<b>last error:</b> "; echo "<pre>"; print_r($wpdb->last_error); echo "</pre>";
	echo '<hr>';
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

add_action("wp_footer","as21_get_info_group_calendar");

function as21_get_info_group_calendar(){

	// it wll work if ?dev=1
	if( (bool)$_GET['dev'] == true ) {

		global $wpdb;
		//$wpdb->query("DELETE FROM {$wpdb->prefix}bp_groups_groupmeta WHERE id='128' AND meta_key='a21_bgc_event_image' ");
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

add_action("wp_head","as21_temp_google",999);

function as21_temp_google(){
	if( is_home() or is_front_page() ) 		echo '<meta name="google-site-verification" content="DzNJ5_KD5zeNnQXMMOcMLyb5I9b1FUPH3H1nd1Wy7lo" />';
}