(function($) {
	$(document).ready(function () {

		// ///////////// for quick editing timeline
		$("#a21_add_new_row_qedit_timel").on("click",function(){
			console.log("click add new");
			var row_i = $(".a21_js").length,el=1;
			console.log( row_i );
			if(row_i > 0) el = row_i+el;
			console.log("el "+el);
			var html = '<tr> \
			<td class="timel_title a21_js">\
				 <input type="text" required="required" placeholder="" name="new_data['+el+'][timel_title]" class="form-control" value="">\
			</td>\
			<td id="a21_wrap_datepicker">\
				 <input  data-date-orientation="right bottom" data-provide="datepicker" type="text" placeholder="" name="new_data['+el+'][timel_date]" class="form-control" required="required" data-date-format="dd M yyyy" value="">\
			</td>\
			<td><textarea placeholder="" required="required" name="new_data['+el+'][timel_content]" class="form-control"></textarea>\
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

	$("#a21_bgc_add_new_column").on("click",function(){

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
	$(".a21_add_new_volunteer").click(function(){
		console.log("add new volunteer");
		var nick = $(this).attr("data-nick");
		var user_id = Number( $(this).attr("data-id") );
		var event_id = Number( $("#a21_bgc_tasks_shifts").attr("data-event-id") );
		var i = $(this).attr("data-i");
		var cnt = $(this).parent().find(".vol_cnt").html();
		var task_id = $(this).parent().parent().attr("data-task_id");
		console.log();
		console.log(nick);
		console.log(cnt);
		console.log("event_id="+event_id);
		console.log("type user_id="+typeof user_id);
		console.log("task_id="+task_id);
		console.log("i"+i);
		$(this).parent().append("<p>"+nick+"</p>");
		if(user_id > 0) $(this).remove();

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
				console.log(data);
				if( data ) { 
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

	});
})(jQuery);
