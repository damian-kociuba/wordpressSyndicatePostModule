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

View::setGlobalViewDirectory(SYNDICATE_POST_PLUGIN_DIR . 'view/');
View::setGlobalScriptDirectory(SYNDICATE_POST_PLUGIN_URL . 'view/js/');
View::setGlobalStyleDirectory(SYNDICATE_POST_PLUGIN_URL . 'view/css/');

$syndicatePostEvent = function ($postId) {
    $post = get_post($postId);
    $postSyndicator = new PostSyndicator($post);
};

add_action('publish_post', $syndicatePostEvent);


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
