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
	console.log("offset=" + offset);
	console.log('user_id='+user_id);
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
			console.log("\r\n################# data from WP AJAX ######\r\n\r\n");
			// console.log("data="+data);
			console.log('type data='+typeof data);
			data = JSON.parse(data); 

			if( data ) {
				timeliner1.add(data).render();
	 			offset += 5;
				console.log("after ajax offset=" + offset);
				offset = self.data("offset",offset);
			} else { $(".activity-list .load-more").hide(); console.log("data is empty!"); }
		}

	 });

});


/* **** as21 ajax load part timeline data **** */

    var timeliner1 = jQuery('#timeliner').timeliner({onAdd:alex_onadd, onDelete:alex_ondelete, onEdit:alex_onedit});

});
