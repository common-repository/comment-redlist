<?php
/**
 * Plugin Name: Comment Redlist
 * Description: Blocks spam, identified by your specified character sequences, instead of inserting it into the database.
 * Author: Robert Brian Gottier
 * Version: 1.0.9
 * Author URI: http://brianswebdesign.com
 * Plugin URI: http://blog.skunkbad.com/wordpress/the-comment-redlist-plugin
 * Text Domain: comment-redlist
 * License: MIT
 */
class comment_redlist {

	/**
	 * Plugin options retreived from the DB
	 * @var array
	 */
	private $options;

	/**
	 * The new value of the form token
	 * @var string
	 */
	private $token = '';

	/**
	 * The last request's value for the form token
	 * @var string
	 */
	private $flash_value = '';

	/**
	 * Everything that could be replaced for i18n
	 * @var array
	 */
	private $lang = array();

	/**
	 * The HTML for the discussion settings page
	 * @var array
	 */
	private $fields = array();

	/**
	 * The status of a posted comment
	 * @var string
	 */
	private $error = '';

	/**
	 * Debug (now set as plugin option)
	 * @var boolean
	 */
	private $debug = FALSE;

	// -----------------------------------------------------------------------

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		/* On activation */
		register_activation_hook( __FILE__, array( $this, 'activate_plugin_event' ) );

		/* On deactivation */
		register_deactivation_hook( __FILE__, array( $this, 'deactivate_plugin_event' ) );

		/* Load the localization file to do the translation */
		load_plugin_textdomain( 'comment-redlist', NULL, 'comment-redlist/localization/' );

		/* Load plugin lang file */
		require  dirname( __FILE__ ) . '/inc/lang.php';
		$this->lang = $comment_redlist_lang;

		/* Get the plugin options from the database */
		$this->options = get_option('comment_redlist');

		/* Backwards compatibility for old uskeys option */
		if( isset( $this->options['uskeys'] ) && $this->options['uskeys'] == 'TRUE' )
		{
			$this->options['xchars'] = 'us';
		}

		/* Set debug mode */
		if( isset( $this->options['debug'] ) && $this->options['debug'] == 'TRUE' )
		{
			$this->debug = TRUE;
		}

		/* Check redlisted IP addresses */
		$this->block_ip_addrs();

		/* Form tokens */
		if( isset( $this->options['token'] ) && $this->options['token'] == 'TRUE' )
		{
			/* Backwards compatibility for pre-option sessname */
			if( ! isset( $this->options['sessname'] ) OR empty( $this->options['sessname'] ) )
			{
				$this->options['sessname'] = 'skunkToken';
			}

			/* Name and start session */
			session_name( $this->options['sessname'] );
			session_start();

			/* Retain the session's last token value */
			$this->flash();

			/* Create new token value */
			$this->token = sha1( microtime( TRUE ) );

			/* Insert the form token into the comment forms */
			add_action( 'comment_form', array( $this, 'insert_form_token_field') );

			/* Store new token value in session */
			$_SESSION['comment_form_token'] = $this->token;
		}

		/**
		 * Add field to settings > discussion page
		 * and create dedicated plugin options page.
		 * This allows two locations for the same setting.
		 */
		if( is_admin() )
		{
			add_action( 'admin_init', array( $this, 'register_settings') );
			add_action( 'admin_init', array( $this, 'discussion_settings' ) );
			add_action( 'admin_menu', array( $this, 'dedicated_plugin_options' ) );
		}

		/**
		 * Check if singular and load jquery or remove links if either option selected
		 */
		else
		{
			add_action( 'wp', array( $this, 'check_is_singular' ) );
		}

		/* Check comment ASAP when it gets posted to wp-comments-post.php */
		add_action( 'pre_comment_on_post', array( $this, 'block_spam' ) );

		/* Add settings link on plugins page */
		add_filter( 'plugin_row_meta', array( $this, 'settings_link' ), 10, 4  );		

