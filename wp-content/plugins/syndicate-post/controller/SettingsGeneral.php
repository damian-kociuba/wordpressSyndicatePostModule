<?php

require_once 'Controller.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'View.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'PublishDriverManager.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'TextSpinner/SpinnerChief.php';

/**
 * Description of settingsAccounts
 *
 * @author dkociuba
 */
class SettingsGeneral extends Controller {

    public function __construct() {
        $this->addRoute('index', 'index', 'GET');
        $this->addRoute('save_general_settings', 'handleFormWithGeneralSettings', 'POST');
        $this->addRoute('save_spinner_chief_settings', 'handleFormWithSpinnerChiefSettings', 'POST');
        $this->addRoute('save_notification_settings', 'handleFormWithNotificationSettings', 'POST');
    }

    public function actionIndex() {
        $message = '';
        $defaultFormValues = array();

        $spinnerChief = new SpinnerChief();
        $spinnerChief->loadPreservedParameters();
        $defaultFormValues['minimal_syndicated_content_length'] = get_option('syndicate_post_minimal_syndicated_content_length');
        $defaultFormValues['maximal_syndicated_content_length'] = get_option('syndicate_post_maximal_syndicated_content_length');
        $defaultFormValues['default_anchor_text'] = get_option('syndicate_post_default_anchor_text');
        $defaultFormValues['spinner_chief_url'] = $spinnerChief->getAddress();
        $defaultFormValues['spinner_chief_username'] = $spinnerChief->getUsername();
        $defaultFormValues['spinner_chief_password'] = $spinnerChief->getPassword();
        $defaultFormValues['spinner_chief_api_key'] = $spinnerChief->getApikey();
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
        $view->addStyle('layout.css');
        foreach ($defaultFormValues as $field => $value) {
            $view->setParameter($field, $value);
        }
        $view->setParameter('general_settings_form_action', $this->getLinkToRoute('save_general_settings', true));
        $view->setParameter('spinner_chief_settings_form_action', $this->getLinkToRoute('save_spinner_chief_settings', true));
        $view->setParameter('notification_settings_form_action', $this->getLinkToRoute('save_notification_settings', true));
        $view->setParameter('message', $message);
        $view->render();
    }

    public function actionHandleFormWithGeneralSettings() {
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
        header('Location: '.$this->getLinkToRoute('index'));
    }
    
    public function actionHandleFormWithSpinnerChiefSettings() {
        $url = filter_input(INPUT_POST, 'spinner_chief_url');
        $username = filter_input(INPUT_POST, 'spinner_chief_username');
        $password = filter_input(INPUT_POST, 'spinner_chief_password');
        $apiKey = filter_input(INPUT_POST, 'spinner_chief_api_key');

        $spinnerChief = new SpinnerChief();
        $spinnerChief->loadPreservedParameters();
        $spinnerChief->setAddress($url);
        $spinnerChief->setUsername($username);
        $spinnerChief->setPassword($password);
        $spinnerChief->setApikey($apiKey);
        $spinnerChief->preserveParameters();
        
        header('Location: '.$this->getLinkToRoute('index'));
    }
    
    public function actionHandleFormWithNotificationSettings() {
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
        
        header('Location: '.$this->getLinkToRoute('index'));
    }

}
