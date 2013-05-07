/*
 * Javascript file for handling the pagination functionality for the past events
 * version: 0.16
 */

jQuery(document).ready(function($) {

	$('.event_paginate').live('click',function(event){
		event.preventDefault();

		var data = $('#event_search_code').attr('data');
		var current_page = $(this).attr('current_page');

		data = "action=past_events_pagination&current_page="+current_page+"&"+data;

		$('#event_content').html("<div class='ajx_loading'>&nbsp;</div>");
		$.ajax( {
			type: "POST",
			url: ctlt_past_event_ajax_pagination.ajaxurl,
			data: data,
			dataType: "html",
			success: function(response) {
				$('#event_wrapper').html(response);
			}
		});
	});
});