jQuery( document ).ready( function(){
	jQuery( 'body' ).on( 'click', '#cbk-settings-signin-btn', function( e ){
		e.preventDefault();
		var el = jQuery('#cbk-settings-signin-btn');
		buttonOption(el);
		raiseError(false, true);
		jQuery.ajax( {
			url:cbk_ajax_object.cbk_ajax_url,
			method:'post',
			dataType:'json',
			data:{
				action:'cbk_signin_v1',
				login: jQuery( '#cbk-settings-login-login-input' ).val(),
				password: jQuery( '#cbk-settings-login-password-input' ).val()
			},
			success:function( response ){
				buttonOption(el, true);
				if (response.result == 'error') {
					raiseError(response.data);
				} else {
					window.location.reload();
				}
			}
		} );
		return false;
	} );

	jQuery( 'body' ).on( 'click', '#cbk-settings-signup-btn', function( e ){
		e.preventDefault();
		var el = jQuery('#cbk-settings-signup-btn');
		buttonOption(el);
		raiseError(false, true);
		jQuery.ajax( {
			url:cbk_ajax_object.cbk_ajax_url,
			method:'post',
			dataType:'json',
			data:{
				action:'cbk_signup_v1',
				country: jQuery( '#cbk-settings-signup-country' ).val(),
				login: jQuery( '#cbk-settings-signup-login-input' ).val(),
				password: jQuery( '#cbk-settings-signup-password-input' ).val()
			},
			success:function( response ){
				buttonOption(el, true);
				if (response.result == 'error') {
					raiseError(response.data);
				} else {
					window.location.reload();
				}
			}
		} );
		return false;
	} );

	jQuery( 'body' ).on( 'click', '#cbk-settings-signout-btn', function( e ){
		e.preventDefault();
		jQuery.ajax( {
			url:cbk_ajax_object.cbk_ajax_url,
			method:'post',
			dataType:'json',
			data:{
				action:'cbk_signout_v1'
			},
			success:function( response ){
				window.location.reload();
			}
		} );
		return false;
	} );

	jQuery( 'body' ).on( 'click', '#cbk-settings-save-btn', function( e ){
		e.preventDefault();
		var el = jQuery('#cbk-settings-save-bt');
		buttonOption(el);
		jQuery.ajax( {
			url:cbk_ajax_object.cbk_ajax_url,
			method:'post',
			dataType:'json',
			data:{
				action:'cbk_save_v1',
				siteid: jQuery( '#cbk-settings-site-select' ).val()
			},
			success:function( response ){
				buttonOption(el, true);
				window.location.reload();
			}
		} );
		return false;
	} );
} );

function raiseError (data, hide) {
	if (hide) {
		jQuery( '.cbk-settings-error-container' ).addClass('cbk-hide');
	} else {
		jQuery( '.cbk-settings-error-container' ).removeClass('cbk-hide');
		jQuery( '.cbk-settings-error-container p' ).text('Ошибка: ' + data.message);
	}
}

function buttonOption (el, hide) {
	if (hide) {
		el.text( el.data('text') );
		el.attr('id', el.data('id') );
		el.removeClass('cbk-settings-submit-wait');
	} else {
		el.data('text', el.text());
		el.data('id', el.attr('id'));
		el.addClass('cbk-settings-submit-wait');
		el.text('пожалуйста, подождите');
		el.attr('id',false);
	}
}