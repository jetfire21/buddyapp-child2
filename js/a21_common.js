(function($) {
	$(document).ready(function () {

		// ///////////// for quick editing timeline
		$("#a21_add_new_row_qedit_timel").on("click",function(){

			console.log("click add new");
			var row_i = $(".a21_js").length,el=1;
			console.log( row_i );
			if(row_i > 0) el = row_i+el;
			console.log("el "+el);
			var html = '<tr class="dinam_row_js none"> \
			<td class="timel_title a21_js">\
				 <input type="text" required="required" placeholder="" name="new_data['+el+'][timel_title]" class="form-control" value="">\
			</td>\
			<td id="a21_wrap_datepicker">\
				 <input  data-date-orientation="right bottom" data-provide="datepicker" type="text" placeholder="" name="new_data['+el+'][timel_date]" class="form-control" required="required" data-date-format="dd M yyyy" value="">\
			</td>\
			<td><textarea placeholder="" required="required" name="new_data['+el+'][timel_content]" class="form-control"></textarea>\
			</td>\
			<td class="qe_color">\
			    <select class="form-control" name="new_data['+el+'][timel_class]">\
			        <option value="none" >None</option>\
			        <option value="bricky" >Red</option>\
			        <option value="green">Green</option>\
			        <option value="purple" >Purple</option>\
			        <option value="teal">Teal</option>\
			    </select>\
			</td>\
			</tr>';
			$("#a21_timeleline_quick_edit").append(html);
		});

		$(".profile-edit .datepicker.datepicker-dropdown").css({"left":"0"});
		console.log("dp");
		console.log($(".profile-edit .datepicker.datepicker-dropdown").html());
	// ///////////// for quick editing timeline


	// ///////////// add new row for bp group calendar
	// $("#a21_bgc_tasks_shifts").on("click",function(){
	$("#a21_bgc_add_new_row").on("click",function(){

		console.log("click add row");
		$("#a21_bgc_del_row").css({"display":"block"});

		var rows = $("#a21_bgc_tasks_shifts .a21_dinam_row");
		var colls = $("#a21_bgc_tasks_shifts .a21_dinam_row:first .a21_dinam_coll");
		var html='';

		if(colls.length <= 1){
			html = '<tr class="a21_dinam_row"> \
							<td class="a21_dinam_coll">\
								 <input type="text" name="new_event_tasks['+rows.length+'][task]" placeholder="Title task" />\
							</td>\
						</tr>';
		}else{
			html = '<tr class="a21_dinam_row">';
			for(var i=0; i<colls.length; i++){
				if(i == 0){
				html = html+'<td class="a21_dinam_coll">\
								<input type="text" name="new_event_tasks['+rows.length+'][task]" placeholder="Title task" />\
							</td>';
				}else{
				html = html+'<td class="a21_dinam_coll vol_cnt">\
								<input type="text" name="new_event_tasks['+rows.length+'][time_'+i+']" placeholder="2" />\
							</td>';
				}
			}
		}
		html = html+"</tr>";
		$("#a21_bgc_tasks_shifts").append(html);

	});


	$("#a21_bgc_del_row").on("click",function(){ 
		var rows = $("#a21_bgc_tasks_shifts .a21_dinam_row");
		console.log(rows.length);
		if(rows.length > 1){
			$("#a21_bgc_tasks_shifts .a21_dinam_row:last-child").remove();
		}
	});

	$("#a21_bgc_del_column").on("click",function(){ 
		var colls = $("#a21_bgc_tasks_shifts th");
		// return false;
		console.log("COLLS "+colls.length);	
		if(colls.length > 1){
			$("#a21_bgc_tasks_shifts tr td:last-child, #a21_bgc_tasks_shifts tr th:last-child").remove();
		}
		if( colls.length <= 2) $("#a21_bgc_del_column").css({"display":"none"});
		console.log("COLLS "+colls.length);
	});


	$("#a21_bgc_add_new_column").on("click",function(){

		$("#a21_bgc_del_column").css({"display":"block"});
		var html = '';
		console.log("new column");
		var rows = $("#a21_bgc_tasks_shifts .a21_dinam_row");
		if (rows.length <= 0) { alert("Please first add a row"); return false;}
		var colls = $("#a21_bgc_tasks_shifts .a21_dinam_row:first .a21_dinam_coll");
		var th_colls = $("#a21_bgc_tasks_shifts .a21_dinam_th_coll");
		var num_coll = colls.length + 1;
		var title_columns = $("#a21_bgc_tasks_shifts .title_columns");
		console.log(typeof colls.length);
		console.log("------NEW COLUMN count rows-----"+rows.length);
		console.log("------NEW COLUMN count colls-----"+colls.length);
		console.log("------NEW COLUMN count th_colls-----"+th_colls.length);
		rows.each(function( i ) {
  			if(i <= 0){			
  				html = '<td class="a21_dinam_coll vol_cnt"> <input type="text" name="new_event_tasks['+i+'][time_'+colls.length+']" placeholder="2" /></td>';
  				title_columns.append('<th class="a21_dinam_th_coll"> time '+colls.length+'<input type="text" name="new_event_tasks[time]['+th_colls.length+']" placeholder="11:00am-12:00am" /></th>');
			}else{
  				html = '<td class="a21_dinam_coll vol_cnt"> <input type="text" name="new_event_tasks['+i+'][time_'+colls.length+']" placeholder="2" /> </td>';
			}
  			$(this).append(html);
		});
	});


	// ///////////// add new row for bp group calendar

	// ///////////// add new volunteer in event
	// $(".a21_add_new_volunteer").click(function(){
	$(".a21_dinam_coll").on("click", ".a21_add_new_volunteer", function(){

		var self = $(this);
		var self_parent = self.parent();

		var nick = $(this).attr("data-nick");
		var user_id = Number( $(this).attr("data-id") );
		// var s_need_cnt = Number( $(this).attr("data-s-need-cnt") );
		var event_id = Number( $("#a21_bgc_tasks_shifts").attr("data-event-id") );
		var i = $(this).attr("data-i");
		var s_need_cnt = Number($(this).parent().find(".vol_cnt").text());
		var task_id = $(this).parent().parent().attr("data-task_id");

		var data = {
			'action': 'a21_bgc_add_new_volunteer',
			'user_id':user_id,
			'task_id':task_id,
			'event_id':event_id,
			'i':i
		};

		$.ajax({
			url:KLEO.ajaxurl,
			data:data, 
			type:'POST', 
			success:function(data){
				data = JSON.parse(data); 

				if( data ) { 
					self_parent.html(data.html);
					self_parent.addClass("yellow-cell");
					if(data.full) { self_parent.addClass("red-cell"); self_parent.removeClass("yellow-cell"); }
				} else { console.log("data send with errors!");}
			}

		 });

	});
	// ///////////// add new volunteer in event

	/* **** as21 counter and compare total volunteers and current count vol **** */
	$("#a21_bgc_tasks_shifts").on("keyup",".vol_cnt input",function(){
		var total_cnt = 0;
		console.log("change");
		var init_total_vol = $("#total-volunteers").val();
		console.log(init_total_vol);
		if(init_total_vol == "") init_total_vol = 0;
		init_total_vol = parseInt(init_total_vol);
		if(init_total_vol <= 0) alert("Field 'Total Event Volunteers Needed' is empty! Please populeted");

		$("#a21_bgc_tasks_shifts .vol_cnt input").each(function(i){
			var cur_cnt = $(this).val();
			console.log( typeof cur_cnt);
			if(cur_cnt == "") cur_cnt = 0;
			total_cnt = parseInt(total_cnt) + parseInt(cur_cnt);
			console.log("i "+i+" "+cur_cnt+ " "+ typeof cur_cnt + " " + total_cnt + " "+ typeof total_cnt);
		});
		console.log(total_cnt);
		if( total_cnt > init_total_vol && init_total_vol != 0 ) alert("Current cout volunteers-"+total_cnt+", available is - "+init_total_vol+" Please reduce the current number of volunteers");
	});
	/* **** as21 counter and compare total volunteers and current count vol **** */

	// $(".a21_cancel_my_attandance").on("click",function(){
	$(".a21_dinam_coll").on("click", ".a21_cancel_my_attandance", function(){


		var self = $(this);
		var self_parent = self.parent();
		var task_id = self.attr("data-task-id");
		var user_id = self.attr("data-user-id");
		var i = Number(self.attr("data-i"));
		var s_need_cnt = Number( self.parent().find(".vol_cnt").text() );
		var link_cur_user = self.parent().find(".link-user-id-"+user_id );

		var data = {
			'action': 'a21_cancel_my_attandance',
			'user_id':user_id,
			'task_id':task_id,
			'i':i
		};

		$.ajax({
			url:KLEO.ajaxurl,
			data:data, 
			type:'POST', 
			success:function(data){
				console.log(data);
				data = JSON.parse(data); 
				if( data.html ) { 
					console.log("data after ajax="+data);
					s_need_cnt = s_need_cnt+1;
					self_parent.find(".vol_cnt").html(s_need_cnt);
					self_parent.find(".a21_cancel_my_attandance").remove();
					link_cur_user.remove();
					self_parent.removeClass("red-cell");
					if(data.cnt_vols_signup_now > 0) self_parent.addClass("yellow-cell");
					else self_parent.removeClass("yellow-cell")
					self_parent.html(data.html);
				} else { console.log("data send with errors!");}
			}

		 });
	})

	$(".a21_bgc_user_signup").click(function(){
		$(".show-login").trigger("click");
	});

	$("#a21_timeleline_quick_edit select").click(function(){
		console.log("quick edit");
		var color = $(this).val();
		console.log ( $(this).val() );
		$(this).closest("tr").removeClass().addClass(color);
	});


	// $(".profile").on("click","#a21_experience_add_new_row",function(){
	$("#a21_experience_add_new_row").on("click",function(){

		// console.log("click experience add row");
		var rows = $("#as21_experience_volunteer .a21_dinam_row").length;
		var html ='<tr class="a21_dinam_row">\
						<td><input type="text" name="as21_new_experiences['+rows+'][title]" placeholder="Eg. This is an example item to add"></td>\
						<td><input type="text" name="as21_new_experiences['+rows+'][hours]">\
						<td><a href="#" data-id="" class="experience_del">x</a></td>\
					</tr>';

		// console.log("html"+html);
		$("#as21_experience_volunteer").append(html);

	});

	$("#as21_experience_volunteer").on("click",".experience_del",function(){
		console.log("del experience")

		var id = $(this).data("id");
		console.log( "id= "+id );
		$(this).closest('tr').remove();
		if(id == '') return false;

		var confirmation = confirm("Are you sure want to delete it?");
		console.log(confirmation);

		if( confirmation === true){

			var data = {
				'action': 'as21_experience_del',
				'id':id
			};

			$.ajax({
				url:KLEO.ajaxurl,
				data:data, 
				type:'POST', 
				success:function(data){
					// console.log("----from WP AJAX data---");
					// console.log(data);
					if( data ) { 
					} else { console.log("data send with errors!");}
				}

			 });
		}
	});

	$("#as21-reviews-badge img").click(function(){
		console.log('as21-reviews-badge click');
		$("#as21-reviews-badge img").removeClass('active');
		$(this).addClass('active');
		var badge_id = $(this).data('id');
		console.log(badge_id);
		$(".bp-user-reviews #badge_id").val(badge_id);
	});


    jQuery('.popup-modal-exper').magnificPopup({
        type: 'inline',
        preloader: false,
        focus: '#username',
        modal: true,
        callbacks: {
	    open: function() {
	    	$('#ve_loading').remove();
	    }}
    });


    $('.ve_send_notif').click(function(e){
    	e.preventDefault();
    	$('#ve_loading, #message').remove();
    	$('.a21-system-box').remove();
    	console.log('---work action #ve_send_notif---');
    	var f_data = $(this).closest('#ve_form_notif').serialize();
    	console.log( f_data );
		f_data = f_data+'&action=as21_ve_send_notif';

		$.ajax({
			url:KLEO.ajaxurl,
			data:f_data, 
			type:'POST', 
			success:function(data){
				console.log(data);
				data = $.parseJSON(data);
				if( data.success == 'ok' ) { 
					console.log("---step ok---");
					$.magnificPopup.close();
					$('#item-header').append('<div id="message" class="bp-template-notice updated"><p>Notifications successfully sent</p></div>');
			    	$('#ve_loading').remove();
				} 				
				if( data.success == 'exist' ) { 
					$.magnificPopup.close();
					$('#item-header').append('<div id="message" class="bp-template-notice error"><p>You have already requested verification for this experience</p></div>');
			    	$('#ve_loading').remove();
				} 		
				if( data.success == 'nouser' ) { 
					$('#ve_form_notif').prepend("<div class='a21-system-box'>You did not select a user</div>");
				} 
			},

		 });

    });


    $('.ve_send_via_email').click(function(e){
    	e.preventDefault();
    	$('#ve_loading, #message').remove();
    	$('#ve_form_via_email .a21-system-box').remove();
    	console.log('---work action #ve_send_via_email---');
    	var f_data = $(this).closest('#ve_form_via_email').serialize();
		f_data = f_data+'&action=as21_ve_send_via_email';

		$.ajax({
			url:KLEO.ajaxurl,
			data:f_data, 
			type:'POST', 
			success:function(data){
				data = $.parseJSON(data);
				if( data.error ) { 
					$('#ve_form_via_email').prepend("<div class='a21-system-box'>"+data.error+"</div>");
			    	$('#ve_loading').remove();
				} 
				if( data.success ) { 
					$.magnificPopup.close();
					$('#item-header').append('<div id="message" class="bp-template-notice updated"><p>'+data.success+"</p></div>");
			    	$('#ve_loading').remove();
				}
				if( data.warning == 'exist' ) { 
					console.log("---step exist---");
					$.magnificPopup.close();
					$('#item-header').append('<div id="message" class="bp-template-notice error"><p>You have already requested verification for this experience</p></div>');
			    	$('#ve_loading').remove();
				} 
				if(data.tmp_info) console.log(data.tmp_info);
			},
			beforeSend: function(){
				$('#ve_form_via_email .submit').append("<img id='ve_loading' src='"+path.url+"/images/loading.gif' />");
			},

		 });

    });

    $("#ve_form_via_email .ve_email_addresses_wrap span").click(function(){
    	console.log('click :after');
    	$('#ve_email_addresses').val("");
    });

    if( $(".as21-right-group-admins").length == 0 )  $("#item-actions .group-admins").css({"display":"block"});

  });
})(jQuery);
