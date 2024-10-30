=== Comment Redlist ===
Contributors: skunkbad
Tags: spam, comment, comments, red, list, redlist
Tested up to: 4.9.4
Stable tag: 1.0.9

Easily block obvious spam before it is inserted into your database.

== Description ==

[Comment Redlist](http://blog.skunkbad.com/wordpress/the-comment-redlist-plugin) is a WordPress plugin that blocks comment spam before it is inserted into your database.

Manage your Comment Redlist settings on the Settings > Discussion page, or on the plugin's dedicated options page, located in the Settings menu.

For more information, check out [http://blog.skunkbad.com/wordpress/the-comment-redlist-plugin](http://blog.skunkbad.com/wordpress/the-comment-redlist-plugin).

Features include:

* Comments blocked by word, character sequence, or IP address.
* Blocked comments can be logged for debugging or your general information.
* Optional javascript alert informs site visitor if Comment Redlist will detect their submission as spam.
* Optional setting provides spammer with zero feedback, instead of an error message.
* Optional form tokens use cookies (PHP session) to confirm a legitimate post.
* Optional setting restricts comments to US keyboard characters, or any regular expression.
* Optional setting removes your comment form's Website field, and blocks any comment that contains one.

== Installation ==

1. Use the auto-installer or upload entire plugin to `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in Wordpress.
3. Change settings in new Settings->Comment Redlist or Settings->Discussion page.

== Frequently Asked Questions ==

= Who is this plugin aimed at? =
This plugin is aimed at nearly every Wordpress user. Nobody wants spam.

= Why are there no Questions here? =
Because no-one has asked me.. Ask me a question by going to the [Brian's Web Design Contact Page](http://brianswebdesign.com/contact)

== Changelog ==

= 1.0.9 =
 * Serbian language translation files added

= 1.0.8 =
 * Spanish language translation fixed

= 1.0.7 =
 * Spanish language translation files added

= 1.0.6 =
 * Debug mode now enabled via options page / settings page. Previously only set as private class member.
 * Session cookie name for token usage now changed via options page / settings page. Previously set in lang.php.

= 1.0.5 =
 * Existing feature that blocks submissions with Website field now will remove the field if it has not already been removed. Technically it is replacing it with a hidden form field, which is a perfect trap for many bots.

= 1.0.4 =
 * Fixed dedicated options form. Uskeys removed, URL added back in.

= 1.0.3 =
 * Better options for character restriction
 * Fixed bug where all error messages were the same

= 1.0.2 =
 * Initial Commit to SVN