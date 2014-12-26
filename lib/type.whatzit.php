<?php

class WhatzitPostType {

	public function __construct(){
		add_theme_support('post-thumbnails', array('whatzit'));
		add_action('add_meta_boxes', array($this, 'add_meta_box'));
		add_action('save_post', array($this, 'save'));
    
		register_post_type('whatzit',
			array(
				'labels'			=> array(
					'name'			=> __('Whatzits'),
					'singular_name'		=> __('Whatzit')
		  		),
	  			'public'			=> true,
		  		'has_archive'			=> true,
			  	'supports'			=> array(
		  			'title',
		  			'editor',
		  			'thumbnail',
		  			'size',
		  			'weight'
		  		)
  			)
  		);
  	}

  	public function add_meta_box($post_type){
 		$post_types = array("whatzit");
	  	if(in_array($post_type, $post_types)){
		  	add_meta_box("whatzit", "Whatzit Data", array($this, "render_meta_box_content"), "whatzit", "normal", "core");
	  	}
  	}

  	public function save($post_id){
	  	if(!isset($_POST["whatzit_nonce"]))
		  	return $post_id;

	  	$nonce = $_POST["whatzit_nonce"];

	  	if(!wp_verify_nonce($nonce, "whatzit"))
		  	return $post_id;

  		if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
	  		return $post_id;

  		if(!current_user_can("edit_post", $post_id))
	  		return $post_id;

  		$fields = array(
	  		'size'		=> sanitize_text_field($_POST["whatzit_size"]),
		  	'weight'	=> sanitize_text_field($_POST["whatzit_weight"])
  		);

	  	foreach($fields as $k=>$v){
		  	update_post_meta($post_id, $k, $v);
  		}
  	}

  	public function render_meta_box_content($post){
	  	wp_nonce_field("whatzit", "whatzit_nonce");

	  	$size = get_post_meta($post->ID, "size", true);
	  	$weight = get_post_meta($post->ID, "weight", true);
  	?>
	  	<table class="form-table">
		  	<tr>
			  	<th><label for="whatzit_size"><?php _e("Size", "whatzit"); ?></label></th>
			  	<td><input type="text" name="whatzit_size" id="whatzit_size" value="<?php echo $size; ?>" class="regular-text"></td>
		  	</tr>
		  	<tr>
			  	<th><label for="whatzit_weight"><?php _e("Weight", "whatzit"); ?></label></th>
			  	<td><textarea name="whatzit_weight" id="whatzit_weight"><?php echo $weight; ?></textarea></td>
		  	</tr>
	  	</table>
  	<?php
  	}

}

?>
