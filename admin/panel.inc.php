<?
//hacky way to pre-pop form.  may be a better way to do this.
if(get_option('data_source_google_blogsearch') == "1"){
   $dsgb_checked = 'checked="checked"';
   if(get_option('num_results_google_blogsearch') == ""){
      $nrgb = "1";
   } else {
      $nrgb = get_option('num_results_google_blogsearch');
   }
} else {
   $dsgb_checked = "";
   $nrgb = "";
}
if(get_option('data_source_twitter_search') == "1"){
   $dsts_checked = 'checked="checked"';
   if(get_option('num_results_twitter_search') == ""){
      $nrts = "1";
   } else {
      $nrts = get_option('num_results_twitter_search');
   }
} else {
   $dsts_checked = "";
   $nrts = "";
}
if(get_option('animate_reveal') == "1"){
  $animate_checked = 'checked="checked"';
 } else {
  $animate_checked = "";
 }

?>
<div class="wrap">
   <h2>Social Media Integrated Related Content (SMIRC) - Settings</h2>
   <form method="post" action="options.php">
     <?php wp_nonce_field('update-options'); ?>
     <h3>Usage Notes</h3>
     <table class="form-table">
       <tr>
	 <td>
	   <span class="setting-description">Add &lt;?=smirc($post->post_title)?&gt; to your template and set the options below.</span>
	 </td>
       </tr>
     </table>
     <h3>Search Options</h3>
     <table class="form-table">
       <tr>
	 <th scope="row" valign="top">
	   Keyword<br/>
	 </th>
	 <td><input type="text" name="required_keyword" value="<?php echo get_option('required_keyword'); ?>" /></td>
       </tr>
       <tr>
	 <td colspan="2"><span class="setting-description">SMIRC uses a page's (or post's) title as a search term when querying for related content.  If your page title contains commonly-used words, results may not be relevant.  To help relevancy, you may optionally add a keyword that will always be appended to the search term.  In other words, if your page title is "My Portfolio", and your name is "John", entering "John" will ensure that searches occur for "My Portfolio" + "John" without changing the title of your page.</span>
	 </td>
       </tr>
       <tr>
	 <th scope="row" valign="top">
	   Title Separator<br/>
	 </th>
	 <td><input type="text" name="title_separator" value="<?php echo get_option('title_separator'); ?>" /></td>
       </tr>
       <tr>
	 <td colspan="2"><span class="setting-description">Connected to the above, you may have characters (a hyphen, for example) in your page/post title that separates subjects: e.g., "Orange Juice - Tropicana."  In order to make the search return relevant results, enter the character you use to separate subjects in your title.  Continuing the Tropicana example, you'd enter "-" (no quotation marks) in the field above, so the search terms become "Orange Juice" and "Tropicana".  If you use multiple separators, enter them all above, next to each other (":-,").  Leave this field blank if you do not use a separator in your page/post titles.</span>
	 </td>
       </tr>
       <tr>
	 <th scope="row">Data Sources</th>
	 <td>
	   <fieldset><legend class="hidden">Data Sources</legend>
	     <table>
	       <tr>
		 <td valign="top" width="30%"><label><input name="data_source_google_blogsearch" type="checkbox" id="data_source_google_blogsearch" value="1" <?=$dsgb_checked?> /> Google Blog Search</label></td>
		 <td valign="top"><span class="setting-description" style="display:block;margin-top:-4px">Number of Results: <input type="text" name="num_results_google_blogsearch" value="<?=$nrgb?>" class="small-text" /></span></td>
	       </tr>
	       <tr>
		 <td valign="top" colspan="2"><span class="setting-description">Exclude these URLs (separate by line break):</span><br/><textarea name="exclude_list_google_blogsearch" cols="60"><?php echo get_option('exclude_list_google_blogsearch'); ?></textarea></td>
	       </tr>
	       <tr>
		 <td valign="top" width="30"><label><input name="data_source_twitter_search" type="checkbox" id="data_source_twitter_search" value="1" <?=$dsts_checked?> /> Twitter Search</label></td>
		 <td valign="top"><span class="setting-description" style="display:block;margin-top:-4px">Number of Results: <input type="text" name="num_results_twitter_search" value="<?=$nrts?>" class="small-text" /></span></td>
	       </tr>
	       <tr>
		 <td valign="top" colspan="2"><span class="setting-description">Exclude these users (separate by line break, do not include @ symbol):</span><br/><textarea name="exclude_list_twitter_search" cols="40"><?php echo get_option('exclude_list_twitter_search'); ?></textarea></td>
	       </tr>
	     </table>
	   </fieldset>
	 </td>
       </tr>
     </table>
     <h3>Display Options</h3>
     <table class="form-table">
       <tr>
	 <th scope="row" valign="top">
	   Header Text<br/>
	 </th>
	 <td><input type="text" name="header_text" value="<?php echo get_option('header_text'); ?>" /> <span class="setting-description">Text appearing before results.</span></td>
       </tr>
       <tr>
	 <td colspan="2"><span class="setting-description">You can add to the header option by passing two arguments to the SMIRC method call.  &lt;?=smirc($post->post_title, "BEFORE", "AFTER")?&gt;</span>
	 </td>
       </tr>
       <tr>
	 <th scope="row" valign="top">
	   Google Header<br/>
	 </th>
	 <td><input type="text" name="header_google_blogsearch" value="<?php echo get_option('header_google_blogsearch'); ?>" /> <span class="setting-description">Text appearing before Google Blog Search results.</span></td>
       </tr>
       <tr>
	 <th scope="row" valign="top">
	   Twitter Header<br/>
	 </th>
	 <td><input type="text" name="header_twitter_search" value="<?php echo get_option('header_twitter_search'); ?>" /> <span class="setting-description">Text appearing before Twitter search results.</span></td>
       </tr>
       <tr>
	 <th scope="row" colspan="2" class="th-full">
	   <label for="animate_reveal">
	     <input name="animate_reveal" type="checkbox" id="animate_reveal" value="1" <?=$animate_checked?> />
	     Animate reveal of links.  Requires <a href="http://www.jquery.com">jQuery</a> to be included in your theme.
	   </label>
	 </th>
       </tr>
     </table>
     <input type="hidden" name="action" value="update" />
     <input type="hidden" name="page_options" value="required_keyword,title_separator,data_source_google_blogsearch,num_results_google_blogsearch,exclude_list_google_blogsearch,data_source_twitter_search,num_results_twitter_search,exclude_list_twitter_search,header_text,header_google_blogsearch,header_twitter_search,animate_reveal" />
     <p class="submit">
       <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
     </p>
   </form>
   <hr>
   <p style="font-size:x-small"><i><a href="http://www.husani.com/ventures/wordpress-plugins/smirc" target="_blank">SMIRC</a> plugin by <a href="http://www.husani.com" target="_blank">Husani Oakley</a> and <a href="http://www.evb.com">Evolution Bureau</a>.</i></p>
</div>
