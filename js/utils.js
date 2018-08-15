var $j = jQuery.noConflict();

$j( document ).ready( function() {
	"use strict";

	// Initialize Filtered Record List
	initFilteredRecordList();

	// Toggle Record List filter dropdown
	$j( '.mob-filter-button' ).on( 'click', function( event ) {
		var $this = $j( this );
		var $list = $this.next( 'ul' );
		$list.toggleClass( 'active' );
		event.stopPropagation();
	});
	
	$j( '.mob-filter-button + ul span' ).on( 'click', function( event ) {
		var $this = $j( this );
		var $list = $this.closest( 'ul' );
		$list.toggleClass( 'active' );
	});
	
	$j( 'html' ).click( function() {
		$j( '.mob-filter-button + ul' ).removeClass( 'active' );
	});
	
	// Initialize Record Photo Slider
	$j( '.mob-flexslider' ).flexslider( {
		animation: 'slide',
		smoothHeight: true,
		controlNav: false, 
		prevText: '',
		nextText: '',
	});
	
});

function initFilteredRecordList() {
	"use strict";

	if( $j( '.mob-record-list-holder-outer.filtered' ).length ) {
		
		var mixer = mixitup( '.mob-record-list-holder-outer.filtered', {
			controls: {
		        scope: 'local'
		    },
			selectors: {
		        target: '.mix_filtered'
		    },
		    animation: {
			    enable: false
    		}
		});
	}
};

