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

define('SYNDICATE_POST_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SYNDICATE_POST_PLUGIN_URL', plugin_dir_url(__FILE__));
require_once SYNDICATE_POST_PLUGIN_DIR . 'PostSyndicator.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'View.php';

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
    $defaultFormValues = array();


    $message = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && filter_input(INPUT_POST, 'command') === 'save_general_settings') {
        $minimalSyndicatedContentLength = filter_input(INPUT_POST, 'minimal_syndicated_content_length', FILTER_VALIDATE_INT);
        $maximalSyndicatedContentLength = filter_input(INPUT_POST, 'maximal_syndicated_content_length', FILTER_VALIDATE_INT);
        $defaultAnchorText = filter_input(INPUT_POST, 'default_anchor_text');
        $isCorrect = true;
        if ($minimalSyndicatedContentLength >= $maximalSyndicatedContentLength) {
            $message = 'Minimal content length should be less than maximal content length';
            $isCorrect = false;
        }

        if ($isCorrect) {
            update_option('syndicate_post_minimal_syndicated_content_length', $minimalSyndicatedContentLength);
            update_option('syndicate_post_maximal_syndicated_content_length', $maximalSyndicatedContentLength);
            update_option('syndicate_post_default_anchor_text', $defaultAnchorText);
            $message = 'Saved settings';
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && filter_input(INPUT_POST, 'command') === 'save_spinner_chief_settings') {
        $url = filter_input(INPUT_POST, 'spinner_chief_url');
        $username = filter_input(INPUT_POST, 'spinner_chief_username');
        $password = filter_input(INPUT_POST, 'spinner_chief_password');
        $apiKey = filter_input(INPUT_POST, 'spinner_chief_api_key');

        update_option('syndicate_post_spinner_chief_url', $url);
        update_option('syndicate_post_spinner_chief_username', $username);
        update_option('syndicate_post_spinner_chief_password', $password);
        update_option('syndicate_post_spinner_chief_api_key', $apiKey);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && filter_input(INPUT_POST, 'command') === 'save_notification_settings') {
        $host = filter_input(INPUT_POST, 'phpmailer_host');
        $username = filter_input(INPUT_POST, 'phpmailer_username');
        $password = filter_input(INPUT_POST, 'phpmailer_password');
        $port = filter_input(INPUT_POST, 'phpmailer_port');
        $smtpSecure = filter_input(INPUT_POST, 'phpmailer_smtp_secure');

        update_option('syndicate_post_phpmailer_host', $host);
        update_option('syndicate_post_phpmailer_username', $username);
        update_option('syndicate_post_phpmailer_password', $password);
        update_option('syndicate_post_phpmailer_port', $port);
        update_option('syndicate_post_phpmailer_smtp_secure', $smtpSecure);
    }
    $defaultFormValues['minimal_syndicated_content_length'] = get_option('syndicate_post_minimal_syndicated_content_length');
    $defaultFormValues['maximal_syndicated_content_length'] = get_option('syndicate_post_maximal_syndicated_content_length');
    $defaultFormValues['default_anchor_text'] = get_option('syndicate_post_default_anchor_text');
    $defaultFormValues['spinner_chief_url'] = get_option('syndicate_post_spinner_chief_url');
    $defaultFormValues['spinner_chief_username'] = get_option('syndicate_post_spinner_chief_username');
    $defaultFormValues['spinner_chief_password'] = get_option('syndicate_post_spinner_chief_password');
    $defaultFormValues['spinner_chief_api_key'] = get_option('syndicate_post_spinner_chief_api_key');
    $defaultFormValues['phpmailer_host'] = get_option('syndicate_post_phpmailer_host');
    $defaultFormValues['phpmailer_username'] = get_option('syndicate_post_phpmailer_username');
    $defaultFormValues['phpmailer_password'] = get_option('syndicate_post_phpmailer_password');
    $defaultFormValues['phpmailer_port'] = get_option('syndicate_post_phpmailer_port');
    $defaultFormValues['phpmailer_smtp_secure'] = get_option('syndicate_post_phpmailer_smtp_secure');

    $view = new View('general-settings-page.php');
    $view->addScript('general-settings.js');
    $view->addWordpressBuildInScript('jquery');
    $view->addWordpressBuildInScript('jquery-ui-core');
    $view->addWordpressBuildInScript('jquery-ui-tabs');
    $view->addStyle('general-settings.css');
    foreach ($defaultFormValues as $field => $value) {
        $view->setParameter($field, $value);
    }

    $view->setParameter('message', $message);
    $view->render();
};

$pagePrinters['accounts'] = function() {
    $view = new View('accounts-page.php');
    $view->render();
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