		/**
		 * Add a little javascript to alert humans 
		 * if they're about to trigger the redlist
		 */
		add_action( 'wp_footer', array( $this, 'insert_jquery_redlist' ) );
	}

	// -----------------------------------------------------------------------

	/**
	 * Add default option(s) to options table for this plugin
	 */
	public function activate_plugin_event()
	{
		$default_options = array( 
			'alert'    => 'TRUE',
			'jquery'   => 'TRUE',
			'xchars'   => 'no',
			'sessname' => 'skunkToken'
		);

		add_option( 'comment_redlist', $default_options );
	}

	// -----------------------------------------------------------------------

	/**
	 * Remove the plugin options from the options table
	 */
	public function deactivate_plugin_event()
	{
		delete_option('comment_redlist');
	}

	// -----------------------------------------------------------------------

	/**
	 * Register settings for both discussion settings page and dedicated options page
	 */
	public function register_settings()
	{
		/* Register setting for discussion settings page */
		register_setting( 'discussion', 'comment_redlist' );

		/* Register setting for dedicated plugin options page */
		register_setting( 'comment_redlist_group', 'comment_redlist', array( $this, 'validation_callback' ) );
	}

	// -----------------------------------------------------------------------

	/**
	 * Validation callback for custom char limiter
	 */
	public function validation_callback( $input )
	{
		/* If custom regex selected for character restriction, text input must not be empty */
		if( $input['xchars'] == '\c\u\s\t\o\m' && empty( $input['xchars_custom'] ) )
		{
			add_settings_error(
				'comment_redlist_xchars',
				'comment_redlist_xchars_error',
				$this->lang['xchars_validation_error'],
				'error'
			);

			$input['xchars'] = 'no';
		}

		return $input;
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Add section and fields for discussion page
	 */
	public function discussion_settings()
	{
		/* Load the fields */
		require  dirname( __FILE__ ) . '/inc/settings-discussion-field.php';
		$this->fields = $comment_redlist_fields;

		add_settings_section( 'comment-redlist',         $this->lang['h2_header'],                array( $this, 'render_settings_section' ),                    'discussion' );
		add_settings_field( 'comment_redlist_sequences', $this->lang['sequences_textarea_label'], array( $this, 'render_settings_sequences_textarea' ),         'discussion', 'comment-redlist' );
		add_settings_field( 'comment_redlist_ip_addrs',  $this->lang['ip_addrs_textarea_label'],  array( $this, 'render_settings_ip_addrs_textarea' ),          'discussion', 'comment-redlist' );
		add_settings_field( 'comment_redlist_log',       $this->lang['log_checkbox_label'],       array( $this, 'render_settings_discussion_log_checkbox' ),    'discussion', 'comment-redlist' );
		add_settings_field( 'comment_redlist_alert',     $this->lang['alert_checkbox_label'],     array( $this, 'render_settings_discussion_alert_checkbox' ),  'discussion', 'comment-redlist' );
		add_settings_field( 'comment_redlist_jquery',    $this->lang['jquery_checkbox_label'],    array( $this, 'render_settings_discussion_jquery_checkbox' ), 'discussion', 'comment-redlist' );
		add_settings_field( 'comment_redlist_die',       $this->lang['die_checkbox_label'],       array( $this, 'render_settings_discussion_die_checkbox' ),    'discussion', 'comment-redlist' );
		add_settings_field( 'comment_redlist_tokens',    $this->lang['token_checkbox_label'],     array( $this, 'render_settings_discussion_token_checkbox' ),  'discussion', 'comment-redlist' );
		add_settings_field( 'comment_redlist_links',     $this->lang['links_checkbox_label'],     array( $this, 'render_settings_discussion_links_checkbox' ),  'discussion', 'comment-redlist' );
		add_settings_field( 'comment_redlist_sessname',  $this->lang['sessname_text_label'],      array( $this, 'render_settings_discussion_sessname_text' ),   'discussion', 'comment-redlist' );
		add_settings_field( 'comment_redlist_xchars',    $this->lang['xchars_radios_label'],      array( $this, 'render_settings_discussion_xchars_radios' ),   'discussion', 'comment-redlist' );
		add_settings_field( 'comment_redlist_url',       $this->lang['url_checkbox_label'],       array( $this, 'render_settings_discussion_url_checkbox' ),    'discussion', 'comment-redlist' );
		add_settings_field( 'comment_redlist_debug',     $this->lang['debug_checkbox_label'],     array( $this, 'render_settings_discussion_debug_checkbox' ),  'discussion', 'comment-redlist' );
	}

	// -----------------------------------------------------------------------

	/**
	 * Show the settings > discussion > redlist section and fields
	 */
	public function render_settings_section(){                    echo $this->fields['section']; }
	public function render_settings_sequences_textarea(){         echo $this->fields['sequences']; }
	public function render_settings_ip_addrs_textarea(){          echo $this->fields['ip_addrs']; }
	public function render_settings_discussion_log_checkbox(){    echo $this->fields['log_checkbox']; }
	public function render_settings_discussion_alert_checkbox(){  echo $this->fields['alert_checkbox']; }
	public function render_settings_discussion_jquery_checkbox(){ echo $this->fields['jquery_checkbox']; }
	public function render_settings_discussion_die_checkbox(){    echo $this->fields['die_checkbox']; }
	public function render_settings_discussion_token_checkbox(){  echo $this->fields['token_checkbox']; }
	public function render_settings_discussion_links_checkbox(){  echo $this->fields['links_checkbox']; }
	public function render_settings_discussion_sessname_text(){   echo $this->fields['sessname_text']; }
	public function render_settings_discussion_xchars_radios(){   echo $this->fields['xchars_radios']; }
	public function render_settings_discussion_url_checkbox(){    echo $this->fields['url_checkbox']; }
	public function render_settings_discussion_debug_checkbox(){  echo $this->fields['debug_checkbox']; }

	// -----------------------------------------------------------------------

	/**
	 * Create admin page in Settings menu
	 */
	public function dedicated_plugin_options()
	{
		add_options_page( $this->lang['plugin_name'], $this->lang['plugin_name'], 'manage_options', __CLASS__, array( $this, 'render_dedicated_options_page' ) );
	}

	// -----------------------------------------------------------------------

	/**
	 * Show the dedicated plugin options html
	 */
	public function render_dedicated_options_page()
	{
		if( ! current_user_can('manage_options') )
		{
			/* Without correct permissions, die! */
			wp_die( $this->lang['admin_permission_error'] );
		}

		require  dirname( __FILE__ ) . '/inc/dedicated-options-page.php';
	}

	// -----------------------------------------------------------------------

	/**
	 * Insert jQuery script in wp_footer
	 */
	public function insert_jquery_redlist()
	{
		if( 
			isset( $this->options['sequences'] ) &&
			isset( $this->options['alert'] ) && 
			$this->options['alert'] == 'TRUE' 
			&& is_singular() 
		)
		{
			/* No reason to do anything if no sequences exist */
			$option_check = trim( $this->options['sequences'] );

			if( ! empty( $option_check ) )
			{
				/* Create an array from the sequences */
				$sequences = explode("\n", $this->options['sequences'] );

				/* Remove any \r or \n from each array element */
				foreach( $sequences as $sequence )
				{
					$trimmed_sequences[] = trim( $sequence );
				}

				/* Create JS array from sequences */
				$js_sequences = '["' . implode( '","', $trimmed_sequences ) . '"]';

				require  dirname( __FILE__ ) . '/inc/jquery-redlist.php';
			}	
		}
	}

	// -----------------------------------------------------------------------

	/**
	 * Check if page is singular and take action for some settings
	 */
	public function check_is_singular()
	{
		if( 
			isset( $this->options['sequences'] ) && 
			is_singular() 
		)
		{
			/* No reason to do anything if no sequences exist */
			$option_check = trim( $this->options['sequences'] );

			if( ! empty( $option_check ) )
			{
				/* Removal of links from <head> may be necessary if using form tokens */
				if( isset( $this->options['links'] ) && $this->options['links'] == 'TRUE' )
				{
					remove_action( 'wp_head', 'wlwmanifest_link' );
					remove_action( 'wp_head', 'rsd_link' );
					remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
					remove_action( 'wp_head', 'rel_canonical', 10, 0 );
					remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
					remove_action( 'wp_head', 'feed_links', 2 );
					remove_action( 'wp_head', 'feed_links_extra', 3 );
					remove_action( 'wp_head', 'index_rel_link' );
					remove_action( 'wp_head', 'parent_post_rel_link_wp_head', 10, 0);
					remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
					remove_action( 'wp_head', 'wp_generator' );
				}

				/* Load jQuery 1.9.1 from Google */
				if(  isset( $this->options['jquery'] ) && $this->options['jquery'] == 'TRUE' )
				{
					wp_deregister_script('jquery');
					wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', false, '1.9.1');
					wp_enqueue_script('jquery');
				}
			}
		}

		/* Remove website field from comment form if option calls for it */
		if(
			isset( $this->options['url'] ) && 
			$this->options['url'] == 'TRUE' &&
			is_singular()
		)
		{
			add_filter( 'comment_form_defaults', array( $this, 'remove_website_field' ) );
		}
	}

	// -----------------------------------------------------------------------

	/**
	 * Block redlisted IP addresses
	 */
	public function block_ip_addrs()
	{
		/* No reason to do anything if no IP addresses have been redlisted */
		$option_check = trim( $this->options['ip_addrs'] );

		if( ! empty( $option_check ) )
		{
			/* Create an array of the IP addresses */
			$ips = explode("\n", $this->options['ip_addrs'] );

			foreach( $ips as $ip )
			{
				$ip = trim( $ip );

				if( $ip == $_SERVER['REMOTE_ADDR'] )
				{
					if( $this->debug )
					{
						die( $this->lang['debug_ip_address'] . $_SERVER['REMOTE_ADDR'] );
					}
					else
					{
						die();
					}
				}
			}
		}
	}

	// -----------------------------------------------------------------------

	/**
	 * Block spam before it hits the DB
	 */
	public function block_spam()
	{
		/* Form Token Match */
		if( isset( $this->options['token'] ) && $this->options['token'] == 'TRUE' )
		{
			if( ! isset( $_POST['token'] ) OR $_POST['token'] != $this->flash_value )
			{
				if( $this->debug )
				{
					$this->error = $this->lang['debug_token_error_post'] . $_POST['token'] . '<br />' .
									$this->lang['debug_token_error_sess'] . $this->flash_value;
				}
				else
				{
					$this->error = $this->lang['token_error'];
				}
			}
		}

		/* Characters Restriction */
		if( 
			isset( $this->options['xchars'] ) && 
			$this->options['xchars'] != 'no' && 
			$this->error == '' 
		)
		{
			switch( $this->options['xchars'] )
			{
				case 'us':
					$regex = '/[^\x20-\x7E\s]/';
					break;
				case 'us_no_ltgt':
					$regex = '/[^\x20-\x3B\x3D\x3F-\x7E\s]/';
					break;
				case '\c\u\s\t\o\m':
					$regex = ( isset( $this->options['xchars_custom'] ) && $this->options['xchars_custom'] != '' ) ? $this->options['xchars_custom'] : '';
					break;
			}

			if( preg_match(
				$regex, 
				$_POST['author'] . $_POST['email'] . $_POST['comment'],
				$matches )
			)
			{
				if( $this->debug )
				{
					$this->error = $this->lang['debug_xchars_error'] . htmlentities( $matches[0] );
				}
				else
				{
					$this->error = $this->lang['xchars_error'];
				}
			}
		}

		/* If comment form doesn't have a URL field, block comment */
		if( isset( $this->options['url'] ) && $this->options['url'] == 'TRUE' && $this->error == '' )
		{
			if( isset( $_POST['url'] ) && ! empty( $_POST['url'] ) )
			{
				if( $this->debug )
				{
					$this->error = $this->lang['debug_url_field'];
				}
				else
				{
					$this->error = $this->lang['std_error'];
				}
			}
		}

		/* No reason to do anything if sequences option does not exist */
		if( isset( $this->options['sequences'] ) )
		{
			/* No reason to do anything if no sequences exist */
			$option_check = trim( $this->options['sequences'] );

			if( ! empty( $option_check ) && $this->error == '' )
			{
				/* Create an array from the sequences */
				$sequences = explode("\n", $this->options['sequences'] );

				/* Concatenate post vars and IP address to check all at once */
				$string = implode( ' ', $_POST );

				/* Check the concatenated string for each sequence */
				foreach( $sequences as $sequence )
				{
					/* Trim the sequence of any whitespace at ends */
					$sequence = trim( $sequence );

					/* If the sequence was found */
					if( stripos( $string, $sequence ) !== FALSE )
					{
						/* Debug shows offending sequence */
						if( $this->debug )
						{
							$this->error = $this->lang['debug_sequence_error'] . $sequence;
						}
						else
						{
							$this->error = $this->lang['std_error'];
						}
					}
				}
			}
		}

		if( $this->error != '' )
		{
			/* Debug shows detected offense */
			if( $this->debug )
			{
				wp_die( $this->error );
			}

			/* Standard error message */
			else
			{
				/* Log comment if option set to TRUE */
				if( isset( $this->options['log'] ) && $this->options['log'] == 'TRUE' )
				{
					$this->log();
				}

				if( isset( $this->options['die'] ) && $this->options['die'] == 'TRUE' )
				{
					header('HTTP/1.1 403 Forbidden');
					die();
				}

				wp_die( $this->error );
			}
		}
	}

	// -----------------------------------------------------------------------

	/**
	 * Plugins page settings link
	 */
	public function settings_link( $plugin_meta, $plugin_file, $plugin_data, $status )
	{
		if( 
			'Comment Redlist' == $plugin_data['Name'] && 
			is_plugin_active( $plugin_file ) 
		)
		{
			array_push( 
				$plugin_meta, 
				'<a href="' . site_url() . '/wp-admin/options-general.php?page=comment_redlist">
					' . $this->lang['options_link'] . '
				</a>'
			);
		}

		return $plugin_meta;
	}

	// -----------------------------------------------------------------------

	/**
	* Retain the old token value for comparison
	*/
	public function flash()
	{
		if( isset( $_SESSION['comment_form_token'] ) )
		{
			$this->flash_value = $_SESSION['comment_form_token'];
		}

		return $this->flash_value;
	}

	// -----------------------------------------------------------------------

	/**
	* Insert the form token into the comment form
	*/
	public function insert_form_token_field()
	{
		echo '<input type="hidden" name="token" value="' . 
			$this->token . 
			'" />';
	}

	// -----------------------------------------------------------------------

	/**
	 * Removes the website field from the comment form
	 */
	public function remove_website_field( $defaults )
	{
		if( isset( $defaults['fields']['url'] ) )
		{
			$defaults['fields']['url'] = '<input type="hidden" name="url" value="" />';
		}

		return $defaults;
	}
	
	// -----------------------------------------------------------------------

	/**
	 * Log everything
	 */
	public function log()
	{
		$slash = DIRECTORY_SEPARATOR;

		$log_dir = dirname( __FILE__ ) . $slash . 'comment_log' . $slash;

		if( $fp = @fopen( $log_dir . date('dm') . '-' . microtime(TRUE) . '.txt', 'wb' ) )
		{
			date_default_timezone_set( $this->lang['php_timezone'] );
			
			$data = date('l jS \of F Y h:i:s A') . "\n" . $_SERVER['REMOTE_ADDR'] . "\n\n";

			/* POST */
			if( ! empty( $_POST ) )
			{
				$data .= "POST VARS ---------------------------------------------\n";

				foreach( $_POST as $k => $v )
				{
					$data .= $k . ' => ' . $v . "\n";
				}

				$data .= "END POST VARS -----------------------------------------\n";
			}

			/* GET */
			if( ! empty( $_GET ) )
			{
				$data .= "\nGET VARS ----------------------------------------------\n";

				foreach( $_GET as $k => $v )
				{
					$data .= $k . ' => ' . $v . "\n";
				}

				$data .= "END GET VARS ------------------------------------------\n";
			}

			/* SESSION */
			if( ! empty( $_SESSION ) )
			{
				$data .= "\nSESSION VARS ------------------------------------------\n";

				foreach( $_SESSION as $k => $v )
				{
					$data .= $k . ' => ' . $v . "\n";
				}

				$data .= "END SESSION VARS --------------------------------------\n";
			}

			fwrite($fp, $data);
			fclose($fp);
		}
	}

	// -----------------------------------------------------------------------

}

$comment_redlist = new comment_redlist();
