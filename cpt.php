<?php

class TiredbugPostType {

	public function __construct(){
		add_theme_support('post-thumbnails', array('tiredbug'));
		add_action('add_meta_boxes', array($this, 'add_meta_box'));
		add_action('save_post', array($this, 'save'));
    
		register_post_type('tiredbug',
			array(
				'labels'			=> array(
					'name'			=> __('Tiredbugs'),
					'singular_name'		=> __('Tiredbug')
		  		),
	  			'public'			=> true,
		  		'has_archive'			=> true,
			  	'supports'			=> array( 'title', 'editor', 'thumbnail')
  			)
  		);
  	}

  	public function add_meta_box($post_type){
 		$post_types = array("tiredbug");
	  	if(in_array($post_type, $post_types)){
		  	add_meta_box("tiredbug-data", "Tiredbug Data", array($this, "data_meta_box_content"), "tiredbug", "normal", "core");
	  	}
  	}

  	public function save($post_id){
	  	if(!isset($_POST["tiredbug_nonce"]))
		  	return $post_id;

	  	$nonce = $_POST["tiredbug_nonce"];

	  	if(!wp_verify_nonce($nonce, "tiredbug"))
		  	return $post_id;

  		if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
	  		return $post_id;

  		if(!current_user_can("edit_post", $post_id))
	  		return $post_id;

  		$fields = array(
	  		'name'		=> sanitize_text_field($_POST["tiredbug_name"]),
		  	'address'	=> sanitize_text_field($_POST["tiredbug_address"])
  		);

	  	foreach($fields as $k=>$v){
		  	update_post_meta($post_id, $k, $v);
  		}
  	}

  	public function data_meta_box_content($post){
	  	wp_nonce_field("tiredbug", "tiredbug_nonce");

	  	$name = get_post_meta($post->ID, "name", true);
	  	$address = get_post_meta($post->ID, "address", true);
  	?>
	  	<table class="form-table">
		  	<tr>
			  	<th><label for="tiredbug_name"><?php _e("Name", "tiredbug"); ?></label></th>
			  	<td><input type="text" name="tiredbug_name" id="tiredbug_name" value="<?php echo $name; ?>" class="regular-text"></td>
		  	</tr>
		  	<tr>
			  	<th><label for="tiredbug_address"><?php _e("Address", "tiredbug"); ?></label></th>
			  	<td><textarea name="tiredbug_address" id="tiredbug_address"><?php echo $address; ?></textarea></td>
		  	</tr>
	  	</table>
  	<?php
  	}

}

?>
