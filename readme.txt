=== Social Media Integrated Related Content (SMIRC) ===
Contributors: husani
Tags: related content, blogs, posts, google, google blog search, social media, trackbacks, twitter
Requires at least: 2.7
Tested up to: 2.7.1
Stable tag: trunk
Version: 1.0

Like trackbacks, but not -- this plugin searches and displays content related to your CONTENT, not just posts that link to yours.

== Description ==

Searches various data sources (Google Blog Search and Twitter) for posts related to your content and displays links/summaries on your page or 
inside your posts.  These links and summaries can be displayed anywhere and any way you wish -- below your post, in a sidebar, etc.  With the 
magic animation power of jQuery, SMIRC content can be hidden until a user clicks the customizable header.

SMIRC performs related content searching (at least in this version) by creating RSS feed URLs for the various data sources.  The RSS feed is 
parsed and converted into XHTML for display in your blog.

All PHP is separated into logical classes and files, and all XHTML is standards-compliant.  Lists of links, authors, and summaries are built
using standard UL and LI tags, and can be modified to fit your theme via two CSS files contained within this plugin.

SMIRC is released to the Wordpress community under the GPL.  Please feel free to modify as you see fit, and if you find this plugin useful, donate
to the author.  All feedback is welcome at wordpressplugins@husani.com, and you can visit the author's websites at http://www.husani.com and 
http://www.evb.com.

== Installation ==

1.  Upload the SMIRC plugin to your blog (YOURBLOG/wp-content/plugins) and activate it using the Wordpress plugin admin screen.
2.  Modify settings if necessary.  You can modify the following settings:
     - Keyword.  SMIRC uses a page's (or post's) title as a search term when querying for related content.  You can add a word to this search term.
     - Data sources.  SMIRC has the ability to search one or more sources of content.  This version includes Twitter and Google Blog Search.  You can
       indicate which sources you'd like to search, number of results from each source, and what to exclude from each source.
     - Header text.  To better fit within your existing theme, you can set the text that is displayed above SMIRC's list of links.
     - Animation.  You have two choices:  SMIRC will either display the header and list, or display the header with an arrow -- clicking the arrow
       will trigger a sliding animation reveal of the list.
3.  Add `<?=smirc($post->post_title)?>` in your template where you'd like SMIRC to display content.  If you don't want to use your page title (or 
    post title) as keywords, pass any string you'd like to the smirc() function.

== Frequently Asked Questions ==

= Isn't this just trackback? =

No.  Trackbacks are specifically meant to show relationships between posts, they do not care about the CONTENT of posts.  SMIRC shows your users
what the blogosphere is saying about the content in your post/page, not just the URL.  Suppose you're an artist, and you created a painting and posted
a page about it.  Links to that page are not as important as people discussing your painting.  SMIRC will allow you and your readers to follow those
external discussions as simply as if they took place on your own blog.

= What if I need a specific keyword attached to searches? =

Edit SMIRC's settings and use the "Keyword" to add whatever you like.

= What if I don't use jQuery? =

SMIRC is functional without any JavaScript magic -- searching and parsing occurs server-side, not via AJAX.  jQuery is used to animate a reveal of the 
results, but you can disable the animation in the settings admin panel.  If you want the animation but are using scriptaculous or another animation 
framework, feel free to modify SMIRC source to allow for your framework's slide / reveal method.

= Why did you write this plugin? =

We (my company, EVB, where I am Director of Technology) needed to add external-but-related-content to a Wordpress-powered site, and decided to build 
this plugin and release to the community.  

== Screenshots ==

1. Admin panel, `/trunk/screenshots/admin_panel.png`
2. SMIRC, `/trunk/screenshots/smirc_no_ani.png`
3. SMIRC with animated reveal and collapsed, `/trunk/screenshots/smirc_collapsed.png`
