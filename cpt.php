<?php

/* 
 * Plugin Name: A Custom Post Type Plugin for Basic.
 * Plugin URI: http://basoro.org/
 * Description: A simple post type that adds a way to create posts for beginner!
 * Version: 0.1
 * Author: Basoro
 * Author URI: http://basoro.org/
 * License: MIT 
 */

function register_basic_post_type() {
	new BasicPostType();
}

if(is_admin()){
	add_action('init', 'register_basic_post_type');
	add_action('load-post.php', 'register_basic_post_type');
	add_action('load-post-new.php', 'register_basic_post_type');
}

class BasicPostType {

	public function __construct(){
		add_theme_support('post-thumbnails', array('basic'));
		add_action('add_meta_boxes', array($this, 'add_meta_box'));
		add_action('save_post', array($this, 'save'));
    
		register_post_type('basic',
			array(
				'labels'			=> array(
					'name'			=> __('Basics'),
					'singular_name'		=> __('Basic')
		  		),
	  			'public'			=> true,
		  		'has_archive'			=> true,
			  	'supports'			=> array( 'title', 'editor', 'thumbnail')
  			)
  		);
  	}

  	public function add_meta_box($post_type){
 		$post_types = array("basic");
	  	if(in_array($post_type, $post_types)){
		  	add_meta_box("basic-data", "Basic Data", array($this, "data_meta_box_content"), "basic", "normal", "core");
	  	}
  	}

  	public function save($post_id){
	  	if(!isset($_POST["basic_nonce"]))
		  	return $post_id;

	  	$nonce = $_POST["basic_nonce"];

	  	if(!wp_verify_nonce($nonce, "basic"))
		  	return $post_id;

  		if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
	  		return $post_id;

  		if(!current_user_can("edit_post", $post_id))
	  		return $post_id;

  		$fields = array(
	  		'name'		=> sanitize_text_field($_POST["basic_name"]),
		  	'address'	=> sanitize_text_field($_POST["basic_address"])
  		);

	  	foreach($fields as $k=>$v){
		  	update_post_meta($post_id, $k, $v);
  		}
  	}

  	public function data_meta_box_content($post){
	  	wp_nonce_field("basic", "basic_nonce");

	  	$name = get_post_meta($post->ID, "name", true);
	  	$address = get_post_meta($post->ID, "address", true);
  	?>
	  	<table class="form-table">
		  	<tr>
			  	<th><label for="basic_name"><?php _e("Name", "basic"); ?></label></th>
			  	<td><input type="text" name="basic_name" id="basic_name" value="<?php echo $name; ?>" class="regular-text"></td>
		  	</tr>
		  	<tr>
			  	<th><label for="basic_address"><?php _e("Address", "basic"); ?></label></th>
			  	<td><textarea name="basic_address" id="basic_address"><?php echo $address; ?></textarea></td>
		  	</tr>
	  	</table>
  	<?php
  	}

}

?>
