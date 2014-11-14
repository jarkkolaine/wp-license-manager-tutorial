(function( $ ) {
	'use strict';

    // Simple confirmation box for deleting license.
    $(function() {
        $('.delete-license').click( function() {
            return confirm( 'Are you sure you want to delete license?' );
        });
    });

})( jQuery );
