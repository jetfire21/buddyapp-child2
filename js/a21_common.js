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

		/* **** as21 ajax load part timeline data **** *
		$("#a21_load_part_timeline_data").on("click",function(e){

			e.preventDefault();
			console.log("====a21_load_part_timeline_data=====");
			var user_id = $(this).attr("data-user-id");
			console.log(user_id);
			var data = {
				'action': 'a21_load_part_timeline_data',
				'user_id':user_id
			};

			$.ajax({
				url:KLEO.ajaxurl,
				data:data, 
				type:'POST', 
				success:function(data){
					console.log("----from WP AJAX data---");
					console.log("data="+data);
					console.log(typeof data);
					// data = JSON.parse(data); 

					if( data ) { 
						$("#timeliner .timeliner").append(data);
						 $('#timeliner').timeliner();
					} else { console.log("data send with errors!");}
				}

			 });

		});
		* **** as21 ajax load part timeline data **** */


	// ///////////// add new row for bp group calendar
	// $("#a21_bgc_tasks_shifts").on("click",function(){
	$("#a21_bgc_add_new_row").on("click",function(){

		console.log("click add row");
		$("#a21_bgc_del_row").css({"display":"block"});
		// var row_i = $(".a21_number_row").length, el=1;
		// console.log( row_i );
		// if(row_i > 0) el = row_i+el;
		// console.log("el "+el);
		// var html = '<tr class="a21_event_row"> \
		// <td class="a21_number_row">\
		// 	 <input type="text" required="required" placeholder="" name="new_event_tasks['+el+'][task]" class="form-control" value="">\
		// </td>\
		// <td id="">\
		// 	 <input type="text" required="required" placeholder="" name="new_event_tasks['+el+'][shift_1]" class="form-control" value="">\
		// </td>\
		// <td>\
		// 	 <input type="text" required="required" placeholder="" name="new_event_tasks['+el+'][shift_2]" class="form-control" value="">\
		// </td>\
		// </tr>';
		// $("#a21_bgc_tasks_shifts").append(html);
		var rows = $("#a21_bgc_tasks_shifts .a21_dinam_row");
		var colls = $("#a21_bgc_tasks_shifts .a21_dinam_row:first .a21_dinam_coll");
		var html='';
		console.log("------count rows-----"+rows.length);
		console.log("=====count colls===== "+colls.length);

		if(colls.length <= 1){
			html = '<tr class="a21_dinam_row"> \
							<td class="a21_dinam_coll">\
								 <input type="text" name="new_event_tasks['+rows.length+'][task]" placeholder="Title task" />\
							</td>\
						</tr>';
		}else{
			// html = '<tr class="a21_dinam_row">\
			// 			<td class="a21_dinam_coll">\
			// 					 <input type="text" name="new_event_tasks[1][task]" class="form-control">\
			// 			</td>';
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
		// console.log("html"+html);
		$("#a21_bgc_tasks_shifts").append(html);

	});


	$("#a21_bgc_del_row").on("click",function(){ 
		var rows = $("#a21_bgc_tasks_shifts .a21_dinam_row");
		console.log(rows.length);
		if(rows.length > 1){
			// $(".a21_dinam_row").each(function(i){
			// 	console.log("i="+i+" "+$(this).html());
			// })
			$("#a21_bgc_tasks_shifts .a21_dinam_row:last-child").remove();
		}
	});

	$("#a21_bgc_del_column").on("click",function(){ 
		var colls = $("#a21_bgc_tasks_shifts th");
		// return false;
		console.log("COLLS "+colls.length);	
		if(colls.length > 1){
			// $(".a21_dinam_row").each(function(i){
			// 	console.log("i="+i+" "+$(this).html());
			// })
			$("#a21_bgc_tasks_shifts tr td:last-child, #a21_bgc_tasks_shifts tr th:last-child").remove();
		}
		if( colls.length <= 2) $("#a21_bgc_del_column").css({"display":"none"});
		console.log("COLLS "+colls.length);
	});


	$("#a21_bgc_add_new_column").on("click",function(){

		$("#a21_bgc_del_column").css({"display":"block"});
		// $(".wrap_btns_for_event_tasks").append('<div id="a21_bgc_add_new_column">- Delete Column</div>');
		var html = '';
		console.log("new column");
		var rows = $("#a21_bgc_tasks_shifts .a21_dinam_row");
		if (rows.length <= 0) { alert("Please first add a row"); return false;}
		var colls = $("#a21_bgc_tasks_shifts .a21_dinam_row:first .a21_dinam_coll");
		var th_colls = $("#a21_bgc_tasks_shifts .a21_dinam_th_coll");
		var num_coll = colls.length + 1;
		// var title_columns = $("#a21_bgc_tasks_shifts .title_columns th:nth-child("+num_coll+")");
		var title_columns = $("#a21_bgc_tasks_shifts .title_columns");
		console.log(typeof colls.length);
		console.log("------NEW COLUMN count rows-----"+rows.length);
		console.log("------NEW COLUMN count colls-----"+colls.length);
		console.log("------NEW COLUMN count th_colls-----"+th_colls.length);
		rows.each(function( i ) {
			console.log("NEW COLUMN ряд "+i);
			// console.log("counter colls "+j);
  			// console.log( i + ": " + $( this ).html() );
  			// console.log(title_columns.html());
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
		// if(s_need_cnt > 0) s_need_cnt = s_need_cnt-1;
		// $(this).parent().find(".vol_cnt").html(s_need_cnt);
		var task_id = $(this).parent().parent().attr("data-task_id");

		console.log("== CLICK add new volunteer ===\r\n"+"task_id="+task_id+" user_id="+user_id+" i="+i+"\r\n");

		console.log();
		console.log(nick);
		console.log("s_need_cnt="+s_need_cnt);
		console.log("event_id="+event_id);
		console.log("type user_id="+typeof user_id);
		console.log("task_id="+task_id);
		console.log("i"+i);
		// $(this).parent().append("<p>"+nick+"</p>");
		// if(user_id > 0) $(this).remove();

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
				console.log("----from WP AJAX data---");
				console.log("data="+data);
				console.log(typeof data);
				data = JSON.parse(data); 
				console.log(data.html);
				console.log(data.cnt_vols_signup_now);
				console.log("cnt_vols_signup_now "+ typeof data.cnt_vols_signup_now);

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
		console.log("==CLICK #a21_cancel_my_attandance===\r\n"+"task_id="+task_id+" user_id="+user_id+" i="+i+"\r\n");
		// var s_need_cnt = Number( self.attr("data-s-need-cnt") );
		var s_need_cnt = Number( self.parent().find(".vol_cnt").text() );
		console.log("s_need_cnt="+s_need_cnt);
		// console.log( "self="+self.html() );
		// console.log( self.parent().find(".link-user-id-"+user_id ).html() );
		// console.log( self.parent().html() );
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
				console.log(data.html);
				console.log(data.cnt_vols_signup_now);
				console.log("cnt_vols_signup_now "+ typeof data.cnt_vols_signup_now);
				if( data.html ) { 
					console.log("data after ajax="+data);
					s_need_cnt = s_need_cnt+1;
					// console.log( $(this).html() );
					// console.log( "self="+self.html() );
					// console.log("parent self="+self.parent().html() );
					self_parent.find(".vol_cnt").html(s_need_cnt);
					self_parent.find(".a21_cancel_my_attandance").remove();
					// console.log( self.parent().find(".link-user-id-"+user_id ).html() );
					// console.log( self.parent().find(".link-user-id-1" ).html() );
					// console.log("s_need_cnt="+s_need_cnt);
					// console.log( self_sparent.html() );
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
		// rows = rows.length+1;
		// console.log("------count rows-----"+rows);

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
	    	console.log("open---");
	    	// jQuery("#show-signup-report-modal").show();
	    	// var orig = jQuery("body").html();
	    	// console.log("open===="+orig);
	    	console.log(this);
	    	console.log(this.currItem);
	    	console.log(this.content);
	    	// console.log(this.currItem.attr('data-id'));
	    }}
    });


    //  $(".as21-send-verif-exper").click(function(e){
    // 	e.preventDefault();
    // 	console.log('send from magnificPopup');
    // 	console.log( $(this).parent().html() );
    // 	console.log( $(this).data('id') );
    // });

  });
})(jQuery);
