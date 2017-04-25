<?php

/* ****** добавление полля city в профлиь группы,если его не существует в базе ************** */
add_action('wp_footer','alex_test_1');
function alex_test_1(){
	if( $_SERVER['REQUEST_URI'] == "/causes/something-amazing/")
	// if( $_SERVER['REQUEST_URI'] == "/causes/something-amazing/admin/edit-details/")
	{
		echo "<h3>=========Testing========</h3>";
		$groups = BP_Groups_Group::get(array('type'=>'alphabetical','per_page'=>999));
		// print_r($groups);
		echo "Всего групп: ".$groups['total']."<hr>";
		// отбирает только id и name группы
		global $wpdb;
		foreach ($groups['groups'] as $gr) {

			echo $gr->id. " - ".$gr->name;

		    $table_grmeta = $wpdb->prefix."bp_groups_groupmeta";
			$city = $wpdb->get_results( $wpdb->prepare(
				"SELECT meta_value
				FROM {$table_grmeta}
				WHERE group_id = %d
				    AND meta_key = %s",
				intval( $gr->id ),
				"city_state"
			) );

			// print_r($city);
			echo $city = $city[0]->meta_value;
			if( !empty($city)) { echo " ---- создан! "; echo $city;}
			else{
				$wpdb->insert(
					$wpdb->prefix."bp_groups_groupmeta",
					array( 'group_id' => $gr->id, 'meta_key' => 'city_state', 'meta_value'=> " "),
					array( '%d','%s','%s' )
				);
			}
			echo "<br>";
			// echo "<b>last query:</b> ".$wpdb->last_query."<br>";
			// echo "<b>last result:</b> "; print_r($wpdb->last_result);
			// echo "<br><b>last error:</b> "; print_r($wpdb->last_error);
		}

		$table_grmeta = $wpdb->prefix."bp_groups_groupmeta";
		$all_city = $wpdb->get_results( $wpdb->prepare(
			"SELECT meta_value,group_id
			FROM {$table_grmeta}
			WHERE meta_key = %s
			","city_state"
		) );

		echo "<hr>Группы у которых есть города<br>";
		// print_r($all_city);
		$i = 1;
		foreach ($all_city as $item) {
			echo $i."___".$item->group_id." - ".$item->meta_value."<br>"; $i++;
		}
	}
}
/* ****** добавление полля city в профлиь группы,если его не существует в базе ************** */




/* ****** изменение post_type c alex_gfields на alex_grsoclink ************** */
add_action('wp_footer','alex_test_1');
function alex_test_1(){
	// if( $_SERVER['REQUEST_URI'] == "/causes/something-amazing/")
	if( $_SERVER['REQUEST_URI'] == "/causes/something-amazing/admin/edit-details/")
	{
		echo "<h3>=========Testing========</h3>";
		$groups = BP_Groups_Group::get(array('type'=>'alphabetical','per_page'=>999));
		// print_r($groups);
		echo "Всего групп: ".$groups['total']."<hr>";
		// отбирает только id и name группы
		global $wpdb;
		foreach ($groups['groups'] as $gr) {
			echo $gr->id. " - ".$gr->name."<br>";
		}

		echo "<hr>Группы c post_type=alex_gfilds: <hr>";
		$k = 1;
		foreach ($groups['groups'] as $gr) {
			$gr_soclinks = $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM {$wpdb->posts}
				WHERE post_parent = %d
				    AND post_type = %s",
				intval( $gr->id ),
				"alex_gfilds"
			) );
			// echo "<b>last query:</b> ".$wpdb->last_query."<br>";
			// echo "<b>last result:</b> "; print_r($wpdb->last_result);
			// echo "<br><b>last error:</b> "; print_r($wpdb->last_error);

			 // print_r($gr_soclinks);
			$i = 1;
			foreach ($gr_soclinks as $item) {
				echo "гр ".$k;
				echo " поле ".$i."___".$item->post_parent." == ".$item->post_title." == ".$item->post_content."<br>"; $i++;

				$wpdb->update( $wpdb->posts,
					array( 'post_type' => "alex_grsoclink", ),
					array( 'post_parent' => $gr->id, 'post_type' => 'alex_gfilds' ),
					array( '%s' ),
					array( '%d','%s' )
				);
			}
			$k++;
		}

		echo "<hr>Группы c post_type=alex_grsoclink: <hr>";
		$k = 1;
		foreach ($groups['groups'] as $gr) {

			$gr_new_soclinks = $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM {$wpdb->posts}
				WHERE post_parent = %d
				    AND post_type = %s",
				intval( $gr->id ),
				"alex_grsoclink"
			) );
			$i = 1;
			foreach ($gr_new_soclinks as $item) {
				echo "гр ".$k;
				echo " поле ".$i."___".$item->post_parent." == ".$item->post_title." == ".$item->post_content."<br>"; $i++;
			}
			$k++;
		}

	   // echo "<pre>";
	   // print_r($wpdb->queries);
	   // echo "</pre>";

    }
}
/* ****** изменение post_type c alex_gfields на alex_grsoclink ************** */

