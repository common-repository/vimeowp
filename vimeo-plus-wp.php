<?php
/*
Plugin Name: Vimeo+WP
Plugin URI: http://geekdesigngirl.com/products-page/wordpress-plugins/vimeowp/
Description: A widget to easily display your videos from Vimeo
Version: 1.0
Author: The GeekDesignGirl Project
Author URI: http://geekdesigngirl.com

Copyright 2010 The GeekDesignGirl Project (email: nikki@geekdesigngirl.com)

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


function vimeo_plus_wp() {
	add_options_page('Vimeo+WP Options', 'Vimeo+WP', 'manage_options', 'vimeo_plus_wp','vimeo_plus_wp_options');
}

function vimeo_plus_wp_options() {
	//must check that the user has the required capability 
    if (!current_user_can('manage_options')) {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names 
    $opt_name = 'vimeo_plus_wp_username';
    $hidden_field_name = 'vimeo_plus_wp_hidden';
    $data_field_name = 'vimeo_plus_wp_username';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );

        // Put an settings updated message on the screen

?>
<div class="updated"><p><strong><?php _e('You\'re settings have been saved.', 'vimeo_plus_wp' ); ?></strong></p></div>
<?php

    }
    echo "<h2>" . __( 'Vimeo+WP Plugin Settings', 'vimeo_plus_wp' ) . "</h2>";

    // settings form
    
    ?>

		<form name="form1" method="post" action="">
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

		<p><?php _e("Vimeo username:", 'vimeo_plus_wp' ); ?> 
			<input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="20">
		</p>
		<hr />

		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
		</p>

		</form>

<?php
}

function vimeo_plus_wp_widget_Admin()
{
	if ( $_POST["vimeo_plus_wp-submit"] ) {
		$title = strip_tags(stripslashes($_POST["vimeo_plus_wp_title"]));
		$videos = strip_tags(stripslashes($_POST["vimeo_plus_wp_videos"]));
		$portrait = strip_tags(stripslashes($_POST["vimeo_plus_wp_portrait"]));
		$bio = strip_tags(stripslashes($_POST["vimeo_plus_wp_bio"]));
		$img = strip_tags(stripslashes($_POST["vimeo_plus_wp_img"]));
		$width = strip_tags(stripslashes($_POST["vimeo_plus_wp_width"]));
		$height = strip_tags(stripslashes($_POST["vimeo_plus_wp_height"]));
		update_option('vimeo_plus_wp_title',$title);
		update_option('vimeo_plus_wp_portrait',$portrait);
		update_option('vimeo_plus_wp_videos',$videos);
		update_option('vimeo_plus_wp_bio',$bio);
		update_option('vimeo_plus_wp_img',$img);
		update_option('vimeo_plus_wp_width',$width);
		update_option('vimeo_plus_wp_height',$height);
	}	
		
	
	
	?><form method="post" action="">	

	<p><label for="vimeo_plus_wp_title"><?php _e('Title:'); ?> <input id="vimeo_plus_wp_title" name="vimeo_plus_wp_title" type="text" value="<?php echo get_option('vimeo_plus_wp_title'); ?>" /></label></p>
 	<p><label for="vimeo_plus_wp_videos"><?php _e('Show <em>X</em> videos:'); ?> <input id="vimeo_plus_wp_videos" name="vimeo_plus_wp_videos" size="2" maxlength="2" type="text" value="<?php echo get_option('vimeo_plus_wp_videos'); ?>" /></label></p>
	<p><label for="vimeo_plus_wp_img"><?php _e('Show image thumbnails only?'); ?> <input id="vimeo_plus_wp_img" name="vimeo_plus_wp_img" type="checkbox" value="1"<?php if(get_option('vimeo_plus_wp_img')=='1') { echo ' checked="checked"'; } ?> /></label></p>
	<p><label for="vimeo_plus_wp_width"><?php _e('Width:'); ?> <input id="vimeo_plus_wp_width" name="vimeo_plus_wp_width" size="4" maxlength="4" type="text" value="<?php echo get_option('vimeo_plus_wp_width'); ?>" />pixels</label></p>
	<p><label for="vimeo_plus_wp_height"><?php _e('Height:'); ?> <input id="vimeo_plus_wp_height" name="vimeo_plus_wp_height" size="4" maxlength="4" type="text" value="<?php echo get_option('vimeo_plus_wp_height'); ?>" />pixels</label></p>
	<p><label for="vimeo_plus_wp_portrait"><?php _e('Show user portrait?'); ?> <input id="vimeo_plus_wp_portrait" name="vimeo_plus_wp_portrait" type="checkbox" value="1"<?php if(get_option('vimeo_plus_wp_portrait')=='1') { echo ' checked="checked"'; } ?> /></label></p>
	<p><label for="vimeo_plus_wp_bio"><?php _e('Show user bio?'); ?> <input id="vimeo_plus_wp_bio" name="vimeo_plus_wp_bio" type="checkbox" value="1"<?php if(get_option('vimeo_plus_wp_bio')=='1') { echo ' checked="checked"'; } ?> /></label></p>
	<input type="hidden" id="vimeo_plus_wp-submit" name="vimeo_plus_wp-submit" value="1" />	
	</form>

<?php
}

// Curl helper function
function curl_get($url) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	$return = curl_exec($curl);
	curl_close($curl);
	return $return;
}

function widget_vimeo_plus_wp($args) {
  	extract($args);
  	echo $before_widget;
  	echo $before_title . get_option('vimeo_plus_wp_title') . $after_title;
	display_vimeo();
  	echo $after_widget;
}
 
function display_vimeo() {
// Change this to your username to load in your videos
$vimeo_user_name = get_option('vimeo_plus_wp_username');
$displayNumber = get_option('vimeo_plus_wp_videos');

// API endpoint
$api_endpoint = 'http://vimeo.com/api/v2/'.$vimeo_user_name;


// Load the user info and clips
$userInfo = curl_get($api_endpoint.'/info.php');
$videosInfo = curl_get($api_endpoint.'/videos.php');
$user = unserialize($userInfo);
$videos = unserialize($videosInfo);
?>
<ul style="list-style-type: none;">
<?php $i = 0; foreach ($videos as $video): ?>
<li>
	<a class="vimeo_plus_wp_title" href="<?php echo $video['url']; ?>"><?php echo $video['title']; ?></a>
		<?php if(get_option('vimeo_plus_wp_img')==1) { ?>
			<a href="<?php echo $video['url']; ?>"><?php echo '<img src="'.$video['thumbnail_large'].'" style="margin: 0 0 5px 0;" width="'.get_option('vimeo_plus_wp_width').'" />'; ?></a>
		<?php } else { ?>
			<object width="<?php echo get_option('vimeo_plus_wp_width'); ?>" height="<?php echo get_option('vimeo_plus_wp_height'); ?>"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=<?php echo $video['id']; ?>&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=0&amp;show_portrait=1&amp;color=ff0179&amp;fullscreen=1" /><embed src="http://vimeo.com/moogaloop.swf?clip_id=<?php echo $video['id']; ?>&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=0&amp;show_portrait=1&amp;color=ff0179&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="<?php echo get_option('vimeo_plus_wp_width'); ?>" height="<?php echo get_option('vimeo_plus_wp_height'); ?>"></embed></object><br />
		<?php } ?>
		<br /><?php echo $video['description']; ?><hr /></li>
		<?php $i++; if($i == get_option('vimeo_plus_wp_videos')) break; endforeach;   ?>

		<li><?php if(get_option('vimeo_plus_wp_portrait')=='1') { ?><a href="<?php echo $user['profile_url']; ?>"><img id="portrait" style="margin: 0 10px 0 0;" src="<?php echo $user['portrait_small']; ?>" /></a><?php } ?>
			<a href="<?php echo $user['profile_url']; ?>"><?php echo $user['display_name']; ?> on Vimeo &raquo;</a>
			<?php if(get_option('vimeo_plus_wp_bio')=='1') { ?><br /><?php echo $user['bio']; ?><?php } ?></li>
</ul>
<?php } 

function vimeo_plus_wp_init() {
  register_sidebar_widget(__('Vimeo+WP'), 'widget_vimeo_plus_wp');
  register_widget_control(__('Vimeo+WP'), 'vimeo_plus_wp_widget_Admin');
}

add_action("plugins_loaded", "vimeo_plus_wp_init");
add_action('admin_menu', 'vimeo_plus_wp');
?>