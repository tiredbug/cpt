<?php

class CptPostType {

	public function __construct(){
		add_theme_support('post-thumbnails', array('cpt'));
		add_action('add_meta_boxes', array($this, 'add_meta_box'));
		add_action('save_post', array($this, 'save'));
    
		register_post_type('cpt',
			array(
				'labels'			=> array(
					'name'			=> __('Cpts'),
					'singular_name'		=> __('Cpt')
		  		),
	  			'public'			=> true,
		  		'has_archive'			=> true,
			  	'supports'			=> array( 'title', 'editor', 'thumbnail')
  			)
  		);
  	}

  	public function add_meta_box($post_type){
 		$post_types = array("cpt");
	  	if(in_array($post_type, $post_types)){
		  	add_meta_box("cpt-data", "Cpt Data", array($this, "data_meta_box_content"), "cpt", "normal", "core");
	  	}
  	}

  	public function save($post_id){
	  	if(!isset($_POST["cpt_nonce"]))
		  	return $post_id;

	  	$nonce = $_POST["cpt_nonce"];

	  	if(!wp_verify_nonce($nonce, "cpt"))
		  	return $post_id;

  		if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
	  		return $post_id;

  		if(!current_user_can("edit_post", $post_id))
	  		return $post_id;

  		$fields = array(
	  		'name'		=> sanitize_text_field($_POST["cpt_name"]),
		  	'address'	=> sanitize_text_field($_POST["cpt_address"])
  		);

	  	foreach($fields as $k=>$v){
		  	update_post_meta($post_id, $k, $v);
  		}
  	}

  	public function data_meta_box_content($post){
	  	wp_nonce_field("cpt", "cpt_nonce");

	  	$name = get_post_meta($post->ID, "name", true);
	  	$address = get_post_meta($post->ID, "address", true);
  	?>
	  	<table class="form-table">
		  	<tr>
			  	<th><label for="cpt_name"><?php _e("Name", "cpt"); ?></label></th>
			  	<td><input type="text" name="cpt_name" id="cpt_name" value="<?php echo $name; ?>" class="regular-text"></td>
		  	</tr>
		  	<tr>
			  	<th><label for="cpt_address"><?php _e("Address", "cpt"); ?></label></th>
			  	<td><textarea name="cpt_address" id="cpt_address"><?php echo $address; ?></textarea></td>
		  	</tr>
	  	</table>
  	<?php
  	}

}

?>
