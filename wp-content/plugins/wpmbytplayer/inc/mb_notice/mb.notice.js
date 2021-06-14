/*******************************************************************************
 *
 * mb.notice
 * Author: pupunzi
 * Creation date: 19/11/16
 *
 ******************************************************************************/

jQuery.fn.mb_dismiss_notice = function(){

	var notice = this;
	var name_space = notice.data("namespace");
	var id = notice.attr("id");

	if(!name_space)
		return;

	jQuery.ajax({
		type : "post",
		dataType : "json",
		url : ajaxurl,
		data : {action: "mb_ignore_notice", name_space : name_space, notice_id : id},
		success:function(resp){
			console.debug(resp)
		}
	});

};

jQuery(function(){
	jQuery(".notice button.notice-dismiss").on("click", function(){
		var notice = jQuery(this).parent(".notice");
		notice.mb_dismiss_notice();
	});
});
