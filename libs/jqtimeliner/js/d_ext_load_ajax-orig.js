jQuery( document ).ready(function($) {

	function alex_onadd(_data){

			console.log("onadd");
			var alex_tl_grp_id = false;
		    for (var key in grs) {
		    	if(grs[key] == _data.alex_gr_name_select) alex_tl_grp_id = key;
		    }
			var data = {
				'action': 'alex_add_timeline',
				'date': _data.date,
				'title': _data.title,
				'content': _data.content,
				'class': _data.class,
				'alex_tl_grp_id': alex_tl_grp_id
				// 'query': true_posts,
			};

			$.ajax({
				url:ajaxurl, // обработчик
				data:data, // данные
				type:'POST', // тип запроса
				success:function(data){
					if( data ) {
				      location.reload();
					} else { console.log("data send with errors!");}
				}

			 });
	}

	function alex_ondelete(_data){
		// $( "#timeliner" ).on( "click", ".readmore .btn-danger", function() {

	   		 if(!confirm("Are you sure to delete ?")) return false;
			 // console.log("--------delete li !!!!! 1---------------"+$(this).html());
			// console.log("--------delete li !!!!! 1---------------"+_data.html());

			$( "#timeliner" ).on( "click", ".readmore .btn-danger", function() {
				// console.log("--------2 delete li !!!!!---------------");

			   var html = $(this).parents("li");
			  //  console.log(html.html());
			  // return false;
			   var id = html.find(".alex_item_id").text();
			   html.hide();

					var data = {
					'action': 'alex_del_timeline',
					'id':id
				};

				$.ajax({
					url:ajaxurl, // обработчик
					data:data, // данные
					type:'POST', // тип запроса
					success:function(data){
						if( data ) {
						} else { console.log("data send with errors!");}
					}

				 });
				// end ajax
			});
	}

	function alex_onedit(_data){

		// console.log("====onedit======");
	 //    for (var key in _data) {
	 //    	console.log("key-"+key+"="+_data[key]);
	 //    }
		// return false;
		var alex_tl_grp_id = false;
	    for (var key in grs) {
	    	if(grs[key] == _data.alex_gr_name_select) alex_tl_grp_id = key;
	    }

		var data = {
			'action': 'alex_edit_timeline',
			'id': _data.id_alex,
			'date': _data.date,
			'title': _data.title,
			'content': _data.content,
			'vol_hours': _data.vol_hours,
			'class': _data.class,
			'alex_tl_grp_id': alex_tl_grp_id

		};

		$.ajax({
			url:ajaxurl, // обработчик
			data:data, // данные
			type:'POST', // тип запроса
			success:function(data){
				console.log("ajax response get success!");
				if( data ) {
					// console.log(data);
		      		location.reload();
				} else { console.log("data send with errors!");}
			}

		 });
	}

/* **** as21 ajax load part timeline data **** */
$("#a21_load_part_timeline_data").on("click",function(e){

	e.preventDefault();
	var self = $(this);
	// var num = parseInt("a4r t 4r43 43a b345b 123 cc gaeg4".replace(/\D+/g,""));
	// console.log(num);
	console.log("====a21_load_part_timeline_data=====");
	var user_id = $(this).attr("data-user-id");
	var offset = $(this).data("offset");
	console.log("offset" + offset);
	console.log(user_id);
	var data = {
		'action': 'a21_load_part_timeline_data',
		'user_id':user_id,
		'offset':offset
	};

	$.ajax({
		url:KLEO.ajaxurl,
		data:data,
		type:'POST',
		success:function(data){
			console.log("\r\n################# from WP AJAX data ######\r\n\r\n");
			console.log("data="+data);
			console.log(typeof data);
			if(data) data = JSON.parse(data);
			else $(".activity-list .load-more").hide();
			console.log("data="+data.date);

			if( data ) {
				// $("#timeliner .timeliner").append(data);
				 // var tl1 = $('#timeliner').timeliner({a21_gets:getScript});
				 // var tl1 = $('#a21_load_part_timeline_data').timeliner({a21_newItems:data});
		 			offset += 5;
					offset = self.data("offset",offset);
					console.log("offset" + offset);
					// from 0-jan 11-dec
			        var months = {Jan:"January", Feb:"February", Mar:"March", Apr:"April",
				                    May:"May", Jun:"June", Jul:"July", Aug:"August", Sep:"September",
				                    Oct:"October", Nov:"November", Dec:"December"};


					var date_fd = false, date_bd = false;

					/* **** as21 for create new date separator**** */
					var obj_short_date_fd = [], obj_short_date_bd = [],new_date_sep=[],del_same_short_date_bd=[];

					$(".date_separator:not(.alex_btn_add_new)").each(function(i,e){

						console.log("\r\n====loop .date_separator span====\r\n");
						// date_fd = $(this).text(); // January 2017
						date_fd = $(this).find("span").text(); // January 2017
						// console.log("fd item=" + i +" --- "+ date_fd);

						year_fd = parseInt(date_fd.replace(/\D+/g,"")); // 2017
						var m_fd = date_fd.substr(0,3); // Sep,Jun...
						var short_date_fd = m_fd + " "+year_fd;
						obj_short_date_fd[i] = short_date_fd;
					});

					// for (var key in data.date){

					// 	console.log("====loop date_bd=====\r\n");
					// 	console.log("=k="+key+"\r\n");
					// 	var date_bd = data.date[key];
					// 	var date_day_bd = parseInt(date_bd.substr(0,2));
					// 	var obj_short_date_bd[key] = data.date[key].substr(3);
					// 	console.log( "date_bd: "+date_bd );
					// 	console.log( "short_date_bd: "+short_date_bd );
					// }

					//  delete double short_month_year from date bd
					for (var key in data.date){
						var short_date_bd = data.date[key].substr(3);
						if(key == 0) del_same_short_date_bd[key] = short_date_bd;
						if( key > 0) {
							if(short_date_bd == del_same_short_date_bd[key]) break;
							else del_same_short_date_bd[key] = short_date_bd;
						 }
						// console.log("%%% del_same_short_date_bd[key] "+del_same_short_date_bd[key]+" %%%%% short_date_bd "+short_date_bd);
					}


					// console.log("\r\n====="+typeof obj_short_date_fd+"===\r\n");

					for (k_osdf in obj_short_date_fd){
						// console.log("\r\n====="+k_osdf+"==="+obj_short_date_fd[k_osdf]+"\r\n");

						for (var key in del_same_short_date_bd){
								var short_date_bd = data.date[key].substr(3);
								if( short_date_bd != obj_short_date_fd[k_osdf] ) {
									new_date_sep[key] = short_date_bd;
								}
								// else { new_date_sep[key] = false; break;}
								// console.log("%%% new_date_sep[key] "+new_date_sep[key]+"%%%%% short_date_bd"+short_date_bd);
								// console.log("%%% key "+key+"%%%%% ");
						}
					}

					for (var key in new_date_sep){
						// console.log("---new_date_sep[key]----"+new_date_sep[key]);
						if(new_date_sep[key]) {
							var year = parseInt(new_date_sep[key].replace(/\D+/g,"")); // 2017
							var full_month_year = months[new_date_sep[key].substr(0,3)]+" "+year;
							var last_date_sep = $(".date_separator:not(.alex_btn_add_new)").last().find("span").text();
							// console.log("last_date_sep "+last_date_sep);
							if( last_date_sep != full_month_year) $("#timeliner .timeliner").append('<div class="date_separator"><span>'+full_month_year+'</span></div>');
						}
					}
					// return false;

					/* **** as21 for create new date separator **** */

					// перебираем год,месяц и вставляем в нужное место текущую запись

					// $(".date_separator span").each(function(i,e){
					$(".date_separator:not(.alex_btn_add_new)").each(function(i,e){

						console.log("\r\n ############# loop .date_separator span ####### \r\n");
						// date_fd = $(this).text(); // January 2017
						date_fd = $(this).find("span").text(); // January 2017
						// console.log("fd item=" + i +" --- "+ date_fd);

						year_fd = parseInt(date_fd.replace(/\D+/g,"")); // 2017
						var m_fd = date_fd.substr(0,3); // Sep,Jun...
						var short_date_fd = m_fd + " "+year_fd;
						// console.log("short_date_fd ="+short_date_fd);

						// for (var m in months){
						// 		var short_m_fd = months[m].substr(0,3); // sep,jun...
						// 		console.log("m="+m +"-"+ short_m_fd);
						// 		var short_date_fd = short_m_fd +" "+ year_fd;
						// 		console.log("fd after parse: "+short_date_fd);
						// 		if(date_bd == short_date_fd) break;
						// }
						// for (var k in data.date){
					   $(this).after("<ul class='columns'></ul>");
						for (var key in data.date){

							console.log(key+" ====loop date_bd=====\r\n");
							var date_bd = data.date[key];
							var date_day_bd = parseInt(date_bd.substr(0,2));
							var short_date_bd = data.date[key].substr(3);
							// console.log( "date_bd: "+date_bd );
							// console.log( "short_date_bd: "+short_date_bd );
							// console.log("data.li[date_bd]==\r\n"+data.li[date_bd]+"\r\n\r\n");
							// if(key == 2) return false;

							var render_items_li = '';
							// if date_separator == current date

							if(short_date_fd == short_date_bd ) {

								// $(this).parent().after("<p>new el "+date_bd+"</p>");
								// var item_li = $(this).parent();
								var ul_li_items = $(".columns").eq(i+1);
								// console.log("\r\n===next==="+ul_li_items.html());
								// var count_li = ul_li_items.find("li").length;
								// var count_li = 4;
								// console.log( "count="+count_li);

							   render_items_li = render_items_li + data.li[date_bd];
							   $(".timeliner .columns").last().append(render_items_li);

							}


							// else {
							// 	// $(this).after("<p>new el error</p>");
							// 	console.log("data.date_bd======"+date_bd);
							// 	$("#timeliner .timeliner").append('<div class="date_separator"><span>'+date_bd+'</span></div>');
							// 	// break;
							// }

						}

					});
					return false;
					/*
					console.log("\r\n length ");
					var item = $(".global-timeliner").length;
					console.log( item );
					if( item > 0) $(".wrap_timeliner").append("<div class='item-timel-"+item+" global-timeliner'></div>");
					else $(".wrap_timeliner").append("<div class='item-timel-0 global-timeliner'></div>")
				  // $('#timeliner .timeliner').timeliner({a21_newItems:data,onAdd:alex_onadd, onDelete:alex_ondelete, onEdit:alex_onedit});
				  $('.item-timel-' + item).timeliner({a21_newItems:data,onAdd:alex_onadd, onDelete:alex_ondelete, onEdit:alex_onedit});
				  */

			} else { console.log("data send with errors!");}
		}

	 });

});

$("#timeliner").on("click",".li-load-ajax-del", function(){

	// console.log( $(this).html() );
	// alex_ondelete($(this));
	 	if(!confirm("Are you sure to delete ?")) return false;
	// console.log("--------delete li !!!!! 1---------------"+$(this).html());
	// console.log("--------delete li !!!!! 1---------------"+_data.html());

	   var html = $(this).parents("li");
	  //  console.log(html.html());
	  // return false;
	   var id = html.find(".alex_item_id").text();
	   html.hide();

			var data = {
			'action': 'alex_del_timeline',
			'id':id
		};

		$.ajax({
			url:ajaxurl, // обработчик
			data:data, // данные
			type:'POST', // тип запроса
			success:function(data){
				if( data ) {
				} else { console.log("data send with errors!");}
			}

		 });
		// end ajax
});

$("#timeliner").on("click",".li-load-ajax", function(){

	// console.log("come-bd-ajax="+$(this).parent().parent().html());
	var current_li = $(this).closest("li");
	var old_li = current_li.html();
	console.log("come-bd-ajax="+current_li.html());
	var grs_html = '';
	var cur_li_date = $.trim( current_li.find(".timeliner_date").text() );
	var cur_li_title = $.trim( current_li.find(".timeliner_label").text() );
	var cur_li_content = $.trim( current_li.find(".content").text() );
	var cur_li_item_id = $.trim( current_li.find(".alex_item_id").text() );
	var cur_li_all_grs = $.trim( current_li.find(".all_grs").text() );
	var cur_li_gr_name = $.trim( current_li.find("#alex_gr_name_select").text() );
	// console.log("all_grs="+cur_li_all_grs+"\r\n\r\n");
	// console.log("cur_li_gr_name="+cur_li_gr_name+"\r\n\r\n");
	var cur_li_all_grs = cur_li_all_grs.split(",");
	for (k in cur_li_all_grs){
		// console.log("k="+k+"-"+cur_li_all_grs[k]);
		if(cur_li_gr_name == cur_li_all_grs[k]) gr_selected = 'selected'; else gr_selected = '';
         grs_html = grs_html + '<option value="'+cur_li_all_grs[k]+'" '+gr_selected+'>'+cur_li_all_grs[k]+'</option>';
	}
	if(!cur_li_all_grs){
   		grs_html = '<option value="" seleted="selected">None</option>';
	}
    var formTpl =
        '\
            <div class="timeliner_element" style="float: none;">\
                <form role="form" class="form-horizontal timeline_element" >\
                <input type="hidden" name="id" class="form-control" >\
                <div class="timeline_title"> &nbsp; </div>\
                <div class="content">\
                    <div class="form-group">\
                        <label class="col-sm-2 control-label" for="form-field-2"> Date </label>\
                        <div class="col-sm-9">\
                            <input type="text" placeholder="Add Date" name="date" class="form-control datepicker" required="required" data-date-format="dd M yyyy" value="'+cur_li_date+'">\
                        </div>\
                    </div>\
                    <div class="form-group">\
                        <label class="col-sm-2 control-label" for="form-field-1"> Title </label>\
                        <div class="col-sm-9">\
                            <input type="text" placeholder="Event Title" name="title" class="form-control title" required="required" value="'+cur_li_title+'">\
                            <input type="hidden" placeholder="Event Title" name="id_alex" class="form-control id_alex" required="required" value="'+cur_li_item_id+'">\
                        </div>\
                    </div>\
                    <div class="form-group">\
                        <label class="col-sm-2 control-label" for="form-field-12"> Content </label>\
                        <div class="col-sm-9">\
                            <textarea placeholder="Add a brief description" name="content" class="form-control textarea-content" required="required">'+cur_li_content+'</textarea>\
                        </div>\
                    </div>\
                    <div class="form-group">\
                        <label class="col-sm-2 control-label" for="form-field-12"> Shade </label>\
                        <div class="col-sm-9">\
                            <select class="form-control" name="class" id="class_bg">\
                                <option value="" seleted="selected">None</option>\
                                <option value="bricky">Red</option>\
                                <option value="green">Green</option>\
                                <option value="purple">Purple</option>\
                                <option value="teal">Teal</option>\
                            </select>\
                        </div>\
                    </div>\
                    <div class="form-group">\
                        <label class="col-sm-2 control-label" for="form-field-12"> Group </label>\
                        <div class="col-sm-9">\
                            <select class="form-control select-group" name="alex_gr_name_select" id="alex_gr_name_select">\
                                '+grs_html+
                            '</select>\
                        </div>\
                    </div>\
                </div>\
                <div class="readmore">\
                    <button class="btn" type="reset"><i class="fa fa-times"></i> Cancel</button>\
                    <button class="btn btn-primary load-ajax-save-edit" type="submit"><i class="fa fa-save"></i> Save</button>\
                </div>\
                </form>\
            </div>\
        ';
        current_li.html(formTpl);
    	current_li.find('.datepicker').datepicker();

        // current_li.replaceWith(formTpl);
        $("button[type='reset']").click(function(){
        	console.log("reset====");
    		console.log("come-bd-ajax="+typeof old_li);
    		console.log("old_li="+old_li);
        	// current_li.html(current_li);
        	$(this).closest("li").html(old_li);
        });

});

$("#timeliner").on("click",".load-ajax-save-edit", function(e){
        // $(".a21_y").click(function(e){
        	e.preventDefault();
        	console.log("submit====");
        	var cur_form = $(this).closest("li");
        	var _data = {};
        	_data.id_alex = cur_form.find('.id_alex').val();
        	_data.title = cur_form.find('.title').val();
        	_data.date = cur_form.find('.datepicker').val();
        	_data.content = cur_form.find(".textarea-content").val();
        	_data.alex_gr_name_select = cur_form.find("#alex_gr_name_select").val();
        	_data.class= cur_form.find("#class_bg").val();
        	// console.log("---"+data.class);

      //   	console.log("---frm--"+cur_form.html()+"\r\n\r\n");
		    // for (var key in _data) {
		    // 	console.log("key-"+key+"="+_data[key]);
		    // }
        	// return false;
	        alex_onedit(_data);
	        // alex_onedit1(_data);
        });

/* **** as21 ajax load part timeline data **** */

    var tl = jQuery('#timeliner').timeliner({onAdd:alex_onadd, onDelete:alex_ondelete, onEdit:alex_onedit});

});
