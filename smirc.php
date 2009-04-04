<?php
/*
Plugin Name: Social Media Integrated Related Content (SMIRC)
Plugin URI: http://www.husani.com/ventures/wordpress-plugins/smirc
Description: Searches the blogosphere for content related to your content and displays links/summaries on your page or inside your posts.  Revealing the links/summaries requires <a href="http://www.jquery.com/">jQuery</a>.  By <a href="http://www.husani.com" target="_blank">Husani Oakley</a> and <a href="http://www.evb.com">Evolution Bureau</a>.
Version: 1.1
*/

/*  Copyright 2009  Husani Oakley  (email : wordpressplugins@husani.com)

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

$plugin_name = "/social-media-integrated-related-content-smirc";

require_once(ABSPATH . PLUGINDIR . $plugin_name . "/lib/smirclib.php");
require_once(ABSPATH . WPINC . '/rss.php');

function smirc($page_title, $header_text_pre=false, $header_text_post=false){
  //if we need to add a prefix or postfix to the header, do so
  $header_text = _smirc_headertext(get_option('header_text'), $header_text_pre, $header_text_post);
  //instantiate object with page title as argument
  $smirc = new SMIRC($page_title, _smirc_separator(), get_option('required_keyword'), _smirc_datasources(), $header_text, get_option('animate_reveal'));
  //return content
  return $smirc->getContent();
}

//create header based on prefix and postfix
function _smirc_headertext($header_text, $pre, $post){
  return $pre . $header_text . $post;
}

//get and parse smirc title separator from wp options
function _smirc_separator(){
  return str_split(get_option('title_separator'));
}

//get smirc data sources from wp options
function _smirc_datasources(){
  if(get_option('data_source_google_blogsearch') == 1){
    $data_sources[] = array("google_blogsearch", get_option('num_results_google_blogsearch'), _smirc_exclude('exclude_list_google_blogsearch'), _smirc_sectionheader('google_blogsearch'));
  }
  if(get_option('data_source_twitter_search') == 1){
    $data_sources[] = array("twitter_search", get_option('num_results_twitter_search'), _smirc_exclude('exclude_list_twitter_search'), _smirc_sectionheader('twitter_search'));
  }
  return $data_sources;
}

//get and return exclusion list
function _smirc_exclude($source){
  $exclude = get_option($source);
  if($exclude){
    return $exclude;
  } else {
    return -1;
  }
}

//get and return result header(s), if set
function _smirc_sectionheader($source){
  $header = get_option('header_' . $source);
  if($header){
    return $header;
  } else {
    return -1;
  }
}

/**
 * SET UP ACTIONS 
 */
add_action('wp_head', '_smirc_head');
add_action('admin_menu', '_smirc_admin');

/** 
 * ACTION-SPECIFIC FUNCTIONS
 */
//add css and js to head, as required
function _smirc_head(){
  global $plugin_name;
  echo '<link rel="stylesheet" href="/' . PLUGINDIR . $plugin_name . '/css/main.css" type="text/css" />' . "\n";
  /** add animation css/js to head if required */
  if(get_option('animate_reveal')){
    echo '<link rel="stylesheet" href="/' . PLUGINDIR . $plugin_name . '/css/animate.css" type="text/css" />' . "\n";
    echo '<script language="javascript" type="text/javascript" src="/' . PLUGINDIR . $plugin_name . '/js/animate.js"></script>' . "\n";
  }
}

//add hook for options panel
function _smirc_admin(){
    add_options_page('SMIRC Options', 'SMIRC Options', 8, 'smirc_options', '_smirc_options');
}

//display options panel
function _smirc_options(){
  global $plugin_name;
  include_once(ABSPATH . PLUGINDIR . $plugin_name . "/admin/panel.inc.php");
}

?>
