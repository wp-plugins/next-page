<?php
/*
Plugin Name: Next Page
Plugin URI: http://sillybean.net/code/wordpress/next-page/
Description: Provides shortcodes and template tags for next/previous navigation in pages. 
Version: 1.2
Author: Stephanie Leary
Author URI: http://sillybean.net/

Changelog:
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

// Hook for adding admin menus
add_action('admin_menu', 'next_page_add_pages');

function next_page_add_pages() {
    // Add a new submenu under Options:
	add_options_page('Next Page', 'Next Page', 8, 'next-page', 'next_page_options');
	// set defaults
	add_option('next_page__before_prev_link', '<div class="alignleft">', '', 'yes');
	add_option('next_page__prev_link_text', 'Previous: %title%', '', 'yes');
	add_option('next_page__after_prev_link', '</div>', '', 'yes');
	add_option('next_page__before_parent_link', '<div class="aligncenter">', '', 'yes');
	add_option('next_page__parent_link_text', 'Up one level: %title%', '', 'yes');
	add_option('next_page__after_parent_link', '</div>', '', 'yes');
	add_option('next_page__before_next_link', '<div class="alignright">', '', 'yes');
	add_option('next_page__next_link_text', 'Next: %title%', '', 'yes');
	add_option('next_page__after_next_link', '</div>', '', 'yes');
	add_option('next_page__exclude', '', '', 'yes');

}

// displays the options page content
function next_page_options() {
	if ( current_user_can('manage_options') ) {  
	// variables for the field and option names 
		$hidden_field_name = 'next_page_submit_hidden';
	
		// See if the user has posted us some information
		// If they did, this hidden field will be set to 'Y'
		if( $_POST[ $hidden_field_name ] == 'Y' ) {
				// Save the posted value in the database
			update_option('next_page__before_prev_link', $_POST['next_page__before_prev_link']);
			update_option('next_page__prev_link_text', $_POST['next_page__prev_link_text']);
			update_option('next_page__after_prev_link', $_POST['next_page__after_prev_link']);
			
			update_option('next_page__before_parent_link', $_POST['next_page__before_parent_link']);
			update_option('next_page__parent_link_text', $_POST['next_page__parent_link_text']);
			update_option('next_page__after_parent_link', $_POST['next_page__after_parent_link']);
			
			update_option('next_page__before_next_link', $_POST['next_page__before_next_link']);
			update_option('next_page__next_link_text', $_POST['next_page__next_link_text']);
			update_option('next_page__after_next_link', $_POST['next_page__after_next_link']);
			
			update_option('next_page__exclude', $_POST['next_page__exclude']);
	
			// Put an options updated message on the screen
	?>
	<div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
    
	<?php  } // Now display the options editing screen ?>
	
    <div class="wrap">
	<form method="post" id="next_page_form">
    <?php wp_nonce_field('update-options'); ?>

    <h2><?php _e( 'Next Page Options'); ?></h2>
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
    
    <p><label><?php _e("Exclude pages: "); ?><br />
    <input type="text" name="next_page__exclude" id="next_page__exclude" value="<?php echo stripslashes(htmlentities(get_option('next_page__exclude'))); ?>" /><br />
	<small><?php _e("Enter page IDs separated by commas."); ?></small></label></p>
       
    <div style="float: left; width: 30%; margin-right: 5%;">
    <h3><?php _e("Previous Page Display:"); ?></h3>
    <p><label><?php _e("Before previous page link: "); ?><br />
    <input type="text" name="next_page__before_prev_link" id="next_page__before_prev_link" value="<?php echo stripslashes(htmlentities(get_option('next_page__before_prev_link'))); ?>" />  </label></p>
    
    <p><label><?php _e("Previous page link text: <small>Use %title% for the page title</small>"); ?><br />
    <input type="text" name="next_page__prev_link_text" id="next_page__prev_link_text" value="<?php echo stripslashes(htmlentities(get_option('next_page__prev_link_text'))); ?>" />  </label></p>
    
    <p><label><?php _e("After previous page link: "); ?><br />
    <input type="text" name="next_page__after_prev_link" id="next_page__after_prev_link" value="<?php echo stripslashes(htmlentities(get_option('next_page__after_prev_link'))); ?>" />  </label></p>
    <p>Shortcode: <strong>[previous]</strong><br />
    Template tag: <strong>&lt;?php previous_link(); ?&gt;</strong></p>
    </div>
    
    <div style="float: left; width: 30%; margin-right: 5%;">
    <h3><?php _e("Parent Page Display:"); ?></h3>
    <p><label><?php _e("Before parent page link: "); ?><br />
    <input type="text" name="next_page__before_parent_link" id="next_page__before_parent_link" value="<?php echo stripslashes(htmlentities(get_option('next_page__before_parent_link'))); ?>" />  </label></p>
    
    <p><label><?php _e("Parent page link text: <small>Use %title% for the page title</small>"); ?><br />
    <input type="text" name="next_page__parent_link_text" id="next_page__parent_link_text" value="<?php echo stripslashes(htmlentities(get_option('next_page__parent_link_text'))); ?>" />  </label></p>
    
    <p><label><?php _e("After parent page link: "); ?><br />
    <input type="text" name="next_page__after_parent_link" id="next_page__after_parent_link" value="<?php echo stripslashes(htmlentities(get_option('next_page__after_parent_link'))); ?>" />  </label></p>
    <p>Shortcode: <strong>[parent]</strong><br />
    Template tag: <strong>&lt;?php parent_link(); ?&gt;</strong></p>
    </div>
    
    <div style="float: left; width: 30%;">
    <h3><?php _e("Next Page Display:"); ?></h3>
    <p><label><?php _e("Before next page link: "); ?><br />
    <input type="text" name="next_page__before_next_link" id="next_page__before_next_link" value="<?php echo stripslashes(htmlentities(get_option('next_page__before_next_link'))); ?>" />  </label></p>
    
    <p><label><?php _e("Next page link text: <small>Use %title% for the page title</small>"); ?><br />
    <input type="text" name="next_page__next_link_text" id="next_page__next_link_text" value="<?php echo stripslashes(htmlentities(get_option('next_page__next_link_text'))); ?>" />  </label></p>
    
    <p><label><?php _e("After next page link: "); ?><br />
    <input type="text" name="next_page__after_next_link" id="next_page__after_next_link" value="<?php echo stripslashes(htmlentities(get_option('next_page__after_next_link'))); ?>" />  </label></p>
    <p>Shortcode: <strong>[next]</strong><br />
    Template tag: <strong>&lt;?php next_link(); ?&gt;</strong></p>
    </div>
    
    <input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="next_page__before_prev_link,
    next_page__prev_link_text,
    next_page__after_prev_link,
    next_page__before_parent_link,
    next_page__parent_link_text,
    next_page__before_next_link,
    next_page__next_link_text,
    next_page__next_link_text,
    next_page__after_next_link" />
    
	<p class="submit">
	<input type="submit" name="submit" class="button-primary" value="<?php _e('Update Options'); ?>" />
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
	$pages = array();
	foreach ($pagelist as $page) {
	   $pages[] += $page->ID;
	}
	return $pages;
}

function next_page() {
	global $post;
	$exclude = get_option('next_page__exclude');
	$pages = flatten_page_list($exclude);
	$current = array_search($post->ID, $pages);
	$nextID = $pages[$current+1];
	
	$before_link = stripslashes(get_option('next_page__before_next_link'));
	$linkurl = get_permalink($nextID);
	$title = get_the_title($nextID);
	$linktext = get_option('next_page__next_link_text');
	if (strpos($linktext, '%title%') !== false) 
		$linktext = str_replace('%title%', $title, $linktext);
	$after_link = stripslashes(get_option('next_page__after_next_link'));
	
	$link = $before_link . '<a href="' . $linkurl . '" title="' . $title . '">' . $linktext . '</a>' . $after_link;
	return $link;
} 

function prev_page() {
	global $post;
	$exclude = get_option('next_page__exclude');
	$pages = flatten_page_list($exclude);
	$current = array_search($post->ID, $pages);
	$prevID = $pages[$current-1];
	
	$before_link = stripslashes(get_option('next_page__before_prev_link'));
	$linkurl = get_permalink($prevID);
	$title = get_the_title($prevID);
	$linktext = get_option('next_page__prev_link_text');
	if (strpos($linktext, '%title%') !== false) 
		$linktext = str_replace('%title%', $title, $linktext);
	$after_link = stripslashes(get_option('next_page__after_prev_link'));
	
	$link = $before_link . '<a href="' . $linkurl . '" title="' . $title . '">' . $linktext . '</a>' . $after_link;
	return $link;
} 

function parent_page() {
	global $post;
	$parentID = $post->post_parent;
	
	$exclude = array(get_option('next_page__exclude'));
	if (in_array($parentID, $exclude)) return false;
	else {
		$before_link = stripslashes(get_option('next_page__before_parent_link'));
		$linkurl = get_permalink($parentID);
		$title = get_the_title($parentID);
		$linktext = get_option('next_page__parent_link_text');
		if (strpos($linktext, '%title%') !== false) 
			$linktext = str_replace('%title%', $title, $linktext);
		$after_link = stripslashes(get_option('next_page__after_parent_link'));
		
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