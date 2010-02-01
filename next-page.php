<?php
/*
Plugin Name: Next Page
Plugin URI: http://sillybean.net/code/wordpress/next-page/
Description: Provides shortcodes and template tags for next/previous navigation in pages. 
Version: 1.3
Author: Stephanie Leary
Author URI: http://sillybean.net/

Changelog:
= 1.3 = 
* Revised for settings API
* Internationalization (January 31, 2010)
= 1.2 = 
* Added option to exclude pages by ID
* Improved handling of special characters (September 16, 2009)
= 1.1 =
* Added security check before allowing users to manage options
* Fixed typo in template tags shown on options page (August 3, 2009)
= 1.0 = 
* First release (July 4, 2009)

Copyright 2009  Stephanie Leary  (email : steph@sillybean.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('admin_menu', 'next_page_add_pages');

register_activation_hook(__FILE__, 'next_page_activation');
function next_page_activation() {
	// remove old options
	$oldoptions = array();
	$oldoptions[] = get_option('next_page__before_prev_link');
	$oldoptions[] = get_option('next_page__prev_link_text');
	$oldoptions[] = get_option('next_page__after_prev_link');
	
	$oldoptions[] = get_option('next_page__before_parent_link');
	$oldoptions[] = get_option('next_page__parent_link_text');
	$oldoptions[] = get_option('next_page__after_parent_link');
	
	$oldoptions[] = get_option('next_page__before_next_link');
	$oldoptions[] = get_option('next_page__next_link_text');
	$oldoptions[] = get_option('next_page__after_next_link');
	
	$oldoptions[] = get_option('next_page__exclude');
	
	delete_option('next_page__before_prev_link');
	delete_option('next_page__prev_link_text');
	delete_option('next_page__after_prev_link');

	delete_option('next_page__before_parent_link');
	delete_option('next_page__parent_link_text');
	delete_option('next_page__after_parent_link');

	delete_option('next_page__before_next_link');
	delete_option('next_page__next_link_text');
	delete_option('next_page__after_next_link');

	delete_option('next_page__exclude');
	
	// set defaults
	$options = array();
	$options['before_prev_link'] = '<div class="alignleft">';
	$options['prev_link_text'] = __('Previous:', 'next-page').' %title%';
	$options['after_prev_link'] = '</div>';
	
	$options['before_parent_link'] = '<div class="aligncenter">';
	$options['parent_link_text'] = __('Up one level:', 'next-page').' %title%';
	$options['after_parent_link'] = '</div>';
	
	$options['before_next_link'] = '<div class="alignright">';
	$options['next_link_text'] = __('Next:', 'next-page').' %title%';
	$options['after_next_link'] = '</div>';
	
	$options['exclude'] = '';
	
	// set new option
	add_option('next_page', array_merge($oldoptions, $options), '', 'yes');
}

// when deactivated, remove option
register_deactivation_hook( __FILE__, 'next_page_delete_options' );

function html_import_delete_options() {
	delete_option('next_page');
}

// i18n
$plugin_dir = basename(dirname(__FILE__)). '/languages';
load_plugin_textdomain( 'next_page', 'wp-content/plugins/' . $plugin_dir, $plugin_dir );

function next_page_plugin_actions($links, $file) {
 	if ($file == 'next-page/next-page.php' && function_exists("admin_url")) {
		$settings_link = '<a href="' . admin_url('options-general.php?page=next-page') . '">' . __('Settings', 'next-page') . '</a>';
		array_unshift($links, $settings_link); 
	}
	return $links;
}
add_filter('plugin_action_links', 'next_page_plugin_actions', 10, 2);

add_action('admin_init', 'register_next_page_options' );
function register_next_page_options(){
	register_setting( 'next_page', 'next_page' );
}

function next_page_add_pages() {
    // Add a new submenu under Options:
	add_options_page('Next Page', 'Next Page', 8, 'next-page', 'next_page_options');
}

// displays the options page content
function next_page_options() {
	if ( current_user_can('manage_options') ) {  

	?>	
    <div class="wrap">
	<form method="post" id="next_page_form" action="options.php">
		<?php settings_fields('next_page');
		$options = get_option('next_page'); ?>

    <h2><?php _e( 'Next Page Options', 'next-page'); ?></h2>
    
    <p><label><?php _e("Exclude pages: ", 'next-page'); ?><br />
    <input type="text" name="next_page[exclude]" id="exclude" 
		value="<?php echo stripslashes(htmlentities($options['exclude'])); ?>" /><br />
	<small><?php _e("Enter page IDs separated by commas.", 'next-page'); ?></small></label></p>
       
    <div style="float: left; width: 30%; margin-right: 5%;">
    <h3><?php _e("Previous Page Display:", 'next-page'); ?></h3>
    <p><label><?php _e("Before previous page link: ", 'next-page'); ?><br />
    <input type="text" name="next_page[before_prev_link]" id="before_prev_link" 
		value="<?php echo stripslashes(htmlentities($options['before_prev_link'])); ?>" />  </label></p>
    
    <p><label><?php _e("Previous page link text: <small>Use %title% for the page title</small>", 'next-page'); ?><br />
    <input type="text" name="next_page[prev_link_text]" id="prev_link_text" 
		value="<?php echo stripslashes(htmlentities($options['prev_link_text'])); ?>" />  </label></p>
    
    <p><label><?php _e("After previous page link: ", 'next-page'); ?><br />
    <input type="text" name="next_page[after_prev_link]" id="after_prev_link" 
	value="<?php echo stripslashes(htmlentities($options['after_prev_link'])); ?>" />  </label></p>
    <p>Shortcode: <strong>[previous]</strong><br />
    Template tag: <strong>&lt;?php previous_link(); ?&gt;</strong></p>
    </div>
    
    <div style="float: left; width: 30%; margin-right: 5%;">
    <h3><?php _e("Parent Page Display:", 'next-page'); ?></h3>
    <p><label><?php _e("Before parent page link: ", 'next-page'); ?><br />
    <input type="text" name="next_page[before_parent_link]" id="before_parent_link" 
		value="<?php echo stripslashes(htmlentities($options['before_parent_link'])); ?>" />  </label></p>
    
    <p><label><?php _e("Parent page link text: <small>Use %title% for the page title</small>", 'next-page'); ?><br />
    <input type="text" name="next_page[parent_link_text]" id="parent_link_text" 
		value="<?php echo stripslashes(htmlentities($options['parent_link_text'])); ?>" />  </label></p>
    
    <p><label><?php _e("After parent page link: ", 'next-page'); ?><br />
    <input type="text" name="next_page[after_parent_link]" id="after_parent_link" 
		value="<?php echo stripslashes(htmlentities($options['after_parent_link'])); ?>" />  </label></p>
    <p>Shortcode: <strong>[parent]</strong><br />
    Template tag: <strong>&lt;?php parent_link(); ?&gt;</strong></p>
    </div>
    
    <div style="float: left; width: 30%;">
    <h3><?php _e("Next Page Display:", 'next-page'); ?></h3>
    <p><label><?php _e("Before next page link: ", 'next-page'); ?><br />
    <input type="text" name="next_page[before_next_link]" id="before_next_link" 
		value="<?php echo stripslashes(htmlentities($options['before_next_link'])); ?>" />  </label></p>
    
    <p><label><?php _e("Next page link text: <small>Use %title% for the page title</small>", 'next-page'); ?><br />
    <input type="text" name="next_page[next_link_text]" id="next_link_text" 
		value="<?php echo stripslashes(htmlentities($options['next_link_text'])); ?>" />  </label></p>
    
    <p><label><?php _e("After next page link: ", 'next-page'); ?><br />
    <input type="text" name="next_page[after_next_link]" id="after_next_link" 
		value="<?php echo stripslashes(htmlentities($options['after_next_link'])); ?>" />  </label></p>
    <p>Shortcode: <strong>[next]</strong><br />
    Template tag: <strong>&lt;?php next_link(); ?&gt;</strong></p>
    </div>
    
	<p class="submit">
	<input type="submit" name="submit" class="button-primary" value="<?php _e('Update Options', 'next-page'); ?>" />
	</p>
	</form>
	</div>
<?php 
	} // if user can
} // end function next_page_options() 

// make the magic happen
function flatten_page_list($exclude = '') {
	$args = 'sort_column=menu_order&sort_order=asc';
	if (!empty($exclude)) $args .= '&exclude='.$exclude;
	$pagelist = get_pages($args);
	$mypages = array();
	foreach ($pagelist as $thispage) {
	   $mypages[] += $thispage->ID;
	}
	return $mypages;
}

function next_page() {
	global $post;
	$options = get_option('next_page');
	$exclude = $options['exclude'];
	$pagelist = flatten_page_list($exclude);
	$current = array_search($post->ID, $pagelist);
	$nextID = $pagelist[$current+1];
	
	$before_link = stripslashes($options['before_next_link']);
	$linkurl = get_permalink($nextID);
	$title = get_the_title($nextID);
	$linktext = $options['next_link_text'];
	if (strpos($linktext, '%title%') !== false) 
		$linktext = str_replace('%title%', $title, $linktext);
	$after_link = stripslashes($options['after_next_link']);
	
	$link = $before_link . '<a href="' . $linkurl . '" title="' . $title . '">' . $linktext . '</a>' . $after_link;
	return $link;
} 

function prev_page() {
	global $post;
	$options = get_option('next_page');
	$exclude = $options['exclude'];
	$pagelist = flatten_page_list($exclude);
	$current = array_search($post->ID, $pagelist);
	$prevID = $pagelist[$current-1];
	
	$before_link = stripslashes($options['before_prev_link']);
	$linkurl = get_permalink($prevID);
	$title = get_the_title($prevID);
	$linktext = $options['prev_link_text'];
	if (strpos($linktext, '%title%') !== false) 
		$linktext = str_replace('%title%', $title, $linktext);
	$after_link = stripslashes($options['after_prev_link']);
	
	$link = $before_link . '<a href="' . $linkurl . '" title="' . $title . '">' . $linktext . '</a>' . $after_link;
	return $link;
} 

function parent_page() {
	global $post;
	$options = get_option('next_page');
	$parentID = $post->post_parent;
	
	$exclude = array($options['next_page__exclude']);
	if (in_array($parentID, $exclude)) return false;
	else {
		$before_link = stripslashes($options['before_parent_link']);
		$linkurl = get_permalink($parentID);
		$title = get_the_title($parentID);
		$linktext = $options['parent_link_text'];
		if (strpos($linktext, '%title%') !== false) 
			$linktext = str_replace('%title%', $title, $linktext);
		$after_link = stripslashes($options['after_parent_link']);
		
		$link = $before_link . '<a href="' . $linkurl . '" title="' . $title . '">' . $linktext . '</a>' . $after_link;
		return $link;
	}
}

// template tags
function previous_link() {
	echo prev_page();
}

function next_link() {
	echo next_page();
}

function parent_link() {
	echo parent_page();
}

// shortcodes
add_shortcode('previous', 'prev_page');
add_shortcode('next', 'next_page');
add_shortcode('parent', 'parent_page');
?>