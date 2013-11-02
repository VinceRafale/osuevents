jQuery(document).ready(function() {
	jQuery('.subsubsub a.tab').click(function(e){	
		e.preventDefault();	
		jQuery( this ).parents( '.subsubsub' ).find( '.current' ).removeClass( 'current' );
 		jQuery( this ).addClass( 'current' );
		// If "All" is clicked, show all.
 			if ( jQuery( this ).hasClass( 'all' ) ) {
 				jQuery( '#wpbody-content .widgets-holder-wrap' ).show();
 				jQuery( '#wpbody-content .widgets-holder-wrap .widget' ).show();
 				
 				return false;
 			}

 			// If "Updates Available" is clicked, show only those with updates.
 			if ( jQuery( this ).hasClass( 'has-upgrade' ) ) {
 				jQuery( '#wpbody-content .widget_div' ).hide();
 				jQuery( '#wpbody-content .widget_div.has-upgrade' ).show();

 				jQuery( '.widgets-holder-wrap' ).each( function ( i ) {
 					if ( ! jQuery( this ).find( '.has-upgrade' ).length ) {
 						jQuery( this ).hide();
 					} else {
 						jQuery( this ).show();
 					}
 				});
 				
 				return false;
 			} else {
 				jQuery( '#wpbody-content .widget_div' ).show(); // Restore all widgets.
 			}
 			
 			// If the link is a tab, show only the specified tab.
 			var toShow = jQuery( this ).attr( 'href' );			
 			jQuery( '.widgets-holder-wrap:not(' + toShow + ')' ).hide();
 			jQuery( '.widgets-holder-wrap' + toShow ).show();
 			
 			return false;
	});
	
	jQuery( '#wpbody-content .open-close-all a' ).click( function ( e ) {
 			var status = 'closed';
 			
 			if ( jQuery( this ).attr( 'href' ) == '#open-all' ) {
 				status = 'open';
 			}
 			
 			var components = [];
	 		jQuery( '#wpbody-content .widget_div' ).each( function ( i ) {
	 			var obj = jQuery( this );
	 			var componentToken = obj.attr( 'id' ).replace( '#', '' );
	 			components.push( componentToken );
	 			
	 			if ( status == 'open' ) {
		 			obj.addClass( 'open' ).removeClass( 'closed' );
		 		} else {
		 			obj.addClass( 'closed' ).removeClass( 'open' );
		 		}
	 		});	
	 		
 			return false;
 		});
	
});