/* ****** добавление поля website если он не был создан для группы ************** */
add_action('wp_footer','alex_test_1');
function alex_test_1(){
	if( $_SERVER['REQUEST_URI'] == "/causes/something-amazing/")
	// if( $_SERVER['REQUEST_URI'] == "/causes/something-amazing/admin/edit-details/")
	{
		echo "<h3>=========Testing========</h3>";
		$groups = BP_Groups_Group::get(array('type'=>'alphabetical','per_page'=>999));
		// print_r($groups);
		echo "Всего групп: ".$groups['total']."<hr>";
		// отбирает только id и name группы
		global $wpdb;
		foreach ($groups['groups'] as $gr) {
			$new_gr[$gr->id] = $gr->name;
		}
		ksort($new_gr);
		$num =1;
		foreach ($new_gr as $k=>$v) {
			echo $num.".__".$k. " - ".$v."<br>"; $num++;
		}

		echo "<hr>Группы c post_type=alex_grsoclink: <hr>";
		$k = 1;
		foreach ($new_gr as $key=>$v) {

			$gr_new_soclinks = $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM {$wpdb->posts}
				WHERE post_parent = %d
				    AND post_type = %s
				    AND post_title = %s",
				intval( $key),
				"alex_grsoclink",
				"Website"
			) );
			$i = 1;
			foreach ($gr_new_soclinks as $item) {
				echo "гр ".$k;
				echo " поле ".$i."___".$item->post_parent." == ".$item->post_title." == ".$item->post_content."<br>"; $i++;
			}
			$k++;
		}

		echo "<hr>Группы c post_type=alex_grsoclink,проверка на сущ: <hr>";
		$k = 1;
		foreach ($new_gr as $key=>$v) {

			$gr_new_soclinks = $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM {$wpdb->posts}
				WHERE post_parent = %d
				    AND post_type = %s
				    AND post_title = %s",
				intval( $key ),
				"alex_grsoclink",
				"Website"
			) );
			$i = 1;

			// print_r($gr_new_soclinks);
			$gr_new_soclinks = $gr_new_soclinks[0]->post_content;
			if( !empty($gr_new_soclinks)) { echo $k." ---- создан! "; echo $gr_new_soclinks."<br>";}
			else{
				$wpdb->insert(
					$wpdb->posts,
					array( 'post_parent' => $key, 'post_type' => 'alex_grsoclink', 'post_title'=> "Website", 'post_content'=>' '),
					array( '%d','%s','%s','%s' )
				);
			}
			// echo "<b>last query:</b> ".$wpdb->last_query."<br>";
			// echo "<b>last result:</b> "; print_r($wpdb->last_result);
			// echo "<br><b>last error:</b> "; print_r($wpdb->last_error);
			$k++;
		}

	   // echo "<pre>";
	   // print_r($wpdb->queries);
	   // echo "</pre>";

    }
}
/* ****** добавление поля website если он не был создан для группы ************** */


