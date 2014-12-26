<?php

	/* 
	 * Plugin Name: A Custom Post Type Plugin for Basic
	 * Plugin URI: http://basoro.org/
	 * Description: A simple post type that adds a way to create posts for beginner!
	 * Version: 0.1
	 * Author: Basoro
	 * Author URI: http://basoro.org/
	 * License: MIT 
	 */

	require_once 'lib/type.whatzit.php';

	function register_whatzit_post_type() {
		new WhatzitPostType();
	}

	if(is_admin()){
		add_action('init', 'register_whatzit_post_type');
		add_action('load-post.php', 'register_whatzit_post_type');
		add_action('load-post-new.php', 'register_whatzit_post_type');
	}

?>
