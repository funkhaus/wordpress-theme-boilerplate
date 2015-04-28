var funkhausAdmin = {

    init: function() {
        funkhausAdmin.navMenuTweaks();
        funkhausAdmin.autoRefresh();
    },
    
    navMenuTweaks: function(){
		
		// Show the URL of menu items when hovering over wp_nav_menu box
		var url;		
		jQuery('#nav-menu-meta').on('mouseenter', 'label.menu-item-title', function(){
				// On mouse in
				url = jQuery(this).siblings('input.menu-item-url').val();
				jQuery(this).attr('title', url);
			}
		);
				    
    },

	autoRefresh: function(){

		// In the admin backend when a new parent is selected from the dropdown, automatically trigger 'save draft'
		jQuery('.wp-admin #pageparentdiv #parent_id').change(function(e){
			funkhausAdmin.saveDraft();
		});

	},

	saveDraft: function(){
		var $count = 0;

		function loopCount(){
		    	if ( !jQuery('#save-action input[type="submit"]').is(':disabled') ) {
					jQuery('#save-action input[type="submit"]').trigger('click');
		    	} else if ($count < 10) {
				    setTimeout(function() {
				    	$count++;
			    		loopCount();
				    }, 500);
		    	}
		}
		loopCount();
	}

};
jQuery(document).ready(function(){
	funkhausAdmin.init();	
});