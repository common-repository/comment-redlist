<?php

$comment_redlist_lang = array(

// Plugin name
'plugin_name'              => __('Comment Redlist','comment-redlist'),

// PHP timezone
'php_timezone'             => __('America/Los_Angeles','comment-redlist'),

// Admin permission error
'admin_permission_error'   => __('You do not have sufficient permissions to access this page','comment-redlist'),

// Standard error message used with wp_die()
'std_error'                => __('<b>Error</b>: Comment Spam Detected.<br /><br />Something in your comment submission has identified it as spam by our comment processing system. Your comment has not been accepted.','comment-redlist'),

// The link to the options page (shown on plugins page)
'options_link'             => __('Plugin Options', 'comment-redlist'),

// The header and section description for the plugin options form
'h2_header'                => __('Comment Redlist Options','comment-redlist'),
'settings_section_p'       => __('These are the settings for the Comment Redlist plugin.','comment-redlist'),

// Option: Redlisted Sequences
'sequences_textarea_label' => __('Redlisted Sequences','comment-redlist'),
'desc'                     => __('When a comment contains any of the character sequences you specify below, whether in it\'s comment body, name field, website field, or e-mail field, it will <strong>NOT</strong> be marked as spam. Instead it will be blocked, and not appear in your pending comments, spam comments, or trash. <strong style="color:red;">It will not be inserted into your database!</strong> Put one character sequence per line. Sequences will match inside words, so "ass" would match "bass".','comment-redlist'),
'debug_sequence_error'     => __('Redlisted Sequence Detected: ','comment-redlist'),

// Option: Redlisted IP Addresses
'ip_addrs_textarea_label'  => __('Redlisted IP Addresses','comment-redlist'),
'ip_addrs_desc'            => __('Any requests from IP Addresses listed below are totally blocked. Blocking at the server level is preferred, but this may be your only option. Put one IP address per line. <strong style="color:red;">Make sure not to block your own IP Address!</strong>','comment-redlist'),
'debug_ip_address'         => __('Redlisted IP Address: ','comment-redlist'),

// Option: Log Blocked Comments
'log_checkbox_label'       => __('Log Blocked Comments','comment-redlist'),
'log_checkbox_desc'        => __('Blocked comments will be stored in <strong>/wp-content/plugins/comment-redlist/comment_log/</strong>','comment-redlist'),

// Option: Use JS Alerts
'alert_checkbox_label'     => __('Use JS Alerts','comment-redlist'),
'alert_checkbox_desc'      => __('Helpful javascript alerts to warn humans that their comment submission contains sequences you have redlisted.','comment-redlist'),
'js_alert_first_half'      => __('Comment submissions cannot contain:','comment-redlist'),
'js_alert_last_half'       => __('Please remove this and resubmit.','comment-redlist'),

// Option: Load jQuery
'jquery_checkbox_label'    => __('Load jQuery','comment-redlist'),
'jquery_checkbox_desc'     => __('Load jQuery in document &lt;head&gt;. Only required if you are not already loading jQuery and have enabled client side alerts.','comment-redlist'),

// Option: Use die() Instead of wp_die()
'die_checkbox_label'       => __('Use die() Instead of wp_die()','comment-redlist'),
'die_checkbox_desc'        => __('Using PHP\'s die() leaves spammers with the whitescreen of death. Wordpress\' wp_die() offers info about why they were blocked.','comment-redlist'),

// Option: Use Form Tokens
'token_checkbox_label'     => __('Use Form Tokens','comment-redlist'),
'token_checkbox_desc'      => __('Form tokens ensure comment legitimacy through PHP\'s $_SESSION cookies. Links in document &lt;head&gt; may need to be removed.','comment-redlist'),
'token_error'              => __('<b>Error</b>: Comment Spam Detected. Cookies Required For Comment Submission','comment-redlist'),
'debug_token_error_post'   => __('Posted Token: ','comment-redlist'),
'debug_token_error_sess'   => __('Session Token: ','comment-redlist'),

// Option: Remove WP Links From <head>
'links_checkbox_label'     => __('Remove WP Links From &lt;head&gt;','comment-redlist'),
'links_checkbox_desc'      => __('Removes WP links in document &lt;head&gt; if page <a href="http://codex.wordpress.org/Function_Reference/is_singular">is_singular</a>. May be necessary if using form tokens feature.','comment-redlist'),

// Option: Remove WP Links From <head>
'sessname_text_label'      => __('Session Cookie Name','comment-redlist'),
'sessname_text_desc'       => __('If using form tokens, you may choose a unique cookie name. Alphanumeric characters are considered safe. No semi-colon, comma or white space. If you\'re not sure, play it safe and use alphanumeric characters, or check out the <a href="http://curl.haxx.se/rfc/cookie_spec.html">cookie spec</a>.','comment-redlist'),

// Option: Character Restriction
'xchars_radios_label'      => __('Character Restriction','comment-redlist'),
'xchars_radio_no'          => __('No','comment-redlist'),
'xchars_radio_us'          => __('US Keyboard Only (all)','comment-redlist'),
'xchars_radio_us_no_ltgt'  => __('US Keyboard Only (all except greater than or less than signs)','comment-redlist'),
'xchars_custom_label'      => __('Custom Regular Expression: ','comment-redlist'),
'xchars_custom_example'    => __('Example: ','comment-redlist'),
'xchars_error'             => __('<b>Error</b>: Comment Contained Disallowed Characters','comment-redlist'),
'debug_xchars_error'       => __('Character Restriction Detected: ','comment-redlist'),
'xchars_validation_error'  => __('Empty custom regular expression for Character Restriction, PLEASE FIX','comment-redlist'),

// Option: Remove & Block Website Field
'url_checkbox_label'       => __('Remove &amp; Block Website Field','comment-redlist'),
'url_checkbox_desc'        => __('Removes the "Website" field from your comment form, replacing it with a hidden form field. A perfect trap for many bots.','comment-redlist'),
'debug_url_field'          => __('Website Field Detected','comment-redlist'),

// Option: Debug Mode
'debug_checkbox_label'     => __('Debug Mode','comment-redlist'),
'debug_checkbox_desc'      => __('Shows data that may be helpful for understanding more about spam detection and token failures.','comment-redlist'),

// Form button
'button'                   => __('Save Changes','comment-redlist')

);