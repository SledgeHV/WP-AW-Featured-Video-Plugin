jQuery(function($){
 
    $('body').on('click', '.awfv_upload_button', function(e){
 
        e.preventDefault();

        var button = $(this);

	    var custom_uploader = wp.media({
            	title: 'Select or upload MP4 video',
            	library : {
                	type : 'video'
            	},
            	button: {
                	text: 'Use this file'
            	},
            	multiple: false
       	 	});

       	custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $(button).next().val(attachment.id);
            $('#awfv_container video').hide();
            $('#awfv_container .message').html('<p>Selected file: ' + attachment.filename + '<br><em>Don\'t forget to save changes.</em></p>' + '<video width="320" controls><source src="' + attachment.url + '" type="video/mp4"></video>');
        }).open();
    });

    /*
     * Remove image event
     */
    $('body').on('click', '.awfv_remove_button', function(){
        $(this).hide().prev().val('').prev().addClass('button').html('Select video');
        $('#awfv_container video').hide();
        return false;
    });

});