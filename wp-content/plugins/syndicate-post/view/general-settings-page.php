
<h2>General settings</h2> 


<script>
    var AJAX_URL_PREFIX = '<?php echo SYNDICATE_POST_PLUGIN_URL . 'ajax/'; ?>';
    
</script>
<div class="tabs">
    <ul class="menu">
        <li><a href="#tabs-1">General Settings</a></li>
        <li><a href="#tabs-2">SpinnerChief</a></li>
        <li><a href="#tabs-3">Notifications</a></li>
    </ul>
    <div class="tab-content" id="tabs-1">
        <?php if(!empty($message)): ?>
            <div class="message"><?php echo $message;?></div>
        <?php endif; ?>
        <form method="POST" action="<?php echo $general_settings_form_action;?>">
            <label>Minimal syndicated content length</label>
            <input type="number" name="minimal_syndicated_content_length" <?php if(isset($minimal_syndicated_content_length)) {echo "value=\"$minimal_syndicated_content_length\"";} ?> />
            <br />
            <label>Maximal syndicated content length</label>
            <input type="number" name="maximal_syndicated_content_length" <?php if(isset($maximal_syndicated_content_length)) {echo "value=\"$maximal_syndicated_content_length\"";} ?> />
            <br />
            <label>Default anchor text</label>
            <input type="text" name="default_anchor_text" <?php if(isset($default_anchor_text)) {echo "value=\"$default_anchor_text\"";} ?> />
            <br />
            <input type="hidden" name="command" value="save_general_settings" /> 
            <input type="submit" value="Save" />
        </form>
    </div>
    <div class="tab-content" id="tabs-2">
        <form id="spinner_chief_form" method="POST" action="<?php echo $spinner_chief_settings_form_action;?>">
            <label>Api URL</label>
            <input type="text" name="spinner_chief_api_url" <?php if(isset($spinner_chief_api_url)) {echo "value=\"$spinner_chief_api_url\"";} else {echo "value=\"api.spinnerchief.com:443\"";}?> />
            <br />
            <label>User name</label>
            <input type="text" name="spinner_chief_username" <?php if(isset($spinner_chief_username)) {echo "value=\"$spinner_chief_username\"";} ?> />
            <br />
            <label>Password</label>
            <input type="password" name="spinner_chief_password" <?php if(isset($spinner_chief_password)) {echo "value=\"$spinner_chief_password\"";} ?> />
            <br />
            <label>Api Key</label>
            <input type="text" name="spinner_chief_api_key" <?php if(isset($spinner_chief_api_key)) {echo "value=\"$spinner_chief_api_key\"";} ?> />
            <br />
            <input type="button" value="Test connection" id="test_connection"/>
            <input type="hidden" name="command" value="save_spinner_chief_settings" /> 
            <input type="submit" value="Save" />
        </form>
    </div>
    <div class="tab-content" id="tabs-3">
        <form id="notification_form" method="POST" action="<?php echo $notification_settings_form_action;?>">
            <fieldset>
                <legend>PHP Mailer</legend>
                <label>Host</label>
                <input type="text" name="phpmailer_host" <?php if(isset($phpmailer_host)) {echo "value=\"$phpmailer_host\"";} ?> />
                <br />
                <label>User name</label>
                <input type="text" name="phpmailer_username" <?php if(isset($phpmailer_username)) {echo "value=\"$phpmailer_username\"";} ?> />
                <br />
                <label>Password</label>
                <input type="password" name="phpmailer_password" <?php if(isset($phpmailer_password)) {echo "value=\"$phpmailer_password\"";} ?> />
                <br />
                <label>Port</label>
                <input type="text" name="phpmailer_port" <?php if(isset($phpmailer_port)) {echo "value=\"$phpmailer_port\"";} ?> />
                <br />
                <label>Authorization</label>
                <select name="phpmailer_smtp_secure">
                    <option value="" <?php if($phpmailer_smtp_secure===''):?>selected="selected"<?php endif;?>>None</option>
                    <option value="tls" <?php if($phpmailer_smtp_secure==='tls'):?>selected="selected"<?php endif;?>>TLS</option>
                    <option value="ssl" <?php if($phpmailer_smtp_secure==='ssl'):?>selected="selected"<?php endif;?>>SSL</option>
                </select>
                <br />
                <label>To (required for test configuration)</label>
                <input type="text" name="phpmailer_to" />
                <br />
                <input type="button" value="Test connection" id="test_mail"/>
            </fieldset>
            <input type="hidden" name="command" value="save_notification_settings" /> 
            <input type="submit" value="Save" />
        </form>
    </div>
</div>