var tmp = 9;

jQuery(function () {

// Tabs
    jQuery('.tabs').tabs();

//hover states on the static widgets
    jQuery('#dialog_link, ul#icons li').hover(
            function () {
                jQuery(this).addClass('ui-state-hover');
            },
            function () {
                jQuery(this).removeClass('ui-state-hover');
            }
    );


    jQuery('#spinner_chief_form #test_connection').click(function (e) {
        jQuery.post(SPINNER_CHIEF_TEST_URL, {
            command:'spinner_chief_test_connection',
            username: jQuery("#spinner_chief_form input[name='spinner_chief_username']").val(),
            password: jQuery("#spinner_chief_form input[name='spinner_chief_password']").val(),
            api_key: jQuery("#spinner_chief_form input[name='spinner_chief_api_key']").val(),
            url: jQuery("#spinner_chief_form input[name='spinner_chief_api_url']").val()
        }, function(data){
            if(data) {
                alert('Connection success');
            } else {
                alert('Connection faild');
            }
            
        }, 'json');
    });
    
    jQuery('#test_mail').click(function(e){
        jQuery.post(SEND_TEST_MAIL_URL, {
            command:'mail_test',
            username: jQuery("#notification_form input[name='phpmailer_username']").val(),
            password: jQuery("#notification_form input[name='phpmailer_password']").val(),
            host: jQuery("#notification_form input[name='phpmailer_host']").val(),
            port: jQuery("#notification_form input[name='phpmailer_port']").val(),
            to: jQuery("#notification_form input[name='phpmailer_to']").val(),
            smtp_secure: jQuery("#notification_form select[name='phpmailer_smtp_secure']").val()
        }, function(data){
            if(data) {
                alert('Mail has been sent');
            } else {
                alert('Error. Probably wrong configuration');
            }
            
        }, 'json');
    });
});