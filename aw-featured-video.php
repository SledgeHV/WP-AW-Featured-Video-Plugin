<?php

/* 
Plugin Name: AW Featured Video
Author: Aleš Walter
Author URI: http://aleswalter.cz
*/


add_filter('mime_types', 'awfv_add_mp4_mime_type');
function awfv_add_mp4_mime_type($mimetypes) {
 	$mimetypes['mp4']  = 'video/mp4';
 	return $mimetypes;
}



add_action('admin_enqueue_scripts', 'awfv_add_scripts');
function awfv_add_scripts() {
    
	if (isset($_GET['action']) && $_GET['action'] === 'edit' ) {

    	wp_enqueue_script( 'awfv', plugin_dir_url( __FILE__ ) . '/awfv.js', array('jquery'), null, false );

    	if (!did_action( 'wp_enqueue_media'))
        	wp_enqueue_media();

    }

}



/* add metabox */
add_action('admin_menu', 'awfv_meta_box');
function awfv_meta_box() {
    add_meta_box(
    	'awfv_container',
        'Featured video',
        'awfv_metabox_html',
        'post',
        'side',
        'high'
    ); 
}



/* metabox content */
function awfv_metabox_html($post) {
    $meta_key = 'awfv_video';
    $videoID  = get_post_meta( $post->ID, 'awfv_video', true );
    
 	if ($videoID)
 		echo '<video width="320" controls><source src="' . wp_get_attachment_url($videoID) . '" type="video/mp4"></video>';

 	$metaname = $meta_key;
 	$metavalue = get_post_meta($post->ID, $meta_key, true);

    $display_remove_button = 'none';
    if ($metavalue)
        $display_remove_button = 'inline-block';

    echo '
	    <div>
	        <a href="#" class="awfv_upload_button button">Select video</a>
	        <input type="hidden" name="' . $metaname . '" id="' . $metaname . '" value="' . $metavalue . '" />
	        <a href="#" class="awfv_remove_button button" style="display:' . $display_remove_button . '">Remove video</a>
	    </div>
	    <div class="message"></div>
    ';
}



/* save metabox */
add_action('save_post', 'awfv_save_metabox');
function awfv_save_metabox( $post_id ) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
        return $post_id;

    $meta_key = 'awfv_video';
    update_post_meta( $post_id, $meta_key, $_POST[$meta_key] );

    return $post_id;
}