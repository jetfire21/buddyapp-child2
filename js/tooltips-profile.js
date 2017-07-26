jQuery( document ).ready(function() {
	
	 function as21_tooltip_position(id,edge){

	  	  console.log('-------start js tooltip_position ------------');
	  	 // jQuery("body").scrollTo(0);
	  	  var toolt_width = 320;
	  	  // var zindex = zindex ? zindex : 998;
	  	  if(edge) edge = edge;
	  	  else edge = 'top';
	  	  // var manual_offset = 150;
		  // var el = jQuery("#tooltips-name");
		  var el = jQuery("#"+id);
		  el.css({"display":"table"});
		  // console.log()
		  var position = el.offset();
	  	  // var count_tooltips = jQuery(".wp-pointer").length;
	  	  var count_tooltips = jQuery(".wp-pointer").length ? jQuery(".wp-pointer").length+1 : 1;
	  	  var text = text;
	  	  var tooltip = '';
	  	  var html = '';
		//  	  console.log('edge='+edge);
		//   console.log(el.length);
		// console.log(id);
		// console.log('target width-'+el.width());
		// console.log('target height-'+el.height());
		// console.log(position);
		//  	  console.log('count_tooltips='+count_tooltips);

		  if(el.length){

  		  	  tooltip = jQuery("#wp-pointer-"+id);
			// console.log('======target element exist!=====');
			// console.log("edge tooltip="+edge);
			// 	  console.log('id----- ' +id);
			// 	  console.log('--tooltip height='+tooltip.height());
			// 	  // console.log('--tooltip height='+tooltip.parent().height());
			// 	  console.log('--tooltip html='+tooltip.html() );
  		  	  // console.log('full height tooltip name'+jQuery('#wp-pointer-tooltips-name').height());
  		  	  // console.log('full height tooltip mission'+jQuery('#wp-pointer-tooltips-mission').height());

  		  	  // var tooltip = jQuery("#wp-pointer-a");
			  // if(edge == 'left') var pos_left = position.left+el.width()/2;
			  if(edge == 'left') {
				// var total_width = toolt_width + manual_offset;
				var total_width = toolt_width + el.width();
				// console.log('total_width='+total_width);

  	 		     if(jQuery(window).width() < total_width) { pos_left = position.left; pos_top = position.top+el.height();
  	 		     	tooltip.removeClass('wp-pointer-left');
  	 		     	tooltip.addClass('wp-pointer-top');
  	 		     	// console.log('if window.width < total_width');
  	 			 }
  	 		     else{
  	 		     	tooltip.removeClass('wp-pointer-top');
  	 		     	tooltip.addClass('wp-pointer-left');
				  	 // pos_left = position.left+manual_offset;
				  	 pos_left = position.left+el.width();
				  	 pos_top = position.top;
				  	 // var tooltip_offset = tooltip.offset();
				  	 // console.log(tooltip_offset);
   	 		     	// console.log('if window.width > total_width');
  	 		     }
			  }
			  else if(edge == 'bottom'){
			  	// pos_left = position.left; pos_top = position.top-tooltip.height()-el.height();
			  	pos_left = position.left; pos_top = position.top-tooltip.height();
			  }
			  else {
			  	pos_left = position.left; pos_top = position.top+el.height();
			  	// console.log('pos_top '+pos_top);
			  	// console.log( jQuery('#item-header-wrap').offset() );
			  	// console.log( jQuery('#tooltips-socilal-links').position() );
			  }

			  if( id == 'tooltips-socilal-links') pos_top = 246; // exception,cause element in fixed block
			  // console.log('pos_left='+pos_left);
			  // console.log('pos_top tooltip ='+pos_top);
			  // tooltip.css({"top":pos_top,"left":pos_left,"display":"block"});
			  tooltip.css({"top":pos_top,"left":pos_left});
			  // tooltip.css({"top":pos_top,"left":pos_left,"width":toolt_width+"px","position":"absolute","display":"block","z-index":zindex});
			  // jQuery("#wp-pointer-a").css({"top":pos_top,"left":pos_left,"width":toolt_width+"px","position":"absolute","display":"block"});

			  // console.log("window width="+jQuery(window).width());
			  if(jQuery(window).width()>751) jQuery("#wp-pointer-tooltips-socilal-links").css({"position":"fixed"});
			  else jQuery("#wp-pointer-tooltips-socilal-links").css({"position":"absolute"});

		 }
	}

  	// console.log('tooltip_js!!!!'+tooltip_js);
  	// for(tip in tooltip_js){ 	console.log(tip+' === '+tooltip_js[tip].id);	} 
  	// console.log(tooltip_js.length); 
   // console.log('first-'+tooltip_js[0].id);
   jQuery("#wp-pointer-"+tooltip_js[0].id).css({'display':'block'});

  	var tooltips_offset = [];
  	for(tip in tooltip_js){
  		as21_tooltip_position(tooltip_js[tip].id,tooltip_js[tip].edge);
  		// tooltips_offset[tip] = tooltip_js[tip].id;
  		tooltips_offset[tip] = jQuery("#wp-pointer-"+tooltip_js[tip].id).offset().top;
  	}
  	// console.log(tooltips_offset);
	jQuery(window).resize(function(){
	  	for(tip in tooltip_js){
	  		// console.log(tip+' = '+tooltip_js[tip].id);
	  		as21_tooltip_position(tooltip_js[tip].id,tooltip_js[tip].edge);
  		}
	});

  	/*
	as21_r("tooltips-name",'bottom');
	jQuery(window).resize(function(){
		as21_r("tooltips-name",'bottom');
	 });
	*/

	/*
	// dismiss only one tooltip
	jQuery("body").on('click','.wp-pointer',function(){

		// console.log('tootip close');
		jQuery(this).closest(".wp-pointer").remove();
		var target_id = jQuery(this).closest(".wp-pointer").attr("id");
		target_id = target_id.replace('wp-pointer-','');
		// console.log( 'target_id-'+target_id );
		// return false;

		var data = { 'action': 'as21_dismiss_tooltip','id_target':target_id,'id_user':user_id};
		// console.log(data.id_user);	console.log('ajaxurl-'+ajaxurl);  console.log(data);
		// return false;
		jQuery.ajax({
			url:ajaxurl, 
			data:data, 
			type:'POST', 
			success:function(data){
			if( data ) { 
				// console.log(data); console.log('success ajax'); 
			}
			else { console.log("data send with errors!");}
			}
		});

	});
	*/

	// dismiss all tooltips an once
	jQuery(".wp-pointer .close").click(function(){

		console.log('tootip close');
		jQuery(".wp-pointer").remove();
		// var target_id = jQuery(this).closest(".wp-pointer").attr("id");
		// target_id = target_id.replace('wp-pointer-','');
		// console.log( 'target_id-'+target_id );
		// return false;

		var data = { 'action': 'as21_dismiss_all_tooltips','id_user':user_id};
		// console.log(data.id_user);	console.log('ajaxurl-'+ajaxurl);  console.log(data);
		// return false;
		jQuery.ajax({
			url:ajaxurl, 
			data:data, 
			type:'POST', 
			success:function(data){
				if( data ) { 
					// console.log(data); console.log('success ajax'); 
				}
				else { console.log("data send with errors!");}
			}
		});

	});
	

	jQuery(".wp-pointer button").click(function(){ 
		console.log('----next------');
		// console.log(jQuery(this).closest(".wp-pointer").next().html());
		jQuery(this).closest(".wp-pointer").remove();
		jQuery(this).closest(".wp-pointer").next().css({'display':'block'});
 		// console.log('scrollTop-'+ jQuery(this).scrollTop() );
 		// console.log('scrollTop-'+ jQuery(this).html() );
 		var step = '';
 		if(tooltips_offset) { 
			step = parseInt(jQuery(this).data("step") );
			 // console.log(step);  	
	 		if(step != (tooltips_offset.length-1) ) {
	 			// console.log('move to next tip '+(step+1) +' '+tooltips_offset[step+1]);
	 			// console.log(tooltip_js[step+1].id);
 			   jQuery("#wp-pointer-"+tooltip_js[step+1].id).css({'display':'block'});
	 			jQuery("body").scrollTo(tooltips_offset[step+1]-90);
	 		}
 		}
	});

});
