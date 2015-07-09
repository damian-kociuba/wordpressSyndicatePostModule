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

    jQuery(".test_connection").click(function () {
        var driverName = jQuery(this).siblings("input[name='driver_name']").val();
        var parameters = {};
        jQuery(this).siblings("input[name^='driver_" + driverName + "_parameter']").each(function () {
            var paramName = jQuery(this).attr('name');
            var paramNameClean = /.*\[(.*)\]/.exec(paramName)[1];
            var paramValue = jQuery(this).val();

            parameters[paramNameClean] = paramValue;
        });

        jQuery.post(AJAX_TEST_CONNECTION, {
            command: 'test_connection',
            driver_name: driverName,
            driver_parameters: JSON.stringify(parameters),
        }, function (data) {
            try {
                data = JSON.parse(data);
                if (data === true) {
                    alert('Connection success');
                } else {
                    alert('Connection faild');
                }
            } catch (SyntaxError) {
                alert('Connection faild');
            }
        });
    });


});