INSERT INTO `wp8k_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES (NULL, '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'http://instagram.com0', 'Instagram', '', 'publish', 'open', 'open', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '110', '', '0', 'alex_gfilds', '', '0')


add_action('wp_footer',"group_pages_scroll_to_anchor");
function group_pages_scroll_to_anchor(){
	// echo '===alex-gr===';
	echo bp_get_groups_slug();
	var_dump( bp_current_component() );
	var_dump( bp_is_groups_component() );
	// if(bp_is_group_home()) {
	// if page related group
	if( bp_is_groups_component()) {
		?>
		<script type="text/javascript">
	    jQuery(document).ready(function() {
	    	var scroll = (jQuery('#item-nav').offset().top)-110;
	    	jQuery(document.body).scrollTop(scroll);
	    	// console.log(scroll);
	    });
		</script>
		<?
	}
}

add_action('wp_footer',"highlight_group_interest_links_on_profile_member");
function highlight_group_interest_links_on_profile_member(){
	echo '===alex-gr===';
		?>
		<script type="text/javascript">
	    jQuery(document).ready(function(e) {
	    	e.preventDefault();
	    	var link = jQuery(".profile .bp-widget a");
	    	console.log(link.text());
	    });
		</script>
}


<!-- ------- -->

add_action('wp_footer',"highlight_group_interest_links_on_profile_member");
function highlight_group_interest_links_on_profile_member(){
	// echo '===alex-gr===';
		?>
		<script type="text/javascript">
	    jQuery(document).ready(function() {

	    	// console.log(document.cookie);
	    	var link = jQuery(".profile .bp-widget a");
	    	// var link = jQuery("a");

			// возвращает cookie с именем name, если есть, если нет, то undefined
			function getCookie(name) {
			  var matches = document.cookie.match(new RegExp(
			    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
			  ));
			  return matches ? decodeURIComponent(matches[1]) : undefined;
			}

			link.each(function( i,item) {
				var cur_link = jQuery(this).text();
				if( getCookie( cur_link ) == 1) { jQuery(this).css({"color":"#ca0532"}); }
				// console.log(i+"- cur link= "+cur_link);
				// console.log("cookie = "+getCookie( "and" ) );
				// console.log("cookie = "+getCookie( jQuery(this).text() ) );
				// console.log("item = "+item );
			});

	    	jQuery(link).click(function(e){
	    		// e.preventDefault();
	    		var cur_link = jQuery(this);
	    		// console.log(cur_link.text());
				// var date = new Date(new Date().getTime() + 60 * 10000);
				// document.cookie = cur_link.text()+"=1; path=/; expires=" + date.toUTCString();

				// time no set,to delete cookie if close browser
				document.cookie = cur_link.text()+"=1; path=/;";
				// console.log( getCookie(cur_link.text()) );
				// link.each(function( index ) {
				// 	if( getCookie(cur_link.text() ) == 1) { cur_link.css({"color":"blue"}); }
				// });
				// if( getCookie(cur_link.text() ) == 1) { cur_link.css({"color":"#ca0532"}); }
	    	});

	    	window.onbeforeunload = function(){jQuery.cookie('enter', null);}

	    });
		</script>
		<?php
}


############

/* ********** Load modules ******** */

$kleo_modules = array(
    'new_facebook-login.php'
);

$kleo_modules = apply_filters( 'kleo_modules', $kleo_modules );
// var_dump($kleo_modules);
// var_dump(KLEO_LIB_DIR);
// var_dump(THEME_DIR);
// echo trailingslashit(get_stylesheet_directory_uri());
// /* Sets the path to the theme library folder. */
// define( 'KLEO_LIB_DIR', trailingslashit( THEME_DIR ) . 'lib' );
// get absolute path /home/jetfire/www/dugoodr.dev/wp-content/themes/buddyapp
 $theme_url = get_template_directory()."-child/";
 // /home/jetfire/www/dugoodr.dev/wp-content/themes/buddyapp/kleo-framework/lib/function-core.php
 include_once get_template_directory()."/kleo-framework/lib/function-core.php";
// exit;

foreach ( $kleo_modules as $module ) {
    $file_path = $theme_url. 'lib/modules/' . $module;
    include_once $file_path;
}

/* ********** Load modules ******** */


        function fb_intialize(FB_response, token){
            FB.api( '/me', 'GET', {
                    fields : 'id,email,verified,name,cover,age_range,link,locale,picture,gender,first_name',
                    // fields : 'id,email,verified,name',
                    access_token : token
                },
                function(FB_userdata){
                    console.log("====alex data=====");
                    console.log("====user data=====");
                    console.log(FB_userdata);
                    console.log(FB_response);

                    jQuery.ajax({
                        type: 'POST',
                        url: fbAjaxUrl,
                        data: {"action": "fb_intialize", "FB_userdata": FB_userdata, "FB_response": FB_response},
                        success: function(user){
                            
                            console.log("========user ");
                            console.log(user);

                            if( user.error ) {
                                alert( user.error );
                            }
                            // else if( user.loggedin ) {
                            //     jQuery('.kleo-login-result').html(user.message);
                            //     if( user.type === 'login' ) {
                            //         if(window.location.href.indexOf("wp-login.php") > -1) {
                            //             window.location = user.url;
                            //         } else if (user.redirectType == 'reload') {
                            //             window.location.reload();
                            //         } else {
                            //             window.location = user.url;
                            //         }
                            //     }
                            //     else if( user.type === 'register' ) {
                            //         window.location = user.url;
                            //     }
                            // }
                        }
                    });
                }
            );
        }


     #############


            // alex
    $FB_userdata = $_REQUEST['FB_userdata'];
    echo "<h1>777_test</h1>";
    print_r($FB_userdata);
    echo "============";
    $new_fb_data = array();
    echo $new_fb_data["cover"] = $FB_userdata['cover']['source'];
    echo $new_fb_data["name"] = $FB_userdata['name'];
    echo "\r\n============";
    $ser_fb_data = serialize($new_fb_data);
    var_dump($ser_fb_data);
    echo "\r\n============";
    $b = unserialize($ser_fb_data);
    print_r($b);
    echo "\r\n============";

    global $wpdb;
    $wpdb->insert(
        $wpdb->prefix."usermeta",
        array( 'user_id' => 7777, 'meta_key'=>'_afbdata', 'meta_value'=>$ser_fb_data),
        array( '%d','%s','%s' )
    );

    exit;
    // alex

#########

// add_action("wp_head",'alex_t1',1);
// add_action("bp_before_directory_members_page",'alex_t1',1);
// add_action("bp_after_member_home_content",'get_cover_image_from_fbuser');
add_action("bp_before_member_header",'get_cover_image_from_fbuser');
function get_cover_image_from_fbuser(){
	echo "is_page ==".is_page('new_members');
	echo "is_page ==".is_page('members');
	var_dump( bp_current_component() );
	var_dump( bp_is_profile_component() );
	echo"ddd";
	// 1 - only one profile user page
	// echo "-- ".bp_is_user_profile();
	echo "--bp_is_members_directory() ".bp_is_members_directory();
	echo "\r\nbp_is_profile_component() ==".bp_is_profile_component();
	echo "\r\nbp_is_members_component() ==".bp_is_members_component();
	echo "\r\nbp_is_root_component('members') ==".bp_is_root_component('members');

	global $bp,$wpdb;
	// get profile slug ( example: profile )
	// echo "slug ".$bp->profile->slug;
	// echo "s ".bp_get_profile_slug();
	// echo "bp_is_directory() =".bp_is_directory();
	echo "\r\n=====777user_id===".$user_id = $bp->displayed_user->id;
    // array( 'user_id' => $user_ID, 'meta_key'=>'_afbdata', 'meta_value'=>$ser_fb_data),
	$table = $wpdb->prefix."usermeta";
	$get_fb_data = $wpdb->get_results( $wpdb->prepare(
		"SELECT meta_value
		FROM {$table}
		WHERE user_id = %d
		    AND meta_key = %s",
		intval( $user_id ),
		"_afbdata"
	) );
	// alex_debug(1,1,"fb",$get_fb_data);
	if( !empty($get_fb_data[0]->meta_value) ) $cover_url = unserialize($get_fb_data[0]->meta_value);
	// alex_debug(1,1,"fb",$cover_url);
	// echo "<b>last query:</b> ".$wpdb->last_query."<br>";
	// echo "<b>last result:</b> "; print_r($wpdb->last_result);
	// echo "<br><b>last error:</b> "; print_r($wpdb->last_error);
	if( !empty($cover_url['cover']) ){
	?>
	<script type="text/javascript">
	// jQuery( document ).ready(function() {
		// document.write("<style>body.buddypress div#item-header #header-cover-image {background-image: url(<?php echo $cover_url['cover'];?>); background-repeat: no-repeat; background-size: cover; background-position:center center;}</style>");
		jQuery("body.buddypress div#item-header #header-cover-image").css({"background-image":"url(<?php echo $cover_url['cover'];?>)","background-repeat":"no-repeat","background-size":"cover","background-position":"center center"});
		// jQuery("body.buddypress div#item-header #header-cover-image").hide();
		var e = document.getElementById("header-cover-image");
		console.log(e);
		// e.style.background = "yellow";
		e.style.background = "url(<?php echo $cover_url['cover'];?>) no-repeat center center";
	// });
	</script>
	<?php
	}
}

#############



add_action('wp_footer',"group_pages_scroll_to_anchor",999);
function group_pages_scroll_to_anchor(){
	echo $d = "<div id='alex-s'>dddddddd</div>";
	var_dump(bp_is_groups_component());
	// if page related group
	if( bp_is_groups_component() ||  bp_is_user() ) {
		?>
		<script type="text/javascript">
	    jQuery(document).ready(function() {
	    	var scroll = (jQuery('#item-nav').offset().top)-110;
	    	// jQuery(document.body).scrollTop(scroll);
	    	setTimeout(function(){ 
		    	// var scroll = (jQuery('#item-nav').offset().top)-110;
		    	jQuery(document.body).scrollTop(scroll);
	    		// jQuery("#alex-s").scroll(); 
	    	  	// window.scrollTo(0,1000);
	    	}, 50);
	    	// console.log(jQuery(document.body).height());
	    	console.log(scroll);
	    	console.log(jQuery(window).width());
	    });
		</script>
		<?
	}
}

################

// rev1 работает частично ( пропускает самое первое нажатие клавиши в поле поиска)
// rev2 проверяем есть ли в результатах поиска группы или участники и при клике на кнопку search делаем перенаправление на нужную опцию
// ( только не срабатывает когда пользователь слишком быстро введет слово для поиска,то есть когда не успевают показать ajax результаты)
add_action("wp_footer","alex_redirect_context_for_searchform");
function alex_redirect_context_for_searchform(){
	// alex_debug(1,0,"s",is_front_page() );
	?>
		<script type="text/javascript">
		console.log('test');

	    jQuery(document).ready(function() {

		  // var input = document.getElementById('main-search');
		  // var search_type = jQuery('.kleo-ajax-part').html();
		  // console.log(search_type);
		  // input.oninput = function() {
		  //   console.log(input.value);
		  //   // kleo-ajax-part kleo-ajax-type-members
		  //   // kleo-ajax-part kleo-ajax-type-groups
		  //   // if(search_type.hasClass("kleo-ajax-type-groups")) console.log("res "+search_type.hasClass("kleo-ajax-type-groups"));
		  // };

	    	// jQuery(".kleo-search-form").on("keypress","#main-search",function(){
	    	// // jQuery(".kleo-search-form").on("input","#main-search",function(){
	    	// 	console.log('change');
	    	// 	// console.log("input event= " +jQuery('.kleo-ajax-part').html()  );
	    	// 	console.log("input event= " +jQuery(this).html()  );
	    	// 	setTimeout(function() { console.log("kleo-ajax-part duration 1s\r\n" +jQuery('.kleo-ajax-part').html()  ); }, 700)

	    	// });

	    	jQuery(".header-search-button").on("click",function(e){
	    		e.preventDefault();
	    		var search_type = jQuery('.kleo_ajax_results').html();
	    		console.log('submit click');	
			  	// console.log("input event= \r\n" + search_type.html() +"\r\n" );  
			  	console.log("input event= \r\n" + search_type +"\r\n" );  
			  	console.log("input event= \r\n" + typeof search_type +"\r\n" );  
			  	// console.log("input event= \r\n" + search_type +"\r\n" );  
			  	// console.log(search_type.find(".kleo-ajax-type-groups").html() );	
			  	var has_groups = search_type.search(/kleo-ajax-type-groups/i);
			  	var has_members = search_type.search(/kleo-ajax-type-members/i);
			  	console.log("has_g "+has_groups);	
			  	console.log("has_m "+has_members);	
			  	console.log('v '+jQuery("#main-search").val() );
			  	console.log('t '+jQuery("#main-search").text() );
			  	var serach_text = jQuery("#main-search").val();
			  	if( has_groups >= 0 && has_members == "-1") {
			  		console.log("only gr exist");
			  		// console.log(jQuery(".kleo-search-form").attr("action");
			  		location.href="http://dugoodr.dev/causes/?s="+serach_text;
					// http://dugoodr.dev/causes/?s=ottawa			  
				}else{
					location.href="http://dugoodr.dev/members/?s="+serach_text;
				}
			  	// if( has_groups == "-1" && has_members >= 0) console.log("only memb exist");
	    	});
	    	
	    });
	    </script>
	<?php
}

################

add_action("wp_footer","alex_redirect_context_for_searchform");
function alex_redirect_context_for_searchform(){
	$site = "http://".$_SERVER['HTTP_HOST'];
	?>
		<script type="text/javascript">

	    jQuery(document).ready(function() {
	    	jQuery(".header-search-button").on("click",function(e){
	    		e.preventDefault();
	    		var search_type = jQuery('.kleo_ajax_results').html();
			  	var has_groups = search_type.search(/kleo-ajax-type-groups/i);
			  	var has_members = search_type.search(/kleo-ajax-type-members/i);
			  	var serach_text = jQuery("#main-search").val();
			  	var host = '<?php echo $site;?>';
			  	// only if exist groups results
			  	if( has_groups >= 0 && has_members == "-1") {
			  		location.href=host+"/causes/?s="+serach_text;
					// http://dugoodr.dev/causes/?s=ottawa			  
				}else{
					location.href=host+"/members/?s="+serach_text;
				}
	    	});

	    });
	    </script>
	<?php
}

############

add_action( 'wp_footer','alex_test1');
function alex_test1(){
	global $bp;
	// получить slug группы, текущий компонент,текущий action и все созданные страницы pages
	$slug = $bp->groups->slug;
	$root_slug = $bp->groups->root_slug;
	$current_component = $bp->current_component;
	$current_action = $bp->current_action;
	echo "PAGES "; print_r($bp->pages);
	echo "<h1>=== 777 slug-{$slug} / root_slug-{$root_slug} / current_component-{$current_component}
	/ current_action-{$current_action}</h1>";
	// print_r($bp);

}

function bbg_change_profile_tab_order() {
global $bp;

// unset( $bp->bp_nav['groups'] );
$bp->bp_nav['groups'] = false;
}
add_action( 'bp_setup_nav', 'bbg_change_profile_tab_order', 999 );

function a_redirect_if_changed_group_page() {
global $bp;

	// $bp->pages->groups = false;
	// $bp->groups = false; // не будет показыать группы

	// print_r($bp);

	$bp->members->slug; // выводит- members
	// выводит например- new_members...это page_name  (если компонент ассоциировали с нестандартной страницей в http://dugoodr.dev/wp-admin/admin.php?page=bp-page-settings)
	$root_slug = $bp->members->root_slug;
	$uri = $_SERVER['REQUEST_URI'];  // /members/admin7/groups/
	$has_members_slug = preg_match("/{$root_slug}/i", $uri);
	$has_groups_slug = preg_match("/groups/i", $uri);

	// var_dump($has_members_slug);
	// var_dump($has_groups_slug);

	if($has_members_slug && $has_groups_slug) {
		// echo "is groups!";
		get_template_part("404");
		exit;
	}

	// сделать перенаправление на страницу 404.php,например если мы на странице http://dugoodr.dev/members/admin7/groups/
	// if( bp_is_user_groups() ){
	// 	get_template_part("404");
	// 	exit;
	// }
}
add_action( 'wp_head', 'a_redirect_if_changed_group_page', 999 );

#####

$members = groups_get_group_members($group->id);

################
add_action("bp_after_members_loop","a_show_groups_search_result_on_members");
function a_show_groups_search_result_on_members(){

	global $wpdb,$bp;
	$table = $wpdb->prefix."bp_groups_groupmeta";
	$root_slug = $bp->groups->root_slug;

	//temp
	if ( class_exists('BP_Groups_Template') ){
		$groups_template = new BP_Groups_Template();
		$groups_template->pag_num = 3;
		alex_debug(1,1,"gr_temp",$groups_template);
		// var_dump($class->pag_links);

		$start_num = intval( ( $groups_template->pag_page - 1 ) * $groups_template->pag_num ) + 1;
		$from_num  = bp_core_number_format( $start_num );
		$to_num    = bp_core_number_format( ( $start_num + ( $groups_template->pag_num - 1 ) > $groups_template->total_group_count ) ? $groups_template->total_group_count : $start_num + ( $groups_template->pag_num - 1 ) );
		$total     = bp_core_number_format( $groups_template->total_group_count );

		if ( 1 == $groups_template->total_group_count ) {
			$message = __( 'Viewing 1 group', 'buddypress' );
		} else {
			$message = sprintf( _n( 'Viewing %1$s - %2$s of %3$s group', 'Viewing %1$s - %2$s of %3$s groups', $groups_template->total_group_count, 'buddypress' ), $from_num, $to_num, $total );
		}
		echo $message;
	}
	// var_dump( bp_has_groups() );
	echo "-bp_has_groups -";
	// var_dump( bp_has_groups( bp_ajax_querystring( 'groups' )."&per_page=3" ) );
	var_dump( bp_has_groups( bp_ajax_querystring( 'groups' )."&per_page=4&search_terms=test" ) );
	var_dump(bp_groups());
	var_dump(bp_the_group());

	?>
		<br><h3>начало цикла группы</h3>

	<div id="pag-top" class="pagination" xmlns="http://www.w3.org/1999/html">
		<div class="pag-count" id="group-dir-count-top">
			<?php bp_groups_pagination_count(); ?>
		</div>
		<div class="pagination-links" id="group-dir-pag-top">
			<?php bp_groups_pagination_links(); ?>
		</div>
	</div>
	<ul id="groups-list" class="item-list">

	<?php while ( bp_groups() ) : bp_the_group(); ?>

		<li <?php bp_group_class(); ?>>
			<div class="item-wrap">
			<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
				<div <?php echo kleo_bp_get_group_cover_attr();?>>
					<div class="item-avatar">
						<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=thumb&width=50&height=50' ); ?></a>
					</div>
				</div>
			<?php endif; ?>

			<div class="item">

					<div class="item-title"><a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a></div>
					<div class="item-meta"><span class="activity"><?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?></span></div>

				<div class="item-desc"><?php bp_group_description_excerpt(); ?></div>

				<?php

				/**
				 * Fires inside the listing of an individual group listing item.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_directory_groups_item' ); ?>

				<div class="action">
					<?php

					/**
					 * Fires inside the action section of an individual group listing item.
					 *
					 * @since BuddyPress (1.1.0)
					 */
					do_action( 'bp_directory_groups_actions' ); ?>

				</div>
				<div class="meta">

					<?php bp_group_type(); ?> / <?php bp_group_member_count(); ?>

				</div>
			</div>



			</div><!-- end item-wrap -->
		</li>

	<?php endwhile; ?>

	</ul>	<h3>конец цикла группы</h3>
	<?php

 	$search_string = esc_html($_GET['s']);
	if( !empty($search_string) ){

		// echo '<h3>this is test 888</h3>';
		
		// $context = "groups";
		// $defaults = array(
		// 	'numberposts' => 4,
		// 	'posts_per_page' => 20,
		// 	// 'post_type' => 'any',
		// 	'post_type' => $context,
		// 	'post_status' => 'publish',
		// 	'post_password' => '',
		// 	'suppress_filters' => false,
		// 	's' => $search_string
		// );

		// $defaults =  apply_filters( 'kleo_ajax_query_args', $defaults);
		// // print_r($defaults);

		// $the_query = new WP_Query( $defaults );
		// $posts = $the_query->get_posts();
		// print_r($posts);

		// $groups = groups_get_groups(array('search_terms' => $search_string, 'per_page' => $defaults['numberposts'], 'populate_extras' => false));
		// $groups = groups_get_groups(array('search_terms' => $search_string, 'per_page' => 4, 'populate_extras' => false));
		// alex_debug(0,1,"gr search ", $groups);
		?>

		<?php
		if ( $groups['total'] != 0 ) {
			?>
			<?php 

			// add_filter('bp_get_groups_pagination_count', 'add_text_to_content');
			// function add_text_to_content($message, $from_num = 1, $to_num=10, $total=20){
			// 	return $message;
			// }
			// echo apply_filters( 'bp_get_groups_pagination_count', false,1,10, 20 );

			// echo bp_get_groups_pagination_count();
			// 	function bp_get_groups_pagination_count_on_members_page() {

			// 		$pag_page = 1;
			// 		$pag_num = 20;
			// 		$start_num = intval( ( $pag_page - 1 ) * $pag_num ) + 1;
			// 		echo $from_num  = bp_core_number_format( $start_num );
			// 		$to_num    = bp_core_number_format( ( $start_num + ( $pag_num - 1 ) > $groups['total'] ) ? $groups['total'] : $start_num + ( $pag_num - 1 ) );
			// 		$total     = bp_core_number_format( $groups['total'] );

			// 		if ( 1 == $groups['total'] ) {
			// 			$message = __( 'Viewing 1 group', 'buddypress' );
			// 		} else {
			// 			$message = sprintf( _n( 'Viewing %1$s - %2$s of %3$s group', 'Viewing %1$s - %2$s of %3$s groups', $groups['total'], 'buddypress' ), $from_num, $to_num, $total );
			// 		}
			// 		return $message;
			// 	}
			// 	echo "new pag ".bp_get_groups_pagination_count_on_members_page();

			?>

		<ul id="groups-list" class="item-list">
		<?php foreach ( (array) $groups['groups'] as $group ) :?>

			<?php 
			$group_rating = $wpdb->get_row($wpdb->prepare("SELECT meta_value FROM {$table} WHERE group_id = %d AND meta_key = %s",intval($group->id),"bpgr_rating")); 
			$group_enable = $wpdb->get_row($wpdb->prepare("SELECT meta_value FROM {$table} WHERE group_id = %d AND meta_key = %s",intval($group->id),"bpgr_is_reviewable")); 
			$members = groups_get_group_members($group->id);
			// print_r($members);
			$avatar_options = array ( 'item_id' => $group->id, 'object' => 'group', 'type' => 'full', 'avatar_dir' => 'group-avatars', 'alt' => 'Group avatar', 'css_id' => 1234, 'class' => 'avatar', 'width' => 50, 'height' => 50, 'html' => true );
			$gr_avatar = bp_core_fetch_avatar($avatar_options);
			?>
			<li class="odd public is-admin is-member group-has-avatar">
				<div class="item-wrap">
					<div <?php echo kleo_bp_get_group_cover_attr($group->id);?> >
						<div class="item-avatar">
							<a href="<?php echo get_site_url()."/".$root_slug."/".$group->slug;?>"><?php echo $gr_avatar; ?></a>
						</div>
					</div>
				
				<div class="item">
					<div class="item-title"><a href="<?php echo get_site_url()."/".$root_slug."/".$group->slug;?>"><?php echo $group->name;?></a></div>
					<div class="item-meta"><span class="activity">active 1 day, 3 hours ago</span></div>
					<div class="item-desc"><p><?php echo $group->description;?></p></div>				
					<div class="action">					
		
				<?php
				if( $group_enable->meta_value == strtolower("yes") ) echo bpgr_get_plugin_rating_html($group_rating->meta_value);

				// var_dump( bpgr_directory_rating() );
				// print_r($group_rating);
				// print_r($group_enable);
				// echo kleo_bp_get_group_cover_attr();
				// var_dump(kleo_bp_get_group_cover_attr($group->id));

				?>
	 			</div>
	 				<?php // members+1 as admin user not considered ?>
					<div class="meta"><?php echo ucfirst($group->status); ?> / <?php echo $members['count']+1; ?> members</div>
				</div>

				</div><!-- end item-wrap -->
			</li>
			<?php endforeach;?>
			</ul>
			<?php
		} // end if related $groups['total'] 

	}
}

############
// from original functions.php

/*********** this code for work with twitter ***********/

add_action('wp_ajax_get_tweets', 'alex_my_action_callback');
add_action('wp_ajax_nopriv_get_tweets', 'alex_my_action_callback');
function alex_my_action_callback(){

// print_r($_POST);
$gr_id = (int)$_POST['gr_id'];
$gr_permalink = sanitize_text_field($_POST['gr_permalink']);
$gr_name = sanitize_text_field($_POST['gr_name']);
$gr_avatar = sanitize_text_field($_POST['gr_avatar']);
    // [gr_id] => 13
    // [gr_permalink] => http://dugoodr.dev/causes/linux/
    // [gr_name] => Linux
	// require 'libs/twitter/test.php';
// require 'libs/twitter/TwitterAPIExchange.php';
require 'libs/twitter/tw-api.php';
$twitter_debug = false;
$tweets = a21_tw_get_tweets($settings,$url,$getfield,$requestMethod,$twitter_debug);

if(!$twitter_debug):
	foreach ($tweets as $k => $v):
		$output['date_format'] = ago($v->created_at,1,1)."<br>";
		$output['tweet'] = $v->text;
		$output['date'] = $v->created_at."<br>";
		if(!empty($v->entities->urls[0]->url)) $output['short_link'] = $v->entities->urls[0]->url;

$html .= '
<li class="groups activity_update activity-item date-recorded-1488491970" id="activity-900">
	<div class="activity-avatar">
		<a href="http://dugoodr.dev/members/admin7/">
			<img src="http://dugoodr.dev/wp-content/uploads/avatars/1/5803f61eb836b-bpthumb.jpg" class="avatar user-1-avatar avatar-50 photo" width="50" height="50" alt="Profile picture of Admin" />
		</a>
	</div>

	<div class="activity-content">
		<div class="activity-header">
			<p><a href="http://dugoodr.dev/members/admin7/" title="Admin">Admin</a> posted tweet <a href="'.$gr_permalink.'" class=""><img src="'.$gr_avatar.'" class="avatar group-8-avatar avatar-20 photo" width="20" height="20" alt="Group cause logo of Ottawa Food Bank" /></a><a href="http://dugoodr.dev/causes/ottawa-food-bank/">'.$gr_name.'</a> <a href="http://dugoodr.dev/activity/p/168/" class="view activity-time-since" title="View Discussion"><span class="time-since" data-livestamp="2017-03-04T19:59:30+0000">'.$output['date_format'].'</span></a></p>
		</div>	
			<div class="activity-inner">
				<p>'.$output['tweet'].'</p>
			</div>	
		<div class="activity-meta">		
			<a href="?ac=168/#ac-form-168" class="button acomment-reply bp-primary-action" id="acomment-comment-168">
						Comment <span>0</span>					</a>				
				<a href="http://dugoodr.dev/activity/delete/168/?_wpnonce=d14e60b831" class="button item-button bp-secondary-action delete-activity confirm" rel="nofollow">Delete</a>
		</div>
	</div>
		<div class="activity-comments">	
				<form action="http://dugoodr.dev/activity/reply/" method="post" id="ac-form-168" class="ac-form">
					<div class="ac-reply-avatar"><img src="http://dugoodr.dev/wp-content/uploads/avatars/1/5803f61eb836b-bpthumb.jpg" class="avatar user-1-avatar avatar-50 photo" width="50" height="50" alt="Profile picture of Admin" /></div>
					<div class="ac-reply-content">
						<div class="ac-textarea">
							<textarea id="ac-input-168" class="ac-input bp-suggestions" name="ac_input_168"></textarea>
						</div>
						<input type="submit" name="ac_form_submit" value="Post" /> &nbsp; <a href="#" class="ac-reply-cancel">Cancel</a>
						<input type="hidden" name="comment_form_id" value="168" />
					</div>
									<input type="hidden" id="rt_upload_hf_comment"
				       value="1"
				       name="comment"/>
								<input type="hidden" id="rt_upload_hf_privacy"
				       value="0"
				       name="privacy"/>
								<input type="hidden" class="rt_upload_hf_upload_parent_id"
				       value="168"
				       name="upload_parent_id"/>
								<input type="hidden" class="rt_upload_hf_upload_parent_id_type"
				       value="activity"
				       name="upload_parent_id_type"/>
								<input type="hidden" id="rt_upload_hf_upload_parent_id_context"
				       value="groups"
				       name="upload_parent_id_context"/>
					<div class="rtmedia-container rtmedia-uploader-div">
					<div class="rtmedia-uploader no-js">
				<div id="rtmedia-uploader-form-activity-168">				
					<div class="rtm-tab-content-wrapper">
						<div id="rtm-file_upload-ui-activity-168" class="rtm-tab-content">
							<div class="rtmedia-plupload-container rtmedia-comment-media-main rtmedia-container clearfix"><div id="rtmedia-comment-action-update-activity-168" class="clearfix"><div class="rtm-upload-button-wrapper"><div id="rtmedia-comment-media-upload-container-activity-168"></div><button type="button" class="rtmedia-comment-media-upload" data-media_context="groups" id="rtmedia-comment-media-upload-activity-168" title="Attach Media"><span class="dashicons dashicons-admin-media"></span></button></div><input type="hidden" name="privacy" value="0" /></div></div><div class="rtmedia-plupload-notice"><ul class="plupload_filelist_content ui-sortable rtm-plupload-list clearfix" id="rtmedia_uploader_filelist-activity-168"></ul></div><input type="hidden" name="mode" value="file_upload" />							</div>
						</div>				
						<input type="hidden" id="rtmedia_upload_nonce" name="rtmedia_upload_nonce" value="dc7191db20" /><input type="hidden" name="_wp_http_referer" value="/causes/ottawa-food-bank/" />
												<input type="submit" id="rtMedia-start-upload-activity-168" name="rtmedia-upload" value="Upload" />
					</div>
				</div>
				</div>
					<input type="hidden" id="_wpnonce_new_activity_comment" name="_wpnonce_new_activity_comment" value="7e4cd8101a" /><input type="hidden" name="_wp_http_referer" value="/causes/ottawa-food-bank/" />
				</form>
		</div>
</li>';
	endforeach;
	endif;
	// echo $output['date'];
	// var_dump($output);
	echo $html;
	// echo json_encode($html);
	exit;
}

add_action("wp_footer","alex_tweet");
function alex_tweet(){

		echo $gr_id = bp_get_group_id();
		// echo "gr ".$gid;
		$group = groups_get_group($gr_id);
		echo $group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
		$avatar_options = array ( 'item_id' => $gr_id, 'object' => 'group','avatar_dir' => 'group-avatars', 'html' => false );
		echo $gr_avatar = bp_core_fetch_avatar($avatar_options);

		// print_r($bp);

echo $getfield;
// print_r($settings);

	if(bp_is_group_home()) {
		echo "<h3>this is page-test888</h3>";

		// add_action("wp_ajax_get_tweets", "alex_my_action_callback");
		// add_action("wp_ajax_nopriv_get_tweets", 'alex_my_action_callback');
		// function alex_my_action_callback(){
		// 	echo $res['res'] = "php ajax";
		// 	json_encode($res);
		// 	exit;
		// }
	?>
	<script>
	jQuery( document ).ready(function() {
	    function get_tweets(){
	    	console.log(KLEO.ajaxurl);
		var data = {
			'action': 'get_tweets',
			'gr_id': <?php echo $gr_id;?>,
			'gr_permalink': '<?php echo $group_permalink;?>',
			'gr_name': '<?php echo $group->name;?>',
			'gr_avatar': '<?php echo $gr_avatar;?>',
		};

		jQuery.ajax({
			url:KLEO.ajaxurl, // обработчик
			data:data, // данные
			type:'POST', // тип запроса
			success:function(data){
				console.log("js ok!");
				console.log(data);
				if( data ) { 
					// current_page++; // увеличиваем номер страницы на единицу
					// if (current_page == max_pages) $("#true_loadmore").remove(); // если последняя страница, удаляем кнопку
					jQuery(".activity.single-group>ul").prepend(data);
				} else {
					// $('#true_loadmore').remove(); // если мы дошли до последней страницы постов, скроем кнопку
				}
			},
			beforeSend: function(){
				// $("#loading-text").html('<a class="loading-link" href="#">Loading ...</a>');
				console.log("Loading get_tweets");
			}
		 });
		}
		get_tweets();
	});
	</script>
	<?php
	}
}

/*********** this code for work with twitter ***********/


                
// ************* doc #10.4 попытка сделать универсальное решение

// add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );
// function my_wp_nav_menu_args( $args = '' ){
    
//     print_r($args);
// }
 // echo "777";
// add_filter( 'wp_nav_menu_items', 'filter_function_name_11', 10, 2 );
// function filter_function_name_11( $items, $args ){
//     // filter...

//     var_dump($items);
// } 

// add_filter( 'wp_nav_menu_objects', 'filter_function_name_11', 10, 2 );
// function filter_function_name_11( $menu_obj, $args ){
//     // filter...
//         print_r($menu_obj);
//     print_r($args);

//     if( empty($args['walker']) ){
//     echo "<ul>";
//     echo '555<li><a title="'.$menu_obj[1]->post_title.'" href="'.$menu_obj[1]->url.'"><i class="icon-'.$menu_obj[1]->icon.'" ></i>
//      <span>'.$menu_obj[1]->title.'</span></a></li>';
//    echo '<li><a title="'.$menu_obj[2]->title.'" href="'.$menu_obj[2]->url.'"><i class="icon-'.$menu_obj[2]->icon.'" ></i>
//      <span>'.$menu_obj[2]->title.'</span></a></li>';
//      echo "</ul>";
//  }else return $menu_obj;
// }      
//  echo "777end";

//   wp_nav_menu( array(
//     'theme_location' => 'top-left',
//     'menu_class'     => 'basic-menu header-icons kleo-nav-menu',
//     'container' => false,
//     'link_before'       => '<span>',
//     'link_after'        => '</span>',
//     'depth' => 1,
//     'max_elements' => 2,
//     'walker' => '',
//     'fallback_cb' => 'kleo_header_icons_menu',
//     'echo'=>false
// ) );

/***************************************/

/* ***** temp for doc 9.4 ***** */
add_action('wp_enqueue_scripts','a21_include_css_js_for_page_edit_profile');
function a21_include_css_js_for_page_edit_profile(){

	// echo '777=='.bp_get_the_profile_group_slug();
	// echo bp_get_current_profile_group_id();
	// $args = array('profile_group_id'   => $current_profile_group_id);
	// обьект с именем,порядком,id полей xprofile
	// get all name,id,order fields xprofile group 
	$data_groups = BP_XProfile_Group::get( $args );
	foreach ($data_groups as $k => $v) {
		if( preg_match("#timeline#i",strtolower($v->name)) ) $profile_group_id = $v->id;
	}

	// is page == edit field timeline
	// if( bp_is_profile_edit() && bp_get_current_profile_group_id() == $profile_group_id){
	if( bp_is_profile_edit() ){
	// if( bp_is_user_profile() ){
		// wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
		// wp_enqueue_style( 'datepicker', "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css",array('bootstrap'));
		// echo '<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>';
	   wp_enqueue_script('datepicker',"https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js",array('jquery'),'',true);
	}
}

	echo "xxx===".bp_get_current_profile_group_id();var_dump(bp_is_user_profile());
	// echo $_SERVER['REQUEST_URI'];
	// if( !preg_match("/edit/i", $_SERVER['REQUEST_URI']) ) echo "main";


				<!-- <div class="a21_wrap_datepicker input-group date" style="position: relative;"> -->
<!-- 			<div class="a21_wrap_datepicker" style="position: relative;">
			    <input type="text" class="form-control" name="date" data-provide='datepicker' data-date-autoclose="true" data-date-container='#a21_wrap_datepicker' value="02-16-2012">
			</div>
 -->
<!--  
		<div id="a21_wrap_datepicker">
		 <input id='a21-datepicker' type="text" class="form-control" data-provide='datepicker' data-date-autoclose="true" data-date-container='#a21_wrap_datepicker'>
		 </div>
 --> 
<!--  <input data-provide="datepicker" type="text" placeholder="Add Date" name="date" class="form-control a21-datepicker" required="required" data-date-format="dd M yyyy">
 -->				<script>
					jQuery(document).ready(function () {
						// jQuery('.a21-datepicker input').datepicker({});
						// var datep = jQuery('.a21-datepicker');
						// console.log(datep.offset().top);
						// datep.datepicker({'autoclose':true});
					});
				</script>




	// echo $count_rows = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}bp_groups_calendars`");
	// получить полностью один столбец (все id)
	$ids = $wpdb->get_col("SELECT id FROM {$wpdb->prefix}bp_groups_calendars");

	foreach($ids as $id){
		echo $event_title = $wpdb->get_var( "SELECT event_title FROM {$wpdb->prefix}bp_groups_calendars` WHERE id='{$id}'");
		$event_title = strtolower($event_title);
		$event_slug = str_replace(" ", "_", $event_title);
		$query = 
		$wpdb->update(
			$wpdb->posts,
			array( 'event_slug' => $event_slug, ),
			array( '%s' )
		);
	}

/* **** as21 **** */

/* ****  извлекает числа из строки **** */
$str = 'here any 234 $ text';
$str = preg_replace("/[^0-9]/", '', $str);
/* ****  извлекает числа из строки **** */

$option = "bp_group_calendar_installed";
echo " option $option=".get_option($option);
if( delete_option( $option ) ) echo " $option - success delete";
