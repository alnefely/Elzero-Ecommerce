$(document).ready(function () {

	'use strict';

	// Dashboard
	$('.toggle-info').on ("click", function() {
		$(this).toggleClass('selected').parent().next('.panel-body').slideToggle(400);

		if($(this).hasClass('selected')){
			$(this).html('<i class="fa fa-minus fa-lg"></i>'); 
		}else{
			$(this).html('<i class="fa fa-plus fa-lg"></i>');
		}
	});

	// trigger The Selectboxit
	$("select").selectBoxIt({
		autoWidth: false,
	});

	// Hide Placeholder On Form focus
	$('[placeholder]').focus(function () {

		$(this).attr('data-text', $(this).attr('placeholder') );

		$(this).attr('placeholder', '');
	
	}).blur(function (){

		$(this).attr('placeholder', $(this).attr('data-text'));

	})
//////////////////////////////////////////////////////////////////////////////
	// Add Asterisk On Required Field
	$("input").each(function () {

		if ($(this).attr('required') == 'required'){

			$(this).after('<span class="asterisk">*</span>');

		}

	});
//////////////////////////////////////////////////////////////////////////////

	//Convert Password Field To Text Field On Hover
	var passField = $('.password');

	$('.show-pass').hover( function(){

		passField.attr('type', 'text');

	}, function (){

		passField.attr('type', 'password');

	});

////////////////////////////////////////////////////////////////////

	// Confirmation Message On Button

	$('.confirm').click(function (){

		return confirm('Are You Sure.?');

	});

////////////////////////////////////////////////////////////////////

	// Category View Option
	$(".cat h3").on("click", function () {

		$(this).next(".full-view").fadeToggle(200);

	});

	$(".option span").on("click", function() {

		$(this).addClass('active').siblings('span').removeClass('active');

		if( $(this).data('view') === 'full' ) {

			$('.cat .full-view').slideDown(500);

		} else {

			$('.cat .full-view').slideUp(500);

		}

	});

	// Show Delete Button On Child Cats

	$(".chiled-link").hover(function () {

		$(this).find(".show-delete").fadeIn(300);

	}, function () {

		$(this).find(".show-delete").fadeOut(200);

	}); 


});