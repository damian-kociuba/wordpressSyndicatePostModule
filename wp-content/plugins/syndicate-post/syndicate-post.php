<?php
/**
 * @package Syndicate-post
 */
/*
  Plugin Name: Syndicate post
  Plugin URI: http://www.yourpluginurlhere.com/
  Version: 0.1
  Author: Damian Kociuba
  Description: What does your plugin do and what features does it offer...
 */


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

define('SYNDICATE_POST_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SYNDICATE_POST_PLUGIN_URL', plugin_dir_url(__FILE__));
require_once SYNDICATE_POST_PLUGIN_DIR . 'PostSyndicator.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'controller/SettingsAccounts.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'controller/SettingsGeneral.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'SyndicatePostException.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'DisposableAdminMessage.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'TextSpinner/TextSpinnerException.php';

View::setGlobalViewDirectory(SYNDICATE_POST_PLUGIN_DIR . 'view/');
View::setGlobalScriptDirectory(SYNDICATE_POST_PLUGIN_URL . 'view/js/');
View::setGlobalStyleDirectory(SYNDICATE_POST_PLUGIN_URL . 'view/css/');

$initDisposableAdminMessages = function() {
    if (!session_id()) {
        session_start();
    }
};

add_action('admin_init', $initDisposableAdminMessages);

$syndicatePostEvent = function ($postId) {
    $post = get_post($postId);
    try {
        $postSyndicator = new PostSyndicator($post);
        $postSyndicator->syndicate();
    } catch (TextSpinnerException $ex) {
        DisposableAdminMessage::getInstance()->pushMessage($ex->getMessage(). ' Message not sended to linked blogs');
    } catch (SyndicatePostException $ex) {
        DisposableAdminMessage::getInstance()->pushMessage($ex->getMessage());
    }
};

add_action('publish_post', $syndicatePostEvent);

$printErrorsMessages = function() {
    while ($msg = DisposableAdminMessage::getInstance()->popMessage()) :
        ?>
        <div class="error">
            <p><?php echo $msg; ?></p>
        </div>
        <?php
    endwhile;
};

add_action('admin_notices', $printErrorsMessages);



$pagePrinters = array();
$pagePrinters['main'] = function () {
    $view = new View('settings-main-page.php');
    $view->render();
};

$pagePrinters['settings'] = function() {
    $controller = new SettingsGeneral();
    $controller->handleExecution();
};

$pagePrinters['accounts'] = function() {
    $controller = new SettingsAccounts();
    $controller->handleExecution();
};
$pagePrinters['reporting'] = function() {
    $view = new View('reporting-page.php');
    $view->render();
};


$pluginMenu = function() use ($pagePrinters) {
    add_menu_page('Settings', 'Syndicate Post', 'manage_options', 'syndicate-post-settings-menu', $pagePrinters['main']);
    add_submenu_page('syndicate-post-settings-menu', 'Genaral settings', 'Genaral settings', 'manage_options', 'syndicate-post-ganaral-settings-menu', $pagePrinters['settings']);
    add_submenu_page('syndicate-post-settings-menu', 'Accounts', 'Accounts', 'manage_options', 'syndicate-post-accounts-menu', $pagePrinters['accounts']);
    add_submenu_page('syndicate-post-settings-menu', 'Genaral settings', 'Genaral settings', 'manage_options', 'syndicate-post--ganaral-settings-menu', $pagePrinters['reporting']);
};

add_action('admin_menu', $pluginMenu);
