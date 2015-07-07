<?php
require_once realpath(SYNDICATE_POST_PLUGIN_DIR . 'PublishDriver/OAuth2PublishDriver.php');
?>

<script>
    var AJAX_TEST_CONNECTION = '<?php echo $testConnectionURL; ?>';
</script>

<h2>Accounts</h2>

<div class="tabs">
    <ul class="menu">
        <?php foreach ($drivers as $i => $driver): ?>
            <li><a href="#tabs-<?php echo $i; ?>"><?php echo $driver->getName(); ?></a></li>
        <?php endforeach; ?>

    </ul>
    <?php foreach ($drivers as $i => $driver): ?>
        <div class="tab-content" id="tabs-<?php echo $i; ?>">
            <form method="POST">
                <label>Active?</label>
                <input type="checkbox" name="driver_<?php echo $driver->getName(); ?>_is_active" <?php if ($driver_active[$driver->getName()]): ?> checked="checked"<?php endif; ?>/>
                <br />
                <?php $fields = $driver->getRequiredParameters(); ?>
                <?php foreach ($fields as $field): ?>
                    <label><?php echo $field['label']; ?></label>
                    <input class="long-one-line-text" type="text" name="driver_<?php echo $driver->getName(); ?>_parameter[<?php echo $field['name']; ?>]" <?php if (isset($field['value'])): ?>value="<?php echo $field['value']; ?>"<?php endif; ?>>
                    <br />
                <?php endforeach; ?>

                <?php if ($driver instanceof OAuth2PublishDriver): ?>
                    <a href="<?php echo $driver->getLoginUrl();?>"><input type="button" value="Login" /></a>
                <?php endif; ?>
                <input type="hidden" name="driver_name" value="<?php echo $driver->getName(); ?>" />
                <input type="button" value="Test connection" class="test_connection"/>
                <input type="submit" value="save">
            </form>


        </div>
    <?php endforeach; ?>
</div>