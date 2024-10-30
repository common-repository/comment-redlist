<?php

echo '
<div class="wrap">
	<h2>' . $this->lang['h2_header'] . '</h2>
	<form method="post" action="options.php">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">' . $this->lang['sequences_textarea_label'] . '</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span>' . $this->lang['sequences_textarea_label'] . '</span></legend>
						<p>
							<label for="comment_redlist_sequences">' . $this->lang['desc'] . '</label>
						</p>
						<p>
							<textarea name="comment_redlist[sequences]" rows="5" cols="50" id="comment_redlist_sequences" class="large-text code">' . esc_textarea( $this->options['sequences'] ) . '</textarea>
						</p>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . $this->lang['ip_addrs_textarea_label'] . '</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span>' . $this->lang['ip_addrs_textarea_label'] . '</span></legend>
						<p>
							<label for="comment_redlist_ip_addrs">' . $this->lang['ip_addrs_desc'] . '</label>
						</p>
						<p>
							<textarea name="comment_redlist[ip_addrs]" rows="5" cols="50" id="comment_redlist_ip_addrs" class="large-text code">' . esc_textarea( $this->options['ip_addrs'] ) . '</textarea>
						</p>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . $this->lang['log_checkbox_label'] . '</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>' . $this->lang['log_checkbox_label'] . '</span>
						</legend>
						<label for="comment_redlist_log_cb">
							<input id="comment_redlist_log_cb" type="checkbox" name="comment_redlist[log]" value="TRUE" ' . ( isset( $this->options['log'] ) && $this->options['log'] == 'TRUE' ? 'checked="checked"' : '' ) . ' /> ' . $this->lang['log_checkbox_desc'] . '
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . $this->lang['alert_checkbox_label'] . '</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>' . $this->lang['alert_checkbox_label'] . '</span>
						</legend>
						<label for="comment_redlist_alert_cb">
							<input id="comment_redlist_alert_cb" type="checkbox" name="comment_redlist[alert]" value="TRUE" ' . ( isset( $this->options['alert'] ) && $this->options['alert'] == 'TRUE' ? 'checked="checked"' : '' ) . ' /> ' . $this->lang['alert_checkbox_desc'] . '
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . $this->lang['jquery_checkbox_label'] . '</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>' . $this->lang['jquery_checkbox_label'] . '</span>
						</legend>
						<label for="comment_redlist_jquery_cb">
							<input id="comment_redlist_jquery_cb" type="checkbox" name="comment_redlist[jquery]" value="TRUE" ' . ( isset( $this->options['jquery'] ) && $this->options['jquery'] == 'TRUE' ? 'checked="checked"' : '' ) . ' /> ' . $this->lang['jquery_checkbox_desc'] . '
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . $this->lang['die_checkbox_label'] . '</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>' . $this->lang['die_checkbox_label'] . '</span>
						</legend>
						<label for="comment_redlist_die_cb">
							<input id="comment_redlist_die_cb" type="checkbox" name="comment_redlist[die]" value="TRUE" ' . ( isset( $this->options['die'] ) && $this->options['die'] == 'TRUE' ? 'checked="checked"' : '' ) . ' /> ' . $this->lang['die_checkbox_desc'] . '
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . $this->lang['token_checkbox_label'] . '</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>' . $this->lang['token_checkbox_label'] . '</span>
						</legend>
						<label for="comment_redlist_token_cb">
							<input id="comment_redlist_token_cb" type="checkbox" name="comment_redlist[token]" value="TRUE" ' . ( isset( $this->options['token'] ) && $this->options['token'] == 'TRUE' ? 'checked="checked"' : '' ) . ' /> ' . $this->lang['token_checkbox_desc'] . '
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . $this->lang['links_checkbox_label'] . '</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>' . $this->lang['links_checkbox_label'] . '</span>
						</legend>
						<label for="comment_redlist_links_cb">
							<input id="comment_redlist_links_cb" type="checkbox" name="comment_redlist[links]" value="TRUE" ' . ( isset( $this->options['links'] ) && $this->options['links'] == 'TRUE' ? 'checked="checked"' : '' ) . ' /> ' . $this->lang['links_checkbox_desc'] . '
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . $this->lang['sessname_text_label'] . '</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>' . $this->lang['sessname_text_label'] . '</span>
						</legend>
						<label for="comment_redlist_sessname_text">
							<input id="comment_redlist_sessname_text" class="regular-text" type="text" name="comment_redlist[sessname]" value="' . ( isset( $this->options['sessname'] ) && ! empty( $this->options['sessname'] ) ? $this->options['sessname'] : 'skunkToken' ) . '" /> 
							<p class="description">' . $this->lang['sessname_text_desc'] . '</p>
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . $this->lang['xchars_radios_label'] . '</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>' . $this->lang['xchars_radios_label'] . '</span>
						</legend>
						<label title="no">
							<input type="radio" name="comment_redlist[xchars]" value="no" ' . ( isset( $this->options['xchars'] ) && $this->options['xchars'] == 'no' ? 'checked="checked"' : '' ) . ' /> ' . $this->lang['xchars_radio_no'] . '
						</label><br />
						<label title="us">
							<input type="radio" name="comment_redlist[xchars]" value="us" ' . ( isset( $this->options['xchars'] ) && $this->options['xchars'] == 'us' ? 'checked="checked"' : '' ) . ' /> ' . $this->lang['xchars_radio_us'] . '
						</label><br />
						<label title="us_no_ltgt">
							<input type="radio" name="comment_redlist[xchars]" value="us_no_ltgt" ' . ( isset( $this->options['xchars'] ) && $this->options['xchars'] == 'us_no_ltgt' ? 'checked="checked"' : '' ) . ' /> ' . $this->lang['xchars_radio_us_no_ltgt'] . '
						</label><br />
						<label>
							<input type="radio" name="comment_redlist[xchars]" id="xchars_restriction_custom_radio" value="\c\u\s\t\o\m" ' . ( isset( $this->options['xchars'] ) && $this->options['xchars'] == '\c\u\s\t\o\m' ? 'checked="checked"' : '' ) . ' /> ' . $this->lang['xchars_custom_label'] . '
						</label><input name="comment_redlist[xchars_custom]" value="' . ( isset( $this->options['xchars_custom'] ) ? $this->options['xchars_custom'] : '' ) . '" class="medium-text" type="text"> <span class="example">' . $this->lang['xchars_custom_example'] . ' <b style="color:red;">/[^a-z0-9]/i</b></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . $this->lang['url_checkbox_label'] . '</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>' . $this->lang['url_checkbox_label'] . '</span>
						</legend>
						<label for="comment_redlist_url_cb">
							<input id="comment_redlist_url_cb" type="checkbox" name="comment_redlist[url]" value="TRUE" ' . ( isset( $this->options['url'] ) && $this->options['url'] == 'TRUE' ? 'checked="checked"' : '' ) . ' /> ' . $this->lang['url_checkbox_desc'] . '
						</label>
					</fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . $this->lang['debug_checkbox_label'] . '</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>' . $this->lang['debug_checkbox_label'] . '</span>
						</legend>
						<label for="comment_redlist_debug_cb">
							<input id="comment_redlist_debug_cb" type="checkbox" name="comment_redlist[debug]" value="TRUE" ' . ( isset( $this->options['debug'] ) && $this->options['debug'] == 'TRUE' ? 'checked="checked"' : '' ) . ' /> ' . $this->lang['debug_checkbox_desc'] . '
						</label>
					</fieldset>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="' . $this->lang['button'] . '" />
		</p>';

/**
 * The settings_fields() func sets important 
 * hidden form fields critical to updating options
 */
settings_fields('comment_redlist_group');

/* Close the form */
echo '</form></div>';